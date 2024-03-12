<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
</head>

<body>
    @if (!session('token'))
        <div class="w-full h-[100vh] flex items-center justify-center gap-2 py-4 px-4">
            <form class="w-full h-auto flex flex-col items-center justify-center gap-4" name="payment" id="payment"
                method="post" action="{{ url('payment') }}">
                @csrf
                <h1 class="text-xl font-normal">Payment Page</h1>
                <div class="w-1/4 h-full flex items-center justify-center gap-2">
                    <input type="text" class="w-full h-10 py-2 px-2 border-2 border-gray-200 rounded-lg"
                        name="amount" />
                    <select type="text" class="w-1/3 h-10 py-2 px-2 border-2 border-gray-200 rounded-lg"
                        name="currency">
                        <option value="TRY">TRY</option>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                    </select>
                </div>
                <button class="text-white text-sm py-2 px-8 bg-green-500 rounded-lg" type="submit">
                    Pay Now
                </button>
            </form>
        </div>
    @else
        <script src="https://paysellcard.com/js/iframeResizer.min.js"></script>
        <iframe src="https://paysellcard.com/payment-service/secure/{{ session('token') }}" id="paysellcardiframe"
            frameborder="0" scrolling="no" style="width: 100%;"></iframe>
        <script>
            iFrameResize({}, '#paysellcardiframe');
        </script>
    @endif
</body>

</html>
