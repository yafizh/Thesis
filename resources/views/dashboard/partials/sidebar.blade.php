<div id="sidebar" class="p-3 bg-white rounded shadow" style="min-width: 280px; min-height: 96.5vh;">
    <a href="/" class="d-flex flex-column align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom">
        <img src="/images/logo.png" alt="Logo" width="100" />
        <span class="fs-5 fw-semibold text-uppercase">Balai Pengkajian</span>
        <span class="fs-5 fw-semibold text-uppercase">Teknologi Pertanian</span>
        <span class="fs-5 fw-semibold text-uppercase">Kalimantan Selatan</span>
    </a>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="#" class="nav-link {{ Request::is('home*') ? 'active' : 'link-dark' }}" aria-current="page">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#home" />
                </svg>
                Home
            </a>
        </li>
        <li class="border-top my-3"></li>
        <li>
            <a href="/employees" class="nav-link {{ Request::is('employees*') ? 'active' : 'link-dark' }}">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#person-badge" />
                </svg>
                Pegawai
            </a>
        </li>
        <li>
            <a href="#" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#person" />
                </svg>
                User
            </a>
        </li>
        <li class="border-top my-3"></li>
        <li>
            <a href="#" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#grid" />
                </svg>
                Proposal
            </a>
        </li>
        <li>
            <a href="#" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#people-circle" />
                </svg>
                Laporan
            </a>
        </li>
        <li class="border-top my-3"></li>
        <li>
            <a href="#" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#grid" />
                </svg>
                Ganti Password
            </a>
        </li>
        <li>
            <a href="#" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#people-circle" />
                </svg>
                Keluar
            </a>
        </li>
    </ul>
</div>
