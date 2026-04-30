@extends('layouts.admin')

@section('title', 'الدعوات')
@section('header', 'إدارة الدعوات')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">قائمة الدعوات</h3>
        <a href="{{ route('admin.invitations.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> دعوة جديدة
        </a>
    </div>
    <div class="card-body">
        @if($invitations->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>الفعالية</th>
                        <th>QR Code</th>
                        <th>الحالة</th>
                        <th>محاولات المسح</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invitations as $invitation)
                    <tr>
                        <td>{{ $invitation->id }}</td>
                        <td>{{ $invitation->user->name ?? '-' }}</td>
                        <td>{{ $invitation->event->event_name ?? '-' }}</td>
                        {{-- QR Code Column with validation --}}
                        <td>
                            <div style="width: 50px;">
                                @if($invitation->qr_code && !empty($invitation->qr_code) && strlen($invitation->qr_code) > 10)
                                    {!! QrCode::size(50)->generate($invitation->qr_code) !!}
                                @else
                                    <span class="badge bg-danger">⚠ غير صالح</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($invitation->status == 'active')
                                <span class="badge bg-success">نشطة</span>
                            @elseif($invitation->status == 'used')
                                <span class="badge bg-secondary">مستخدمة</span>
                            @else
                                <span class="badge bg-danger">منتهية</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $invitation->scan_attempts }}</span>
                        </td>
                        <td>{{ $invitation->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.invitations.show', $invitation) }}" class="btn btn-sm btn-info" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($invitation->qr_code && !empty($invitation->qr_code))
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" title="تحميل ومشاركة QR Code">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.invitations.download-qr', ['invitation' => $invitation, 'format' => 'svg']) }}">
                                            <i class="fas fa-code"></i> SVG (متجه)
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.invitations.download-qr', ['invitation' => $invitation, 'format' => 'png']) }}">
                                            <i class="fas fa-image"></i> PNG (صورة)
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.invitations.download-qr', ['invitation' => $invitation, 'format' => 'eps']) }}">
                                            <i class="fas fa-print"></i> EPS (طباعة)
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.invitations.download-qr', ['invitation' => $invitation, 'format' => 'pdf']) }}">
                                            <i class="fas fa-file-pdf"></i> PDF (وثيقة)
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="shareQR('{{ $invitation->id }}', '{{ addslashes($invitation->user->name ?? 'مدعو') }}', '{{ addslashes($invitation->event->event_name ?? 'فعالية') }}')">
                                                <i class="fab fa-whatsapp" style="color: #25D366;"></i> مشاركة عبر واتساب
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                @endif
                                <form action="{{ route('admin.invitations.destroy', $invitation) }}" method="POST" style="display:inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الدعوة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </div>
        </div>
        
        @else
        <div class="alert alert-info">
            لا توجد دعوات. <a href="{{ route('admin.invitations.create') }}">أنشئ دعوة جديدة</a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function shareQR(invitationId, userName, eventName) {
    // إنشاء رابط الدعوة
    var invitationLink = window.location.origin + '/admin/invitations/' + invitationId;
    
    // إنشاء رسالة للمشاركة
    var message = `🎫 *دعوة حضور فعالية* 🎫\n\n` +
                  `📌 *الفعالية:* ${eventName}\n` +
                  `👤 *المدعو:* ${userName}\n\n` +
                  `🔗 *رابط الدعوة:* ${invitationLink}\n\n` +
                  `📱 يرجى إظهار هذا الرابط أو QR Code عند وصولك للفعالية.\n\n` +
                  `🛡️ نظام SmartEvent - دخول آمن باستخدام QR Code`;
    
    // فتح واتساب مع الرسالة
    var url = `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(url, '_blank');
}
</script>
@endpush
