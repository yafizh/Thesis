<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg"
            alt="User Image">
        <div>
            <p class="app-sidebar__user-name">John Doe</p>
            <p class="app-sidebar__user-designation">Frontend Developer</p>
        </div>
    </div>
    <ul class="app-menu">
        <li>
            <a class="app-menu__item  {{ Request::is('/') ? 'active' : '' }}" href="dashboard.html">
                <i class="app-menu__icon fa fa-dashboard"></i>
                <span class="app-menu__label">Dashboard</span>
            </a>
        </li>
        <li class="treeview  {{ (Request::is('employees*') || Request::is('users*')) ? 'is-expanded' : 'link-dark' }}">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-laptop"></i>
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
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-laptop"></i>
                <span class="app-menu__label">Buku Tamu</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item" href="bootstrap-components.html">
                        <i class="icon fa fa-circle-o"></i>
                        Buku Tamu
                    </a>
                </li>
                <li>
                    <a class="treeview-item" href="https://fontawesome.com/v4.7.0/icons/" target="_blank"
                        rel="noopener">
                        <i class="icon fa fa-circle-o"></i>
                        Data Tamu
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>
