<!-- header start -->
<header id="xb-header-area" class="header-area header-style header-style--two header-transparent">
    <div class="xb-header stricky">
        <div class="container">
            <div class="header__wrap ul_li_between">
                <div class="xb-header-logo" style="height: 50px; display: flex; align-items: center;">
                    <a href="{{ url('/') }}" class="logo1" style="height: 100%; display: flex; align-items: center;">
                        <img src="{{ asset('assets/img/logo/Swezon_Logo1.1V.svg') }}" alt="Logo" style="height: 200px; width: auto; object-fit: contain; position: relative; top: 8px;">
                    </a>
                </div>
                <div class="main-menu__wrap navbar navbar-expand-lg p-0">
                    <nav class="main-menu collapse navbar-collapse">
                        <ul>
                            <li class="active">
                                <a href="{{ url('/') }}"><span>Home</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="header-bar-mobile side-menu d-lg-none">
                    <a class="xb-nav-mobile" href="javascript:void(0);">
                        <i class="far fa-bars"></i>
                    </a>
                </div>
            </div>
            <div class="xb-header-wrap">
                <div class="xb-header-menu">
                    <div class="xb-header-menu-scroll">
                        <div class="xb-menu-close xb-hide-xl xb-close"></div>
                        <div class="xb-logo-mobile xb-hide-xl" style="height: 50px; display: flex; align-items: center;">
                            <a href="{{ url('/') }}" rel="home" style="height: 100%; display: flex; align-items: center;">
                                <img src="{{ asset('assets/img/logo/Swezon_Logo1.1V.svg') }}" alt="Logo" style="height: 200px; width: auto; object-fit: contain; position: relative; top: 8px;">
                            </a>
                        </div>
                        <nav class="xb-header-nav">
                            <ul class="xb-menu-primary clearfix">
                                <li class="menu-item active">
                                    <a href="{{ url('/') }}"><span>Home</span></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="xb-header-menu-backdrop"></div>
            </div>
        </div>
    </div>
</header>
<!-- header end -->