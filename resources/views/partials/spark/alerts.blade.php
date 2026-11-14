@if (session('success'))
    <div class="alert-custom alert-custom-success mb-4">
        <i class="bi bi-check-circle-fill alert-custom-icon"></i>
        <div class="alert-custom-content">{{ session('success') }}</div>
    </div>
@endif

@if (session('error'))
    <div class="alert-custom alert-custom-danger mb-4">
        <i class="bi bi-exclamation-triangle-fill alert-custom-icon"></i>
        <div class="alert-custom-content">{{ session('error') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="alert-custom alert-custom-danger mb-4">
        <i class="bi bi-exclamation-triangle-fill alert-custom-icon"></i>
        <div class="alert-custom-content">
            <strong>Periksa kembali isian Anda:</strong>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
