@extends('layouts.admin')

@section('title', 'تحليل السلوك')
@section('header', 'تحليل سلوك المستخدمين')

@section('content')
<!-- إحصائيات -->
<div class="row">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>سلوك طبيعي</h5>
                <h3>{{ $stats['normal'] }}</h3>
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
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5>سلوك شاذ</h5>
                <h3>{{ $stats['abnormal'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>متوسط المخاطر</h5>
                <h3>{{ round($stats['avg_risk']) }}%</h3>
            </div>
        </div>
    </div>
</div>

<!-- أكثر المستخدمين خطورة -->
<div class="card mt-3">
    <div class="card-header">
        <h5>أكثر 10 مستخدمين خطورة</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>مجموع المخاطر</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topRiskyUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>
                            <span class="badge bg-danger">{{ round($user->behavior_data_sum_risk_score) }}%</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">
                                عرض
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- جدول تحليل السلوك -->
<div class="card mt-3">
    <div class="card-header">
        <h5>سجل تحليل السلوك</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>المستخدم</th>
                        <th>الفعالية</th>
                        <th>عدد المحاولات</th>
                        <th>Risk Score</th>
                        <th>نوع السلوك</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($behaviorData as $data)
                    <tr>
                        <td>{{ $data->user->name ?? '-' }}</td>
                        <td>{{ $data->event->event_name ?? '-' }}</td>
                        <td>{{ $data->attempts_count }}</td>
                        <td>
                            <span class="badge {{ $data->risk_score > 70 ? 'bg-danger' : ($data->risk_score > 40 ? 'bg-warning' : 'bg-success') }}">
                                {{ $data->risk_score }}%
                            </span>
                        </td>
                        <td>
                            @if($data->behavior_type == 'normal')
                                <span class="badge bg-success">طبيعي</span>
                            @elseif($data->behavior_type == 'suspicious')
                                <span class="badge bg-warning">مشبوه</span>
                            @else
                                <span class="badge bg-danger">شاذ</span>
                            @endif
                        </td>
                        <td>{{ $data->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">لا توجد بيانات</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-3">
            {{ $behaviorData->links() }}
        </div>
    </div>
</div>
@endsection
