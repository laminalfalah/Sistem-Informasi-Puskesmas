<?php
// Configuration Apps
$folder = "simas";
if ($folder != null) {
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/$folder/";
} else {
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
}
define('APPNAME', 'Sistem Informasi Puskesmas');
define('BASE_URL', $url);
define('TIMEZONE', 'Asia/Jakarta');

// Configuration Database
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_DATABASE', 'db_puskesmas');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

$d = $_SERVER['REQUEST_URI'];
$p = parse_url($d, PHP_URL_PATH);
$c = explode('/', $p);
define('FIRST_PART', @$c[2]);
define('SECOND_PART', @$c[3]);
define('THIRD_PART', @$c[4]);
define('FOURTH_PART', @$c[5]);

$home = array(
    'Profile Puskesmas', 'Informasi Pelayanan', 'Data Poli', 'Jadwal Dokter'
);
$subhome = array(
    'Visi Misi', 'Struktur Organisasi','Tentang', 'Jenis Pelayanan', 'Fasilitas Pelayanan'
);

$menu = array(
    'Manajemen Pengguna', 'Manajemen Obat', 'Manajemen Puskesmas', 'Manajemen Beranda', 'Profil', 'Password'
);
$submenu = array(
    'Dokter', 'Pasien', 'Staff', 'Obat', 'Satuan', 'Berita', 'Poli', 'Darah', 'Spesialis', 'Jadwal Dokter', 'Resep', 'Rekam Medis', 
    'Rawat Jalan', 'Pembayaran', 'Balai Pengobatan', 'Menu'
);