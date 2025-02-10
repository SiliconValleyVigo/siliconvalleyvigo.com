<!DOCTYPE html>
<html>
<head>
    <title>QR Code Scanner</title>
    <script src="https://cdn.jsdelivr.net/npm/jsqr"></script>
</head>
<body>
    <textarea id="imageUrls" rows="5" cols="50" placeholder="Ingrese URLs de imágenes PNG con códigos QR">
    <?php

    $servername = getenv('BOT_EXCEL_PRODUCTOS_DB_HOST');
    $username = getenv('BOT_EXCEL_PRODUCTOS_DB_USER');
    $password = getenv('BOT_EXCEL_PRODUCTOS_DB_PASSWORD');
    $dbname = getenv('BOT_EXCEL_PRODUCTOS_DB_NAME');

    // Crea la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica la conexión
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Realiza la consulta
    $sql = "SELECT id, nombre, qr FROM auto_tabla";
    $result = $conn->query($sql);

    $imageObjects = [];
    if ($result->num_rows > 0) {
      // Guarda los enlaces de las imágenes en un array
      while($row = $result->fetch_assoc()) {
        $imageObjects[] = [
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'qr' => $row['qr']
        ];
      }
    } else {
      echo "0 results";
    }

    $conn->close();

    // Imprime los enlaces de las imágenes como una cadena JSON
    echo htmlspecialchars(json_encode($imageObjects));
    ?>
    </textarea>
    <button onclick="processImages()">Procesar Imágenes</button>
    <div id="qrResults"></div>

    <script>
        async function processImages() {
            var imageObjects = JSON.parse(document.getElementById('imageUrls').value);
            var qrResults = [];

            for (var i = 0; i < imageObjects.length; i++) {
                try {
                    var code = await readQRCode(imageObjects[i].qr);
                    if (code) {
                        qrResults.push({
                            id: imageObjects[i].id,
                            nombre: imageObjects[i].nombre,
                            qr: code.data
                        });
                    } else {
                        qrResults.push({
                            id: imageObjects[i].id,
                            nombre: imageObjects[i].nombre,
                            qr: 'No se encontraron códigos QR en la imagen.'
                        });
                    }
                } catch (error) {
                    qrResults.push({
                        id: imageObjects[i].id,
                        nombre: imageObjects[i].nombre,
                        qr: 'Error al procesar la imagen: ' + error.message
                    });
                }
            }

            downloadCSV(qrResults);
        }

        function readQRCode(imageUrl) {
            return new Promise((resolve, reject) => {
                var img = new Image();
                img.crossOrigin = 'Anonymous';
                img.onload = function() {
                    var canvas = document.createElement('canvas');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    var ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, img.width, img.height);
                    var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    var code = jsQR(imageData.data, imageData.width, imageData.height);
                    if (code) {
                        resolve(code);
                    } else {
                        reject(new Error('No se encontraron códigos QR en la imagen.'));
                    }
                };
                img.onerror = function() {
                    reject(new Error('Error al cargar la imagen.'));
                };
                img.src = imageUrl;
            });
        }

        function downloadCSV(results) {
            var csv = 'id,nombre,qr\n';

            for (var i = 0; i < results.length; i++) {
                csv += results[i].id + ',' + results[i].nombre + ',' + results[i].qr + '\n';
            }

            var blob = new Blob([csv], { type: 'text/csv' });
            var url = URL.createObjectURL(blob);

            var a = document.createElement('a');
            a.href = url;
            a.download = 'qr_results.csv';
            a.click();
        }
    </script>
</body>
</html>