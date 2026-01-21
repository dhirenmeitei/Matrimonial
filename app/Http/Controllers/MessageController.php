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
    // public function index_old(Request $request)
    // {
    //     $users = User::where('id', '!=', auth()->id())
    //         ->when($request->search, function ($query, $search) {
    //             $query->where('username', 'like', '%' . $search . '%');
    //         })
    //         ->get();

    //     return view('messages.index', compact('users'));
    // }
    public function index(Request $request)
    {
        $me = auth()->id();
        $search = $request->search;

        // Get users who have a conversation with me
        $users = User::where('id', '!=', $me)
            ->where(function ($q) use ($me) {
                $q->whereIn('id', function ($sub) use ($me) {
                    $sub->select('user_one')
                        ->from('conversations')
                        ->where('user_two', $me);
                })
                    ->orWhereIn('id', function ($sub) use ($me) {
                        $sub->select('user_two')
                            ->from('conversations')
                            ->where('user_one', $me);
                    });
            })
            ->when($search, function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%");
            })
            ->get();

        return view('messages.index', compact('users'));
    }


    // AJAX friends search
    public function ajaxFriends(Request $request)
    {
        $users = User::where('id', '!=', auth()->id())
            ->when($request->search, fn($q, $search) => $q->where('username', 'like', "%$search%"))
            ->get();

        return view('messages.partials.friends-list', compact('users'));
    }


    public function show(User $user, Request $request)
    {
        $conversation = Conversation::firstOrCreate([
            'user_one' => min(auth()->id(), $user->id),
            'user_two' => max(auth()->id(), $user->id)
        ]);

        $me = auth()->id();

        $messages = Message::where('conversation_id', $conversation->id)
            ->with('sender')
            // ->with(['sender', 'reads'])
            ->orderBy('id')
            ->get()
            ->filter(function ($msg) use ($me) {
                // Hide only if receiver deleted
                if ($msg->is_deleted) {
                    if ($msg->deleted_by == $msg->sender_id) {
                        return true; // deleted by sender → show "Message deleted"
                    }
                    if ($msg->deleted_by == $me && $me != $msg->sender_id) {
                        return true; // deleted by receiver → show "Message deleted"
                    }
                    if ($msg->deleted_by != $me) {
                        return true; // message is fine for others
                    }
                    return false; // receiver deleted → hide for that receiver only
                }
                return true; // normal message
            });

        if ($request->ajax()) {
            return response()->json([
                'html' => view('messages.partials.ajax-messages', compact('messages'))->render(),
                'last_id' => optional($messages->last())->id ?? 0
            ]);
        }

        $users = User::where('id', '!=', $me)->get();
        return view('messages.show', compact('user', 'messages', 'users'));
    }

    public function store(Request $request, User $user)
    {
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

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
            'file_path' => $filePath,
            'file_type' => $type
        ]);

        return response()->json([
            'success' => true,
            'message_id' => $message->id
        ]);
    }

    public function destroy(Message $message)
    {
        $me = auth()->id();

        if ($message->sender_id === $me) {
            // Sender deletes → for everyone
            $message->update([
                'is_deleted' => true,
                'deleted_by' => $me
            ]);
        } elseif ($message->receiver_id === $me) {
            // Receiver deletes → only for self
            $message->update([
                'is_deleted' => true,
                'deleted_by' => $me
            ]);
        } else {
            abort(403);
        }

        return response()->json(['success' => true]);
    }

    public function ajaxMessages(User $user, Request $request)
    {
        $conversation = Conversation::firstOrCreate([
            'user_one' => min(auth()->id(), $user->id),
            'user_two' => max(auth()->id(), $user->id),
        ]);

        $me = auth()->id();
        $lastId = (int) $request->get('after_id', 0);

        // Fetch messages after lastId
        $messages = Message::where('conversation_id', $conversation->id)
            ->when($lastId, fn($q) => $q->where('id', '>', $lastId))
            ->with(['sender', 'reads'])
            ->orderBy('id')
            ->get();

        // Filter deleted messages
        $messages = $messages->filter(
            fn($msg) =>
            !$msg->is_deleted ||
            $msg->deleted_by == $msg->sender_id ||
            $msg->deleted_by != $me ||
            ($msg->deleted_by == $me && $me != $msg->sender_id)
        );

        // Mark unread messages received by me as read
        $unreadMessages = $messages->where('receiver_id', $me)->where('is_read', false);
        foreach ($unreadMessages as $msg) {
            if (!$msg->reads->where('user_id', $me)->count()) {
                $msg->reads()->create(['user_id' => $me, 'read_at' => now()]);
            }
            $msg->update(['is_read' => true]);
        }

        return response()->json([
            'html' => view('messages.partials.ajax-messages', compact('messages'))->render(),
            'last_id' => optional($messages->last())->id ?? $lastId
        ]);
    }




    // Route: GET /messages/read-status
    public function readStatus(Request $request)
    {
        $me = auth()->id();
        $ids = $request->ids ?? [];

        // Fetch read status only for messages sent by the logged-in user
        $statuses = Message::whereIn('id', $ids)
            ->where('sender_id', $me)
            ->pluck('is_read', 'id'); // returns [id => is_read]

        return response()->json($statuses);
    }





}
