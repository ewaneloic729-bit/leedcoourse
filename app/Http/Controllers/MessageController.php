<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        return $this->renderMessenger();
    }

    public function show(Conversation $conversation): View
    {
        $this->ensureParticipant($conversation);

        return $this->renderMessenger($conversation);
    }

    public function openConversation(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'target_user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $targetUser = User::query()->findOrFail($validated['target_user_id']);

        if ($targetUser->is($user)) {
            return back()->withErrors([
                'target_user_id' => 'Vous ne pouvez pas ouvrir une conversation avec votre propre compte.',
            ]);
        }

        $conversation = $this->findOrCreateDirectConversation($user, $targetUser);

        return redirect()->route('messages.show', $conversation);
    }

    public function store(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        $this->ensureParticipant($conversation);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'body' => trim($validated['body']),
        ]);

        $conversation->forceFill([
            'last_message_at' => now(),
        ])->save();

        if ($request->expectsJson()) {
            $conversation->load([
                'participants:id,name,email,role',
                'messages.sender:id,name,role',
                'latestMessage.sender:id,name',
            ]);

            return response()->json([
                'message' => $this->serializeMessage($message->load('sender:id,name,role'), $user),
                'conversation' => $this->serializeConversation($conversation, $user, true),
                'unread_total' => $user->unreadConversationMessagesCount(),
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }

    public function poll(Conversation $conversation): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $this->ensureParticipant($conversation);

        $conversation->load([
            'participants:id,name,email,role',
            'messages.sender:id,name,role',
            'latestMessage.sender:id,name',
        ]);

        ConversationMessage::query()
            ->where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->refresh()->load([
            'participants:id,name,email,role',
            'messages.sender:id,name,role',
            'latestMessage.sender:id,name',
        ]);

        $conversations = Conversation::query()
            ->whereHas('participants', function ($query) use ($user) {
                $query->whereKey($user->id);
            })
            ->with([
                'participants:id,name,email,role',
                'latestMessage.sender:id,name',
            ])
            ->withCount([
                'unreadMessages as unread_messages_count' => function ($query) use ($user) {
                    $query->where('sender_id', '!=', $user->id);
                },
            ])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function (Conversation $conversationItem) use ($conversation, $user) {
                return $this->serializeConversation(
                    $conversationItem,
                    $user,
                    $conversationItem->is($conversation)
                );
            })
            ->values();

        return response()->json([
            'conversation_id' => $conversation->id,
            'active_recipient' => $this->serializeRecipient($conversation->otherParticipantFor($user)),
            'messages' => $conversation->messages->map(function (ConversationMessage $message) use ($user) {
                return $this->serializeMessage($message, $user);
            })->values(),
            'conversations' => $conversations,
            'unread_total' => $user->unreadConversationMessagesCount(),
            'server_time' => now()->toIso8601String(),
        ]);
    }

    private function renderMessenger(?Conversation $activeConversation = null): View
    {
        /** @var User $user */
        $user = Auth::user();

        $contacts = User::query()
            ->whereKeyNot($user->id)
            ->when(
                Schema::hasColumn('users', 'is_active'),
                fn ($query) => $query->where('is_active', true)
            )
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        $conversations = Conversation::query()
            ->whereHas('participants', fn ($query) => $query->whereKey($user->id))
            ->with([
                'participants:id,name,email,role',
                'latestMessage.sender:id,name',
            ])
            ->withCount([
                'unreadMessages as unread_messages_count' => fn ($query) => $query
                    ->where('sender_id', '!=', $user->id),
            ])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get();

        if ($activeConversation) {
            $activeConversation->load([
                'participants:id,name,email,role',
                'messages.sender:id,name,role',
            ]);

            ConversationMessage::query()
                ->where('conversation_id', $activeConversation->id)
                ->where('sender_id', '!=', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            $conversations = $conversations->map(function (Conversation $conversation) use ($activeConversation) {
                if ($conversation->is($activeConversation)) {
                    $conversation->unread_messages_count = 0;
                }

                return $conversation;
            });
        }

        return view('messages.index', [
            'contacts' => $contacts,
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'activeRecipient' => $activeConversation ? $activeConversation->otherParticipantFor($user) : null,
        ]);
    }

    private function ensureParticipant(Conversation $conversation): void
    {
        abort_unless(
            $conversation->participants()->whereKey(Auth::id())->exists(),
            403
        );
    }

    private function findOrCreateDirectConversation(User $user, User $targetUser): Conversation
    {
        $conversation = Conversation::query()
            ->where('type', 'direct')
            ->whereHas('participants', fn ($query) => $query->whereKey($user->id))
            ->whereHas('participants', fn ($query) => $query->whereKey($targetUser->id))
            ->withCount('participants')
            ->having('participants_count', 2)
            ->first();

        if ($conversation) {
            return $conversation;
        }

        $conversation = Conversation::query()->create([
            'type' => 'direct',
            'created_by_user_id' => $user->id,
        ]);

        $conversation->participants()->attach([$user->id, $targetUser->id]);

        return $conversation;
    }

    private function serializeConversation(Conversation $conversation, User $user, bool $isActive = false): array
    {
        $participant = $conversation->otherParticipantFor($user);
        $lastMessage = $conversation->latestMessage;
        $messageDate = $lastMessage ? $lastMessage->created_at : $conversation->updated_at;

        return [
            'id' => $conversation->id,
            'url' => route('messages.show', $conversation),
            'participant' => $this->serializeRecipient($participant),
            'preview' => $lastMessage ? mb_strimwidth($lastMessage->body, 0, 90, '...') : 'Conversation creee. Envoyez le premier message.',
            'meta_time' => $messageDate ? $messageDate->diffForHumans() : '',
            'unread_count' => $isActive ? 0 : (int) ($conversation->unread_messages_count ?? 0),
            'is_active' => $isActive,
        ];
    }

    private function serializeRecipient(?User $user): array
    {
        return [
            'id' => $user ? $user->id : null,
            'name' => $user ? $user->name : 'Utilisateur',
            'email' => $user ? $user->email : '',
            'role' => $user ? $user->role : 'utilisateur',
            'initial' => strtoupper(substr($user ? $user->name : 'U', 0, 1)),
        ];
    }

    private function serializeMessage(ConversationMessage $message, User $user): array
    {
        return [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'sender_name' => $message->sender ? $message->sender->name : 'Utilisateur',
            'body' => $message->body,
            'body_html' => nl2br(e($message->body)),
            'created_at' => $message->created_at ? $message->created_at->format('d/m/Y H:i') : '',
            'is_mine' => (int) $message->sender_id === (int) $user->id,
        ];
    }
}
