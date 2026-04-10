<!DOCTYPE html>
<html lang="id" x-data="{ loginModal: {{ $errors->any() ? 'true' : 'false' }} }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Pondok Pesantren</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root { --primary-green: #2d6a4f; --accent-green: #52b788; }
        body { font-family: 'Poppins', sans-serif; }
        .hero { 
            background: linear-gradient(rgba(45,106,79,0.85), rgba(45,106,79,0.85)), url('https://images.unsplash.com/photo-1590076215667-873d3128104a?q=80&w=1470&auto=format&fit=crop'); 
            height: 90vh; background-size: cover; background-position: center; color: white; display: flex; align-items: center; 
        }
        .login-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center; z-index: 9999;
            backdrop-filter: blur(5px);
        }
        .card-login { background: white; padding: 40px; border-radius: 20px; width: 90%; max-width: 400px; position: relative; }
        .feature-icon { width: 70px; height: 70px; background: #d8f3dc; color: var(--primary-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; }
    </style>
</head>
<body>

    <section class="hero">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-3">SI-PONDOK DIGITAL</h1>
            <p class="lead mb-5 px-md-5">{{ $content['home_tagline'] ?? 'Mencetak Generasi Qurani yang Berakhlak Mulia, Berwawasan Global, dan Unggul dalam Teknologi.' }}</p>
            <div>
                <a href="#info" class="btn btn-outline-light rounded-pill px-4 py-2 me-2">Tentang Kami</a>
                <button @click="loginModal = true" class="btn btn-light rounded-pill px-5 py-2 text-success fw-bold shadow">Masuk ke Sistem</button>
            </div>
        </div>
    </section>

    <section id="info" class="py-5 bg-light text-center">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-icon"><i class="fas fa-book-quran fa-2x"></i></div>
                    <h5 class="fw-bold">Tahfidz Quran</h5>
                    <p class="small text-muted">Program hafalan Al-Quran intensif dengan bimbingan ustadz berpengalaman.</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon"><i class="fas fa-microchip fa-2x"></i></div>
                    <h5 class="fw-bold">Modernitas & IT</h5>
                    <p class="small text-muted">Integrasi teknologi dalam pembelajaran dan administrasi keuangan santri.</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon"><i class="fas fa-heart fa-2x"></i></div>
                    <h5 class="fw-bold">Akhlakul Karimah</h5>
                    <p class="small text-muted">Membentuk karakter santri yang sopan, mandiri, dan berjiwa sosial.</p>
                </div>
            </div>
        </div>
    </section>

    <div x-show="loginModal" class="login-overlay" x-transition.opacity>
        <div class="card-login shadow-2xl" @click.away="loginModal = false">
            <button @click="loginModal = false" class="btn-close position-absolute top-0 end-0 m-3 shadow-none"></button>
            <div class="text-center mb-4">
                <i class="fas fa-mosque fa-3x text-success mb-2"></i>
                <h4 class="fw-bold text-dark">Login Sistem</h4>
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
                    <input type="text" name="login" class="form-control rounded-3" value="{{ old('login') }}" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold text-muted mb-1">Password</label>
                    <input type="password" name="password" class="form-control rounded-3" required>
                </div>
                <button class="btn btn-success w-100 py-2 rounded-pill fw-bold">MASUK</button>
            </form>
        </div>
    </div>

</body>
</html>