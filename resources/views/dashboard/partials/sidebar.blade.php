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
            <a class="app-menu__item  {{ Request::is('/') || Request::is('dashboard') ? 'active' : '' }}"
                href="/dashboard">
                <i class="app-menu__icon fa fa-home"></i>
                <span class="app-menu__label">Dashboard</span>
            </a>
        </li>
        @can('admin')
            <li class="treeview  {{ Request::is('employees*') || Request::is('users*') ? 'is-expanded' : 'link-dark' }}">
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
                class="treeview  {{ Request::is('proposal-research*') || Request::is('proposal-study*') ? 'is-expanded' : 'link-dark' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-file-text-o"></i>
                    <span class="app-menu__label">Data Proposal</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a class="treeview-item  {{ Request::is('proposal-research*') ? 'active' : '' }}"
                            href="/proposal-research">
                            <i class="icon fa fa-circle-o"></i>
                            Proposal Penelitian
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('proposal-study*') ? 'active' : '' }}"
                            href="/proposal-study">
                            <i class="icon fa fa-circle-o"></i>
                            Proposal Pengkajian
                        </a>
                    </li>
                </ul>
            </li>
            @canany(['internal'])
                <li class="treeview  {{ Request::is('research*') || Request::is('study*') ? 'is-expanded' : 'link-dark' }}">
                    <a class="app-menu__item" href="#" data-toggle="treeview">
                        <i class="app-menu__icon fa fa-file-archive-o"></i>
                        <span class="app-menu__label">Data Kegiatan</span>
                        <i class="treeview-indicator fa fa-angle-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a class="treeview-item  {{ Request::is('research*') ? 'active' : '' }}" href="/research">
                                <i class="icon fa fa-circle-o"></i>
                                Kegiatan Penelitian
                            </a>
                        </li>
                        <li>
                            <a class="treeview-item {{ Request::is('study*') ? 'active' : '' }}" href="/study">
                                <i class="icon fa fa-circle-o"></i>
                                Kegiatan Pengkajian
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            <li
                class="treeview  {{ Request::is('report-research*') || Request::is('report-study*') ? 'is-expanded' : 'link-dark' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-file-pdf-o"></i>
                    <span class="app-menu__label">Data Laporan Akhir</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a class="treeview-item  {{ Request::is('report-research*') ? 'active' : '' }}"
                            href="/report-research">
                            <i class="icon fa fa-circle-o"></i>
                            Laporan Akhir Penelitian
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('report-study*') ? 'active' : '' }}" href="/report-study">
                            <i class="icon fa fa-circle-o"></i>
                            Laporan Akhir Pengkajian
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @canany(['admin', 'receptionist'])
            <li class="treeview {{ Request::is('guests*') ? 'is-expanded' : 'link-dark' }}">
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
            <li class="treeview {{ Request::is('report*') ? 'is-expanded' : 'link-dark' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-file-o"></i>
                    <span class="app-menu__label">Laporan</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a class="treeview-item {{ Request::is('report/employee') ? 'active' : '' }}" href="/report/employee">
                            <i class="icon fa fa-circle-o"></i>
                            Pegawai
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('guests') ? 'active' : '' }}" href="/guests">
                            <i class="icon fa fa-circle-o"></i>
                            Pengunjung
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('report/proposal-research') ? 'active' : '' }}" href="/report/proposal-research">
                            <i class="icon fa fa-circle-o"></i>
                            Proposal Penelitian
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('guests') ? 'active' : '' }}" href="/guests">
                            <i class="icon fa fa-circle-o"></i>
                            Proposal Pengkajian
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('guests') ? 'active' : '' }}" href="/guests">
                            <i class="icon fa fa-circle-o"></i>
                            Laporan Akhir Penelitian
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('guests') ? 'active' : '' }}" href="/guests">
                            <i class="icon fa fa-circle-o"></i>
                            Laporan Akhir Pengkajian
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('guests') ? 'active' : '' }}" href="/guests">
                            <i class="icon fa fa-circle-o"></i>
                            Penelitian
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('guests') ? 'active' : '' }}" href="/guests">
                            <i class="icon fa fa-circle-o"></i>
                            Pengkajian
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('guests') ? 'active' : '' }}" href="/guests">
                            <i class="icon fa fa-circle-o"></i>
                            Anggota Penelitian
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ Request::is('guests') ? 'active' : '' }}" href="/guests">
                            <i class="icon fa fa-circle-o"></i>
                            Anggota Penggajian
                        </a>
                    </li>
                </ul>
            </li>
        @endcanany
    </ul>
</aside>
