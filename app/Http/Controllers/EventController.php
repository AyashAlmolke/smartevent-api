<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount(['invitations', 'attendances'])->latest()->paginate(10);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'description' => 'nullable|string',
            'max_attendees' => 'nullable|integer|min:1',
        ]);

        Event::create([
            'event_name' => $request->event_name,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
            'max_attendees' => $request->max_attendees,
            'status' => 'active',
            'admin_id' => 1, // مؤقتاً، يمكنك ربطه بالأدمن المسجل
        ]);

        // ✅ تم التعديل: إضافة admin. قبل events.index
        return redirect()->route('admin.events.index')->with('success', 'تم إنشاء الفعالية بنجاح');
    }

    public function show(Event $event)
    {
        $event->load(['invitations.user', 'attendances']);
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,finished',
        ]);

        $event->update($request->all());

        // ✅ تم التعديل: إضافة admin. قبل events.index
        return redirect()->route('admin.events.index')->with('success', 'تم تحديث الفعالية بنجاح');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        // ✅ تم التعديل: إضافة admin. قبل events.index
        return redirect()->route('admin.events.index')->with('success', 'تم حذف الفعالية بنجاح');
    }
}
