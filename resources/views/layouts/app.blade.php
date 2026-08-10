<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kalkulator Estimasi Gajian') - GajiTek</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Tailwind CSS V4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="tailwindcss">
        @theme {
            --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
                'Segoe UI Symbol', 'Noto Color Emoji';
        }
    </style>

    <style>
        /* Custom print stylesheet for monthly report PDF/Print */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                font-size: 12pt;
            }
            .print-container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen flex flex-col antialiased">

    <!-- Header / Navbar -->
    <nav class="no-print bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left: Logo & Navigation Tabs -->
                <div class="flex flex-1">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center space-x-2">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white font-bold shadow-md shadow-blue-500/20">
                                <span>G</span>
                            </div>
                            <span class="font-bold text-xl tracking-tight bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">GajiTek</span>
                        </a>
                    </div>
                    
                    @auth
                    <!-- Desktop Tabs -->
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-4 items-center">
                        @if(!Auth::user()->is_admin)
                            <!-- Technician Desktop Tabs -->
                            <a href="{{ route('harian') }}" 
                               class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('harian') ? 'bg-blue-50 text-blue-600 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"></path></svg>
                                    <span>Harian</span>
                                </span>
                            </a>
                            <a href="{{ route('bulanan') }}" 
                               class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('bulanan') ? 'bg-blue-50 text-blue-600 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                    <span>Bulanan</span>
                                </span>
                            </a>
                        @else
                            <!-- Admin Desktop Tabs -->
                            <a href="{{ route('monitoring.index') }}" 
                               class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('monitoring.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"></path></svg>
                                    <span>Monitoring Gaji</span>
                                </span>
                            </a>
                            <a href="{{ route('tarif.index') }}" 
                               class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('tarif.index') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span>Kategori Tarif</span>
                                </span>
                            </a>
                            <a href="{{ route('users.index') }}" 
                               class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 21H9.91A11.386 11.386 0 015 19.237v-.109m0 .109a11.386 11.386 0 004.912 1.763h.178A11.386 11.386 0 0015 19.237m-10-.109V19c0-1.113.285-2.16.786-3.07M5 19.237a9.38 9.38 0 01-2.625-.372 9.337 9.337 0 01-4.121-.952 4.125 4.125 0 017.533-2.493M10 5a3.5 3.5 0 11-7 0 3.5 3.5 0 017 0zm6.5 1.5a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span>Manage User</span>
                                </span>
                            </a>
                        @endif
                    </div>
                    @endauth
                </div>

                <!-- Right: Profile Info & Logout -->
                @auth
                <div class="flex items-center space-x-3">
                    <!-- Profile Dropdown Container -->
                    <div class="relative">
                        <!-- Dropdown Trigger Button -->
                        <button type="button" 
                                onclick="toggleProfileDropdown(event)" 
                                id="profile-dropdown-btn"
                                class="flex items-center space-x-3 p-1.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-all duration-200 focus:outline-none cursor-pointer">
                            <div class="hidden md:flex flex-col text-right">
                                <span class="text-sm font-semibold text-slate-700 leading-none">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-slate-500 leading-none mt-1.5">
                                    @if(Auth::user()->is_admin)
                                        <span class="text-indigo-600 font-semibold text-[10px] bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded">Admin</span>
                                    @else
                                        <span class="text-blue-600 font-semibold text-[10px] bg-blue-50 border border-blue-100 px-1.5 py-0.5 rounded">Teknisi</span>
                                    @endif
                                </span>
                            </div>

                            <!-- Profile Circle -->
                            <div class="w-10 h-10 rounded-full bg-slate-200 border border-slate-300 flex items-center justify-center text-slate-700 font-bold shadow-inner">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        </button>

                        <!-- Dropdown Menu Content (Smooth Animated) -->
                        <div id="profile-dropdown-menu" 
                             class="absolute right-0 mt-2 w-56 rounded-2xl border border-slate-100 bg-white shadow-xl shadow-slate-200/50 py-1.5 z-50 transition-all duration-200 transform origin-top-right scale-95 opacity-0 pointer-events-none">
                            <!-- User Header -->
                            <div class="px-4 py-2.5 border-b border-slate-100">
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Masuk sebagai</p>
                                <p class="text-sm font-semibold text-slate-800 truncate mt-0.5">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                            </div>
                            
                            <!-- Actions -->
                            <div class="p-1">
                                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full text-left px-3 py-2 text-sm font-medium text-rose-600 hover:bg-rose-50 rounded-xl transition-all duration-150 flex items-center space-x-2 cursor-pointer">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path></svg>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button (Hamburger) -->
                    <button type="button" 
                            onclick="toggleMobileMenu(event)" 
                            class="sm:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none transition duration-200 cursor-pointer">
                        <svg class="w-6 h-6" id="menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path></svg>
                    </button>
                </div>
                @endauth
            </div>
        </div>

        <!-- Mobile Navigation Menu (Smooth Animated Max-Height) -->
        @auth
        <div id="mobile-menu" 
             class="sm:hidden border-t border-slate-200 bg-white px-4 space-y-4 transition-all duration-300 ease-in-out overflow-hidden max-h-0 opacity-0">
            <div class="space-y-1.5 pt-4">
                @if(!Auth::user()->is_admin)
                    <!-- Technician Mobile Menu -->
                    <a href="{{ route('harian') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('harian') ? 'bg-blue-50 text-blue-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"></path></svg>
                        <span>Harian</span>
                    </a>
                    <a href="{{ route('bulanan') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('bulanan') ? 'bg-blue-50 text-blue-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                        <span>Bulanan</span>
                    </a>
                @else
                    <!-- Admin Mobile Menu -->
                    <a href="{{ route('monitoring.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('monitoring.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"></path></svg>
                        <span>Monitoring Gaji</span>
                    </a>
                    <a href="{{ route('tarif.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('tarif.index') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Kategori Tarif</span>
                    </a>
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 21H9.91A11.386 11.386 0 015 19.237v-.109m0 .109a11.386 11.386 0 004.912 1.763h.178A11.386 11.386 0 0015 19.237m-10-.109V19c0-1.113.285-2.16.786-3.07M5 19.237a9.38 9.38 0 01-2.625-.372 9.337 9.337 0 01-4.121-.952 4.125 4.125 0 017.533-2.493M10 5a3.5 3.5 0 11-7 0 3.5 3.5 0 017 0zm6.5 1.5a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Manage User</span>
                    </a>
                @endif
            </div>

            <!-- Profile Info & Logout for Mobile -->
            <div class="border-t border-slate-100 pt-4 pb-4">
                <div class="flex items-center space-x-3 px-3 py-2.5 bg-slate-50/50 rounded-2xl border border-slate-100/50 mb-3.5">
                    <div class="w-10 h-10 rounded-full bg-slate-200 border border-slate-300 flex items-center justify-center text-slate-700 font-bold shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-slate-800 truncate leading-none">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-500 truncate mt-1.5 leading-none">{{ Auth::user()->email }}</div>
                    </div>
                    <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider {{ Auth::user()->is_admin ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                        {{ Auth::user()->is_admin ? 'Admin' : 'Teknisi' }}
                    </span>
                </div>
                
                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                    @csrf
                    <button type="submit" 
                            class="w-full text-center px-4 py-3 text-base font-semibold text-rose-600 hover:bg-rose-50/50 bg-rose-50 border border-rose-100 rounded-2xl transition-all duration-150 flex items-center justify-center space-x-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path></svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </nav>

    <!-- Main Content Area -->
    <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <!-- Success/Error Alert Alert -->
        @if(session('success'))
        <div class="no-print mb-6 max-w-4xl mx-auto flex items-center p-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-sm transition-all duration-300" role="alert">
            <svg class="flex-shrink-0 w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-sm font-medium">
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="no-print mb-6 max-w-4xl mx-auto flex items-center p-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-2xl shadow-sm transition-all duration-300" role="alert">
            <svg class="flex-shrink-0 w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div class="text-sm font-medium">
                {{ session('error') }}
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="no-print bg-white border-t border-slate-200 py-6 mt-12 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} GajiTek. Dibuat khusus untuk Teknisi Lapangan.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function toggleProfileDropdown(event) {
            event.stopPropagation();
            const menu = document.getElementById('profile-dropdown-menu');
            if (menu) {
                if (menu.classList.contains('opacity-0')) {
                    menu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                    menu.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
                } else {
                    menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                    menu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
                }
            }
        }

        function toggleMobileMenu(event) {
            event.stopPropagation();
            const menu = document.getElementById('mobile-menu');
            if (menu) {
                if (menu.classList.contains('max-h-0')) {
                    menu.classList.remove('max-h-0', 'opacity-0');
                    // Calculate real height
                    menu.style.maxHeight = menu.scrollHeight + 'px';
                    menu.classList.add('opacity-100');
                } else {
                    menu.style.maxHeight = '0px';
                    menu.classList.add('max-h-0', 'opacity-0');
                    menu.classList.remove('opacity-100');
                }
            }
        }

        // Close dropdowns when clicking outside
        window.addEventListener('click', function(e) {
            const profileMenu = document.getElementById('profile-dropdown-menu');
            const profileBtn = document.getElementById('profile-dropdown-btn');
            if (profileMenu && !profileMenu.classList.contains('opacity-0')) {
                if (profileBtn && !profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                    profileMenu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
                }
            }

            const mobileMenu = document.getElementById('mobile-menu');
            // Close mobile menu if clicked outside nav area (except buttons)
            if (mobileMenu && !mobileMenu.classList.contains('max-h-0')) {
                const nav = document.querySelector('nav');
                if (nav && !nav.contains(e.target)) {
                    mobileMenu.style.maxHeight = '0px';
                    mobileMenu.classList.add('max-h-0', 'opacity-0');
                    mobileMenu.classList.remove('opacity-100');
                }
            }
        });
    </script>
</body>
</html>
