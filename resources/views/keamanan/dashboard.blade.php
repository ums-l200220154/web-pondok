@extends('layouts.app')

@section('title', 'Dashboard Keamanan')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card card-content bg-white p-4 border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold text-success mb-1">Panel Keamanan</h2>
                    <p class="text-muted mb-0">Selamat bertugas, <strong>{{ Auth::user()->name }}</strong>. Pantau dan kelola kehadiran santri hari ini.</p>
                </div>
                <div class="d-none d-md-block text-success">
                    <i class="fas fa-user-shield fa-4x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card card-content h-100 border-0 shadow-sm p-4 bg-white">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-4 me-4">
                    <i class="fas fa-users text-success fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Santri Terdaftar</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ $totalSantri }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card card-content h-100 border-0 shadow-sm p-4 bg-white">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-4 me-4">
                    <i class="fas fa-user-check text-primary fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Absensi Masuk Hari Ini</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ $absensiHariIni }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-3">
        <div class="card card-content border-0 p-4 bg-white">
            <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-tasks me-2 text-success"></i> Menu Operasional Keamanan</h5>
            <div class="row g-4">
                
                <div class="col-md-6">
                    <a href="{{ route('keamanan.absensi.harian') }}" class="text-decoration-none">
                        <div class="p-4 rounded-4 border border-2 border-light menu-hover transition-all">
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white p-3 rounded-3 me-3 shadow-sm">
                                    <i class="fas fa-edit fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">Input Absensi Harian</h5>
                                    <p class="text-muted small mb-0 text-truncate">Catat kehadiran santri secara cepat (pagi/sore).</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="{{ route('keamanan.histori') }}" class="text-decoration-none">
                        <div class="p-4 rounded-4 border border-2 border-light menu-hover transition-all">
                            <div class="d-flex align-items-center">
                                <div class="bg-info text-white p-3 rounded-3 me-3 shadow-sm">
                                    <i class="fas fa-history fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">Histori Absensi</h5>
                                    <p class="text-muted small mb-0 text-truncate">Lihat rekam jejak kehadiran santri sebelumnya.</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .transition-all {
        transition: all 0.3s ease;
    }
    .menu-hover:hover {
        background-color: #f8fdf9;
        border-color: #2ecc71 !important;
        transform: scale(1.02);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }
</style>
@endsection