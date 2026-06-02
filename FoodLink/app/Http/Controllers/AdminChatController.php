<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AdminChatController extends Controller
{
    public function index(): View
    {
        $this->ensureAdmin();

        $conversations = $this->getConversationList();

        return view('admin.chat.index', [
            'conversations' => $conversations,
            'selectedConversation' => null,
        ]);
    }

    public function show(Conversation $conversation): View
    {
        $this->ensureAdmin();

        $conversation->load([
            'user',
            'admin',
        ]);

        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        $conversations = $this->getConversationList();

        return view('admin.chat.index', [
            'conversations' => $conversations,
            'selectedConversation' => $conversation,
        ]);
    }

    public function messages(Conversation $conversation): JsonResponse
    {
        $this->ensureAdmin();

        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        $messages = $conversation->messages()
            ->with('sender')
            ->oldest()
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $this->formatMessages($messages),
        ]);
    }

    public function send(Request $request, Conversation $conversation): JsonResponse|RedirectResponse
    {
        $this->ensureAdmin();

        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'is_read' => false,
        ]);

        $conversation->update([
            'admin_id' => auth()->id(),
            'last_message_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim.',
            ]);
        }

        return redirect()
            ->route('admin.chat.show', $conversation)
            ->with('success', 'Pesan berhasil dikirim.');
    }

    private function ensureAdmin(): void
    {
        if (!auth()->check()) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk admin.');
        }
    }

    private function getConversationList(): Collection
    {
        return Conversation::with([
                'user',
                'latestMessage.sender',
            ])
            ->withCount([
                'messages as unread_count' => function ($query) {
                    $query->where('is_read', false)
                        ->where('sender_id', '!=', auth()->id());
                },
            ])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get();
    }

    private function formatMessages($messages): array
{
    return $messages->map(function ($message) {
        $createdAt = $message->created_at->timezone('Asia/Jakarta');

        return [
            'id' => $message->id,
            'message' => $message->message,
            'sender_id' => $message->sender_id,
            'sender_name' => $message->sender->name ?? 'User',
            'is_mine' => $message->sender_id === auth()->id(),
            'is_read' => $message->is_read,
            'is_deleted' => $message->is_deleted,
            'is_edited' => !is_null($message->edited_at),
            'time' => $createdAt->format('H:i'),
            'date_time' => $createdAt->format('d M Y H:i'),
        ];
    })->toArray();
}
public function updateMessage(Request $request, Conversation $conversation, Message $message): JsonResponse
{
    $this->ensureAdmin();

    $request->validate([
        'message' => ['required', 'string', 'max:5000'],
    ]);

    abort_if($message->conversation_id !== $conversation->id, 403, 'Pesan tidak sesuai dengan percakapan ini.');
    abort_if($message->sender_id !== auth()->id(), 403, 'Admin hanya bisa mengedit pesan miliknya sendiri.');
    abort_if($message->is_deleted, 403, 'Pesan yang sudah dihapus tidak bisa diedit.');

    $message->update([
        'message' => $request->message,
        'edited_at' => now(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Pesan berhasil diedit.',
    ]);
}

public function deleteMessage(Conversation $conversation, Message $message): JsonResponse
{
    $this->ensureAdmin();

    abort_if($message->conversation_id !== $conversation->id, 403, 'Pesan tidak sesuai dengan percakapan ini.');
    abort_if($message->sender_id !== auth()->id(), 403, 'Admin hanya bisa menghapus pesan miliknya sendiri.');

    $message->update([
        'message' => 'Pesan ini telah dihapus.',
        'is_deleted' => true,
        'edited_at' => null,
    ]);

    $conversation->update([
        'last_message_at' => now(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Pesan berhasil dihapus.',
    ]);
}
}