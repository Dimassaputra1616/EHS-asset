@extends('layouts.app')

@section('title', 'Coming Soon')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-8 text-center animate-fade-in">
        
        <div class="position-relative d-inline-block mb-4">
            <div class="bg-hse-red rounded-circle opacity-25 position-absolute" style="width: 150px; height: 150px; top: 50%; left: 50%; transform: translate(-50%, -50%); animation: pulse 2s infinite;"></div>
            <div class="bg-white rounded-circle shadow-sm position-relative d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; z-index: 2; border: 4px solid var(--hse-red);">
                <i class="bi bi-rocket-takeoff text-hse-red" style="font-size: 3rem; animation: floatIcon 3s ease-in-out infinite;"></i>
            </div>
        </div>

        <h2 class="fw-bold mb-3">Awesome Features Are Brewing!</h2>
        <p class="text-muted fs-5 mb-5 mx-auto" style="max-width: 500px;">
            We are working hard behind the scenes to bring you a powerful, enterprise-grade experience for this module. Stay tuned!
        </p>

        <a href="{{ route('dashboard') }}" class="btn btn-hse-red btn-lg fw-bold px-5 shadow-sm rounded-pill" style="transition: transform 0.3s; hover: transform: translateY(-3px);">
            <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>
</div>

<style>
    @keyframes pulse {
        0% { transform: translate(-50%, -50%) scale(0.9); opacity: 0.5; }
        50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.2; }
        100% { transform: translate(-50%, -50%) scale(0.9); opacity: 0.5; }
    }
</style>
@endsection
