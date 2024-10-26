<?php

namespace App\Providers;


use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('student.sidebar', function ($view) {
            $view->with('notifications', auth()->check() ? auth()->user()->notifications()->orderBy('created_at', 'desc')->get() : collect());
        });

        // Bind data to 'chat.chat_support' view
    View::composer('chat.chat_support', function ($view) {
        // Retrieve admins except the authenticated user
        $admins = User::where('id', '!=', auth()->id())
                      ->where('role', 'admin')
                      ->get();
        
        // Retrieve messages involving the authenticated user
        $messages = Message::with('sender')
            ->where(function ($query) {
                $query->where('receiver_id', auth()->id())
                      ->orWhere('sender_id', auth()->id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
    
        // Group messages
        $groupedMessages = $messages->unique(function ($item) {
            return $item->sender_id . $item->content . $item->created_at;
        });

        // Pass data to the view
        $view->with(compact('admins', 'groupedMessages'));
    });

    View::composer('*', function ($view) {
        $userId = auth()->id();
        $chats = Chat::where('user_id', $userId)->get(); // Adjust this query as needed
        $view->with('chats', $chats);
    });
    }
}
