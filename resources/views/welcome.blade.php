<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartEvent - نظام إدارة الفعاليات الذكي</title>
    
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* تأثير الخلفية المتحركة */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .bg-animation .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: float 20s infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        /* البطاقة الرئيسية */
        .hero-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hero-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.3);
        }
        
        /* زر الدخول */
        .btn-dashboard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 14px 40px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px -5px rgba(102, 126, 234, 0.4);
        }
        
        .btn-dashboard:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 30px -5px rgba(102, 126, 234, 0.6);
        }
        
        /* الميزات */
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            border-color: rgba(102, 126, 234, 0.3);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .feature-icon i {
            font-size: 32px;
            color: white;
        }
        
        /* الإحصائيات */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        /* الفوتر */
        .footer {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 30px 0;
            margin-top: 60px;
        }
    </style>
</head>
<body>

<!-- خلفية متحركة -->
<div class="bg-animation">
    <div class="circle" style="width: 300px; height: 300px; top: -100px; right: -100px; animation-duration: 25s;"></div>
    <div class="circle" style="width: 200px; height: 200px; bottom: 50px; left: -50px; animation-duration: 20s;"></div>
    <div class="circle" style="width: 150px; height: 150px; bottom: 30%; right: 20%; animation-duration: 30s;"></div>
    <div class="circle" style="width: 400px; height: 400px; top: 40%; left: -150px; animation-duration: 35s;"></div>
</div>

<!-- المحتوى الرئيسي -->
<div class="container py-5">
    <!-- الهيدر -->
    <div class="text-center text-white mb-5">
        <i class="fas fa-qrcode fa-3x mb-3"></i>
        <h1 class="display-4 fw-bold">SmartEvent System</h1>
        <p class="lead">نظام إدارة الفعاليات الذكي باستخدام QR Code</p>
    </div>

    <!-- البطاقة الرئيسية -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="hero-card p-5 text-center">
                <div class="mb-4">
                    <i class="fas fa-shield-alt fa-4x" style="color: #667eea;"></i>
                </div>
                <h2 class="mb-3" style="color: #333;">مرحباً بك في النظام</h2>
                <p class="text-muted mb-4">قم بتسجيل الدخول للوصول إلى لوحة التحكم وإدارة الفعاليات والدعوات</p>
                <a href="/admin/dashboard" class="btn btn-dashboard text-white">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    دخول إلى لوحة التحكم
                </a>
            </div>
        </div>
    </div>

    <!-- الميزات -->
    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon mx-auto">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h5>مسح QR Code</h5>
                <p class="text-muted">مسح سريع وآمن للدخول إلى الفعاليات</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon mx-auto">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h5>تحليل السلوك</h5>
                <p class="text-muted">نظام ذكي لكشف محاولات الاحتيال</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon mx-auto">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h5>إدارة الدعوات</h5>
                <p class="text-muted">إنشاء دعوات رقمية مشفرة</p>
            </div>
        </div>
    </div>

    <!-- إحصائيات -->
    <div class="row g-4 mt-4">
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <i class="fas fa-users fa-2x mb-2" style="color: #667eea;"></i>
                <h3 class="stat-number" id="statUsers">0</h3>
                <p class="text-muted mb-0">مستخدم نشط</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <i class="fas fa-calendar-alt fa-2x mb-2" style="color: #667eea;"></i>
                <h3 class="stat-number" id="statEvents">0</h3>
                <p class="text-muted mb-0">فعالية منشأة</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <i class="fas fa-ticket-alt fa-2x mb-2" style="color: #667eea;"></i>
                <h3 class="stat-number" id="statInvitations">0</h3>
                <p class="text-muted mb-0">دعوة مرسلة</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <i class="fas fa-check-circle fa-2x mb-2" style="color: #667eea;"></i>
                <h3 class="stat-number" id="statAttendances">0</h3>
                <p class="text-muted mb-0">حضور مؤكد</p>
            </div>
        </div>
    </div>
</div>

<!-- فوتر -->
<footer class="footer text-center">
    <div class="container">
        <p class="mb-0">&copy; 2026 SmartEvent System. جميع الحقوق محفوظة</p>
        <small>نظام إدارة الفعاليات الذكي باستخدام QR Code</small>
    </div>
</footer>

<script>
    // جلب الإحصائيات من الـ API
    async function fetchStats() {
        try {
            const response = await fetch('/api/stats');
            const stats = await response.json();
            
            document.getElementById('statUsers').textContent = stats.users || 0;
            document.getElementById('statEvents').textContent = stats.events || 0;
            document.getElementById('statInvitations').textContent = stats.invitations || 0;
            document.getElementById('statAttendances').textContent = stats.attendances || 0;
        } catch (error) {
            console.error('خطأ في جلب الإحصائيات:', error);
            // أرقام توضيحية في حالة الخطأ
            document.getElementById('statUsers').textContent = '0';
            document.getElementById('statEvents').textContent = '0';
            document.getElementById('statInvitations').textContent = '0';
            document.getElementById('statAttendances').textContent = '0';
        }
    }
    
    // تنفيذ جلب الإحصائيات عند تحميل الصفحة
    fetchStats();
</script>

</body>
</html>
