<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvitationController extends Controller
{
    public function index()
    {
        //$invitations = Invitation::with(['user', 'event', 'attendance'])->latest()->paginate(10);
        //$invitations = Invitation::with(['user', 'event', 'attendance'])->latest()->get();
        //$invitations = Invitation::with(['user', 'event', 'attendance'])->orderBy('id', 'desc')->get();
        $invitations = Invitation::with(['user', 'event', 'attendance'])->oldest()->get();
        return view('invitations.index', compact('invitations'));
    }

    public function create()
    {
        $users = User::where('is_blacklisted', false)->get();
        $events = Event::where('status', 'active')->get();
        return view('invitations.create', compact('users', 'events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
        ]);
        
        // منع تكرار الدعوة
        $exists = Invitation::where('user_id', $request->user_id)
            ->where('event_id', $request->event_id)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'هذا المستخدم لديه دعوة مسبقة لهذه الفعالية');
        }
        
        // إنشاء الدعوة (بدون qr_code أولاً)
        $invitation = new Invitation();
        $invitation->user_id = $request->user_id;
        $invitation->event_id = $request->event_id;
        $invitation->status = 'active';
        $invitation->scan_attempts = 0;
        $invitation->qr_code = ''; // قيمة مؤقتة
        $invitation->save();
        
        // توليد QR مشفر
        $qrPayload = json_encode([
            'invitation_id' => $invitation->id,
            'user_id' => $invitation->user_id,
            'event_id' => $invitation->event_id,
            'created_at' => now()->timestamp,
        ]);
        
        $invitation->qr_code = Crypt::encryptString($qrPayload);
        $invitation->save();
        
        return redirect()->route('admin.invitations.index')->with('success', 'تم إنشاء الدعوة بنجاح');
    }
    
    public function show(Invitation $invitation)
    {
        $invitation->load(['user', 'event', 'attendance', 'logs']);
        return view('invitations.show', compact('invitation'));
    }
    
    public function edit(Invitation $invitation)
    {
        $users = User::where('is_blacklisted', false)->get();
        $events = Event::where('status', 'active')->get();
        return view('invitations.edit', compact('invitation', 'users', 'events'));
    }
    
    public function update(Request $request, Invitation $invitation)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'status' => 'required|in:active,used,expired',
        ]);
        
        $invitation->update([
            'user_id' => $request->user_id,
            'event_id' => $request->event_id,
            'status' => $request->status,
        ]);
        
        // إذا تم تغيير المستخدم أو الفعالية، قم بتحديث QR Code
        if ($invitation->wasChanged('user_id') || $invitation->wasChanged('event_id')) {
            $qrPayload = json_encode([
                'invitation_id' => $invitation->id,
                'user_id' => $invitation->user_id,
                'event_id' => $invitation->event_id,
                'created_at' => now()->timestamp,
            ]);
            
            $invitation->qr_code = Crypt::encryptString($qrPayload);
            $invitation->save();
        }
        
        return redirect()->route('admin.invitations.index')->with('success', 'تم تحديث الدعوة بنجاح');
    }
    
    public function destroy(Invitation $invitation)
    {
        $invitation->delete();
        return redirect()->route('admin.invitations.index')->with('success', 'تم حذف الدعوة بنجاح');
    }
    
    /**
     * تحميل QR Code بصيغ متعددة
     * الصيغ المدعومة: svg, png, eps, pdf
     */
    public function downloadQr(Invitation $invitation, $format = 'svg')
    {
        // التحقق من صحة الصيغة
        $allowedFormats = ['svg', 'png', 'eps', 'pdf'];
        if (!in_array($format, $allowedFormats)) {
            $format = 'svg';
        }
        
        $qrCode = QrCode::size(300);
        
        switch($format) {
            case 'png':
                $content = $qrCode->format('png')->generate($invitation->qr_code);
                $mimeType = 'image/png';
                break;
            case 'eps':
                $content = $qrCode->format('eps')->generate($invitation->qr_code);
                $mimeType = 'application/postscript';
                break;
            case 'pdf':
                $content = $qrCode->format('pdf')->generate($invitation->qr_code);
                $mimeType = 'application/pdf';
                break;
            case 'svg':
            default:
                $content = $qrCode->format('svg')->generate($invitation->qr_code);
                $mimeType = 'image/svg+xml';
                break;
        }
        
        return response($content)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'attachment; filename="qrcode_invitation_' . $invitation->id . '.' . $format . '"');
    }
    
    public function regenerateQr(Invitation $invitation)
    {
        $qrPayload = json_encode([
            'invitation_id' => $invitation->id,
            'user_id' => $invitation->user_id,
            'event_id' => $invitation->event_id,
            'created_at' => now()->timestamp,
        ]);
        
        $invitation->qr_code = Crypt::encryptString($qrPayload);
        $invitation->save();
        
        return back()->with('success', 'تم إعادة إنشاء QR Code بنجاح');
    }
}
