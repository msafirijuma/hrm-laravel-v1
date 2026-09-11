@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Notifications</h2>
    @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('notifications.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">
                Mark all as read
            </button>
        </form>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="list-group list-group-flush">
        @forelse($notifications as $notification)
            <a href="{{ route('notifications.read', $notification->id) }}"
               class="list-group-item list-group-item-action py-3 {{ $notification->read_at ? '' : 'bg-light' }}">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                        <div class="text-muted small">{{ $notification->data['message'] ?? '' }}</div>
                    </div>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
            </a>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>
                Hakuna notifications
            </div>
        @endforelse
    </div>
</div>

<div class="mt-3">
    {{ $notifications->links() }}
</div>
@endsection