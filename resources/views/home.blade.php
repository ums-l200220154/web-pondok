<!DOCTYPE html>
<html lang="id" x-data="{ loginModal: {{ $errors->any() ? 'true' : 'false' }} }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-NAJAH | Pondok Pesantren An-Najah</title>
    
    <link rel="icon" type="image/png" href="{{ asset('storage/background/logo an-najah.png') }}">
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --primary-green: #1a5d1a; 
            --accent-green: #2ecc71; 
        }
        
        body { 
            font-family: 'Poppins', sans-serif; 
        }
        
        /* Hero Section dengan Background dari Storage atau URL */
        .hero { 
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=1470&auto=format&fit=crop'); 
            height: 100vh; 
            background-size: cover; 
            background-position: center; 
            background-attachment: fixed;
            color: white; 
            display: flex; 
            align-items: center; 
        }

        /* Modal Login Styling */
        .login-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center; z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .card-login { 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            width: 90%; 
            max-width: 400px; 
            position: relative; 
            border-top: 5px solid var(--primary-green);
        }

        /* Logo kontras di dalam Modal */
        .logo-modal-wrapper {
            background-color: white;
            padding: 8px;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }

        .logo-modal-wrapper img {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }

        .feature-icon { 
            width: 70px; 
            height: 70px; 
            background: #d8f3dc; 
            color: var(--primary-green); 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0 auto 15px; 
        }
        
        /* Pengaturan Tombol Mobile agar rapi */
        @media (max-width: 768px) {
            .hero-buttons {
                display: flex;
                flex-direction: column;
                gap: 15px;
                padding: 0 20px;
            }
            .hero-buttons .btn {
                width: 100%;
                margin: 0 !important;
            }
            .btn-masuk { order: 1; }
            .btn-tentang { order: 2; }
            
            .hero h1 { font-size: 2.5rem; }
            .hero h2 { font-size: 1rem; }
        }
    </style>
</head>
<body>

    <section class="hero">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-2">SI-NAJAH</h1>
            <h2 class="h4 fw-light mb-4 text-uppercase tracking-widest">Langkah Modern Menuju Tata Kelola Mandiri</h2>
            
            <div class="row justify-content-center mb-5">
                <div class="col-md-8">
                    <p class="lead">
                        Menghadirkan sistem administrasi yang <strong>transparan</strong> dan <strong>efisien</strong> guna mendukung kenyamanan belajar di Pondok Pesantren An-Najah.
                    </p>
                </div>
            </div>

            <div class="hero-buttons">
                <button @click="loginModal = true" class="btn btn-light rounded-pill px-5 py-3 text-success fw-bold shadow btn-masuk">
                    <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Sistem
                </button>
                <a href="#info" class="btn btn-outline-light rounded-pill px-4 py-3 me-md-2 btn-tentang">
                    Tentang Kami
                </a>
            </div>
        </div>
    </section>

    <section id="info" class="py-5 bg-light text-center">
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-icon"><i class="fas fa-file-invoice-dollar fa-2x"></i></div>
                    <h5 class="fw-bold">Administrasi Transparan</h5>
                    <p class="small text-muted">Pantau rincian pembayaran SPP dan uang saku santri secara akurat dan terbuka.</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon"><i class="fas fa-bolt fa-2x"></i></div>
                    <h5 class="fw-bold">Layanan Efisien</h5>
                    <p class="small text-muted">Proses data administrasi yang cepat melalui sistem digital yang terintegrasi.</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon"><i class="fas fa-user-shield fa-2x"></i></div>
                    <h5 class="fw-bold">Keamanan Data</h5>
                    <p class="small text-muted">Seluruh data santri dan transaksi terlindungi dalam sistem keamanan database kami.</p>
                </div>
            </div>
        </div>
    </section>

    <div x-show="loginModal" class="login-overlay" x-transition.opacity style="display: none;">
        <div class="card-login shadow-2xl" @click.away="loginModal = false">
            <button @click="loginModal = false" class="btn-close position-absolute top-0 end-0 m-3 shadow-none"></button>
            
            <div class="text-center mb-4">
                <div class="logo-modal-wrapper">
                    <img src="{{ asset('storage/background/logo an-najah.png') }}" alt="Logo SI-NAJAH">
                </div>
                <h4 class="fw-bold text-dark">Login SI-NAJAH</h4>
                <p class="small text-muted">Akses Sistem Administrasi Santri</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger py-2 small mb-3">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">Username / Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                        <input type="text" name="login" class="form-control rounded-end-3 border-start-0" value="{{ old('login') }}" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold text-muted mb-1">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control rounded-end-3 border-start-0" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100 py-2 rounded-pill fw-bold shadow-sm">
                    MASUK SEKARANG
                </button>
            </form>
        </div>
    </div>

</body>
</html>