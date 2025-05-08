<!DOCTYPE html>
<html>

<head>
    <title>Cek Ongkir</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tambahkan style dasar untuk membantu debugging -->
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        select,
        input,
        button {
            margin: 5px 0;
            padding: 8px;
            width: 300px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        #debug {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <h2>Cek Ongkir</h2>

    <form id="ongkirForm">
        <div>
            <label for="province">Provinsi:</label>
            <select name="province" id="province">
                <option value="">Pilih Provinsi</option>
            </select>
        </div>

        <div>
            <label for="city">Kota:</label>
            <select name="city" id="city">
                <option value="">Pilih Kota</option>
            </select>
        </div>

        <div>
            <label for="weight">Berat (gram):</label>
            <input type="number" name="weight" id="weight" placeholder="Berat (gram)">
        </div>

        <div>
            <label for="courier">Kurir:</label>
            <select name="courier" id="courier">
                <option value="">Pilih Kurir</option>
                <option value="jne">JNE</option>
                <option value="tiki">TIKI</option>
                <option value="pos">POS Indonesia</option>
            </select>
        </div>

        <button type="submit">Cek Ongkir</button>
    </form>

    <div id="result"></div>

    <!-- Tambahkan area debug -->
    <div id="debug">
        <h3>Debug Info:</h3>
        <div id="debugInfo"></div>
    </div>

    <script>
        // Fungsi untuk menambahkan log debug
        function debugLog(message) {
            const debugInfo = document.getElementById('debugInfo');
            const logEntry = document.createElement('div');
            logEntry.textContent = new Date().toLocaleTimeString() + ': ' + message;
            debugInfo.appendChild(logEntry);
            console.log(message);
        }

        document.addEventListener('DOMContentLoaded', function() {
            debugLog('DOM telah dimuat, mengambil data provinsi...');

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            debugLog('CSRF Token: ' + csrfToken);

            // Ambil data provinsi
            fetch('/provinces')
                .then(response => {
                    debugLog('Respons provinsi diterima dengan status: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    debugLog('Data provinsi berhasil diambil');
                    if (data.rajaongkir && data.rajaongkir.status && data.rajaongkir.status.code === 200) {
                        let provinces = data.rajaongkir.results;
                        let provinceSelect = document.getElementById('province');
                        debugLog('Jumlah provinsi ditemukan: ' + provinces.length);

                        provinces.forEach(province => {
                            let option = document.createElement('option');
                            option.value = province.province_id;
                            option.textContent = province.province;
                            provinceSelect.appendChild(option);
                        });
                        debugLog('Dropdown provinsi berhasil diisi');
                    } else {
                        debugLog('Gagal mengambil provinsi: ' +
                            (data.rajaongkir && data.rajaongkir.status ?
                                data.rajaongkir.status.description : 'Status tidak diketahui'));
                    }
                })
                .catch(error => {
                    debugLog('Error saat mengambil provinsi: ' + error.message);
                });

            // Event listener untuk perubahan provinsi
            document.getElementById('province').addEventListener('change', function() {
                let provinceId = this.value;
                let citySelect = document.getElementById('city');

                debugLog('Provinsi dipilih: ' + provinceId);

                // Reset dropdown kota
                citySelect.innerHTML = '<option value="">Pilih Kota</option>';

                if (provinceId) {
                    debugLog('Mengambil data kota untuk provinsi ID: ' + provinceId);

                    fetch(`/cities?province_id=${provinceId}`)
                        .then(response => {
                            debugLog('Respons kota diterima dengan status: ' + response.status);
                            return response.json();
                        })
                        .then(data => {
                            if (data.rajaongkir && data.rajaongkir.status && data.rajaongkir.status
                                .code === 200) {
                                let cities = data.rajaongkir.results;
                                debugLog('Jumlah kota ditemukan: ' + cities.length);

                                cities.forEach(city => {
                                    let option = document.createElement('option');
                                    option.value = city.city_id;
                                    option.textContent = city.city_name;
                                    citySelect.appendChild(option);
                                });
                                debugLog('Dropdown kota berhasil diisi');
                            } else {
                                debugLog('Gagal mengambil kota: ' +
                                    (data.rajaongkir && data.rajaongkir.status ?
                                        data.rajaongkir.status.description :
                                        'Status tidak diketahui'));
                            }
                        })
                        .catch(error => {
                            debugLog('Error saat mengambil kota: ' + error.message);
                        });
                }
            });

            // Event listener untuk submit form
            document.getElementById('ongkirForm').addEventListener('submit', function(event) {
                event.preventDefault();
                let origin = 501; // Kode kota asal
                let destination = document.getElementById('city').value;
                let weight = document.getElementById('weight').value;
                let courier = document.getElementById('courier').value;

                debugLog('Form di-submit dengan data: ' +
                    'origin=' + origin +
                    ', destination=' + destination +
                    ', weight=' + weight +
                    ', courier=' + courier);

                // Validasi
                if (!destination || !weight || !courier) {
                    debugLog('Validasi gagal: Data tidak lengkap');
                    alert('Silakan lengkapi semua data');
                    return;
                }

                // Ambil CSRF token lagi (untuk memastikan)
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                debugLog('CSRF Token untuk POST: ' + token);

                fetch('/cost', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            origin: origin,
                            destination: destination,
                            weight: weight,
                            courier: courier
                        })
                    })
                    .then(response => {
                        debugLog('Respons biaya diterima dengan status: ' + response.status);
                        return response.json();
                    })
                    .then(data => {
                        debugLog('Data biaya berhasil diambil');
                        if (data.rajaongkir && data.rajaongkir.status && data.rajaongkir.status.code ===
                            200) {
                            let result = data.rajaongkir.results[0].costs;
                            let resultDiv = document.getElementById('result');
                            resultDiv.innerHTML = '<h3>Hasil Cek Ongkir:</h3>';

                            debugLog('Jumlah layanan tersedia: ' + result.length);

                            result.forEach(cost => {
                                let div = document.createElement('div');
                                div.textContent =
                                    `${cost.service} : ${cost.cost[0].value} Rupiah (${cost.cost[0].etd} hari)`;
                                resultDiv.appendChild(div);
                            });
                        } else {
                            debugLog('Gagal mengambil biaya: ' +
                                (data.rajaongkir && data.rajaongkir.status ?
                                    data.rajaongkir.status.description : 'Status tidak diketahui'));
                        }
                    })
                    .catch(error => {
                        debugLog('Error saat mengambil biaya: ' + error.message);
                    });
            });
        });
    </script>
</body>

</html>
