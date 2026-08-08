@extends('layouts.app')

@section('title', 'Buku Penghubung')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Buku Penghubung</h4>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (!$guardian)
        <div class="alert alert-warning mb-0">
            Akun Anda belum terhubung sebagai orang tua/wali siswa manapun. Hubungi admin sekolah.
        </div>
    @elseif ($logs->isEmpty())
        <div class="alert alert-warning mb-0">Belum ada catatan buku penghubung untuk anak Anda.</div>
    @else
        @foreach ($logs as $log)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-semibold mb-0">{{ $log->student->name ?? '-' }}</h6>
                            <span class="text-muted small">{{ $log->date->translatedFormat('d F Y') }}</span>
                        </div>
                        @if ($log->parent_note)
                            <span class="badge text-bg-success">Sudah Dibalas</span>
                        @else
                            <span class="badge text-bg-warning">Perlu Dibalas</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small mb-1">Catatan Wali Kelas:</div>
                        <div class="border rounded p-2 bg-light">{{ $log->teacher_note }}</div>
                    </div>

                    @if ($log->parent_note)
                        <div>
                            <div class="text-muted small mb-1">Balasan Anda:</div>
                            <div class="border rounded p-2 bg-success-subtle">{{ $log->parent_note }}</div>
                        </div>
                    @else
                        <form method="POST" action="{{ route('orang-tua.communication-logs.reply', $log) }}">
                            @csrf
                            <div class="mb-2">
                                <textarea name="parent_note" rows="2" placeholder="Tulis balasan..."
                                    class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">Kirim Balasan</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

@endsection