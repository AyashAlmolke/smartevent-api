@extends('layouts.admin')

@section('title', 'اكتشاف الاحتيال')
@section('header', 'نظام كشف الاحتيال الذكي')

@section('content')
<style>
    .risk-gauge {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 24px;
        color: white;
        margin: 0 auto;
    }
    
    .risk-critical { background: linear-gradient(135deg, #dc3545, #c82333); box-shadow: 0 0 15px rgba(220,53,69,0.5); }
    .risk-high { background: linear-gradient(135deg, #fd7e14, #e8590c); box-shadow: 0 0 15px rgba(253,126,20,0.4); }
    .risk-medium { background: linear-gradient(135deg, #ffc107, #e0a800); box-shadow: 0 0 15px rgba(255,193,7,0.3); }
    .risk-low { background: linear-gradient(135deg, #28a745, #1e7e34); box-shadow: 0 0 15px rgba(40,167,69,0.3); }
    
    .fraud-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .fraud-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.15);
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 0.6; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.05); }
        100% { opacity: 0.6; transform: scale(1); }
    }
    
    .alert-card {
        border-right: 4px solid;
        transition: all 0.3s ease;
    }
    
    .alert-card:hover {
        background-color: #fff3f0;
    }
    
    .stat-number {
        font-size: 32px;
        font-weight: 800;
    }
    
    .suspicion-table tbody tr:hover {
        background-color: #fff8e7;
        cursor: pointer;
    }
    
    .glow-text {
        text-shadow: 0 0 10px rgba(220,53,69,0.5);
    }
</style>

<!-- بطاقات الإحصائيات الرئيسية -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card fraud-card bg-gradient-danger text-white border-0 shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="opacity-75">محاولات مشبوهة</small>
                        <h2 class="mb-0 fw-bold">{{ $fraudStats['total_suspicious'] }}</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                        <div class="progress-bar bg-white" style="width: {{ min($fraudStats['total_suspicious'] * 5, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card fraud-card bg-gradient-dark text-white border-0 shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="opacity-75">مستخدمين محظورين</small>
                        <h2 class="mb-0 fw-bold">{{ $fraudStats['blacklisted_users'] }}</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-ban fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card fraud-card bg-gradient-warning text-white border-0 shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="opacity-75">محاولات مكررة</small>
                        <h2 class="mb-0 fw-bold">{{ $fraudStats['duplicate_attempts'] }}</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-retweet fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card fraud-card bg-gradient-info text-white border-0 shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="opacity-75">محاولات فاشلة</small>
                        <h2 class="mb-0 fw-bold">{{ $fraudStats['failed_attempts'] }}</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مستوى الأمان العام -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-shield-alt text-primary me-2"></i>
                            مستوى أمان النظام
                        </h5>
                        <small class="text-muted">بناءً على تحليل المخاطر والسلوكيات المشبوهة</small>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        @php
                            $securityScore = max(0, 100 - ($fraudStats['total_suspicious'] * 2) - ($fraudStats['failed_attempts'] * 0.5));
                            $securityScore = min(100, $securityScore);
                            $securityLevel = $securityScore >= 80 ? 'ممتاز' : ($securityScore >= 60 ? 'جيد' : ($securityScore >= 40 ? 'متوسط' : 'ضعيف'));
                            $securityColor = $securityScore >= 80 ? 'success' : ($securityScore >= 60 ? 'info' : ($securityScore >= 40 ? 'warning' : 'danger'));
                        @endphp
                        <span class="badge bg-{{ $securityColor }} fs-6 px-3 py-2 rounded-pill">
                            <i class="fas fa-chart-line me-1"></i>
                            مستوى الأمان: {{ $securityLevel }} ({{ round($securityScore) }}%)
                        </span>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 8px; border-radius: 10px;">
                    <div class="progress-bar bg-{{ $securityColor }} progress-bar-striped progress-bar-animated" 
                         style="width: {{ $securityScore }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- المستخدمين عاليي المخاطر -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-transparent border-0 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-skull-crosswalk text-danger me-2"></i>
                المستخدمين عاليي المخاطر
                <span class="badge bg-danger ms-2 pulse">تنبيه!</span>
            </h5>
            <span class="badge bg-light text-dark">{{ $highRiskUsers->count() }} مستخدم</span>
        </div>
    </div>
    <div class="card-body">
        @if($highRiskUsers->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover suspicion-table">
                <thead class="table-light">
                    <tr>
                        <th><i class="fas fa-user"></i> المستخدم</th>
                        <th><i class="fas fa-envelope"></i> البريد الإلكتروني</th>
                        <th><i class="fas fa-chart-line"></i> Risk Score</th>
                        <th><i class="fas fa-brain"></i> نوع السلوك</th>
                        <th><i class="fas fa-clock"></i> آخر نشاط</th>
                        <th><i class="fas fa-cogs"></i> الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($highRiskUsers as $user)
                        @foreach($user->behaviorData as $behavior)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-2">
                                        <i class="fas fa-user-secret text-danger"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email ?? '-' }}</td>
                            <td>
                                <div class="text-center">
                                    <span class="badge bg-danger rounded-pill px-3 py-2">
                                        {{ $behavior->risk_score }}%
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                    <i class="fas fa-flag me-1"></i> {{ $behavior->behavior_type }}
                                </span>
                            </td>
                            <td>{{ $behavior->updated_at->diffForHumans() }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.users.blacklist', $user) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger" title="حظر المستخدم">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </div>
        </div>
        @else
        <div class="alert alert-success text-center">
            <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
            <h5>لا توجد مخاطر عالية!</h5>
            <p class="mb-0">جميع المستخدمين يتصرفون بشكل طبيعي</p>
        </div>
        @endif
    </div>
</div>

<!-- آخر المحاولات المشبوهة -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-transparent border-0 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-history text-warning me-2"></i>
                آخر المحاولات المشبوهة
            </h5>
            <span class="badge bg-light text-dark">آخر 50 محاولة</span>
        </div>
    </div>
    <div class="card-body p-0">
        @if($suspiciousLogs->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><i class="fas fa-clock"></i> الوقت</th>
                        <th><i class="fas fa-user"></i> المستخدم</th>
                        <th><i class="fas fa-calendar"></i> الفعالية</th>
                        <th><i class="fas fa-chart-line"></i> Risk Score</th>
                        <th><i class="fas fa-mobile-alt"></i> الجهاز</th>
                        <th><i class="fas fa-network-wired"></i> IP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suspiciousLogs as $log)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                <span dir="ltr">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                        </td>
                        <td>
                            @if($log->invitation && $log->invitation->user)
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-warning bg-opacity-10 p-1 me-2">
                                        <i class="fas fa-user text-warning fa-sm"></i>
                                    </div>
                                    <span>{{ $log->invitation->user->name }}</span>
                                </div>
                            @else
                                <span class="text-muted">غير معروف</span>
                            @endif
                        </td>
                        <td>
                            @if($log->invitation && $log->invitation->event)
                                {{ $log->invitation->event->event_name }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-center">
                                <span class="badge {{ $log->risk_score > 80 ? 'bg-danger pulse' : ($log->risk_score > 60 ? 'bg-warning' : 'bg-secondary') }} rounded-pill px-3 py-1">
                                    {{ $log->risk_score }}%
                                </span>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($log->device_info ?? '-', 30) }}</small>
                        </td>
                        <td><small dir="ltr">{{ $log->ip_address ?? '-' }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-check-circle fa-4x text-success mb-3 d-block"></i>
            <h5>لا توجد محاولات مشبوهة!</h5>
            <p class="text-muted">جميع المحاولات كانت ضمن الحدود الطبيعية</p>
        </div>
        @endif
    </div>
</div>

<!-- نصائح أمنية -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 bg-gradient-primary text-white shadow-sm rounded-4">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>نصائح أمنية:</strong>
                        <span class="opacity-75 ms-2">قم بمراجعة المستخدمين عاليي المخاطر بشكل دوري، واستخدم نظام الحظر التلقائي للمستخدمين المشبوهين.</span>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <button class="btn btn-light btn-sm rounded-pill" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> تحديث البيانات
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // تحديث تلقائي للصفحة كل 30 ثانية (اختياري)
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endpush


