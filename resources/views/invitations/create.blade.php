@extends('layouts.admin')

@section('title', 'إنشاء دعوة')
@section('header', 'إنشاء دعوة جديدة')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">بيانات الدعوة</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.invitations.store') }}" id="invitationForm">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="user_id" class="form-label">المستخدم <span class="text-danger">*</span></label>
                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                        <option value="">-- اختر مستخدم --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email ?? $user->phone ?? 'لا يوجد' }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="event_id" class="form-label">الفعالية <span class="text-danger">*</span></label>
                    <select name="event_id" id="event_id" class="form-control @error('event_id') is-invalid @enderror" required>
                        <option value="">-- اختر فعالية --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->event_name }} ({{ $event->location }})
                            </option>
                        @endforeach
                    </select>
                    @error('event_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- ميزات الأمان -->
            <div class="alert alert-info">
                <i class="fas fa-shield-alt"></i> <strong>ميزات الأمان التالية ستعمل تلقائياً:</strong>
                <ul class="mt-2 mb-0">
                    <li>✅ QR Code مشفر بشكل آمن</li>
                    <li>✅ منع تكرار الدعوة لنفس المستخدم لنفس الفعالية</li>
                    <li>✅ تسجيل عدد محاولات المسح</li>
                    <li>✅ منع استخدام QR بعد الاستخدام</li>
                    <li>✅ تحليل سلوك المستخدم تلقائياً</li>
                </ul>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-ticket-alt"></i> إنشاء الدعوة
                </button>
                <a href="{{ route('admin.invitations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // تحقق إضافي قبل الإرسال
    document.getElementById('invitationForm').addEventListener('submit', function(e) {
        let userId = document.getElementById('user_id').value;
        let eventId = document.getElementById('event_id').value;
        
        if (!userId || !eventId) {
            e.preventDefault();
            alert('الرجاء اختيار المستخدم والفعالية');
            return false;
        }
    });
</script>
@endpush
@endsection
