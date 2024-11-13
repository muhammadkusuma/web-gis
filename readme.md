# Peta Wilayah Pekanbaru

Proyek ini adalah aplikasi web sederhana yang menampilkan peta wilayah Kota Pekanbaru menggunakan **Leaflet** untuk pemetaan dan data **GeoJSON** yang diambil dari database PostgreSQL. Aplikasi ini menampilkan lokasi tertentu di wilayah Pekanbaru dan menyediakan pop-up dengan informasi nama lokasi ketika diklik.

## Fitur
- Menampilkan peta wilayah Pekanbaru.
- Memuat data lokasi dari database PostgreSQL dalam format GeoJSON.
- Menyediakan pop-up pada setiap lokasi dengan informasi nama lokasi.

## Prasyarat
- **Leaflet**: Library JavaScript untuk pemetaan interaktif (diambil langsung dari CDN).
- **PostgreSQL**: Database dengan tabel yang berisi data lokasi dan geometri.
- **PHP**: Backend untuk mengambil data dari database dan mengubahnya menjadi format GeoJSON.

## Struktur Proyek
- **index.html**: Halaman utama yang berisi elemen peta dan script untuk memuat data GeoJSON.
- **get_data.php**: Script PHP untuk menghubungkan ke database PostgreSQL, mengambil data lokasi, dan mengirimkan data dalam format GeoJSON.

## Instalasi

1. **Siapkan Database PostgreSQL**:
    - Buat database bernama `gis_pekanbaru` dan tabel `lokasi_pekanbaru` dengan kolom:
      - `id`: Primary key.
      - `name`: Nama lokasi.
      - `geom`: Tipe data geometri (point, polygon, atau lain-lain yang mendukung GeoJSON).
    - Masukkan data lokasi yang ingin ditampilkan pada peta.

2. **Konfigurasi Koneksi Database**:
    - Buka file `get_data.php`.
    - Sesuaikan informasi koneksi database PostgreSQL:
      ```php
      $conn = pg_connect("host=localhost dbname=gis_pekanbaru user=postgres password=12345678");
      ```

3. **Jalankan Aplikasi**:
    - Simpan file `index.html` dan `get_data.php` pada server lokal atau server web dengan dukungan PHP.
    - Akses halaman `index.html` melalui browser untuk melihat peta wilayah Pekanbaru dengan data lokasi.

## File Utama

### index.html
File ini berisi HTML dan JavaScript untuk menampilkan peta wilayah Pekanbaru menggunakan Leaflet. Kode JavaScript mengambil data dari `get_data.php` dan menambahkannya sebagai layer GeoJSON pada peta.

### get_data.php
File ini menghubungkan ke database PostgreSQL, mengambil data lokasi dari tabel `lokasi_pekanbaru`, mengonversinya ke format GeoJSON, dan mengirimkannya kembali ke `index.html` dalam format JSON.

## Contoh Kode

### HTML dan JavaScript (index.html)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Wilayah Pekanbaru</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 600px; width: 100%; }
    </style>
</head>
<body>
    <h1>Peta Wilayah Pekanbaru</h1>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([0.5333, 101.4473], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        fetch('get_data.php')
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data, {
                    onEachFeature: function (feature, layer) {
                        if (feature.properties && feature.properties.name) {
                            layer.bindPopup(feature.properties.name);
                        }
                    }
                }).addTo(map);
            })
            .catch(error => console.error('Error:', error));
    </script>
</body>
</html>
