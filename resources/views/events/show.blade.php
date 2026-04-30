@extends('layouts.admin')

@section('title', 'تفاصيل الفعالية')
@section('header', 'تفاصيل الفعالية: ' . $event->event_name)

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>معلومات الفعالية</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>الاسم</th><td>{{ $event->event_name }}</td></tr>
                    <tr><th>المكان</th><td>{{ $event->location }}</td></tr>
                    <tr><th>تاريخ البدء</th><td>{{ $event->start_time ? $event->start_time->format('Y-m-d H:i') : '-' }}</td></tr>
                    <tr><th>تاريخ الانتهاء</th><td>{{ $event->end_time ? $event->end_time->format('Y-m-d H:i') : '-' }}</td></tr>
                    <tr><th>الحد الأقصى</th><td>{{ $event->max_attendees ?? 'غير محدد' }}</td></tr>
                    <tr><th>الحالة</th>
                        <td>
                            @if($event->status == 'active')
                                <span class="badge bg-success">نشطة</span>
                            @elseif($event->status == 'draft')
                                <span class="badge bg-warning">مسودة</span>
                            @else
                                <span class="badge bg-secondary">منتهية</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>الوصف</th><td>{{ $event->description ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>إحصائيات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <h3>{{ $event->invitations->count() }}</h3>
                            <p class="text-muted">إجمالي الدعوات</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <h3>{{ $event->attendances->count() }}</h3>
                            <p class="text-muted">عدد الحضور</p>
                        </div>
                    </div>
                </div>
                
                @if($event->max_attendees)
                <div class="mt-3">
                    <label>نسبة الإشغال</label>
                    <div class="progress">
                        @php
                            $percentage = ($event->attendances->count() / $event->max_attendees) * 100;
                        @endphp
                        <div class="progress-bar {{ $percentage > 90 ? 'bg-danger' : ($percentage > 70 ? 'bg-warning' : 'bg-success') }}" 
                             style="width: {{ min($percentage, 100) }}%">
                            {{ round($percentage) }}%
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('events.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> رجوع
    </a>
    <a href="{{ route('events.edit', $event) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> تعديل
    </a>
</div>
@endsection

