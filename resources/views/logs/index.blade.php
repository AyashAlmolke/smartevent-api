@extends('layouts.admin')

@section('title', 'السجلات')
@section('header', 'سجلات النظام')

@section('content')
<!-- إحصائيات سريعة -->
<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>إجمالي المحاولات</h5>
                <h3>{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>محاولات ناجحة</h5>
                <h3>{{ $stats['success'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5>محاولات فاشلة</h5>
                <h3>{{ $stats['fail'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>سلوك مشبوه</h5>
                <h3>{{ $stats['suspicious'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- فلتر البحث -->
<div class="card mt-3">
    <div class="card-header">
        <h5>فلترة السجلات</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row">
            <div class="col-md-4">
                <label>نوع العملية</label>
                <select name="action_type" class="form-control">
                    <option value="">الكل</option>
                    <option value="success" {{ request('action_type') == 'success' ? 'selected' : '' }}>ناجح</option>
                    <option value="fail" {{ request('action_type') == 'fail' ? 'selected' : '' }}>فشل</option>
                    <option value="suspicious" {{ request('action_type') == 'suspicious' ? 'selected' : '' }}>مشبوه</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>مستوى المخاطر</label>
                <select name="risk_level" class="form-control">
                    <option value="">الكل</option>
                    <option value="low" {{ request('risk_level') == 'low' ? 'selected' : '' }}>منخفض (&lt;40)</option>
                    <option value="medium" {{ request('risk_level') == 'medium' ? 'selected' : '' }}>متوسط (40-70)</option>
                    <option value="high" {{ request('risk_level') == 'high' ? 'selected' : '' }}>مرتفع (&gt;70)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control">بحث</button>
            </div>
        </form>
    </div>
</div>

<!-- جدول السجلات -->
<div class="card mt-3">
    <div class="card-header">
        <h5>جميع السجلات</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>الوقت</th>
                        <th>المستخدم</th>
                        <th>الفعالية</th>
                        <th>نوع العملية</th>
                        <th>الجهاز</th>
                        <th>Risk Score</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if($log->invitation && $log->invitation->user)
                                {{ $log->invitation->user->name }}
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
                            @if($log->action_type == 'success')
                                <span class="badge bg-success">✔ دخول ناجح</span>
                            @elseif($log->action_type == 'fail')
                                <span class="badge bg-danger">✘ فشل</span>
                            @elseif($log->action_type == 'suspicious')
                                <span class="badge bg-warning">⚠ مشبوه</span>
                            @elseif($log->action_type == 'blacklisted')
                                <span class="badge bg-dark">🚫 محظور</span>
                            @else
                                <span class="badge bg-secondary">{{ $log->action_type }}</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ Str::limit($log->device_info, 30) }}</small>
                        </td>
                        <td>
                            @if($log->risk_score > 70)
                                <span class="badge bg-danger">{{ $log->risk_score }}%</span>
                            @elseif($log->risk_score > 40)
                                <span class="badge bg-warning">{{ $log->risk_score }}%</span>
                            @else
                                <span class="badge bg-success">{{ $log->risk_score }}%</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.logs.show', $log) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">لا توجد سجلات</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-3">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
