<h6 class="text-muted mb-3">Data Diri</h6>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <label for="photo" class="form-label">Foto{{ $teacher ? ' Saat Ini' : '' }}</label>

        @if ($teacher)
            @php $photoUrl = $teacher->photoUrl(); @endphp
            <div>
                @if ($photoUrl)
                    <img src="{{ $photoUrl }}" alt="{{ $teacher->user->name ?? 'Guru' }}"
                        class="rounded-circle mb-2" width="70" height="70" style="object-fit: cover;">
                @else
                    <div class="rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center fw-semibold mb-2"
                        style="width: 70px; height: 70px; font-size: 1.5rem;">
                        {{ strtoupper(substr($teacher->user->name ?? '?', 0, 1)) }}
                    </div>
                @endif
            </div>
        @endif

        <input type="file" id="photo" name="photo" accept="image/png, image/jpeg"
            class="form-control {{ $teacher ? 'form-control-sm' : '' }} @error('photo') is-invalid @enderror">
        @error('photo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">
            {{ $teacher ? 'Kosongkan jika tidak ingin mengganti foto.' : 'Format JPG/PNG, maksimal 2MB.' }}
        </div>
    </div>

    <div class="col-md-9">
        <div class="row g-3">
            <div class="col-md-8">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $teacher->user->name ?? '') }}"
                    class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="nuptk" class="form-label">NUPTK <span class="text-muted">(opsional)</span></label>
                <input type="text" id="nuptk" name="nuptk" value="{{ old('nuptk', $teacher->nuptk ?? '') }}"
                    class="form-control @error('nuptk') is-invalid @enderror">
                @error('nuptk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-12">
        <label for="specialization" class="form-label">Spesialisasi / Bidang Studi</label>
        <input type="text" id="specialization" name="specialization"
            value="{{ old('specialization', $teacher->specialization ?? '') }}"
            placeholder="Contoh: Matematika, IPA, Bahasa Inggris"
            class="form-control @error('specialization') is-invalid @enderror">
        @error('specialization')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<h6 class="text-muted mb-3">Akun Login</h6>

@unless ($teacher)
    <div class="alert alert-info small">
        Setiap guru wajib memiliki akun login untuk mengakses sistem.
    </div>
@endunless

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $teacher->user->email ?? '') }}"
            class="form-control @error('email') is-invalid @enderror" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">Password{{ $teacher ? ' Baru' : '' }}</label>
        <input type="password" id="password" name="password" minlength="8"
            class="form-control @error('password') is-invalid @enderror" {{ $teacher ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">
            {{ $teacher ? 'Kosongkan jika tidak ingin mengganti password.' : 'Minimal 8 karakter.' }}
        </div>
    </div>
</div>