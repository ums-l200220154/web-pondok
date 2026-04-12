<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-NAJAH | @yield('title')</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/background/logo an-najah.png') }}">
    
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-green: #1a5d1a; /* Hijau Khas Pesantren */
            --accent-green: #2ecc71;
            --dark-green: #144514;
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Styling */
        .navbar-custom {
            background-color: var(--primary-green);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 0.5rem 1rem;
        }

        /* Logo & Brand Styling */
        .logo-wrapper {
            background-color: white; /* Memberikan kontras agar logo hijau terlihat */
            padding: 4px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .logo-wrapper img {
            width: 40px; 
            height: 40px;
            object-fit: contain;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 1.4rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
        }

        /* Container Content */
        .main-container {
            margin-top: 30px;
            margin-bottom: 50px;
            flex: 1;
        }

        /* Footer Style */
        .footer-custom {
            background-color: white;
            border-top: 1px solid #dee2e6; /* Garis lebih tipis */
            padding: 15px 0; /* Padding dikurangi dari 25px ke 15px */
            color: #6c757d;
            font-size: 0.8rem; /* Ukuran teks lebih kecil */
        }
        .footer-text {
            margin-bottom: 0;
            letter-spacing: 0.3px;
        }
        .footer-brand {
            color: var(--primary-green);
            font-weight: 600;
        }

        /* Dropdown Style */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 10px;
        }

        @media (max-width: 768px) {
            .logo-wrapper img {
                width: 32px;
                height: 32px;
            }
            .navbar-brand {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <div class="logo-wrapper me-2">
                <img src="{{ asset('storage/background/logo an-najah.png') }}" alt="Logo SI-NAJAH">
            </div>
            <span>SI-NAJAH</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                {{-- MENU KHUSUS ADMIN --}}
                @if(Auth::user()->role == 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-line me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/santri*') ? 'active' : '' }}" href="{{ route('admin.santri.index') }}">
                            <i class="fas fa-users me-1"></i> Data Santri
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/registrasi*') ? 'active' : '' }}" href="{{ route('admin.registrasi.index') }}">
                            <i class="fas fa-user-cog me-1"></i> Kelola User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.jenis-pembayaran.index') }}">
                            <i class="fas fa-cog me-1"></i> Jenis Pembayaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/settings') ? 'active' : '' }}" href="{{ route('admin.content.index') }}">
                            <i class="fas fa-tools me-1"></i> Pengaturan Web
                        </a>
                    </li>
                @endif

                {{-- MENU KHUSUS BENDAHARA --}}
                @if(Auth::user()->role == 'bendahara')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('bendahara/dashboard') ? 'active' : '' }}" href="{{ route('bendahara.dashboard') }}">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ Request::is('bendahara/pembayaran*') ? 'active' : '' }}" href="#" id="pembayaranDrop" data-bs-toggle="dropdown">
                            <i class="fas fa-money-check-alt me-1"></i> Pembayaran
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('bendahara.pembayaran.index') }}"><i class="fas fa-tasks me-2 text-primary"></i>Verifikasi Online</a></li>
                            <li><a class="dropdown-item" href="{{ route('bendahara.pembayaran.manual') }}"><i class="fas fa-cash-register me-2 text-success"></i>Input Bayar Manual</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('bendahara.pembayaran.rekap') }}"><i class="fas fa-file-invoice-dollar me-2 text-warning"></i>Rekap & Histori</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('bendahara/uang-saku*') ? 'active' : '' }}" href="{{ route('bendahara.uangsaku.index') }}">
                            <i class="fas fa-wallet me-1"></i> Uang Saku
                        </a>
                    </li>
                @endif

                {{-- MENU KHUSUS KEAMANAN --}}
                @if(Auth::user()->role == 'keamanan')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('keamanan/dashboard') ? 'active' : '' }}" href="{{ route('keamanan.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('keamanan/absensi-harian') ? 'active' : '' }}" href="{{ route('keamanan.absensi.harian') }}">
                            <i class="fas fa-edit me-1"></i> Input Absen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('keamanan/histori*') ? 'active' : '' }}" href="{{ route('keamanan.histori') }}">
                            <i class="fas fa-history me-1"></i> Histori
                        </a>
                    </li>
                @endif

                {{-- MENU KHUSUS USER/SANTRI --}}
                @if(Auth::user()->role == 'user')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('user/dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('user/absensi*') ? 'active' : '' }}" href="{{ route('user.absensi.index') }}">
                            <i class="fas fa-clipboard-check me-1"></i> Absensi
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="moneyDrop" data-bs-toggle="dropdown">
                            <i class="fas fa-money-bill-wave me-1"></i> Keuangan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('user.pembayaran.create') }}">Bayar SPP</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.pembayaran.index') }}">Riwayat Bayar</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('user.uangsaku.index') }}">Saldo Uang Saku</a></li>
                        </ul>
                    </li>
                @endif
            </ul>

            {{-- MENU PROFIL --}}
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle btn btn-success px-3 py-1 text-white shadow-sm" href="#" data-bs-toggle="dropdown" style="border-radius: 20px;">
                        <i class="fas fa-user-circle me-1"></i> 
                        @if(Auth::user()->role == 'user')
                            {{ Auth::user()->santri->nama ?? Auth::user()->name }}
                        @else
                            {{ Auth::user()->name }}
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2 small fw-bold text-muted text-uppercase">Role: {{ Auth::user()->role }}</li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container main-container">
    @yield('content')
</div>

<footer class="footer-custom mt-auto">
    <div class="container text-center">
        <p class="footer-text">
            &copy; 2025 <span class="footer-brand">An-Najah</span>. 
            Transparansi & Efisiensi Pengelolaan Santri.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>