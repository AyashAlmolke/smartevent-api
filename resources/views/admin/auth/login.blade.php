<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المشرفين - SmartEvent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-header i {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .login-header h2 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        .login-header p {
            margin: 0;
            opacity: 0.9;
        }
        .login-body {
            padding: 40px;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 10px;
        }
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-shield-alt"></i>
                        <h2>SmartEvent System</h2>
                        <p>لوحة تحكم المشرفين</p>
                    </div>
                    <div class="login-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> 
                                {{ $errors->first() }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> 
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i> البريد الإلكتروني
                                </label>
                                <input type="email" name="email" class="form-control form-control-lg" 
                                       value="{{ old('email') }}" placeholder="admin@smartevent.com" 
                                       required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i> كلمة المرور
                                </label>
                                <input type="password" name="password" class="form-control form-control-lg" 
                                       placeholder="••••••••" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">
                                    <i class="fas fa-check-circle"></i> تذكرني
                                </label>
                            </div>
                            <button type="submit" class="btn btn-login btn-lg w-100 text-white">
                                <i class="fas fa-sign-in-alt"></i> دخول
                            </button>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center text-muted">
                            <small>
                                <i class="fas fa-shield-alt"></i> نظام إدارة فعاليات ذكي
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
