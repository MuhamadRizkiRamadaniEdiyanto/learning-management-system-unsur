<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display all messages for a specific course.
     */
    public function index(Course $course)
    {
        $this->authorize('view', $course);

        $messages = $course->messages()
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($messages);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request, Course $course)
    {
        $this->authorize('create', [Message::class, $course]);

        $validated = $request->validate([
            'isi' => 'required|string|max:5000',
            'receiver_id' => 'nullable|exists:users,id',
        ]);

        $message = $course->messages()->create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'] ?? null,
            'isi' => $validated['isi'],
        ]);

        return response()->json($message->load(['sender', 'receiver']), 201);
    }

    /**
     * Mark a message as read.
     */
    public function markAsRead(Message $message)
    {
        $this->authorize('view', $message->course);

        if ($message->receiver_id === Auth::id() && is_null($message->dibaca_at)) {
            $message->update(['dibaca_at' => now()]);
        }

        return response()->json($message);
    }
}
