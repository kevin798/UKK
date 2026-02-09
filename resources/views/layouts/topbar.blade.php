<nav class="navbar navbar-expand navbar-light bg-white border-bottom shadow-sm px-4"
     style="height:64px;">

    <div class="d-flex w-100 justify-content-end">

        <ul class="navbar-nav">
            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-dark px-2"
                   href="#"
                   role="button"
                   data-bs-toggle="dropdown"
                   aria-expanded="false">

                    <div class="text-end d-none d-sm-block me-1">
                        <p class="mb-0 fw-semibold small" style="line-height:1;">
                            {{ auth()->user()->name }}
                        </p>
                        <small class="text-muted text-uppercase" style="font-size:0.65rem;">
                            {{ auth()->user()->role }}
                        </small>
                    </div>

                    @php
                        $photo = auth()->user()->photo ?? null;
                        $photoUrl = $photo ? asset('storage/'.$photo) : null;
                    @endphp

                    @if($photoUrl)
                        <div class="rounded-circle overflow-hidden border shadow-sm"
                             style="width:38px;height:38px;">
                            <img src="{{ $photoUrl }}" alt="avatar"
                                 style="width:100%;height:100%;object-fit:cover;">
                        </div>
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold border shadow-sm"
                             style="width:38px;height:38px;">
                            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                        </div>
                    @endif
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li>
                        @php
                            $profileRoute = match(auth()->user()->role) {
                                'admin' => route('admin.profile.edit'),
                                'petugas' => route('petugas.profile.edit'),
                                default => route('user.profile.edit'),
                            };
                        @endphp
                        <a href="{{ $profileRoute }}"
                           class="dropdown-item d-flex align-items-center gap-2 py-2">
                            <i class="bi bi-person"></i>
                            Profile
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit"
                                    class="dropdown-item text-danger d-flex align-items-center gap-2 py-2">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>

            </li>
        </ul>

    </div>
</nav>
