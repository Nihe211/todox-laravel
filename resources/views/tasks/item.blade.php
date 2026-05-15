<div class="list-group-item d-flex justify-content-between align-items-center p-3 card shadow-sm border-0 task-item"
    data-id="{{ $task->id }}">
    <div class="d-flex align-items-center">
        <input type="checkbox" class="form-check-input rounded-circle shadow-sm toggle-status"
            style="width: 1.4rem; height: 1.4rem; cursor: pointer;"
            {{ $task->status == 'complete' ? 'checked disabled' : '' }}>
        <div class="ms-3">
            <span
                class="task-title fs-6 fw-semibold {{ $task->status == 'complete' ? 'text-decoration-line-through text-muted opacity-50' : 'text-dark' }}">
                {{ $task->title }}
            </span>
            <div class="completed-time small text-muted mt-1" style="font-size: 0.75rem;">
                @if($task->completedAt)
                <i class="bi bi-clock-history"></i> {{ $task->completedAt->format('H:i - d/m') }}
                @endif
            </div>
        </div>
    </div>
    <button class="btn btn-light btn-sm text-danger rounded-circle border-0 delete-task">
        <i class="bi bi-trash3"></i>
    </button>
</div>