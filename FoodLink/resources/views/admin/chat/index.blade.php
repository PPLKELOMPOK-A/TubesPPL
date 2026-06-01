@extends('layouts.app')

@section('title', 'Admin - Chat FoodLink')

@section('content')
<style>
    .fc-admin-chat {
        height: calc(100vh - 70px);
        width: 100%;
        display: flex;
        overflow: hidden;
        background: #ffffff;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .fc-chat-list {
        width: 390px;
        min-width: 390px;
        height: 100%;
        background: #ffffff;
        border-right: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
    }

    .fc-chat-list-header {
        padding: 22px 20px 16px;
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .fc-chat-list-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
    }

    .fc-search-input {
        width: 100%;
        margin-top: 14px;
        padding: 11px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
        color: #374151;
        background: #ffffff;
    }

    .fc-search-input:focus {
        border-color: #6B4F2A;
        box-shadow: 0 0 0 2px rgba(107, 79, 42, 0.15);
    }

    .fc-chat-items {
        flex: 1;
        overflow-y: auto;
        background: #ffffff;
    }

    .fc-chat-item {
        display: block;
        text-decoration: none;
        color: inherit;
        padding: 14px 18px;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s ease;
    }

    .fc-chat-item:hover {
        background: #FFF7E6;
    }

    .fc-chat-item.active {
        background: #F8E7C1;
    }

    .fc-chat-item-inner {
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .fc-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #6B4F2A;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
        font-size: 16px;
    }

    .fc-chat-preview {
        flex: 1;
        min-width: 0;
    }

    .fc-preview-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .fc-user-name {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fc-preview-time {
        font-size: 11px;
        color: #6b7280;
        flex-shrink: 0;
    }

    .fc-preview-bottom {
        margin-top: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .fc-last-message {
        margin: 0;
        font-size: 12px;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fc-unread-badge {
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        border-radius: 999px;
        background: #6B4F2A;
        color: #ffffff;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .fc-chat-room {
        flex: 1;
        height: 100%;
        min-width: 0;
        background: #efeae2;
        display: flex;
        flex-direction: column;
    }

    .fc-room-header {
        height: 68px;
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 0 22px;
        flex-shrink: 0;
    }

    .fc-room-header h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .fc-room-header p {
        margin: 3px 0 0;
        font-size: 12px;
        color: #6b7280;
    }

    .fc-alert {
        display: none;
        padding: 10px 16px;
        font-size: 13px;
    }

    .fc-alert.error {
        display: block;
        background: #fee2e2;
        color: #991b1b;
    }

    .fc-messages {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        background: #efeae2;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .fc-message-row {
        display: flex;
        width: 100%;
    }

    .fc-message-row.mine {
        justify-content: flex-end;
    }

    .fc-message-row.other {
        justify-content: flex-start;
    }

    .fc-message-bubble {
        max-width: 68%;
        padding: 10px 13px;
        border-radius: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
        word-wrap: break-word;
    }

    .fc-message-bubble.mine {
        background: #F8E7C1;
        border-top-right-radius: 3px;
    }

    .fc-message-bubble.other {
        background: #ffffff;
        border-top-left-radius: 3px;
    }

    .fc-message-text {
        margin: 0;
        font-size: 14px;
        line-height: 1.45;
        color: #1f2937;
        white-space: pre-line;
    }

    .fc-message-time {
        margin-top: 4px;
        text-align: right;
        font-size: 10px;
        color: #6b7280;
    }

    .fc-edited-label {
        font-style: italic;
        color: #8a8a8a;
    }

    .fc-deleted-message .fc-message-text {
        font-style: italic;
        color: #777777;
    }

    .fc-message-actions {
        margin-top: 6px;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .fc-message-actions button {
        border: none;
        background: transparent;
        color: #6B4F2A;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        padding: 2px 4px;
    }

    .fc-message-actions button:hover {
        text-decoration: underline;
    }

    .fc-chat-form {
        min-height: 66px;
        background: #ffffff;
        border-top: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 18px;
        flex-shrink: 0;
    }

    .fc-chat-input {
        flex: 1;
        height: 44px;
        border: 1px solid #d1d5db;
        border-radius: 999px;
        padding: 0 16px;
        font-size: 14px;
        outline: none;
        color: #374151;
        background: #ffffff;
    }

    .fc-chat-input:focus {
        border-color: #6B4F2A;
        box-shadow: 0 0 0 2px rgba(107, 79, 42, 0.15);
    }

    .fc-send-button {
        width: 46px;
        height: 46px;
        border: none;
        border-radius: 50%;
        background: #6B4F2A;
        color: #ffffff;
        font-size: 18px;
        cursor: pointer;
        transition: 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fc-send-button:hover {
        background: #563d1f;
    }

    .fc-send-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .fc-empty-state {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 24px;
        color: #6b7280;
    }

    .fc-empty-icon {
        width: 78px;
        height: 78px;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    .fc-empty-state h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #374151;
    }

    .fc-empty-state p {
        margin: 8px 0 0;
        font-size: 14px;
    }

    @media (max-width: 900px) {
        .fc-chat-list {
            width: 330px;
            min-width: 330px;
        }

        .fc-message-bubble {
            max-width: 82%;
        }
    }
</style>

<div class="fc-admin-chat">
    <section class="fc-chat-list">
        <div class="fc-chat-list-header">
            <h2>Chats</h2>

            <input
                type="text"
                id="searchConversation"
                class="fc-search-input"
                placeholder="Cari nama user..."
            >
        </div>

        <div class="fc-chat-items" id="conversationItems">
            @forelse ($conversations as $conversation)
                @php
                    $isActive = $selectedConversation && $selectedConversation->id === $conversation->id;
                    $latestMessage = $conversation->latestMessage;
                    $userName = $conversation->user->name ?? 'User tidak ditemukan';
                @endphp

                <a
                    href="{{ route('admin.chat.show', $conversation) }}"
                    class="fc-chat-item {{ $isActive ? 'active' : '' }}"
                    data-name="{{ strtolower($userName) }}"
                >
                    <div class="fc-chat-item-inner">
                        <div class="fc-avatar">
                            {{ strtoupper(substr($userName, 0, 1)) }}
                        </div>

                        <div class="fc-chat-preview">
                            <div class="fc-preview-top">
                                <h3 class="fc-user-name">{{ $userName }}</h3>

                                <span class="fc-preview-time">
                                    {{ $conversation->last_message_at ? $conversation->last_message_at->timezone('Asia/Jakarta')->format('H:i') : '-' }}
                                </span>
                            </div>

                            <div class="fc-preview-bottom">
                                <p class="fc-last-message">
                                    {{ $latestMessage ? $latestMessage->message : 'Belum ada pesan' }}
                                </p>

                                @if ($conversation->unread_count > 0)
                                    <span class="fc-unread-badge">
                                        {{ $conversation->unread_count }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="fc-empty-state">
                    <div>
                        <div class="fc-empty-icon">💬</div>
                        <h2>Belum ada chat</h2>
                        <p>Chat dari user akan muncul di sini.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <section class="fc-chat-room">
        @if ($selectedConversation)
            @php
                $selectedUserName = $selectedConversation->user->name ?? 'User tidak ditemukan';
            @endphp

            <header class="fc-room-header">
                <div class="fc-avatar">
                    {{ strtoupper(substr($selectedUserName, 0, 1)) }}
                </div>

                <div>
                    <h2>{{ $selectedUserName }}</h2>
                    <p>{{ $selectedConversation->user->email ?? '-' }}</p>
                </div>
            </header>

            <div id="alertBox" class="fc-alert"></div>

            <main id="messagesContainer" class="fc-messages">
                <div class="fc-empty-state">
                    <div>
                        <div class="fc-empty-icon">⏳</div>
                        <p>Memuat pesan...</p>
                    </div>
                </div>
            </main>

            <form id="chatForm" class="fc-chat-form">
                <input
                    type="text"
                    id="messageInput"
                    name="message"
                    class="fc-chat-input"
                    placeholder="Tulis balasan..."
                    autocomplete="off"
                    required
                >

                <button type="submit" id="sendButton" class="fc-send-button">
                    ➤
                </button>
            </form>
        @else
            <div class="fc-empty-state">
                <div>
                    <div class="fc-empty-icon">💬</div>
                    <h2>Pilih salah satu chat</h2>
                    <p>Daftar percakapan user akan tampil di sebelah kiri.</p>
                </div>
            </div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
    const searchConversation = document.getElementById('searchConversation');

    if (searchConversation) {
        searchConversation.addEventListener('input', function () {
            const keyword = this.value.toLowerCase();
            const items = document.querySelectorAll('.fc-chat-item');

            items.forEach((item) => {
                const name = item.dataset.name || '';
                item.style.display = name.includes(keyword) ? 'block' : 'none';
            });
        });
    }
</script>

@if ($selectedConversation)
<script>
    const messagesUrl = @json(route('admin.chat.messages', $selectedConversation));
    const sendUrl = @json(route('admin.chat.send', $selectedConversation));
    const messageBaseUrl = @json(url('/admin/chat/' . $selectedConversation->id . '/messages'));
    const csrfToken = @json(csrf_token());

    const messagesContainer = document.getElementById('messagesContainer');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const alertBox = document.getElementById('alertBox');

    let lastMessagesJson = '';
    let currentMessages = [];

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text ?? '';
        return div.innerHTML;
    }

    function showError(message) {
        alertBox.className = 'fc-alert error';
        alertBox.textContent = message;

        setTimeout(() => {
            alertBox.className = 'fc-alert';
            alertBox.textContent = '';
        }, 2500);
    }

    function renderMessages(messages) {
        currentMessages = messages || [];

        if (!messages || messages.length === 0) {
            messagesContainer.innerHTML = `
                <div class="fc-empty-state">
                    <div>
                        <div class="fc-empty-icon">💬</div>
                        <p>Belum ada pesan dalam percakapan ini.</p>
                    </div>
                </div>
            `;
            return;
        }

        let html = '';

        messages.forEach((message) => {
            const rowClass = message.is_mine ? 'mine' : 'other';
            const bubbleClass = message.is_mine ? 'mine' : 'other';
            const readStatus = message.is_mine ? `<span>${message.is_read ? ' ✓✓' : ' ✓'}</span>` : '';
            const editedLabel = message.is_edited ? `<span class="fc-edited-label"> edited</span>` : '';
            const deletedClass = message.is_deleted ? ' fc-deleted-message' : '';

            const actionButtons = message.is_mine && !message.is_deleted
                ? `
                    <div class="fc-message-actions">
                        <button type="button" class="js-edit-admin-message" data-id="${message.id}">Edit</button>
                        <button type="button" class="js-delete-admin-message" data-id="${message.id}">Hapus</button>
                    </div>
                `
                : '';

            html += `
                <div class="fc-message-row ${rowClass}">
                    <div class="fc-message-bubble ${bubbleClass}${deletedClass}">
                        <p class="fc-message-text">${escapeHtml(message.message)}</p>
                        <div class="fc-message-time">
                            ${message.date_time}${editedLabel}${readStatus}
                        </div>
                        ${actionButtons}
                    </div>
                </div>
            `;
        });

        messagesContainer.innerHTML = html;
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    async function fetchMessages() {
        try {
            const response = await fetch(messagesUrl, {
                headers: {
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (!data.success) {
                return;
            }

            const currentMessagesJson = JSON.stringify(data.messages);

            if (currentMessagesJson !== lastMessagesJson) {
                lastMessagesJson = currentMessagesJson;
                renderMessages(data.messages);
            }
        } catch (error) {
            console.error('Gagal mengambil pesan:', error);
        }
    }

    async function editAdminMessage(messageId) {
        const selectedMessage = currentMessages.find((message) => Number(message.id) === Number(messageId));

        if (!selectedMessage) {
            showError('Pesan tidak ditemukan.');
            return;
        }

        const newMessage = prompt('Edit pesan:', selectedMessage.message);

        if (newMessage === null) {
            return;
        }

        const trimmedMessage = newMessage.trim();

        if (!trimmedMessage) {
            showError('Pesan tidak boleh kosong.');
            return;
        }

        try {
            const response = await fetch(`${messageBaseUrl}/${messageId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    message: trimmedMessage,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Pesan gagal diedit.');
            }

            await fetchMessages();
        } catch (error) {
            showError(error.message);
        }
    }

    async function deleteAdminMessage(messageId) {
        const confirmDelete = confirm('Yakin ingin menghapus pesan ini?');

        if (!confirmDelete) {
            return;
        }

        try {
            const response = await fetch(`${messageBaseUrl}/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Pesan gagal dihapus.');
            }

            await fetchMessages();
        } catch (error) {
            showError(error.message);
        }
    }

    messagesContainer.addEventListener('click', function (event) {
        const editButton = event.target.closest('.js-edit-admin-message');
        const deleteButton = event.target.closest('.js-delete-admin-message');

        if (editButton) {
            editAdminMessage(editButton.dataset.id);
            return;
        }

        if (deleteButton) {
            deleteAdminMessage(deleteButton.dataset.id);
        }
    });

    chatForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const message = messageInput.value.trim();

        if (!message) {
            return;
        }

        sendButton.disabled = true;

        try {
            const response = await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    message: message,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Pesan gagal dikirim.');
            }

            messageInput.value = '';

            await fetchMessages();
        } catch (error) {
            showError(error.message);
        } finally {
            sendButton.disabled = false;
            messageInput.focus();
        }
    });

    fetchMessages();
    setInterval(fetchMessages, 2000);
</script>
@endif
@endpush