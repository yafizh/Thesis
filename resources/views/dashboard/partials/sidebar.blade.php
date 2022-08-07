<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar" src="{{ asset('storage/' . auth()->user()->employee->file_image) }}"
            alt="User Image" style="object-fit: cover; width: 60px; height: 60px;">
        <div>
            <p class="app-sidebar__user-name">{{ auth()->user()->employee->name }}</p>
            <p class="app-sidebar__user-designation">
                @if (auth()->user()->status === 'EMPLOYEE')
                    Pegawai {{ auth()->user()->employee->status === 'INTERNAL' ? 'Internal' : 'Eksternal' }}
                @else
                    {{ auth()->user()->status === 'ADMIN' ? 'Admin' : 'Resepsionis' }}
                @endif
            </p>
        </div>
    </div>
    <ul class="app-menu">
        <li>
            <a class="app-menu__item  {{ $page == 'dashboard' ? 'active' : '' }}" href="/dashboard">
                <i class="app-menu__icon fa fa-home"></i>
                <span class="app-menu__label">Dashboard</span>
            </a>
        </li>
        @can('admin')
            <li
                class="treeview  {{ in_array(true, [Request::is('employees/create'), Request::is('employees/update'), Request::is('employees'), Request::is('users')]) ? 'is-expanded' : 'link-dark' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-database"></i>
                    <span class="app-menu__label">Data Master</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a class="treeview-item  {{ Request::is('employees*') ? 'active' : '' }}" href="/employees">
                            <i class="icon fa fa-circle-o"></i>
                            Data Pegawai
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('users*') ? 'active' : '' }}" href="/users">
                            <i class="icon fa fa-circle-o"></i>
                            Data Pengguna
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @canany(['admin', 'employee'])
            <li
                class="treeview  {{ in_array($page, ['proposal-research', 'proposal-study']) ? 'is-expanded' : 'link-dark' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-file-text-o"></i>
                    <span class="app-menu__label">Data Proposal</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a class="treeview-item  {{ $page == 'proposal-research' ? 'active' : '' }}"
                            href="/proposal-research">
                            <i class="icon fa fa-circle-o"></i>
                            Proposal Penelitian
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ $page == 'proposal-study' ? 'active' : '' }}" href="/proposal-study">
                            <i class="icon fa fa-circle-o"></i>
                            Proposal Pengkajian
                        </a>
                    </li>
                </ul>
            </li>
            @canany(['internal', 'external'])
                <li class="treeview  {{ in_array($page, ['research', 'study']) ? 'is-expanded' : 'link-dark' }}">
                    <a class="app-menu__item" href="#" data-toggle="treeview">
                        <i class="app-menu__icon fa fa-file-archive-o"></i>
                        <span class="app-menu__label">Data Kegiatan</span>
                        <i class="treeview-indicator fa fa-angle-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a class="treeview-item  {{ $page == 'research' ? 'active' : '' }}" href="/research">
                                <i class="icon fa fa-circle-o"></i>
                                Kegiatan Penelitian
                            </a>
                        </li>
                        <li>
                            <a class="treeview-item {{ $page == 'study' ? 'active' : '' }}" href="/study">
                                <i class="icon fa fa-circle-o"></i>
                                Kegiatan Pengkajian
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            <li class="treeview  {{ in_array($page, ['report-research', 'report-study']) ? 'is-expanded' : 'link-dark' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-file-pdf-o"></i>
                    <span class="app-menu__label">Data Laporan Akhir</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a class="treeview-item  {{ $page == 'report-research' ? 'active' : '' }}" href="/report-research">
                            <i class="icon fa fa-circle-o"></i>
                            Laporan Akhir Penelitian
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ $page == 'report-study' ? 'active' : '' }}" href="/report-study">
                            <i class="icon fa fa-circle-o"></i>
                            Laporan Akhir Pengkajian
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @canany(['admin', 'receptionist'])
            <li class="treeview {{ Request::is('guests') ? 'is-expanded' : 'link-dark' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-address-book-o"></i>
                    <span class="app-menu__label">Buku Tamu</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    @can('receptionist')
                        <li>
                            <a class="treeview-item {{ Request::is('guests/create') ? 'active' : '' }}" href="/guests/create">
                                <i class="icon fa fa-circle-o"></i>
                                Buku Tamu
                            </a>
                        </li>
                    @endcan
                    <li>
                        <a class="treeview-item {{ Request::is('guests') ? 'active' : '' }}" href="/guests">
                            <i class="icon fa fa-circle-o"></i>
                            Data Tamu
                        </a>
                    </li>
                </ul>
            </li>
        @endcanany
        @can('admin')
            <li
                class="treeview {{ in_array(true, [Request::is('employees/report'), Request::is('guests/report'), Request::is('proposal-research/report'), Request::is('proposal-study/report'), Request::is('report-research/report'), Request::is('report-study/report'), Request::is('research/report'), Request::is('study/report'), Request::is('research-member/report'), Request::is('study-member/report')]) ? 'is-expanded' : 'link-dark' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-file-o"></i>
                    <span class="app-menu__label">Laporan</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <form action="/employees/report" method="POST">
                            @csrf
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-link w-100 treeview-item {{ Request::is('employees/report') ? 'active' : '' }}">
                                <i class="icon fa fa-circle-o"></i>
                                Pegawai
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="/guests/report" method="POST">
                            @csrf
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-link w-100 treeview-item {{ Request::is('guests/report') ? 'active' : '' }}">
                                <i class="icon fa fa-circle-o"></i>
                                Pengunjung
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="/proposal-research/report" method="POST">
                            @csrf
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-link w-100 treeview-item {{ Request::is('proposal-research/report') ? 'active' : '' }}">
                                <i class="icon fa fa-circle-o"></i>
                                Proposal Penelitian
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="/proposal-study/report" method="POST">
                            @csrf
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-link w-100 treeview-item {{ Request::is('proposal-study/report') ? 'active' : '' }}">
                                <i class="icon fa fa-circle-o"></i>
                                Proposal Study
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="/report-research/report" method="POST">
                            @csrf
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-link w-100 treeview-item {{ Request::is('report-research/report') ? 'active' : '' }}">
                                <i class="icon fa fa-circle-o"></i>
                                Laporan Akhir Penelitian
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="/report-study/report" method="POST">
                            @csrf
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-link w-100 treeview-item {{ Request::is('report-study/report') ? 'active' : '' }}">
                                <i class="icon fa fa-circle-o"></i>
                                Laporan Akhir Pengkajian
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="/research/report" method="POST">
                            @csrf
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-link w-100 treeview-item {{ Request::is('research/report') ? 'active' : '' }}">
                                <i class="icon fa fa-circle-o"></i>
                                Penelitian
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="/study/report" method="POST">
                            @csrf
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-link w-100 treeview-item {{ Request::is('study/report') ? 'active' : '' }}">
                                <i class="icon fa fa-circle-o"></i>
                                Penelitian
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="/research-member/report" method="POST">
                            @csrf
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-link w-100 treeview-item {{ Request::is('research-member/report') ? 'active' : '' }}">
                                <i class="icon fa fa-circle-o"></i>
                                Anggota Penelitian
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="/study-member/report" method="POST">
                            @csrf
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-link w-100 treeview-item {{ Request::is('study-member/report') ? 'active' : '' }}">
                                <i class="icon fa fa-circle-o"></i>
                                Anggota Pengkajian
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        @endcanany
    </ul>
</aside>
