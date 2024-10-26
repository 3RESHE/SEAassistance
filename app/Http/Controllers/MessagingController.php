<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MessagingController extends Controller
{
    public function index() {
        // Get all admins except the authenticated user
        $admins = User::where('id', '!=', auth()->id())
                      ->where('role', 'admin')
                      ->get();
    
        // Retrieve messages involving the authenticated user
        $messages = Message::with('sender')
            ->where(function ($query) {
                $query->where('receiver_id', auth()->id())
                      ->orWhere('sender_id', auth()->id());
            })
            ->orderBy('created_at', 'asc') // Keep messages sorted by time
            ->get();
    
        // Group identical messages sent by the same sender with same content
        $groupedMessages = $messages->unique(function ($item) {
            return $item->sender_id . $item->content;
        })->values();
    
        return view('chat.chat_support', compact('admins', 'groupedMessages'));
    }

    public function showChatSupport() {
        // Retrieve messages for the authenticated user
        $groupedMessages = Message::with('sender')
            ->where(function ($query) {
                $query->where('receiver_id', auth()->id())
                      ->orWhere('sender_id', auth()->id());
            })
            ->orderBy('created_at', 'asc') // Ensure messages are sorted
            ->get()
            ->unique(function ($item) {
                return $item->sender_id . $item->content;
            })->values();

        $userId = Auth::id();
        $chats = Chat::where('user_id', $userId)->orderBy('created_at', 'asc')->get();

        return view('chat.chat_support', compact('groupedMessages', 'chats'));
    }
    
    public function send(Request $request) {
        $request->validate(['content' => 'required|string|max:255']);
    
        $adminUsers = User::where('role', 'admin')->get();
    
        foreach ($adminUsers as $admin) {
            Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $admin->id,
                'content' => $request->content,
            ]);
        }
    
        return redirect()->back();
    }

    public function user_chats() {
        $userIdsWithChats = Message::where('sender_id', auth()->id())
                                    ->orWhere('receiver_id', auth()->id())
                                    ->distinct()
                                    ->pluck('sender_id')
                                    ->merge(
                                        Message::where('receiver_id', auth()->id())
                                                ->distinct()
                                                ->pluck('sender_id')
                                    )
                                    ->unique();
    
        $users = User::whereIn('id', $userIdsWithChats)
                     ->where('role', '!=', 'admin')
                     ->get();
    
        return view('chat.user_list', compact('users'));
    }

    public function viewUserMessages($userId) {
        $user = User::findOrFail($userId);

        $adminUserMessages = Message::with('sender')
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', auth()->id())
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('receiver_id', auth()->id())
                      ->where('sender_id', $userId);
            });

        $otherAdminReplies = Message::with('sender')
            ->where('receiver_id', $userId)
            ->whereHas('sender', function ($q) {
                $q->where('role', 'admin')
                  ->where('id', '!=', auth()->id());
            });

        $messages = $adminUserMessages
            ->union($otherAdminReplies)
            ->orderBy('created_at', 'asc') // Ensure messages are sorted by time
            ->get()
            ->unique('id');
    
        return view('chat.user_messages_partial', compact('messages', 'user'));
    }

    public function reply(Request $request, $userId) {
        $request->validate(['content' => 'required']);
        
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $userId,
            'content' => $request->content,
        ]);
    
        $message->load('sender');
    
        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }
}
