<div class="page-header">
    <div>
        <h1 class="page-title">@yield('page_title', 'Dashboard')</h1>
        @hasSection('page_subtitle')
            <p class="page-subtitle">@yield('page_subtitle')</p>
        @endif
    </div>
    @yield('page_actions')
</div>
