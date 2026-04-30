@extends('layouts.admin')

@section('title', 'تفاصيل الدعوة')
@section('header', 'تفاصيل الدعوة #' . $invitation->id)

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h5>QR Code</h5>
            </div>
            <div class="card-body text-center">
                <div class="qr-code">
                    {!! QrCode::size(250)->generate($invitation->qr_code) !!}
                </div>
                <div class="mt-3">
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download"></i> تحميل QR Code
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.invitations.download-qr', ['invitation' => $invitation, 'format' => 'svg']) }}">
                                <i class="fas fa-code"></i> SVG (متجه - للويب)
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.invitations.download-qr', ['invitation' => $invitation, 'format' => 'png']) }}">
                                <i class="fas fa-image"></i> PNG (صورة - للاستخدام العام)
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.invitations.download-qr', ['invitation' => $invitation, 'format' => 'eps']) }}">
                                <i class="fas fa-print"></i> EPS (للطباعة الاحترافية)
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.invitations.download-qr', ['invitation' => $invitation, 'format' => 'pdf']) }}">
                                <i class="fas fa-file-pdf"></i> PDF (وثيقة قابلة للطباعة)
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5>معلومات الدعوة</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>المستخدم</th><td>{{ $invitation->user->name ?? '-' }}</td></tr>
                    <tr><th>البريد الإلكتروني</th><td>{{ $invitation->user->email ?? '-' }}</td></tr>
                    <tr><th>رقم الهاتف</th><td>{{ $invitation->user->phone ?? '-' }}</td></tr>
                    <tr><th>الفعالية</th><td>{{ $invitation->event->event_name ?? '-' }}</td></tr>
                    <tr><th>مكان الفعالية</th><td>{{ $invitation->event->location ?? '-' }}</td></tr>
                    <tr><th>حالة الدعوة</th>
                        <td>
                            @if($invitation->status == 'active')
                                <span class="badge bg-success">نشطة</span>
                            @elseif($invitation->status == 'used')
                                <span class="badge bg-secondary">مستخدمة</span>
                            @else
                                <span class="badge bg-danger">منتهية</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>عدد محاولات المسح</th><td>{{ $invitation->scan_attempts }}</td></tr>
                    <tr><th>وقت الاستخدام</th><td>{{ $invitation->used_at ? $invitation->used_at->format('Y-m-d H:i:s') : 'لم يستخدم بعد' }}</td></tr>
                    <tr><th>تاريخ الإنشاء</th><td>{{ $invitation->created_at->format('Y-m-d H:i:s') }}</td></tr>
                </table>
            </div>
        </div>
        
        <!-- سجل محاولات الدخول لهذه الدعوة -->
        @if($invitation->logs && $invitation->logs->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-warning">
                <h5>📋 سجل محاولات الدخول</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>الوقت</th>
                                <th>نوع العملية</th>
                                <th>Risk Score</th>
                                <th>الجهاز</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invitation->logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                <tr>
                                    @if($log->action_type == 'success')
                                        <span class="badge bg-success">دخول ناجح</span>
                                    @else
                                        <span class="badge bg-danger">{{ $log->action_type }}</span>
                                    @endif
                                </table>
                                <tr>
                                    <span class="badge {{ $log->risk_score > 70 ? 'bg-danger' : 'bg-warning' }}">
                                        {{ $log->risk_score }}%
                                    </span>
                                </td>
                                <td><small>{{ Str::limit($log->device_info, 30) }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        
        <!-- معلومات الحضور -->
        @if($invitation->attendance)
        <div class="card mt-3 bg-success text-white">
            <div class="card-header">
                <h5>✅ معلومات الحضور</h5>
            </div>
            <div class="card-body">
                <p><strong>وقت الدخول:</strong> {{ $invitation->attendance->check_in_time->format('Y-m-d H:i:s') }}</p>
                <p><strong>الجهاز المستخدم:</strong> {{ $invitation->attendance->device_info ?? '-' }}</p>
                <p><strong>IP Address:</strong> {{ $invitation->attendance->ip_address ?? '-' }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.invitations.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> رجوع
    </a>
</div>
@endsection
