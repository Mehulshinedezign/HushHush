<!-- resources/views/components/notification.blade.php -->
<div class="dropdown-toggle" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa-regular fa-bell"></i>
    <span class="badge" id="notification-count">{{ $notifications->count() }}</span>
</div>

<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
    @forelse($notifications as $notification)
        <li class="dropdown-item">
            @if (isset($notification->data['url']) && $notification->data['url'])
                <a href="{{ $notification->data['url'] }}" class="d-block">
                    {{ $notification->data['message'] ?? 'New Notification' }}
                </a>
            @else
                {{ $notification->data['message'] ?? 'New Notification' }}
            @endif
            <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="text-muted small">Mark as
                read</a>
        </li>
    @empty
        <li class="dropdown-item">No new notifications</li>
    @endforelse
</ul>



