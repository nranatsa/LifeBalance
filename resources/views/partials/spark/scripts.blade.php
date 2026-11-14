<script src="{{ asset('spark/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('spark/assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('spark/assets/js/dashboard.js') }}"></script>
<script>
    // Konfirmasi untuk form berbahaya: <form data-confirm="Yakin?">
    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            if (!confirm(f.getAttribute('data-confirm'))) e.preventDefault();
        });
    });
    // Date picker sederhana: <input data-datepicker>
    document.querySelectorAll('input[data-datepicker]').forEach(function (el) {
        flatpickr(el, { dateFormat: 'Y-m-d' });
    });
</script>
@stack('scripts')
