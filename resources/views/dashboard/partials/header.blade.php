<nav class="topnav navbar navbar-light">
    <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
        <i class="fe fe-menu navbar-toggler-icon"></i>
    </button>

    <ul class="nav">

        <li class="nav-item nav-notif">
            <a class="nav-link text-muted my-2" href="#" data-toggle="modal" data-target=".modal-notif">
                <span class="fe fe-bell fe-16"></span>
                <span class="dot dot-md text-danger">{{ Auth::guard('admin')->user()->unreadNotifications->count()}}</span>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink"
               role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                <span class="avatar avatar-sm mt-2">
                    <img src="{{ asset('assets/images/avatar.png') }}" alt="Profile image"
                         class="avatar-img rounded-circle">
                </span>

            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <form action="{{ route('admin.logout') }}" method="POST" class="dropdown-item">
                    @csrf
                    <button type="submit" class="border-0 bg-transparent p-0 text-danger">
                        {{ __('lang.logout') }}
                    </button>
                </form>
            </div>
        </li>


        <!-- Notifications Modal -->
        <div class="modal fade modal-notif modal-slide" tabindex="-1" role="dialog"
             aria-labelledby="defaultModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="defaultModalLabel">Notifications</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                    @if (count(Auth::guard('admin')->user()->notifications))

                        <div class="list-group-item bg-transparent" >
                            <div class="col-auto">
                                <span class="fe fe-link fe-24"></span>
                            </div>
                            <div class="col">
                               @foreach (Auth::guard('admin')->user()->notifications->take(5) as $notification)

    <div class="py-2 border-bottom
        {{ is_null($notification->read_at) ? 'bg-light' : '' }}"
        style="border-radius: 6px;">

        <small>
            <strong>New User Registered:</strong>
            {{ $notification->data['name'] ?? $notification->data['user']['name'] ?? 'Unknown User' }}
        </small>

        <div class="my-0 text-muted small">
            A new user has joined your system.
        </div>

        <small class="badge badge-pill badge-light text-muted">
            {{ $notification->created_at->diffForHumans() }}
        </small>
    </div>

@endforeach

                            </div>
                        </div>
                    @endif
                    </div>
                    <!-- Footer with Clear All -->
                    <div class="modal-footer">

                        @if (Auth::guard('admin')->user()->notifications->count() > 0)
                            <button class="btn btn-danger btn-block"
                                    onclick="document.getElementById('clearNotificationsForm').submit();">
                                Clear All Notifications
                            </button>
                        @else
                            <button class="btn btn-secondary btn-block" disabled>
                                No Notifications
                            </button>
                        @endif

                        <form id="clearNotificationsForm"
                              action="{{ url('/admin/notifications/clear') }}"
                              method="POST" style="display:none;">
                            @csrf
                        </form>

                    </div>

                </div>
            </div>

        </div>

    </ul>
</nav>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notifIcon = document.querySelector('.nav-notif a');

        notifIcon.addEventListener('click', function () {
            fetch("{{ route('admin.notifications.markAsRead') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                }
            })
            .then(response => response.json())
            .then(data => {
                // Remove unread count instantly
                const dot = document.querySelector('.dot');
                if (dot) dot.style.display = 'none';
            });
        });
    });
</script>

