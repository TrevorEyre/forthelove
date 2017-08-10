<header class="tke-site-header">
  <div class="tke-nav-container">
    <nav class="tke-nav-bar">
      <button class="tke-nav-button">
        <span>Menu</span>
      </button>
      <a class="tke-nav-logo" href="{{ home_url('/') }}">
        @include('partials.logo')
      </a>
    </nav>
    <nav class="tke-nav-drawer tke-open">
      <div class="tke-nav-button-container">
        <button class="tke-nav-button tke-open">
          <span>Menu</span>
        </button>
        <a class="tke-nav-logo" href="{{ home_url('/') }}">
          @include('partials.logo')
        </a>
      </div>
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu([
          'theme_location' => 'primary_navigation',
          'menu_class' => 'tke-nav-primary'
        ]) !!}
      @endif
    </nav>
  </div>
</header>
