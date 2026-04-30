@extends('layouts.admin')

@section('title', 'إنشاء دعوة سريعة')
@section('header', '⚡ إنشاء دعوة سريعة')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> إنشاء دعوة في خطوة واحدة</h5>
            </div>
            <div class="card-body">
                
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <hr>
                        <strong>المستخدم:</strong> {{ session('last_user')['name'] ?? '' }}<br>
                        <strong>رقم الدعوة:</strong> #{{ session('last_invitation')['id'] ?? '' }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.invitations.quick.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">الفعالية <span class="text-danger">*</span></label>
                        <select name="event_id" class="form-control @error('event_id') is-invalid @enderror" required>
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

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> إذا كان المستخدم موجوداً، سيتم إنشاء دعوة له مباشرة.
                    </div>

                    <h6 class="mb-3">بيانات المدعو:</h6>

                    <div class="mb-3">
                        <label class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-ticket-alt"></i> إنشاء الدعوة
                        </button>
                        <a href="{{ route('admin.invitations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> العودة للدعوات
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
