<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة التحكم - SmartEvent System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
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
        
        /* تنسيق شريط التحميل */
        .progress-custom {
            height: 4px;
            border-radius: 2px;
            background-color: rgba(0,0,0,0.1);
        }
        
        /* للشاشات الصغيرة */
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
        <a href="{{ route('admin.dashboard') }}" class="nav-link active">
            <i class="fas fa-tachometer-alt"></i> لوحة التحكم
        </a>
        
        <div class="nav-header">الإدارة</div>
        <a href="{{ route('admin.events.index') }}" class="nav-link">
            <i class="fas fa-calendar-alt"></i> الفعاليات
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link">
            <i class="fas fa-users"></i> المستخدمين
        </a>
        <a href="{{ route('admin.invitations.index') }}" class="nav-link">
            <i class="fas fa-ticket-alt"></i> الدعوات
        </a>
        
        <!-- ✅ رابط الإنشاء السريع (جديد) -->
        <a href="{{ route('admin.invitations.quick') }}" class="nav-link">
            <i class="fas fa-bolt"></i> إنشاء سريع
        </a>
        
        <div class="nav-header">التحليلات</div>
        <a href="{{ route('admin.logs.index') }}" class="nav-link">
            <i class="fas fa-history"></i> السجلات
        </a>
        <a href="{{ route('admin.behavior.index') }}" class="nav-link">
            <i class="fas fa-brain"></i> تحليل السلوك
        </a>
    </nav>
</div>

<!-- المحتوى الرئيسي -->
<div class="main-content">
    <!-- معلومات المسؤول وزر الخروج -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-gradient-primary">
                <div class="card-body py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                <i class="fas fa-user-shield text-primary fs-5"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">مرحباً، {{ session('admin_name', 'Admin') }}</h5>
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

    <!-- بطاقات الإحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="small opacity-75">المستخدمين</span>
                            <h3 class="mb-0 fw-bold">{{ $stats['users'] }}</h3>
                        </div>
                        <i class="fas fa-users stats-icon"></i>
                    </div>
                    <div class="progress-custom bg-white bg-opacity-25 mt-2">
                        <div class="progress-bar bg-white" style="width: {{ $stats['users'] > 0 ? 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card bg-success text-white">
                <div class="card-body py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="small opacity-75">الفعاليات</span>
                            <h3 class="mb-0 fw-bold">{{ $stats['events'] }}</h3>
                        </div>
                        <i class="fas fa-calendar-alt stats-icon"></i>
                    </div>
                    <div class="progress-custom bg-white bg-opacity-25 mt-2">
                        <div class="progress-bar bg-white" style="width: {{ $stats['events'] > 0 ? 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card bg-warning text-white">
                <div class="card-body py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="small opacity-75">الدعوات</span>
                            <h3 class="mb-0 fw-bold">{{ $stats['invitations'] }}</h3>
                        </div>
                        <i class="fas fa-ticket-alt stats-icon"></i>
                    </div>
                    <div class="progress-custom bg-white bg-opacity-25 mt-2">
                        <div class="progress-bar bg-white" style="width: {{ $stats['invitations'] > 0 ? 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card bg-info text-white">
                <div class="card-body py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="small opacity-75">الحضور</span>
                            <h3 class="mb-0 fw-bold">{{ $stats['attendances'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle stats-icon"></i>
                    </div>
                    <div class="progress-custom bg-white bg-opacity-25 mt-2">
                        <div class="progress-bar bg-white" style="width: {{ $stats['attendances'] > 0 ? 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- نسبة النجاح - مصغرة ومطورة -->
    <div class="row">
        <div class="col-md-5 mx-auto">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill mb-2">
                        <i class="fas fa-chart-line me-1"></i> إحصائيات الدخول
                    </span>
                    <div class="d-flex justify-content-center align-items-center gap-4 mt-2">
                        <div style="width: 100px; height: 100px; position: relative;">
                            <canvas id="successRateChart"></canvas>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <span class="fw-bold fs-5 text-primary">{{ $successRate }}%</span>
                            </div>
                        </div>
                        <div class="text-start">
                            <div class="mb-2">
                                <span class="badge bg-success rounded-pill px-2 py-1">
                                    <i class="fas fa-check-circle me-1"></i> نجاح
                                </span>
                                <span class="fw-bold ms-2">{{ $successCount ?? 0 }}</span>
                            </div>
                            <div>
                                <span class="badge bg-danger rounded-pill px-2 py-1">
                                    <i class="fas fa-times-circle me-1"></i> فشل
                                </span>
                                <span class="fw-bold ms-2">{{ $failCount ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const rateCtx = document.getElementById('successRateChart').getContext('2d');
    new Chart(rateCtx, {
        type: 'doughnut',
        data: {
            labels: ['نجاح', 'فشل'],
            datasets: [{
                data: [{{ $successRate }}, {{ 100 - $successRate }}],
                backgroundColor: ['#28a745', '#dc3545'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            cutout: '70%',
        }
    });
</script>
</body>
</html>
