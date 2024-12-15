<!DOCTYPE html>
<html>
<head>
    <title>QR Code Scanner</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quaggaJS/1.4.4/quagga.min.js"></script>
</head>
<body>
    <div id="interactive" class="viewport"></div>
    <script type="text/javascript">
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#interactive')
            },
            decoder: {
                readers: ['code_128_reader'] // Adjust reader types as needed
            }
        }, function(err) {
            if (err) {
                console.error(err);
                return;
            }
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            // Handle the scanned data
            var code = result.codeResult.code;
            console.log(code);

            // Send the scanned data to your server-side script
            // You can use AJAX or form submission to send the data to your controller
            $.ajax({
                url: 'your_controller_url',
                type: 'POST',
                data: { 'scanned_data': code },
                success: function(response) {
                    // Handle the server response
                    console.log(response);
                }
            });
        });
    </script>
</body>
</html>