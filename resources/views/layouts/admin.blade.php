<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم - SmartEvent System')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        /* التدرجات اللونية */
        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545, #b02a37);
        }
        .bg-gradient-dark {
            background: linear-gradient(135deg, #2d3748, #1a202c);
        }
        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107, #d39e00);
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8, #0f6674);
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        
        /* القائمة الجانبية */
        .sidebar {
            background-color: #1a1e2b;
            min-height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            width: 250px;
            z-index: 100;
        }
        
        /* المحتوى الرئيسي */
        .main-content {
            margin-right: 250px;
            padding: 20px;
        }
        
        .sidebar a {
            color: #a8b3cf;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: 0.3s;
        }
        
        .sidebar a:hover, .sidebar a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .sidebar .nav-header {
            color: #6c7a91;
            font-size: 12px;
            padding: 20px 20px 8px 20px;
        }
        
        .stats-card {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            border-radius: 16px;
            overflow: hidden;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }
        
        .progress-custom {
            height: 4px;
            border-radius: 2px;
            background-color: rgba(0,0,0,0.1);
        }
        
        /* ============================================ */
        /* 🎨 تنسيق أزرار التنقل (Pagination) - حجم صغير */
        /* ============================================ */
        .pagination {
            direction: ltr;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }

        .pagination .page-item .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            color: #667eea;
            background-color: white;
            border: 1px solid #dee2e6;
            font-size: 12px;
            padding: 4px 10px;
            min-width: 32px;
            text-align: center;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #667eea;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
        }

        .pagination .page-item .page-link:hover:not(.active) {
            background-color: #e9ecef;
            transform: translateY(-1px);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            .main-content {
                margin-right: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- القائمة الجانبية -->
<div class="sidebar">
    <div class="p-3 text-center text-white border-bottom">
        <h4 class="mb-0">🎫 SmartEvent</h4>
        <small class="text-white-50">نظام إدارة الفعاليات</small>
    </div>
    <nav class="nav flex-column">
        <div class="nav-header">الرئيسية</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> لوحة التحكم
        </a>
        
        <div class="nav-header">الإدارة</div>
        <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> الفعاليات
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> المستخدمين
        </a>
        <a href="{{ route('admin.invitations.index') }}" class="nav-link {{ request()->routeIs('admin.invitations.*') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i> الدعوات
        </a>
        
        <!-- ✅ رابط الإنشاء السريع -->
        <a href="{{ route('admin.invitations.quick') }}" class="nav-link {{ request()->routeIs('admin.invitations.quick') ? 'active' : '' }}">
            <i class="fas fa-bolt"></i> إنشاء سريع
        </a>
        
        <div class="nav-header">التحليلات</div>
        <a href="{{ route('admin.logs.index') }}" class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> السجلات
        </a>
        <a href="{{ route('admin.behavior.index') }}" class="nav-link {{ request()->routeIs('admin.behavior.*') ? 'active' : '' }}">
            <i class="fas fa-brain"></i> تحليل السلوك
        </a>
        <a href="{{ route('admin.fraud.index') }}" class="nav-link {{ request()->routeIs('admin.fraud.*') ? 'active' : '' }}">
            <i class="fas fa-shield-alt"></i> اكتشاف الاحتيال
        </a>
    </nav>
</div>

<!-- المحتوى الرئيسي -->
<div class="main-content">
    <!-- معلومات المسؤول وزر الخروج -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                <i class="fas fa-user-shield text-primary fs-5"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">مرحباً، {{ session('admin_name', Auth::guard('admin')->user()->name ?? 'Admin') }}</h5>
                                <small class="text-muted">أهلاً بك في نظام SmartEvent</small>
                            </div>
                        </div>
                        <div class="mt-2 mt-sm-0">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger rounded-pill px-4 py-1">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    تسجيل خروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- المحتوى الخاص بكل صفحة -->
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@stack('scripts')
</body>
</html>
