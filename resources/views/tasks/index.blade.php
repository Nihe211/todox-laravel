@extends('layouts.app')

@section('content')
<style>
    .pagination {
        --bs-pagination-border-radius: 12px;
        --bs-pagination-active-bg: var(--primary-color);
        --bs-pagination-color: var(--primary-color);
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
            <form action="{{ route('tasks.store') }}" method="POST" class="d-flex gap-2">
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

    <div class="list-group gap-3">
        @forelse($tasks as $task)
        <div class="list-group-item d-flex justify-content-between align-items-center p-3 card shadow-sm border-0">
            <div class="d-flex align-items-center">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="me-3 m-0">
                    @csrf @method('PUT')
                    <input type="checkbox" name="status" class="form-check-input rounded-circle shadow-sm"
                        style="width: 1.4rem; height: 1.4rem; cursor: pointer;" onchange="this.form.submit()"
                        {{ $task->status == 'complete' ? 'checked disabled' : '' }}>
                </form>
                <div>
                    <span
                        class="fs-6 fw-semibold {{ $task->status == 'complete' ? 'text-decoration-line-through text-muted opacity-50' : 'text-dark' }}">
                        {{ $task->title }}
                    </span>
                    @if($task->completedAt)
                    <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                        <i class="bi bi-clock-history"></i> {{ $task->completedAt->format('H:i - d/m') }}
                    </div>
                    @endif
                </div>
            </div>
            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="m-0">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-light btn-sm text-danger rounded-circle border-0"><i
                        class="bi bi-trash3"></i></button>
            </form>
        </div>
        @empty
        <div class="text-center py-5 empty-state">
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
@endsection