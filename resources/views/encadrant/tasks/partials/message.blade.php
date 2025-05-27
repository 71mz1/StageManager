@props(['message'])

@php
    $isCurrentUser = $message->user_id === auth()->id();
    $messageClass = $isCurrentUser ? 'current-user' : 'other-user';
    $time = $message->created_at->format('H:i');
    $user = $message->user;
    $avatar = $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name);
@endif

<div class="message {{ $messageClass }} mb-3">
    @if(!$isCurrentUser)
        <div class="d-flex align-items-start">
            <img src="{{ $avatar }}" alt="{{ $user->name }}" class="rounded-circle me-2" width="32" height="32">
            <div>
                <div class="message-content">
                    <div class="message-sender">{{ $user->name }}</div>
                    <div class="message-text">{!! nl2br(e($message->message)) !!}</div>
                    <div class="message-time">{{ $time }}</div>
                </div>
            </div>
        </div>
    @else
        <div class="d-flex justify-content-end">
            <div class="message-content">
                <div class="message-text">{!! nl2br(e($message->message)) !!}</div>
                <div class="message-time text-end">{{ $time }}</div>
            </div>
        </div>
    @endif
</div>
