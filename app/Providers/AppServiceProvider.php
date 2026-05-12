<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // Thêm dòng này

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Khai báo sử dụng giao diện Bootstrap 5 cho phân trang
        Paginator::useBootstrapFive();
    }
}
