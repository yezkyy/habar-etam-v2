@if (session('success') || session('error') || session('warning') || session('info') || (isset($errors) && $errors->any()))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const triggerSweetAlertToast = () => {
                if (typeof window.showToast === 'function') {
                    @if (session('success'))
                        window.showToast('success', {!! json_encode(session('success')) !!});
                    @endif

                    @if (session('error'))
                        window.showToast('error', {!! json_encode(session('error')) !!});
                    @endif

                    @if (session('warning'))
                        window.showToast('warning', {!! json_encode(session('warning')) !!});
                    @endif

                    @if (session('info'))
                        window.showToast('info', {!! json_encode(session('info')) !!});
                    @endif

                    @if (isset($errors) && $errors->any())
                        @php
                            $errorList = $errors->all();
                            $firstError = $errorList[0] ?? 'Terdapat kesalahan pada formulir.';
                            $remainingCount = count($errorList) - 1;
                            $errorTitle = $firstError . ($remainingCount > 0 ? " (dan {$remainingCount} lainnya)" : '');
                        @endphp
                        window.showToast('error', {!! json_encode($errorTitle) !!});
                    @endif
                } else {
                    setTimeout(triggerSweetAlertToast, 80);
                }
            };
            triggerSweetAlertToast();
        });
    </script>
@endif
