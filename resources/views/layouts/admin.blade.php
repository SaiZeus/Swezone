<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Dashboard - Marathon Ticketing')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <style>
        /* =========================================
           GLOBAL
           ========================================= */

        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        *::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        *::-webkit-scrollbar-track {
            background: transparent;
        }

        *::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        body {
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        /* =========================================
           SIDEBAR
           ========================================= */

        .admin-sidebar {
            width: 260px;
            background:
                radial-gradient(
                    circle at top left,
                    rgba(99, 102, 241, 0.16),
                    transparent 32%
                ),
                linear-gradient(
                    180deg,
                    #111827 0%,
                    #0f172a 45%,
                    #0b1120 100%
                );
            border-right: 1px solid rgba(255, 255, 255, 0.06);
        }

        /* =========================================
           BRAND
           ========================================= */

        .admin-brand {
            height: 72px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .admin-brand-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: linear-gradient(
                135deg,
                #4f46e5,
                #6366f1
            );
            color: white;
            box-shadow:
                0 8px 20px rgba(79, 70, 229, 0.28);
        }

        .admin-brand-text {
            margin-left: 11px;
        }

        .admin-brand-title {
            color: #f8fafc;
            font-size: 0.82rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            line-height: 1.2;
        }

        .admin-brand-subtitle {
            margin-top: 3px;
            color: #64748b;
            font-size: 0.58rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* =========================================
           NAVIGATION
           ========================================= */

        .admin-nav {
            padding: 22px 13px;
        }

        .admin-nav-label {
            padding: 0 12px;
            margin-bottom: 9px;
            color: #64748b;
            font-size: 0.59rem;
            font-weight: 850;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .admin-nav-link {
            position: relative;
            display: flex;
            align-items: center;
            min-height: 46px;
            padding: 0 13px;
            margin-bottom: 5px;
            border: 1px solid transparent;
            border-radius: 11px;
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 650;
            transition:
                background .18s ease,
                color .18s ease,
                transform .18s ease,
                border-color .18s ease;
        }

        .admin-nav-link i {
            width: 28px;
            margin-right: 8px;
            color: #64748b;
            font-size: 0.84rem;
            transition: color .18s ease;
        }

        .admin-nav-link:hover {
            background: rgba(255, 255, 255, 0.045);
            color: #e2e8f0;
            transform: translateX(2px);
        }

        .admin-nav-link:hover i {
            color: #a5b4fc;
        }

        .admin-nav-link.active {
            background:
                linear-gradient(
                    90deg,
                    rgba(79, 70, 229, 0.22),
                    rgba(99, 102, 241, 0.08)
                );
            border-color: rgba(129, 140, 248, 0.13);
            color: #c7d2fe;
            font-weight: 800;
            box-shadow:
                inset 3px 0 0 #6366f1,
                0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .admin-nav-link.active i {
            color: #818cf8;
        }

        .admin-nav-link.external {
            margin-top: 18px;
            color: #64748b;
        }

        .admin-nav-link.external:hover {
            color: #cbd5e1;
        }

        /* =========================================
           SIDEBAR FOOTER
           ========================================= */

        .admin-sidebar-footer {
            padding: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .admin-system-status {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 10px 11px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.035);
        }

        .admin-status-dot {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: #34d399;
            box-shadow: 0 0 0 4px rgba(52, 211, 153, 0.08);
        }

        .admin-system-status span {
            color: #64748b;
            font-size: 0.61rem;
            font-weight: 700;
        }

        /* =========================================
           MAIN AREA
           ========================================= */

        .admin-main {
            background:
                linear-gradient(
                    180deg,
                    #f8fafc 0%,
                    #f4f6fa 100%
                );
        }

        /* =========================================
           TOP HEADER
           ========================================= */

        .admin-header {
            min-height: 72px;
            background: rgba(255, 255, 255, 0.94);
            border-bottom: 1px solid #e5e7eb;
            box-shadow:
                0 3px 15px rgba(15, 23, 42, 0.035);
            backdrop-filter: blur(12px);
        }

        .admin-page-title-wrapper {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .admin-page-title-icon {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #eef2ff;
            color: #6366f1;
            font-size: 0.75rem;
        }

        .admin-page-title {
            color: #1e293b;
            font-size: 1rem;
            font-weight: 850;
            letter-spacing: -0.02em;
        }

        /* =========================================
           ADMIN PROFILE
           ========================================= */

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 14px;
            border-left: 1px solid #e5e7eb;
        }

        .admin-profile-info {
            text-align: right;
        }

        .admin-profile-name {
            color: #344054;
            font-size: 0.71rem;
            font-weight: 800;
        }

        .admin-profile-role {
            margin-top: 2px;
            color: #98a2b3;
            font-size: 0.58rem;
            font-weight: 650;
        }

        .admin-avatar {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: linear-gradient(
                135deg,
                #4f46e5,
                #6366f1
            );
            color: white;
            font-size: 0.76rem;
            font-weight: 900;
            box-shadow:
                0 6px 15px rgba(79, 70, 229, 0.2);
        }

        /* =========================================
           CONTENT AREA
           ========================================= */

        .admin-content {
            padding: 28px;
        }

        /* =========================================
           SUCCESS ALERT
           ========================================= */

        .admin-success-alert {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 22px;
            padding: 13px 15px;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            background: #f0fdf4;
            color: #166534;
            font-size: 0.72rem;
            font-weight: 700;
            box-shadow:
                0 4px 12px rgba(22, 101, 52, 0.035);
        }

        .admin-success-icon {
            width: 30px;
            height: 30px;
            min-width: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #dcfce7;
            color: #16a34a;
        }

        /* =========================================
           MOBILE
           ========================================= */

        @media (max-width: 900px) {

            .admin-sidebar {
                width: 220px;
            }

            .admin-content {
                padding: 22px;
            }

            .admin-profile-info {
                display: none;
            }
        }

        @media (max-width: 640px) {

            .admin-sidebar {
                width: 68px;
            }

            .admin-brand {
                justify-content: center;
                padding: 0;
            }

            .admin-brand-text,
            .admin-nav-label,
            .admin-nav-link span,
            .admin-sidebar-footer {
                display: none;
            }

            .admin-brand-icon {
                width: 38px;
                height: 38px;
            }

            .admin-nav {
                padding: 15px 8px;
            }

            .admin-nav-link {
                justify-content: center;
                padding: 0;
            }

            .admin-nav-link i {
                width: auto;
                margin: 0;
            }

            .admin-nav-link.active {
                box-shadow:
                    inset 3px 0 0 #6366f1;
            }

            .admin-header {
                padding-left: 16px;
                padding-right: 16px;
            }

            .admin-content {
                padding: 17px;
            }

            .admin-page-title-icon {
                display: none;
            }

            .admin-page-title {
                font-size: 0.88rem;
            }

            .admin-profile {
                padding-left: 10px;
            }
        }
    </style>
</head>


<body class="font-sans antialiased">

    <div class="flex h-screen overflow-hidden">


        <!-- =========================================
             SIDEBAR
             ========================================= -->
        <aside class="admin-sidebar text-slate-200 flex flex-col flex-shrink-0">


            <!-- Brand -->
            <div class="admin-brand">

                <div class="admin-brand-icon">
                    <i class="fa-solid fa-person-running"></i>
                </div>

                <div class="admin-brand-text">

                    <div class="admin-brand-title">
                        MARATHON ADMIN
                    </div>

                    <div class="admin-brand-subtitle">
                        Ticketing System
                    </div>

                </div>

            </div>


            <!-- Navigation -->
            <nav class="admin-nav flex-1">

                <div class="admin-nav-label">
                    Main Menu
                </div>


                <!-- Dashboard -->
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                >

                    <i class="fa-solid fa-chart-line"></i>

                    <span>
                        Dashboard
                    </span>

                </a>


                <!-- Events -->
                <a
                    href="{{ route('admin.events.index') }}"
                    class="admin-nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}"
                >

                    <i class="fa-solid fa-calendar-days"></i>

                    <span>
                        Manage Events
                    </span>

                </a>


                <!-- Main Site -->
                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    class="admin-nav-link external"
                >

                    <i class="fa-solid fa-arrow-up-right-from-square"></i>

                    <span>
                        Visit Main Site
                    </span>

                </a>

            </nav>


            <!-- Sidebar Footer -->
            <div class="admin-sidebar-footer">

                <div class="admin-system-status">

                    <span class="admin-status-dot"></span>

                    <span>
                        System Online
                    </span>

                </div>

            </div>

        </aside>


        <!-- =========================================
             MAIN CONTENT AREA
             ========================================= -->
        <div class="admin-main flex-1 flex flex-col overflow-y-auto">


            <!-- =====================================
                 HEADER
                 ===================================== -->
            <header class="admin-header px-7 flex items-center justify-between flex-shrink-0">


                <!-- Page Title -->
                <div class="admin-page-title-wrapper">

                    <div class="admin-page-title-icon">

                        <i class="fa-solid fa-layer-group"></i>

                    </div>

                    <h1 class="admin-page-title">
                        @yield('page-title', 'Dashboard')
                    </h1>

                </div>


                <!-- Admin Profile -->
                <div class="admin-profile">

                    <div class="admin-profile-info">

                        <div class="admin-profile-name">
                            Admin User
                        </div>

                        <div class="admin-profile-role">
                            Administrator
                        </div>

                    </div>


                    <div class="admin-avatar">
                        A
                    </div>

                </div>

            </header>


            <!-- =====================================
                 PAGE CONTENT
                 ===================================== -->
            <main class="admin-content">

                @if(session('success'))

                    <div class="admin-success-alert">

                        <div class="admin-success-icon">

                            <i class="fa-solid fa-check"></i>

                        </div>

                        <span>
                            {{ session('success') }}
                        </span>

                    </div>

                @endif


                @yield('content')

            </main>


        </div>

    </div>

</body>

</html>