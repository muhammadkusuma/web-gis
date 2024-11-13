<?php
header('Content-Type: application/json');

// Koneksi ke database PostgreSQL
$conn = pg_connect("host=localhost dbname=gis_pekanbaru user=postgres password=12345678");
if (!$conn) {
    die("Error: Gagal terhubung ke database.");
}

// Query untuk mendapatkan data GeoJSON dari tabel lokasi
$query = "SELECT id, name, ST_AsGeoJSON(geom) as geometry FROM lokasi_pekanbaru";
$result = pg_query($conn, $query);

$features = [];
while ($row = pg_fetch_assoc($result)) {
    $geometry = json_decode($row['geometry']);
    $features[] = [
        'type' => 'Feature',
        'geometry' => $geometry,
        'properties' => [
            'name' => $row['name']
        ]
    ];
}

// Bungkus dalam format GeoJSON
$geojson = [
    'type' => 'FeatureCollection',
    'features' => $features
];

// Output dalam format JSON
echo json_encode($geojson);
?>
