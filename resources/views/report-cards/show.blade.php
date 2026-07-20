@extends('layouts.app')

@section('title', 'Detail Rapor')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Rapor — {{ $reportCard->student->name ?? '-' }}</h4>
            <div class="text-muted small">
                {{ $reportCard->schoolClass->name ?? '-' }} —
                Semester {{ ucfirst($reportCard->semester) }} —
                {{ $reportCard->schoolYear->name ?? '-' }}
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($reportCard->status === 'final')
        <div class="alert alert-success">
            Rapor ini sudah <strong>difinalisasi</strong> pada {{ $reportCard->finalized_at?->translatedFormat('d F Y H:i') }}
            oleh {{ $reportCard->finalizedBy->name ?? '-' }}. Nilai tidak bisa diubah lagi.
        </div>
    @else
        <div class="alert alert-warning">
            Rapor ini masih berstatus <strong>draft</strong> — nilai otomatis dihitung ulang dari data nilai terbaru
            setiap kali halaman ini dibuka. Klik "Finalisasi" kalau sudah yakin nilainya final.
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($reportCard->details->isEmpty())
                <div class="alert alert-warning mb-0">
                    Belum ada nilai yang tercatat untuk siswa ini pada semester & tahun ajaran yang dipilih.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Nilai Akhir</th>
                                <th>Predikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reportCard->details as $detail)
                                <tr>
                                    <td>{{ $detail->subject->name ?? '-' }}</td>
                                    <td>{{ number_format($detail->final_score, 1) }}</td>
                                    <td><span class="badge text-bg-info">{{ $detail->predicate }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-semibold">
                                <td>Rata-rata Keseluruhan</td>
                                <td colspan="2">{{ $overallAverage ?? '-' }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if ($reportCard->status === 'draft')
                    <form method="POST" action="{{ route('akademik.report-cards.finalize', $reportCard) }}"
                        onsubmit="return confirm('Setelah difinalisasi, nilai tidak bisa diubah otomatis lagi. Lanjutkan?');"
                        class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-primary">Finalisasi Rapor</button>
                    </form>
                @endif
            @endif
        </div>
    </div>

@endsection