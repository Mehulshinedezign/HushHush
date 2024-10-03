<!-- resources/views/components/notification.blade.php -->
<div class="dropdown-toggle-end" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa-regular fa-bell"></i>
    <span class="badge" id="notification-count">{{ $notifications->count() }}</span>
</div>


<ul class="dropdown-menu dropdown-menu-end-notification" aria-labelledby="notificationsDropdown">
    @if ($notifications->count() > 0)
        <div class ="mark-as-read">
            {{-- <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                @csrf --}}

                <div class="mark-as-read">
                    <button id="markAllAsRead" class="btn btn-link" onclick="markAllNotificationsAsRead()" style="font-size: 12px;">Mark All as Read</button>
                </div>
            {{-- </form> --}}
        </div>
    @endif

    @forelse($notifications as $notification)
        <li class="dropdown-item-end">
            @if (isset($notification->data['url']) && $notification->data['url'])
                <a href="{{ $notification->data['url'] }}" class="d-block">
                    {{ $notification->data['message'] ?? 'New Notification' }}
                </a>
            @else
                {{ $notification->data['message'] ?? 'New Notification' }}
            @endif

        </li>
    @empty
        <li class="dropdown-item">No new notifications</li>
    @endforelse
</ul>
