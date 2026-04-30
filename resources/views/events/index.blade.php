@extends('layouts.admin')

@section('title', 'الفعاليات')
@section('header', 'إدارة الفعاليات')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">قائمة الفعاليات</h3>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> فعالية جديدة
        </a>
    </div>
    <div class="card-body">
        @if($events->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>اسم الفعالية</th>
                        <th>المكان</th>
                        <th>التاريخ</th>
                        <th>الدعوات</th>
                        <th>الحضور</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td>{{ $event->event_name }}</td>
                        <td>{{ $event->location }}</td>
                        <td>{{ $event->start_time ? $event->start_time->format('Y-m-d H:i') : '-' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $event->invitations_count ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $event->attendances_count ?? 0 }}</span>
                        </td>
                        <td>
                            @if($event->status == 'active')
                                <span class="badge bg-success">نشطة</span>
                            @elseif($event->status == 'draft')
                                <span class="badge bg-warning">مسودة</span>
                            @else
                                <span class="badge bg-secondary">منتهية</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الفعالية؟')">
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
        
        <div class="mt-3">
            {{ $events->links() }}
        </div>
        @else
        <div class="alert alert-info">
            لا توجد فعاليات. <a href="{{ route('admin.events.create') }}">أنشئ فعالية جديدة</a>
        </div>
        @endif
    </div>
</div>
@endsection
