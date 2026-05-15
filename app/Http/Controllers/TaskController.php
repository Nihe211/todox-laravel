<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy giá trị filter từ URL (mặc định là 'all')
        $filter = $request->query('filter', 'all');

        $query = Task::query();
        $now = \Carbon\Carbon::now();

        // 2. Xử lý Logic lọc thời gian & trạng thái
        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', \Carbon\Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', $now->month)
                    ->whereYear('created_at', $now->year);
                break;
            case 'active':
                $query->where('status', 'active');
                break;
            case 'complete':
                $query->where('status', 'complete');
                break;
            case 'all':
            default:
                // Không thêm điều kiện filter nào cả
                break;
        }

        // 3. Thực thi lấy dữ liệu có phân trang (5 task / trang)
        $tasks = (clone $query)->latest()->paginate(5)->withQueryString();

        // 4. Đếm số lượng (Luôn đếm tổng toàn bộ để Badge không bị nhảy số về 0 khi lọc)
        $activeCount = Task::where('status', 'active')->count();
        $completeCount = Task::where('status', 'complete')->count();

        return view('tasks.index', compact('tasks', 'activeCount', 'completeCount', 'filter'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $task = Task::create([
            'title' => $request->title
        ]);
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'task' => $task,
                'html' => view('tasks.item', compact('task'))->render()
            ]);
        }

        return back();
    }

    public function update(Request $request, Task $task)
    { // 1. Chặn logic: Nếu task đã hoàn thành thì không làm gì cả và quay lại
        if ($task->status === 'complete') {
            return response()->json(['success' => false, 'message' => 'Task đã hoàn thành']);
            // Bạn có thể dùng return back()->with('error', 'Không thể hoàn tác'); nếu có làm thông báo lỗi
        }

        // 2. Nếu task đang active và người dùng gửi request check (có biến status)
        if ($request->has('status')) {
            $task->update([
                'status' => 'complete',
                'completedAt' => now()
            ]);

            // Trả về JSON báo thành công kèm thời gian vừa hoàn thành
            if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'completed_at' => $task->completedAt->format('H:i - d/m')
                ]);
            }
        }

        return back();
    }

    public function destroy($id, Request $request)
    {
        // 1. Chủ động tìm task theo ID gửi lên
        $task = \App\Models\Task::find($id);

        // 2. Nếu tìm thấy thì thực hiện lệnh xóa
        if ($task) {
            $task->delete();
        }

        // 3. Trả về JSON xác nhận xóa thành công cho JavaScript
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true]);
        }
        return back();
    }
}
