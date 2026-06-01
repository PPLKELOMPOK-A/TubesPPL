@extends('layouts.app')

@section('title', 'Chat Admin - FoodLink')

@section('content')
<style>
    .chat-user-wrapper {
        height: calc(100vh - 70px);
        width: 100%;
        display: flex;
        background: #efeae2;
        overflow: hidden;
    }

    .chat-user-card {
        width: 100%;
        height: 100%;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        box-shadow: none;
    }

    .chat-user-header {
        height: 68px;
        background: #6B4F2A;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 24px;
        flex-shrink: 0;
    }

    .chat-back {
        color: #ffffff;
        text-decoration: none;
        font-size: 24px;
        line-height: 1;
        margin-right: 4px;
    }

    .chat-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .chat-user-header h1 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .chat-user-header p {
        margin: 3px 0 0;
        font-size: 12px;
        opacity: 0.85;
    }

    .chat-alert {
        display: none;
        padding: 10px 16px;
        font-size: 13px;
    }

    .chat-alert.error {
        display: block;
        background: #fee2e2;
        color: #991b1b;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 24px 28px;
        background: #efeae2;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .chat-empty {
        margin-top: 60px;
        text-align: center;
        color: #6b7280;
        font-size: 14px;
    }

    .message-row {
        display: flex;
        width: 100%;
    }

    .message-row.mine {
        justify-content: flex-end;
    }

    .message-row.other {
        justify-content: flex-start;
    }

    .message-bubble {
        max-width: 62%;
        padding: 9px 12px;
        border-radius: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
        word-wrap: break-word;
    }

    .message-bubble.mine {
        background: #F8E7C1;
        border-top-right-radius: 2px;
    }

    .message-bubble.other {
        background: #ffffff;
        border-top-left-radius: 2px;
    }

    .message-text {
        margin: 0;
        font-size: 14px;
        line-height: 1.45;
        color: #1f2937;
        white-space: pre-line;
    }

    .message-time {
        margin-top: 4px;
        text-align: right;
        font-size: 10px;
        color: #6b7280;
    }

    .edited-label {
        font-style: italic;
        color: #8a8a8a;
    }

    .deleted-message .message-text {
        font-style: italic;
        color: #777777;
    }

    .message-actions {
        margin-top: 6px;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .message-actions button {
        border: none;
        background: transparent;
        color: #6B4F2A;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        padding: 2px 4px;
    }

    .message-actions button:hover {
        text-decoration: underline;
    }

    .chat-form {
        min-height: 66px;
        background: #ffffff;
        border-top: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 24px;
        flex-shrink: 0;
    }

    .chat-input {
        flex: 1;
        border: 1px solid #d1d5db;
        border-radius: 999px;
        padding: 12px 16px;
        font-size: 14px;
        outline: none;
        background: #ffffff;
    }

    .chat-input:focus {
        border-color: #6B4F2A;
        box-shadow: 0 0 0 2px rgba(107, 79, 42, 0.18);
    }

    .send-button {
        width: 46px;
        height: 46px;
        border: none;
        border-radius: 50%;
        background: #6B4F2A;
        color: #ffffff;
        font-size: 18px;
        cursor: pointer;
        transition: 0.2s;
    }

    .send-button:hover {
        background: #563d1f;
    }

    .send-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .chat-messages {
            padding: 18px 16px;
        }

        .chat-form {
            padding: 10px 14px;
        }

        .message-bubble {
            max-width: 82%;
        }
    }
</style>

<div class="chat-user-wrapper">
    <div class="chat-user-card">
        <div class="chat-user-header">
            <a href="{{ route('dashboard') }}" class="chat-back">‹</a>

            <div class="chat-avatar">A</div>

            <div>
                <h1>Admin FoodLink</h1>
            </div>
        </div>

        <div id="alertBox" class="chat-alert"></div>

        <main id="messagesContainer" class="chat-messages">
            <div class="chat-empty">Memuat pesan...</div>
        </main>

        <form id="chatForm" class="chat-form">
            <input
                type="text"
                id="messageInput"
                name="message"
                class="chat-input"
                placeholder="Tulis pesan..."
                autocomplete="off"
                required
            >

            <button type="submit" id="sendButton" class="send-button">
                ➤
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const messagesUrl = @json(route('chat.messages'));
        const sendUrl = @json(route('chat.send'));
        const messageBaseUrl = @json(url('/chat/messages'));
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
            alertBox.className = 'chat-alert error';
            alertBox.textContent = message;

            setTimeout(function () {
                alertBox.className = 'chat-alert';
                alertBox.textContent = '';
            }, 2500);
        }

        function renderMessages(messages) {
            currentMessages = messages || [];

            if (!messages || messages.length === 0) {
                messagesContainer.innerHTML = '<div class="chat-empty">Belum ada pesan. Mulai chat dengan admin.</div>';
                return;
            }

            let html = '';

            messages.forEach(function (message) {
                const rowClass = message.is_mine ? 'mine' : 'other';
                const bubbleClass = message.is_mine ? 'mine' : 'other';
                const readStatus = message.is_mine ? `<span>${message.is_read ? ' ✓✓' : ' ✓'}</span>` : '';
                const editedLabel = message.is_edited ? `<span class="edited-label"> edited</span>` : '';
                const deletedClass = message.is_deleted ? ' deleted-message' : '';

                const actionButtons = message.is_mine && !message.is_deleted
                    ? `
                        <div class="message-actions">
                            <button type="button" class="js-edit-message" data-id="${message.id}">Edit</button>
                            <button type="button" class="js-delete-message" data-id="${message.id}">Hapus</button>
                        </div>
                    `
                    : '';

                html += `
                    <div class="message-row ${rowClass}">
                        <div class="message-bubble ${bubbleClass}${deletedClass}">
                            <p class="message-text">${escapeHtml(message.message)}</p>
                            <div class="message-time">
                                ${message.time}${editedLabel}${readStatus}
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
                showError('Gagal memuat pesan.');
            }
        }

        async function editMessage(messageId) {
            const selectedMessage = currentMessages.find(function (message) {
                return Number(message.id) === Number(messageId);
            });

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

                lastMessagesJson = '';
                await fetchMessages();
            } catch (error) {
                showError(error.message);
            }
        }

        async function deleteMessage(messageId) {
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

                lastMessagesJson = '';
                await fetchMessages();
            } catch (error) {
                showError(error.message);
            }
        }

        messagesContainer.addEventListener('click', function (event) {
            const editButton = event.target.closest('.js-edit-message');
            const deleteButton = event.target.closest('.js-delete-message');

            if (editButton) {
                editMessage(editButton.dataset.id);
                return;
            }

            if (deleteButton) {
                deleteMessage(deleteButton.dataset.id);
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
                lastMessagesJson = '';

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
    });
</script>
@endpush