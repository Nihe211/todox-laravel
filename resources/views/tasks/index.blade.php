@extends('layouts.app')

@section('content')
<style>
    .pagination {
        --bs-pagination-border-radius: 12px;
        --bs-pagination-active-bg: var(--primary-color, #0d6efd);
        --bs-pagination-color: var(--primary-color, #0d6efd);
    }

    .page-link {
        border: none;
        margin: 0 3px;
        border-radius: 8px !important;
    }

    .empty-state {
        transition: all 0.3s ease;
    }
</style>

<div class="container py-5" style="max-width: 650px;">
    <div class="text-center mb-5">
        <h1 class="display-6 fw-bold text-primary">Nhiệm vụ của tôi</h1>
        <p class="text-muted">Lên kế hoạch và hoàn thành mục tiêu mỗi ngày</p>
    </div>

    <div class="card shadow-sm mb-4 border-0 p-2">
        <div class="card-body">
            <form id="add-task-form" class="d-flex gap-2">
                @csrf
                <input type="text" name="title" class="form-control border-0 bg-light p-3"
                    placeholder="Hôm nay bạn cần làm gì?" required style="border-radius: 12px;">
                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
        <div class="d-flex gap-2">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                Đang làm: {{ $activeCount }}
            </span>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                Hoàn thành: {{ $completeCount }}
            </span>
        </div>

        <div class="d-flex gap-2">
            <div class="btn-group btn-group-sm" role="group">
                <a href="?filter=active"
                    class="btn {{ $filter == 'active' ? 'btn-primary' : 'btn-outline-primary' }} rounded-start-pill px-3">Active</a>
                <a href="?filter=complete"
                    class="btn {{ $filter == 'complete' ? 'btn-primary' : 'btn-outline-primary' }} rounded-end-pill px-3">Done</a>
            </div>

            @php
            $dateFilters = ['today' => 'Hôm nay', 'week' => 'Tuần này', 'month' => 'Tháng này', 'all' => 'Tất cả'];
            $currentDateName = $dateFilters[$filter] ?? 'Thời gian';
            @endphp
            <div class="dropdown">
                <button class="btn btn-light btn-sm dropdown-toggle border rounded-pill px-3" type="button"
                    data-bs-toggle="dropdown">
                    <i class="bi bi-funnel me-1"></i> {{ $currentDateName }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 12px;">
                    @foreach($dateFilters as $k => $v)
                    <li><a class="dropdown-item {{ $filter == $k ? 'active bg-primary text-white' : '' }}"
                            href="?filter={{ $k }}">{{ $v }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div id="task-list" class="list-group gap-3">
        @forelse($tasks as $task)
        @include('tasks.item', ['task' => $task])
        @empty
        <div id="no-tasks" class="text-center py-5 empty-state">
            @if($filter == 'active')
            <i class="bi bi-check2-all display-4 d-block mb-3 text-success opacity-50"></i>
            <h5 class="text-dark fw-bold">Tuyệt vời!</h5>
            <p class="text-muted">Bạn đã xử lý hết các công việc đang dở dang.</p>
            @elseif($filter == 'complete')
            <i class="bi bi-clipboard-x display-4 d-block mb-3 text-warning opacity-50"></i>
            <h5 class="text-dark fw-bold">Chưa có kết quả</h5>
            <p class="text-muted">Hãy hoàn thành các nhiệm vụ để thấy chúng ở đây nhé.</p>
            @else
            <i class="bi bi-inboxes display-4 d-block mb-3 text-muted opacity-50"></i>
            <h5 class="text-dark fw-bold">Danh sách trống</h5>
            <p class="text-muted">Bắt đầu ngày mới bằng cách thêm một vài nhiệm vụ nào!</p>
            @endif
            <a href="{{ route('tasks.index') }}?filter=all"
                class="btn btn-link text-primary text-decoration-none shadow-none">Xem tất cả công việc</a>
        </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $tasks->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const taskList = document.getElementById('task-list');
        const addForm = document.getElementById('add-task-form');

        // Lấy filter hiện tại từ URL (để biết đang ở tab Active hay Done)
        const urlParams = new URLSearchParams(window.location.search);
        const currentFilter = urlParams.get('filter') || 'all';

        // 1. Thêm Task
        if (addForm) {
            addForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(addForm);
                const response = await fetch("{{ route('tasks.store') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    const noTasksEl = document.getElementById('no-tasks');
                    if (noTasksEl) noTasksEl.remove();
                    taskList.insertAdjacentHTML('afterbegin', data.html);
                    addForm.reset();
                }
            });
        }

        // 2. Xóa và Check hoàn thành
        if (taskList) {
            taskList.addEventListener('click', async (e) => {
                const item = e.target.closest('.task-item');
                if (!item) return;
                const id = item.dataset.id;

                // XÓA TASK
                if (e.target.closest('.delete-task')) {
                    if (!confirm('Bạn muốn xóa nhiệm vụ này?')) return;
                    const res = await fetch(`/tasks/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if ((await res.json()).success) {
                        item.style.transition = 'all 0.3s ease';
                        item.style.opacity = '0';
                        setTimeout(() => item.remove(), 300);
                    }
                }

                // CHECK HOÀN THÀNH
                // CHECK HOÀN THÀNH (Cập nhật cực mượt & tức thời)
                if (e.target.classList.contains('toggle-status')) {
                    // 1. GIAO DIỆN PHẢN HỒI NGAY LẬP TỨC (Không cần đợi Server)
                    e.target.disabled = true; // Khóa chết ô check ngay lập tức
                    const titleEl = item.querySelector('.task-title');
                    titleEl.classList.add('text-decoration-line-through', 'text-muted', 'opacity-50');
                    titleEl.classList.remove('text-dark', 'fw-semibold');

                    // Tự động in giờ hiện tại ra cho nhanh
                    const now = new Date();
                    const timeStr = now.getHours() + ':' + (now.getMinutes() < 10 ? '0' : '') + now
                        .getMinutes();
                    item.querySelector('.completed-time').innerHTML =
                        `<i class="bi bi-clock-history"></i> ${timeStr}`;

                    // 2. GỬI DỮ LIỆU NGẦM XUỐNG SERVER
                    fetch(`/tasks/${id}`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                status: 'complete'
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            // 3. Nếu đang ở tab Active, cho task từ từ "bay màu"
                            if (currentFilter === 'active') {
                                setTimeout(() => {
                                    item.style.transition = 'all 0.5s ease';
                                    item.style.opacity = '0';
                                    item.style.transform =
                                        'translateX(50px)'; // Trượt sang phải

                                    setTimeout(() => {
                                        item.remove();
                                        // Nếu hết sạch task thì F5 nhẹ để hiện màn hình trống
                                        if (taskList.querySelectorAll('.task-item')
                                            .length === 0) {
                                            window.location.reload();
                                        }
                                    }, 500);
                                }, 500); // Dừng nửa giây cho bạn ngắm cái gạch ngang rồi mới bay
                            }
                        })
                        .catch(err => {
                            console.error("Lỗi mạng:", err);
                            // Nếu server lỗi thì nhả checkbox ra lại
                            e.target.disabled = false;
                            titleEl.classList.remove('text-decoration-line-through', 'text-muted',
                                'opacity-50');
                            titleEl.classList.add('text-dark');
                        });
                }
            });
        }
    });
</script>
@endsection