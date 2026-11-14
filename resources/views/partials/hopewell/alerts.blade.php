@if (session('success'))
    <div class="alert alert-success d-flex align-items-start gap-2" style="border-radius:12px;" role="alert">
        <i class="bi bi-check-circle-fill"></i><div>{{ session('success') }}</div>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger d-flex align-items-start gap-2" style="border-radius:12px;" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i><div>{{ session('error') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" style="border-radius:12px;" role="alert">
        <strong>Periksa kembali isian Anda:</strong>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
