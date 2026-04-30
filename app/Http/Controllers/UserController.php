<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('invitations')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }
    
    public function create()
    {
        return view('users.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',
            'phone' => 'nullable|string|unique:users',
            'national_id' => 'nullable|string|unique:users',
            'organization' => 'nullable|string|max:255',
        ]);
        
        User::create($request->all());
        
        // ✅ تم التعديل: إضافة admin. قبل users.index
        return redirect()->route('admin.users.index')->with('success', 'تم إضافة المستخدم بنجاح');
    }
    
    public function show(User $user)
    {
        $user->load(['invitations.event', 'behaviorData']);
        return view('users.show', compact('user'));
    }
    
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|unique:users,phone,' . $user->id,
            'organization' => 'nullable|string|max:255',
        ]);
        
        $user->update($request->all());
        
        // ✅ تم التعديل: إضافة admin. قبل users.index
        return redirect()->route('admin.users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }
    
    public function toggleBlacklist(User $user)
    {
        $user->update(['is_blacklisted' => !$user->is_blacklisted]);
        
        $status = $user->is_blacklisted ? 'تم حظر المستخدم' : 'تم إلغاء حظر المستخدم';
        return back()->with('success', $status);
    }
    
    public function destroy(User $user)
    {
        $user->delete();
        
        // ✅ تم التعديل: إضافة admin. قبل users.index
        return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم');
    }
}