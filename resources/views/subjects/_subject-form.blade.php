<div class="row g-3 mb-4">
    <div class="col-md-8">
        <label for="name" class="form-label">Nama Mata Pelajaran</label>
        <input type="text" id="name" name="name" value="{{ old('name', $subject->name ?? '') }}"
            placeholder="Contoh: Matematika"
            class="form-control @error('name') is-invalid @enderror" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="code" class="form-label">Kode</label>
        <input type="text" id="code" name="code" value="{{ old('code', $subject->code ?? '') }}"
            placeholder="Contoh: MTK"
            class="form-control @error('code') is-invalid @enderror" required>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="kkm" class="form-label">KKM</label>
        <input type="number" id="kkm" name="kkm" value="{{ old('kkm', $subject->kkm ?? 70) }}" min="0" max="100"
            class="form-control @error('kkm') is-invalid @enderror" required>
        @error('kkm')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Kriteria Ketuntasan Minimal (0-100).</div>
    </div>
</div>