<header class="app-header">
    {{-- <a class="app-header__logo" href="index.html">Vali</a> --}}
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar"
        aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
        @can('employee')
            @can('internal')
                <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown"
                        aria-label="Show notifications"><i class="fa fa-bell-o fa-lg"></i></a>
                    <ul class="app-notification dropdown-menu dropdown-menu-right">
                        <div class="app-notification__content">
                            @foreach ($anggaran ?? [] as $item)
                                <li>
                                    <a class="app-notification__item" href="javascript:;"><span
                                            class="app-notification__icon"><span class="fa-stack fa-lg"><i
                                                    class="fa fa-circle fa-stack-2x text-success"></i><i
                                                    class="fa fa-money fa-stack-1x fa-inverse"></i></span></span>
                                        <div>
                                            <p class="app-notification__message">Anggaran {{ $item->research->title }} Telah
                                                Masuk</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </div>
                    </ul>
                </li>
            @endcan
        @endcan
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown"
                aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
                <li><a class="dropdown-item" href="/employees/{{ auth()->user()->employee->id }}"><i
                            class="fa fa-user fa-lg"></i> Profile</a></li>
                <li><a class="dropdown-item" href="/logout"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</header>
