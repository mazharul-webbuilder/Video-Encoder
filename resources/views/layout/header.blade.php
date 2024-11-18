<nav class="navbar">
  <a href="#" class="sidebar-toggler">
    <i data-feather="menu"></i>
  </a>
  <div class="navbar-content">
    <form class="search-form">
      <div class="input-group">
        <div class="input-group-text">
          <i data-feather="search"></i>
        </div>
        <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
      </div>
    </form>
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="{{ url('assets/images/flags/us.svg') }}" class="wd-20 me-1" title="us" alt="us"> <span class="ms-1 me-1 d-none d-md-inline-block">English</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="languageDropdown">
          <a href="javascript:;" class="dropdown-item py-2"> <img src="{{ url('assets/images/flags/us.svg') }}" class="wd-20 me-1" title="us" alt="us"> <span class="ms-1"> English </span></a>
          <a href="javascript:;" class="dropdown-item py-2"> <img src="{{ url('assets/images/flags/fr.svg') }}" class="wd-20 me-1" title="fr" alt="fr"> <span class="ms-1"> French </span></a>
          <a href="javascript:;" class="dropdown-item py-2"> <img src="{{ url('assets/images/flags/de.svg') }}" class="wd-20 me-1" title="de" alt="de"> <span class="ms-1"> German </span></a>
          <a href="javascript:;" class="dropdown-item py-2"> <img src="{{ url('assets/images/flags/pt.svg') }}" class="wd-20 me-1" title="pt" alt="pt"> <span class="ms-1"> Portuguese </span></a>
          <a href="javascript:;" class="dropdown-item py-2"> <img src="{{ url('assets/images/flags/es.svg') }}" class="wd-20 me-1" title="es" alt="es"> <span class="ms-1"> Spanish </span></a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i data-feather="grid"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i data-feather="mail"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i data-feather="bell"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img class="wd-30 ht-30 rounded-circle" src="{{ asset('/DurbarTechIcon.svg') }}" alt="profile">
        </a>
        <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
          <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
            <div class="mb-3">
              <img class="wd-80 ht-80 rounded-circle" src="{{ asset('/DurbarTechIcon.svg') }}" alt="">
            </div>
            <div class="text-center">
                @php
                    $admin = \Illuminate\Support\Facades\Auth::user();
                @endphp
              <p class="tx-16 fw-bolder">{{$admin->name}}</p>
              <p class="tx-12 text-muted">{{$admin->username}}</p>
            </div>
          </div>
          <ul class="list-unstyled p-1">
            <li class="dropdown-item py-2">
              <a href="{{ url('/general/admin') }}" class="text-body ms-0">
                <i class="me-2 icon-md" data-feather="user"></i>
                <span>Profile</span>
              </a>
            </li>
            <li class="dropdown-item py-2">
              <a href="{{ route('admin.edit') }}" class="text-body ms-0 d-block">
                <i class="me-2 icon-md" data-feather="edit"></i>
                <span>Edit Profile</span>
              </a>
            </li>
            <li class="dropdown-item py-2">
              <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('LogoutForm').submit()"  class="text-body ms-0 d-block">
                <i class="me-2 icon-md" data-feather="log-out"></i>
                <span>Log Out</span>
              </a>
                <form action="{{route('admin.logout')}}" method="post" id="LogoutForm">
                    @csrf
                </form>
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</nav>
