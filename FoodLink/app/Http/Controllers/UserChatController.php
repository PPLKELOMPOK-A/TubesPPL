<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\SistemNotifikasi; // Ditambahkan untuk notifikasi
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserChatController extends Controller
{
    public function index(): View
    {
        $admin = User::where('role', 'admin')->first();

        abort_if(!$admin, 404, 'Admin belum tersedia.');

        $conversation = $this->getOrCreateConversation();

        return view('chat', [
            'conversation' => $conversation,
            'admin' => $admin,
        ]);
    }

    public function messages(): JsonResponse
    {
        $conversation = $this->getOrCreateConversation();

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

    public function send(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $conversation = $this->getOrCreateConversation();

        $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'is_read' => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        // ==========================================
        // FITUR NOTIFIKASI: Beri tahu Admin ada pesan
        // ==========================================
        try {
            $admin = User::find($conversation->admin_id);
            if ($admin) {
                $namaPengirim = auth()->user()->name ?? 'User';
                
                // Siapkan 3 parameter sesuai format SistemNotifikasi Anda
                $title = "Pesan Chat Baru";
                $messageNotif = "Anda mendapatkan pesan dari {$namaPengirim}.";
                $category = "chat";
                
                $admin->notify(new SistemNotifikasi($title, $messageNotif, $category));
            }
        } catch (\Exception $e) {
            // Jika notifikasi gagal, catat errornya di log, tapi JANGAN gagalkan chat-nya
            \Log::error('Notifikasi Chat Gagal: ' . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim.',
            ]);
        }

        return redirect()
            ->route('chat.index')
            ->with('success', 'Pesan berhasil dikirim.');
    }

    private function getOrCreateConversation(): Conversation
    {
        $admin = User::where('role', 'admin')->first();

        abort_if(!$admin, 404, 'Admin belum tersedia.');

        $conversation = Conversation::firstOrCreate(
            [
                'user_id' => auth()->id(),
            ],
            [
                'admin_id' => $admin->id,
                'last_message_at' => now(),
            ]
        );

        if (!$conversation->admin_id) {
            $conversation->update([
                'admin_id' => $admin->id,
            ]);
        }

        return $conversation;
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
    
    public function updateMessage(Request $request, Message $message): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $conversation = $this->getOrCreateConversation();

        abort_if($message->conversation_id !== $conversation->id, 403, 'Pesan tidak sesuai dengan percakapan ini.');
        abort_if($message->sender_id !== auth()->id(), 403, 'Kamu hanya bisa mengedit pesan milikmu sendiri.');
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

    public function deleteMessage(Message $message): JsonResponse
    {
        $conversation = $this->getOrCreateConversation();

        abort_if($message->conversation_id !== $conversation->id, 403, 'Pesan tidak sesuai dengan percakapan ini.');
        abort_if($message->sender_id !== auth()->id(), 403, 'Kamu hanya bisa menghapus pesan milikmu sendiri.');

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