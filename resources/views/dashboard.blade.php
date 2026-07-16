@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Selamat datang</h6>
                    <h4 class="mb-0">{{ auth()->user()->name }}</h4>
                    <span class="badge text-bg-secondary mt-2">
                        {{ auth()->user()->getRoleNames()->first() ?? 'Belum ada role' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection