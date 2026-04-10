@extends('layouts.app')

@section('title', 'Messagerie | LEEDCOURSE')

@section('content')
    @php
        $messageUnreadCount = auth()->user()->unreadConversationMessagesCount();
    @endphp

    <style>
        .messenger-shell {
            max-width: 1320px;
            margin: 0 auto;
            padding: 1rem 0 2rem;
        }

        .messenger-hero {
            position: relative;
            overflow: hidden;
            border-radius: 1.75rem;
            background:
                radial-gradient(circle at top right, rgba(125, 211, 252, 0.26), transparent 26%),
                radial-gradient(circle at bottom left, rgba(52, 211, 153, 0.22), transparent 24%),
                linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(6, 78, 59, 0.88));
            color: #fff;
            padding: 1.75rem;
            box-shadow: 0 22px 44px rgba(15, 23, 42, 0.18);
        }

        .messenger-hero::after {
            content: "";
            position: absolute;
            inset: 1rem;
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .messenger-grid {
            margin-top: 1.25rem;
            display: grid;
            grid-template-columns: 360px minmax(0, 1fr);
            gap: 1rem;
            min-height: 760px;
        }

        .messenger-sidebar,
        .messenger-panel {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 1.5rem;
            box-shadow: 0 20px 38px rgba(15, 23, 42, 0.08);
        }

        .messenger-sidebar {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .messenger-panel {
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .panel-block-title {
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 0.7rem;
        }

        .conversation-list,
        .contact-list {
            display: grid;
            gap: 0.75rem;
        }

        .conversation-card,
        .contact-card {
            display: block;
            width: 100%;
            text-align: left;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 0.9rem 0.95rem;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            text-decoration: none;
            color: #0f172a;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .contact-card {
            cursor: pointer;
        }

        .conversation-card:hover,
        .contact-card:hover {
            transform: translateY(-1px);
            border-color: #86efac;
            box-shadow: 0 14px 24px rgba(34, 197, 94, 0.1);
        }

        .conversation-card.active {
            background: linear-gradient(135deg, #f0fdf4, #ecfeff);
            border-color: #6ee7b7;
        }

        .conversation-top,
        .contact-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .avatar {
            width: 2.8rem;
            height: 2.8rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #22c55e, #0ea5e9);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            flex-shrink: 0;
        }

        .name-line {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            min-width: 0;
        }

        .name-line strong {
            display: block;
            font-size: 0.96rem;
        }

        .role-badge,
        .unread-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.2rem 0.55rem;
            font-size: 0.72rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .role-badge {
            background: #ecfeff;
            color: #0f766e;
        }

        .unread-badge {
            background: #dcfce7;
            color: #166534;
        }

        .conversation-preview {
            margin-top: 0.75rem;
            color: #64748b;
            font-size: 0.88rem;
            line-height: 1.55;
        }

        .conversation-meta {
            margin-top: 0.65rem;
            display: flex;
            justify-content: space-between;
            gap: 0.75rem;
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .messenger-header {
            padding: 1.15rem 1.2rem;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .messenger-messages {
            flex: 1;
            padding: 1.2rem;
            overflow-y: auto;
            background:
                radial-gradient(circle at top right, rgba(187, 247, 208, 0.2), transparent 22%),
                linear-gradient(180deg, #f8fafc, #ffffff);
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
            min-height: 420px;
        }

        .message-row {
            display: flex;
        }

        .message-row.mine {
            justify-content: flex-end;
        }

        .message-bubble {
            max-width: min(78%, 680px);
            border-radius: 1.2rem;
            padding: 0.9rem 1rem;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
        }

        .message-row.mine .message-bubble {
            background: linear-gradient(135deg, #22c55e, #14b8a6);
            color: #fff;
            border-bottom-right-radius: 0.35rem;
        }

        .message-row.other .message-bubble {
            background: #ffffff;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 0.35rem;
        }

        .message-meta {
            margin-bottom: 0.45rem;
            font-size: 0.73rem;
            font-weight: 700;
            opacity: 0.85;
        }

        .message-body {
            line-height: 1.65;
            word-break: break-word;
        }

        .messenger-form {
            padding: 1rem 1.2rem 1.2rem;
            border-top: 1px solid #e2e8f0;
            background: #fff;
        }

        .messenger-textarea {
            width: 100%;
            min-height: 110px;
            resize: vertical;
            border: 1px solid #dbe2ea;
            border-radius: 1rem;
            padding: 0.95rem 1rem;
            font: inherit;
            background: linear-gradient(180deg, #ffffff, #f8fafc);
        }

        .messenger-textarea:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.12);
        }

        .primary-btn {
            border: none;
            border-radius: 0.95rem;
            padding: 0.85rem 1.15rem;
            background: linear-gradient(135deg, #22c55e, #14b8a6);
            color: #fff;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 16px 26px rgba(20, 184, 166, 0.18);
        }

        .primary-btn[disabled] {
            opacity: 0.7;
            cursor: wait;
        }

        .messenger-status {
            font-size: 0.8rem;
            color: #64748b;
        }

        .empty-state {
            flex: 1;
            display: grid;
            place-items: center;
            text-align: center;
            padding: 2rem;
            color: #475569;
            background:
                radial-gradient(circle at top right, rgba(125, 211, 252, 0.14), transparent 22%),
                linear-gradient(180deg, #f8fafc, #ffffff);
        }

        .empty-card {
            max-width: 440px;
            padding: 2rem;
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 38px rgba(15, 23, 42, 0.06);
        }

        @media (max-width: 1080px) {
            .messenger-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="messenger-shell">
        <section class="messenger-hero">
            <div style="position:relative;z-index:1;display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <div>
                    <p style="margin:0;font-size:.78rem;font-weight:800;letter-spacing:.24em;text-transform:uppercase;color:#bbf7d0;">Communication privee</p>
                    <h1 style="margin:.6rem 0 0;font-size:clamp(1.8rem,3vw,2.8rem);line-height:1.05;">Messagerie entre utilisateurs</h1>
                    <p style="margin:.9rem 0 0;max-width:720px;color:rgba(226,232,240,.9);line-height:1.7;">
                        Chaque utilisateur peut ouvrir une conversation privee avec un autre compte actif, echanger des messages et suivre les discussions non lues depuis un seul espace.
                    </p>
                </div>
                <div style="min-width:220px;padding:1rem 1.1rem;border-radius:1.25rem;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12);">
                    <div style="font-size:.8rem;color:#d1fae5;font-weight:700;">Messages non lus</div>
                    <div id="heroUnreadCount" style="margin-top:.35rem;font-size:2rem;font-weight:800;">{{ $messageUnreadCount }}</div>
                    <div style="margin-top:.35rem;font-size:.84rem;color:rgba(226,232,240,.82);">Suivi rapide de vos conversations privees.</div>
                </div>
            </div>
        </section>

        @if ($errors->any())
            <div style="margin-top:1rem;border-radius:1rem;padding:1rem 1.1rem;background:#fff1f2;border:1px solid #fecdd3;color:#be123c;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="messenger-grid">
            <aside class="messenger-sidebar">
                <section>
                    <div class="panel-block-title">Conversations</div>
                    <div class="conversation-list" id="conversationList">
                        @forelse ($conversations as $conversation)
                            @php
                                $participant = $conversation->otherParticipantFor(auth()->user());
                                $lastMessage = $conversation->latestMessage;
                                $participantName = $participant ? $participant->name : 'Utilisateur';
                                $participantEmail = $participant ? $participant->email : '';
                                $participantRole = $participant ? $participant->role : 'utilisateur';
                                $messageDate = $lastMessage ? $lastMessage->created_at : $conversation->updated_at;
                            @endphp
                            <a href="{{ route('messages.show', $conversation) }}" class="conversation-card {{ $activeConversation && $activeConversation->is($conversation) ? 'active' : '' }}">
                                <div class="conversation-top">
                                    <div class="name-line">
                                        <span class="avatar">{{ strtoupper(substr($participantName, 0, 1)) }}</span>
                                        <div style="min-width:0;">
                                            <strong>{{ $participantName }}</strong>
                                            <div style="font-size:.8rem;color:#64748b;">{{ $participantEmail }}</div>
                                        </div>
                                    </div>
                                    @if (($conversation->unread_messages_count ?? 0) > 0)
                                        <span class="unread-badge">{{ $conversation->unread_messages_count }}</span>
                                    @endif
                                </div>
                                <div class="conversation-preview">
                                    {{ $lastMessage ? \Illuminate\Support\Str::limit($lastMessage->body, 90) : 'Conversation creee. Envoyez le premier message.' }}
                                </div>
                                <div class="conversation-meta">
                                    <span>{{ $participantRole }}</span>
                                    <span>{{ optional($messageDate)->diffForHumans() }}</span>
                                </div>
                            </a>
                        @empty
                            <div style="padding:1rem;border-radius:1rem;background:#f8fafc;border:1px dashed #cbd5e1;color:#64748b;">
                                Aucune conversation pour le moment. Lancez-en une depuis la liste des utilisateurs.
                            </div>
                        @endforelse
                    </div>
                </section>

                <section>
                    <div class="panel-block-title">Utilisateurs</div>
                    <div class="contact-list">
                        @foreach ($contacts as $contact)
                            <form method="POST" action="{{ route('messages.open') }}">
                                @csrf
                                <input type="hidden" name="target_user_id" value="{{ $contact->id }}">
                                <button type="submit" class="contact-card">
                                    <div class="contact-top">
                                        <div class="name-line">
                                            <span class="avatar">{{ strtoupper(substr($contact->name, 0, 1)) }}</span>
                                            <div>
                                                <strong>{{ $contact->name }}</strong>
                                                <div style="font-size:.8rem;color:#64748b;">{{ $contact->email }}</div>
                                            </div>
                                        </div>
                                        <span class="role-badge">{{ $contact->role }}</span>
                                    </div>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </section>
            </aside>

            <section class="messenger-panel">
                @if ($activeConversation && $activeRecipient)
                    <div class="messenger-header">
                        <div class="name-line">
                            <span class="avatar" id="activeRecipientInitial">{{ strtoupper(substr($activeRecipient->name, 0, 1)) }}</span>
                            <div>
                                <strong id="activeRecipientName">{{ $activeRecipient->name }}</strong>
                                <div id="activeRecipientEmail" style="font-size:.82rem;color:#64748b;">{{ $activeRecipient->email }}</div>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <span class="messenger-status" id="messengerStatus">Synchronisation automatique active</span>
                            <span class="role-badge" id="activeRecipientRole">{{ $activeRecipient->role }}</span>
                        </div>
                    </div>

                    <div class="messenger-messages" id="messagesContainer">
                        @forelse ($activeConversation->messages as $message)
                            <div class="message-row {{ $message->sender_id === auth()->id() ? 'mine' : 'other' }}">
                                <div class="message-bubble">
                                    <div class="message-meta">
                                        {{ $message->sender_id === auth()->id() ? 'Vous' : $message->sender->name }}
                                        · {{ $message->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="message-body">{!! nl2br(e($message->body)) !!}</div>
                                </div>
                            </div>
                        @empty
                            <div style="padding:1rem;border-radius:1rem;background:#ffffff;border:1px dashed #cbd5e1;color:#64748b;">
                                Cette conversation est vide. Ecrivez le premier message pour demarrer l'echange.
                            </div>
                        @endforelse
                    </div>

                    <form method="POST" action="{{ route('messages.store', $activeConversation) }}" class="messenger-form" id="messageForm">
                        @csrf
                        <label for="body" style="display:block;font-size:.86rem;font-weight:800;color:#334155;margin-bottom:.6rem;">Votre message</label>
                        <textarea id="body" name="body" class="messenger-textarea" placeholder="Ecrivez votre message prive ici..." required>{{ old('body') }}</textarea>
                        <div style="margin-top:.9rem;display:flex;justify-content:flex-end;">
                            <button type="submit" class="primary-btn" id="messageSubmitBtn">Envoyer le message</button>
                        </div>
                    </form>
                @else
                    <div class="empty-state">
                        <div class="empty-card">
                            <h2 style="margin:0;font-size:1.45rem;color:#0f172a;">Choisissez un utilisateur</h2>
                            <p style="margin:.8rem 0 0;line-height:1.75;">
                                Selectionnez une conversation existante ou lancez un nouveau message prive depuis l'annuaire a gauche.
                            </p>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>

    @if ($activeConversation && $activeRecipient)
        <script>
            (function () {
                const pollUrl = @json(route('messages.poll', $activeConversation));
                const postUrl = @json(route('messages.store', $activeConversation));
                const csrfToken = @json(csrf_token());
                const activeConversationId = {{ (int) $activeConversation->id }};
                const conversationList = document.getElementById('conversationList');
                const messagesContainer = document.getElementById('messagesContainer');
                const heroUnreadCount = document.getElementById('heroUnreadCount');
                const messageForm = document.getElementById('messageForm');
                const messageInput = document.getElementById('body');
                const messageSubmitBtn = document.getElementById('messageSubmitBtn');
                const messengerStatus = document.getElementById('messengerStatus');
                const activeRecipientInitial = document.getElementById('activeRecipientInitial');
                const activeRecipientName = document.getElementById('activeRecipientName');
                const activeRecipientEmail = document.getElementById('activeRecipientEmail');
                const activeRecipientRole = document.getElementById('activeRecipientRole');
                let pollingHandle = null;
                let isSending = false;

                function escapeHtml(value) {
                    return String(value)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                function renderMessage(message) {
                    return `
                        <div class="message-row ${message.is_mine ? 'mine' : 'other'}">
                            <div class="message-bubble">
                                <div class="message-meta">${escapeHtml(message.is_mine ? 'Vous' : message.sender_name)} · ${escapeHtml(message.created_at)}</div>
                                <div class="message-body">${message.body_html}</div>
                            </div>
                        </div>
                    `;
                }

                function renderConversation(conversation) {
                    const participant = conversation.participant || {};
                    const unreadBadge = conversation.unread_count > 0
                        ? `<span class="unread-badge">${conversation.unread_count}</span>`
                        : '';

                    return `
                        <a href="${conversation.url}" class="conversation-card ${conversation.is_active ? 'active' : ''}" data-conversation-id="${conversation.id}">
                            <div class="conversation-top">
                                <div class="name-line">
                                    <span class="avatar">${escapeHtml(participant.initial || 'U')}</span>
                                    <div style="min-width:0;">
                                        <strong>${escapeHtml(participant.name || 'Utilisateur')}</strong>
                                        <div style="font-size:.8rem;color:#64748b;">${escapeHtml(participant.email || '')}</div>
                                    </div>
                                </div>
                                ${unreadBadge}
                            </div>
                            <div class="conversation-preview">${escapeHtml(conversation.preview || '')}</div>
                            <div class="conversation-meta">
                                <span>${escapeHtml(participant.role || 'utilisateur')}</span>
                                <span>${escapeHtml(conversation.meta_time || '')}</span>
                            </div>
                        </a>
                    `;
                }

                function updateUnreadCount(value) {
                    if (heroUnreadCount) {
                        heroUnreadCount.textContent = value;
                    }
                }

                function syncNavigationBadge(value) {
                    const links = document.querySelectorAll('a[href$="/messages"]');
                    links.forEach((link) => {
                        const baseText = link.textContent.indexOf('Messagerie') !== -1 ? 'Messagerie' : (link.textContent.indexOf('Messages') !== -1 ? 'Messages' : '');
                        if (!baseText) {
                            return;
                        }

                        link.textContent = value > 0 ? `${baseText} (${value})` : baseText;
                    });
                }

                function updateRecipient(recipient) {
                    if (!recipient) {
                        return;
                    }

                    activeRecipientInitial.textContent = recipient.initial || 'U';
                    activeRecipientName.textContent = recipient.name || 'Utilisateur';
                    activeRecipientEmail.textContent = recipient.email || '';
                    activeRecipientRole.textContent = recipient.role || 'utilisateur';
                }

                function updateMessages(messages) {
                    if (!messagesContainer || !Array.isArray(messages)) {
                        return;
                    }

                    const previousHeight = messagesContainer.scrollHeight;
                    const previousTop = messagesContainer.scrollTop;
                    const nearBottom = previousTop + messagesContainer.clientHeight >= previousHeight - 100;

                    messagesContainer.innerHTML = messages.map(renderMessage).join('');

                    if (nearBottom) {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }
                }

                function updateConversations(conversations) {
                    if (!conversationList || !Array.isArray(conversations)) {
                        return;
                    }

                    conversationList.innerHTML = conversations.map(renderConversation).join('');
                }

                function setStatus(text) {
                    if (messengerStatus) {
                        messengerStatus.textContent = text;
                    }
                }

                async function pollMessages() {
                    try {
                        const response = await fetch(pollUrl, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        });

                        if (!response.ok) {
                            throw new Error('Polling failed');
                        }

                        const data = await response.json();
                        updateRecipient(data.active_recipient);
                        updateMessages(data.messages);
                        updateConversations(data.conversations);
                        updateUnreadCount(data.unread_total || 0);
                        syncNavigationBadge(data.unread_total || 0);
                        setStatus('Synchronisation automatique active');
                    } catch (error) {
                        setStatus('Connexion instable, nouvelle tentative...');
                    }
                }

                async function sendMessage(event) {
                    event.preventDefault();

                    if (isSending) {
                        return;
                    }

                    const body = messageInput.value.trim();
                    if (!body) {
                        return;
                    }

                    isSending = true;
                    messageSubmitBtn.disabled = true;
                    setStatus('Envoi du message...');

                    try {
                        const response = await fetch(postUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({
                                body: body
                            })
                        });

                        if (!response.ok) {
                            throw new Error('Send failed');
                        }

                        const data = await response.json();
                        messageInput.value = '';
                        await pollMessages();
                        updateUnreadCount(data.unread_total || 0);
                        syncNavigationBadge(data.unread_total || 0);
                        setStatus('Message envoye');
                    } catch (error) {
                        setStatus('Echec de l envoi, reessayez.');
                    } finally {
                        isSending = false;
                        messageSubmitBtn.disabled = false;
                    }
                }

                if (messageForm) {
                    messageForm.addEventListener('submit', sendMessage);
                }

                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }

                pollMessages();
                pollingHandle = window.setInterval(pollMessages, 5000);

                window.addEventListener('beforeunload', function () {
                    if (pollingHandle) {
                        window.clearInterval(pollingHandle);
                    }
                });
            })();
        </script>
    @endif
@endsection
