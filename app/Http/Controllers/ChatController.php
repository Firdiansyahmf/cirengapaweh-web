<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Show chat sessions list
     */
    public function index()
    {
        // Get all chat sessions with message count and latest message
        $sessions = ChatSession::with('messages')
            ->withCount('messages')
            ->latest('updated_at')
            ->paginate(15);

        return view('admin.chat', ['sessions' => $sessions]);
    }

    /**
     * Get chat session details with messages
     */
    public function show($sessionId)
    {
        // Only SuperAdmin can access
        if (auth()->user()->role !== 'superadmin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $session = ChatSession::with('messages')->findOrFail($sessionId);
        
        return response()->json([
            'success' => true,
            'session' => $session,
            'messages' => $session->messages()->orderBy('created_at', 'asc')->get()
        ]);
    }

    /**
     * Send message in chat session
     */
    public function sendMessage(Request $request, $sessionId)
    {
        // Only SuperAdmin can access
        if (auth()->user()->role !== 'superadmin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        try {
            $session = ChatSession::findOrFail($sessionId);
            
            $message = ChatMessage::create([
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
                'sender_type' => 'admin',
                'message' => $validated['message']
            ]);

            // Update session updated_at
            $session->touch();

            return response()->json([
                'success' => true,
                'message' => 'Pesan terkirim',
                'data' => $message
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Send message error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan'
            ], 500);
        }
    }

    /**
     * Close chat session
     */
    public function closeSession($sessionId)
    {
        // Only SuperAdmin can access
        if (auth()->user()->role !== 'superadmin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $session = ChatSession::findOrFail($sessionId);
            $session->update(['status' => 'closed']);

            return response()->json([
                'success' => true,
                'message' => 'Chat session ditutup'
            ]);
        } catch (\Exception $e) {
            \Log::error('Close session error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menutup chat session'
            ], 500);
        }
    }

    /**
     * Reopen chat session
     */
    public function reopenSession($sessionId)
    {
        // Only SuperAdmin can access
        if (auth()->user()->role !== 'superadmin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $session = ChatSession::findOrFail($sessionId);
            $session->update(['status' => 'open']);

            return response()->json([
                'success' => true,
                'message' => 'Chat session dibuka kembali'
            ]);
        } catch (\Exception $e) {
            \Log::error('Reopen session error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuka chat session'
            ], 500);
        }
    }
}
