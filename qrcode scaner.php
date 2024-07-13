<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <style>
        #preview {
            transform: scaleX(-1);
        }
    </style>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>
<body>
    <h1>QR Code Scanner</h1>
    <video id="preview"></video>

    <script>
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            acceptLanguages: 'ar'
        });

        scanner.addListener('scan', function (content) {
            alert('QR Code content: ' + content);
            // You can store the content in a variable or send it to the server as needed
        });

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[cameras.length -1 ]); // Use the last available camera (likely the rear camera)
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (error) {
            console.error(error);
        });
    </script>
</body>
</html>
