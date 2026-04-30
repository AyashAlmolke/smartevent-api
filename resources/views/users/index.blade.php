@extends('layouts.admin')

@section('title', 'المستخدمين')
@section('header', 'إدارة المستخدمين')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-users"></i> قائمة المستخدمين
        </h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> إضافة مستخدم جديد
        </a>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 20%">الاسم</th>
                        <th style="width: 20%">البريد الإلكتروني</th>
                        <th style="width: 15%">رقم الهاتف</th>
                        <th style="width: 15%">المنظمة</th>
                        <th style="width: 5%">الدعوات</th>
                        <th style="width: 8%">الحالة</th>
                        <th style="width: 12%">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="text-center">{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td dir="ltr">{{ $user->email ?? '-' }}</td>
                        <td dir="ltr">{{ $user->phone ?? '-' }}</td>
                        <td>{{ $user->organization ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $user->invitations_count }}</span>
                        </td>
                        <td class="text-center">
                            @if($user->is_blacklisted)
                                <span class="badge bg-danger px-3 py-2">محظور</span>
                            @else
                                <span class="badge bg-success px-3 py-2">نشط</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.blacklist', $user) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    <button type="submit" class="btn {{ $user->is_blacklisted ? 'btn-success' : 'btn-danger' }}" title="{{ $user->is_blacklisted ? 'إلغاء الحظر' : 'حظر' }}">
                                        <i class="fas {{ $user->is_blacklisted ? 'fa-check' : 'fa-ban' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3 d-flex justify-content-center">
            {{ $users->links() }}
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> لا يوجد مستخدمين حالياً. 
            <a href="{{ route('admin.users.create') }}">أضف مستخدم جديد</a>
        </div>
        @endif
    </div>
</div>
@endsection