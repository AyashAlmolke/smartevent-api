@extends('layouts.admin')

@section('title', 'تفاصيل المستخدم')
@section('header', 'تفاصيل المستخدم: ' . $user->name)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-circle"></i> معلومات أساسية</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">الاسم:</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>البريد:</th>
                        <td>{{ $user->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>الهاتف:</th>
                        <td>{{ $user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>الرقم الوطني:</th>
                        <td>{{ $user->national_id ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>المنظمة:</th>
                        <td>{{ $user->organization ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>الحالة:</th>
                        <td>
                            @if($user->is_blacklisted)
                                <span class="badge bg-danger">محظور</span>
                            @else
                                <span class="badge bg-success">نشط</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>تاريخ التسجيل:</th>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-ticket-alt"></i> الدعوات</h5>
            </div>
            <div class="card-body">
                @if($user->invitations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الفعالية</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->invitations as $index => $invitation)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $invitation->event->event_name ?? '-' }}
                                    @if($invitation->event)
                                        <br><small class="text-muted">{{ $invitation->event->location ?? '' }}</small>
                                    @endif
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
                                <td>{{ $invitation->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center">لا توجد دعوات لهذا المستخدم</div>
                @endif
            </div>
        </div>
        
        @if($user->behaviorData->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-chart-line"></i> تحليل السلوك</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>الفعالية</th>
                                <th>Risk Score</th>
                                <th>نوع السلوك</th>
                                <th>عدد المحاولات</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->behaviorData as $behavior)
                            <tr>
                                <td>{{ $behavior->event->event_name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $behavior->risk_score > 70 ? 'bg-danger' : ($behavior->risk_score > 40 ? 'bg-warning' : 'bg-success') }} p-2">
                                        {{ $behavior->risk_score }}%
                                    </span>
                                </td>
                                <td>
                                    @if($behavior->behavior_type == 'normal')
                                        <span class="badge bg-success">طبيعي</span>
                                    @elseif($behavior->behavior_type == 'suspicious')
                                        <span class="badge bg-warning">مشبوه</span>
                                    @else
                                        <span class="badge bg-danger">شاذ</span>
                                    @endif
                                </td>
                                <td>{{ $behavior->attempts_count }}</td>
                                <td>{{ $behavior->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>
@endsection