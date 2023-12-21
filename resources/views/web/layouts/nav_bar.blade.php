<nav class="navbar navbar-expand-lg">
    <div class="container p-3">
        <a class="navbar-brand fw-bolder" href="#"><h4 class="mb-1">Warehouse</h4></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto ms-4 mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link @isset($page) {{ $page == "dashboard" ? 'active fw-medium' : '' }} @endisset" aria-current="page" href="{{ route('w-dashboard.index') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @isset($page) {{ $page == "manual" ? 'active fw-medium' : '' }} @endisset" aria-current="page" href="{{ route('w-cek-manual.index') }}">Cek Manual</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @isset($page) {{ ($page == "users" || $page == "barang") ? 'fw-medium' : '' }} @endisset" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Data Master
                    </a>
                    <ul class="dropdown-menu">  
                        <li><a class="dropdown-item @isset($page) {{ $page == "users" ? 'fw-medium' : '' }} @endisset" href="{{ route('m-users.index') }}">Master User</a></li>
                        <li><a class="dropdown-item @isset($page) {{ $page == "barang" ? 'fw-medium' : '' }} @endisset" href="{{ route('m-barang.index') }}">Master Barang</a></li>
                    </ul>
                </li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <img src="{{ asset('images/dummy.jpg') }}" class="rounded float-end" width="40" alt="...">
            </a> 
        </div>
    </div>
</nav>