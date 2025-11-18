<!DOCTYPE html>
<html>
<head>
    <title>Midtrans Payment</title>
</head>
<body>
    <h2>Bayar dengan Midtrans</h2>
    <button id="pay-button">Pay Now</button>

    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){ console.log(result); },
                onPending: function(result){ console.log(result); },
                onError: function(result){ console.log(result); },
                onClose: function(){ alert('Anda menutup popup tanpa menyelesaikan pembayaran'); }
            });
        };
    </script>
</body>
</html>
