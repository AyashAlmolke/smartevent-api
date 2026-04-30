@extends('layouts.admin')

@section('title', 'تفاصيل السجل')
@section('header', 'تفاصيل السجل #' . $log->id)

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>معلومات العملية</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>الوقت:</th>
                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>نوع العملية:</th>
                        <td>
                            @if($log->action_type == 'success')
                                <span class="badge bg-success">دخول ناجح</span>
                            @elseif($log->action_type == 'fail')
                                <span class="badge bg-danger">فشل</span>
                            @elseif($log->action_type == 'suspicious')
                                <span class="badge bg-warning">مشبوه</span>
                            @else
                                <span class="badge bg-secondary">{{ $log->action_type }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Risk Score:</th>
                        <td>
                            <span class="badge {{ $log->risk_score > 70 ? 'bg-danger' : ($log->risk_score > 40 ? 'bg-warning' : 'bg-success') }}">
                                {{ $log->risk_score }}%
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>الجهاز:</th>
                        <td><small>{{ $log->device_info ?? '-' }}</small></td>
                    </tr>
                    <tr>
                        <th>IP Address:</th>
                        <td>{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>الرسالة:</th>
                        <td>{{ $log->message ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>المستخدم</h5>
            </div>
            <div class="card-body">
                @if($log->invitation && $log->invitation->user)
                    <p><strong>الاسم:</strong> {{ $log->invitation->user->name }}</p>
                    <p><strong>البريد:</strong> {{ $log->invitation->user->email ?? '-' }}</p>
                    <p><strong>الهاتف:</strong> {{ $log->invitation->user->phone ?? '-' }}</p>
                    <p><strong>المنظمة:</strong> {{ $log->invitation->user->organization ?? '-' }}</p>
                @else
                    <p class="text-muted">لا توجد معلومات عن المستخدم</p>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>الفعالية</h5>
            </div>
            <div class="card-body">
                @if($log->invitation && $log->invitation->event)
                    <p><strong>اسم الفعالية:</strong> {{ $log->invitation->event->event_name }}</p>
                    <p><strong>المكان:</strong> {{ $log->invitation->event->location }}</p>
                    <p><strong>التاريخ:</strong> {{ $log->invitation->event->start_time ? $log->invitation->event->start_time->format('Y-m-d H:i') : '-' }}</p>
                @else
                    <p class="text-muted">لا توجد معلومات عن الفعالية</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('logs.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> رجوع
    </a>
</div>
@endsection

