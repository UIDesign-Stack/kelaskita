<?php

namespace App\Http\Controllers;

use App\Models\CommunicationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentCommunicationController extends Controller
{
    public function index()
    {
        $guardian = Auth::user()->guardian;

        $childrenIds = $guardian ? $guardian->students()->pluck('students.id') : collect();

        $logs = CommunicationLog::with('student')
            ->whereIn('student_id', $childrenIds)
            ->latest('date')
            ->get();

        return view('parent-communication-logs.index', compact('logs', 'guardian'));
    }

    public function reply(Request $request, CommunicationLog $log)
    {
        $guardian = Auth::user()->guardian;

        $isMyChild = $guardian && $guardian->students()->where('students.id', $log->student_id)->exists();

        abort_unless($isMyChild, 403, 'Ini bukan catatan untuk anak Anda.');

        $validated = $request->validate([
            'parent_note' => ['required', 'string', 'max:1000'],
        ]);

        $log->update([
            'parent_note' => $validated['parent_note'],
            'parent_replied_at' => now(),
        ]);

        return redirect()
            ->route('orang-tua.communication-logs.index')
            ->with('status', 'Balasan berhasil dikirim.');
    }
}