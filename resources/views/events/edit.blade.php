@extends('layouts.admin')

@section('title', 'تعديل فعالية')
@section('header', 'تعديل الفعالية: ' . $event->event_name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">تعديل بيانات الفعالية</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.events.update', $event) }}" id="eventForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="event_name" class="form-label">اسم الفعالية <span class="text-danger">*</span></label>
                    <input type="text" name="event_name" id="event_name" class="form-control @error('event_name') is-invalid @enderror" value="{{ old('event_name', $event->event_name) }}" required>
                    @error('event_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="location" class="form-label">المكان <span class="text-danger">*</span></label>
                    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $event->location) }}" required>
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">تاريخ ووقت البدء <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $event->start_time ? $event->start_time->format('Y-m-d\TH:i') : '') }}" required>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">تاريخ ووقت الانتهاء</label>
                    <input type="datetime-local" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $event->end_time ? $event->end_time->format('Y-m-d\TH:i') : '') }}">
                    <small class="text-muted">يجب أن يكون بعد وقت البدء</small>
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="max_attendees" class="form-label">الحد الأقصى للحضور</label>
                    <input type="number" name="max_attendees" id="max_attendees" class="form-control @error('max_attendees') is-invalid @enderror" value="{{ old('max_attendees', $event->max_attendees) }}" min="1">
                    @error('max_attendees')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">الحالة</label>
                    <select name="status" id="status" class="form-control">
                        <option value="draft" {{ $event->status == 'draft' ? 'selected' : '' }}>مسودة</option>
                        <option value="active" {{ $event->status == 'active' ? 'selected' : '' }}>نشطة</option>
                        <option value="finished" {{ $event->status == 'finished' ? 'selected' : '' }}>منتهية</option>
                    </select>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> تنبيه: تغيير حالة الفعالية إلى "منتهية" سيمنع أي محاولات دخول جديدة.
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> تحديث
                </button>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
