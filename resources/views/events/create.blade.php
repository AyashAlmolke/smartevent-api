@extends('layouts.admin')

@section('title', 'إضافة فعالية')
@section('header', 'إضافة فعالية جديدة')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">بيانات الفعالية</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.events.store') }}" id="eventForm">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="event_name" class="form-label">اسم الفعالية <span class="text-danger">*</span></label>
                    <input type="text" name="event_name" id="event_name" class="form-control @error('event_name') is-invalid @enderror" value="{{ old('event_name') }}" required>
                    @error('event_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="location" class="form-label">المكان <span class="text-danger">*</span></label>
                    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" required>
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">تاريخ ووقت البدء <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">تاريخ ووقت الانتهاء</label>
                    <input type="datetime-local" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}">
                    <small class="text-muted">يجب أن يكون بعد وقت البدء</small>
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="max_attendees" class="form-label">الحد الأقصى للحضور</label>
                    <input type="number" name="max_attendees" id="max_attendees" class="form-control @error('max_attendees') is-invalid @enderror" value="{{ old('max_attendees') }}" min="1">
                    @error('max_attendees')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">الحالة</label>
                    <select name="status" id="status" class="form-control">
                        <option value="draft">مسودة</option>
                        <option value="active" selected>نشطة</option>
                        <option value="finished">منتهية</option>
                    </select>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> ملاحظة: يمكنك تغيير حالة الفعالية لاحقاً من صفحة التعديل.
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> حفظ
                </button>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        let startTime = document.getElementById('start_time').value;
        let endTime = document.getElementById('end_time').value;
        
        if (endTime && startTime && new Date(endTime) <= new Date(startTime)) {
            e.preventDefault();
            alert('خطأ: وقت الانتهاء يجب أن يكون بعد وقت البدء');
            return false;
        }
    });
</script>
@endpush
@endsection