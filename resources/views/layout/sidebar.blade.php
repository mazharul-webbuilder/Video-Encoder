<nav class="sidebar">
  <div class="sidebar-header">
    <a href="javascript:void(0)" class="sidebar-brand">
      Durbar<span>Tech</span>
    </a>
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category">Main</li>
      <li class="nav-item {{ active_class(['/']) }}">
        <a href="{{ url('/') }}" class="nav-link">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item nav-category">Manage</li>
      <li class="nav-item {{ active_class(['email/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#email" role="button" aria-expanded="{{ is_active_route(['email/*']) }}" aria-controls="email">
          <i class="link-icon" data-feather="mail"></i>
          <span class="link-title">Encoder</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['fetch-videos', 'fetch-all-videos', 'encoding-list', 'encoded-videos']) }}" id="email">
          <ul class="nav sub-menu">
              <li class="nav-item">
                  <a href="{{ route('encoding.list') }}" class="nav-link {{ active_class(['encoding-list']) }}">Fetch Videos</a>
              </li>
            <li class="nav-item">
              <a href="{{ route('fetch.all.videos') }}" class="nav-link {{ active_class(['fetch-all-videos']) }}">All Videos</a>
            </li>
              <li class="nav-item">
              <a href="{{ route('fetch.videos') }}" class="nav-link {{ active_class(['fetch-videos']) }}">Encoding Videos</a>
            </li>
              <li class="nav-item">
              <a href="{{ route('encoded.videos') }}" class="nav-link {{ active_class(['encoded-videos']) }}">Encoded Videos</a>
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</nav>
