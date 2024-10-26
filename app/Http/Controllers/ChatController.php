<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Keyword;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    public function index()
    {
        // Get the chat history for the current user
        $userId = Auth::id();
        $chats = Chat::where('user_id', $userId)->orderBy('created_at', 'asc')->get();

        return view('chat.index', compact('chats'));
    }


    public function sendMessage(Request $request)
    {
        // Get and sanitize the input by removing punctuation
        $message = strtolower($request->input('message'));
        $sanitizedMessage = preg_replace("/[^\w\s]/", '', $message); // Remove special characters like "?", ".", etc.

        $userId = Auth::id();

        // Save the user message to the chats table
        $chat = new Chat();
        $chat->user_id = $userId;
        $chat->message = $message; // Save the original message
        $chat->is_bot = false;
        $chat->save();

        // Split message into words, remove empty values (if there are multiple spaces)
        $words = array_filter(explode(' ', $sanitizedMessage));

        // Find keywords using the sanitized message words
        $keywords = Keyword::whereIn('keyword', $words)->pluck('id');

        // Debugging output (optional)
        Log::info('User message:', ['message' => $sanitizedMessage, 'keywords' => $keywords]);

        // Check if any keywords were found
        if ($keywords->isNotEmpty()) {
            // Get all related questions for the detected keyword(s)
            $questions = Question::whereIn('keyword_id', $keywords)->get();
        } else {
            $questions = collect(); // Create an empty collection if no keywords
        }

        // Check if questions are empty or keywords were not found
        if ($questions->isEmpty()) {
            $defaultResponse = "Thanks for your message. I couldn't find an answer to your query. Please provide more details related to SEAassist, such as subject evaluation or advising. Our chat support is available for urgent help.";
            return response()->json(['default_response' => $defaultResponse]);
        } else {
            return response()->json($questions);
        }
    }

    public function getAnswer(Request $request, $questionId)
    {
        // Fetch the question and its answer
        $question = Question::find($questionId);

        // Check if the answer exists, if not provide a default message
        $answer = $question && $question->answer ? $question->answer : "Thanks for your message. I couldn't find an answer to your query. Please provide more details related to SEAassist, such as subject evaluation or advising. Our chat support is available for urgent help.";

        // Log the bot response to the chat history
        $chat = new Chat();
        $chat->user_id = Auth::id();
        $chat->message = $answer;
        $chat->is_bot = true;
        $chat->save();

        return response()->json(['answer' => $answer]);
    }
}
