<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageLike;
use App\Models\MessageRead;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('id', '!=', auth()->id());

        if ($request->search) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }

        $users = $query->get();

        return view('messages.index', compact('users'));
    }


    // public function show_backup(User $user)
    // {
    //     $conversation = Conversation::firstOrCreate([
    //         'user_one' => min(auth()->id(), $user->id),
    //         'user_two' => max(auth()->id(), $user->id)
    //     ]);

    //     $messages = $conversation->messages()->with(['sender', 'likes', 'reads'])->get();

    //     foreach ($messages as $msg) {
    //         if ($msg->sender_id != auth()->id()) {
    //             $msg->reads()->firstOrCreate([
    //                 'user_id' => auth()->id()
    //             ]);
    //         }
    //     }

    //     // Fetch all users for chat list
    //     $users = User::where('id', '!=', auth()->id())->get();

    //     return view('messages.show', compact('user', 'messages', 'conversation', 'users'));
    // }
    public function show(User $user, Request $request)
    {
        // Get or create the conversation
        $conversation = Conversation::firstOrCreate([
            'user_one' => min(auth()->id(), $user->id),
            'user_two' => max(auth()->id(), $user->id)
        ]);

        // Get messages with relations
        $messages = $conversation->messages()->with(['sender', 'likes', 'reads'])->get();

        // Mark messages as read for current user
        foreach ($messages as $msg) {
            if ($msg->sender_id != auth()->id()) {
                $msg->reads()->firstOrCreate(['user_id' => auth()->id()]);
            }
        }

        // Handle AJAX request for auto-refresh
        if ($request->ajax()) {
            $html = view('messages.partials.ajax-messages', compact('messages', 'user'))->render();
            return response()->json(['html' => $html]);
        }

        // Fetch all users for chat list
        $users = User::where('id', '!=', auth()->id())->get();

        return view('messages.show', compact('user', 'messages', 'conversation', 'users'));
    }



    public function store(Request $request, User $user)
    {
        // Get or create the conversation between the two users
        $conversation = Conversation::firstOrCreate([
            'user_one' => min(auth()->id(), $user->id),
            'user_two' => max(auth()->id(), $user->id)
        ]);

        $filePath = null;
        $type = null;

        if ($request->hasFile('file')) {
            $ext = $request->file('file')->extension();
            $type = $ext === 'pdf' ? 'pdf' : 'image';
            $filePath = $request->file('file')->store('messages', 'public');
        }

        // Message::create([
        //     'conversation_id' => $conversation->id,
        //     'sender_id' => auth()->id(),
        //     'message' => $request->message,
        //     'file_path' => $filePath,
        //     'file_type' => $type
        // ]);
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
            'file_path' => $filePath,
            'file_type' => $type
        ]);


        return back();
    }


    public function like(Message $message)
    {
        MessageLike::firstOrCreate([
            'message_id' => $message->id,
            'user_id' => auth()->id()
        ]);
        return back();
    }

    public function update(Request $request, Message $message)
    {
        abort_if($message->sender_id !== auth()->id(), 403);
        $message->update([
            'message' => $request->message,
            'edited_at' => now()
        ]);
        return back();
    }

    public function destroy(Message $message)
    {
        abort_if($message->sender_id !== auth()->id(), 403);
        $message->update(['is_deleted' => true]);
        return back();
    }

    // Add this method to your MessageController
    public function ajaxMessages(User $user, Request $request)
    {
        $conversation = Conversation::firstOrCreate([
            'user_one' => min(auth()->id(), $user->id),
            'user_two' => max(auth()->id(), $user->id),
        ]);

        // Get last message ID from request
        $lastId = (int) $request->get('after_id', 0);

        // Fetch messages newer than last_id
        $messages = Message::where('conversation_id', $conversation->id)
            ->when($lastId, fn($q) => $q->where('id', '>', $lastId))
            ->with(['sender', 'reads'])
            ->orderBy('id')
            ->get();

        // Mark messages as read for current user
        foreach ($messages as $msg) {
            if ($msg->sender_id !== auth()->id()) {
                $msg->reads()->firstOrCreate(['user_id' => auth()->id()], ['read_at' => now()]);
            }
        }

        return response()->json([
            'html' => view('messages.partials.ajax-messages', compact('messages'))->render(),
            'last_id' => optional($messages->last())->id ?? $lastId,
        ]);
    }
}
