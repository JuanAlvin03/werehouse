<!-- Toast Container -->
<div id="toast-container" aria-live="polite" aria-atomic="true"></div>

@session('success')
    <script>
        showToast('success', 'Success', '{{ session('success') }}');
    </script>
@endsession

@session('error')
    <script>
        showToast('error', 'Error', '{{ session('error') }}');
    </script>
@endsession

@session('warning')
    <script>
        showToast('warning', 'Warning', '{{ session('warning') }}');
    </script>
@endsession

@session('info')
    <script>
        showToast('info', 'Info', '{{ session('info') }}');
    </script>
@endsession