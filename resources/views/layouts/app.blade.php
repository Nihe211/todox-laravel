<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoX - Quản lý công việc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
    :root {
        --primary-color: #4361ee;
        --bg-body: #f8f9fc;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-body);
        color: #2b2d42;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .main-content {
        flex: 1;
    }

    .card {
        border: none;
        border-radius: 16px;
        transition: transform 0.2s;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
    }

    .btn-primary:hover {
        background-color: #3046c9;
        transform: translateY(-1px);
    }

    footer {
        background-color: #ffffff;
        border-top: 1px solid #e9ecef;
        padding: 30px 0;
        margin-top: 50px;
    }

    .footer-brand {
        font-weight: 700;
        color: var(--primary-color);
    }
    </style>
</head>

<body>
    <div class="main-content">
        @yield('content')
    </div>

    <footer>
        <div class="container text-center">
            <div class="mb-2">
                <span class="footer-brand">TodoX</span>
                <span class="text-muted mx-2">|</span>
                <span class="text-secondary fw-medium">Nguyễn Thanh Hiển</span>
            </div>
            <p class="text-muted small mb-0">
                &copy; 2026 Student Project - K2022 - UIT.
                <br>
                Đồ án Hệ thống thông tin - Phát triển Web với Laravel.
            </p>
            <div class="mt-3">
                <a href="#" class="text-secondary mx-2 fs-5"><i class="bi bi-github"></i></a>
                <a href="#" class="text-secondary mx-2 fs-5"><i class="bi bi-envelope-fill"></i></a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>