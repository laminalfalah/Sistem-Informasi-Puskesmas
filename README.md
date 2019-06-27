# Sistem Informasi Puskesmas

Sistem Informasi Puskesmas mengolah data pasien, dokter, obat, resep, rawat jalan, rekam medis, jadwal dokter, berita, serta dengan laporan harian, bulanan, rentang tanggal dan semua. Sistem ini dirancang dengan menggunakan php native & jQuery. data penyimpanan menggunakan MySQL. Sistem ini tidaklah sempurna, diharapkan jika ada berkenan untuk mengembangkan lagi.

## Instalasi

Gunakan package manager [Composer](https://getcomposer.org/download/)

```bash
composer install
```

#### Template
* [Admin-LTE](https://adminlte.io/)

#### Pustaka composer yang digunakan
* [Ramsey](https://github.com/ramsey/uuid) 
* [setasign/fpdf](https://packagist.org/packages/setasign/fpdf)

#### Pustaka pendukung
* [AnimateCSS](https://daneden.github.io/animate.css/)
* [Autonumeric](http://autonumeric.org/)
* [Bootstrap](https://getbootstrap.com/docs/3.3/)
* [Bootstrap-daterangepicker](http://www.daterangepicker.com/)
* [Bootstrap-datetimepicker](https://eonasdan.github.io/bootstrap-datetimepicker/)
* [Datatables](https://datatables.net/)
* [FastClick](https://github.com/ftlabs/fastclick)
* [Font Awesome](https://fontawesome.com/v4.7.0/)
* [JQuery](https://jquery.com/download/)
* [Jquery-Validation](https://jqueryvalidation.org/)
* [Jquery-slimscroll](https://github.com/rochal/jQuery-slimScroll)
* [Moment](https://momentjs.com/)
* [Responsive File Manager](https://www.responsivefilemanager.com/)
* [Select2](https://select2.org/)
* [SweetAlert2](https://sweetalert2.github.io/)
* [Tinymce-v4](https://www.tiny.cloud/get-tiny)

#### Struktur Folder
* assets
  * css
  * img
  * js
  * plugins
* config
* filemanger
* source
* resources
  * app
  * backend
  * frontend
  * master

## Penggunaan

1. Jalankan composer
```bash
composer install
```
2. Import database ke MySQL
```bash
mysql -u <username> -p <nama_database> < db_puskesmas.sql
```
3. Buka file [config.php](https://github.com/laminal-falah/Sistem-Informasi-Puskesmas/blob/master/config/config.php)
```php
$folder = "Sesuaikan dengan root folder aplikasi";
define('APPNAME', "Isi nama aplikasi");

#configuration database
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_DATABASE', ''); # Sesuaikan dengan nama database
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); # Jika menggunakan password, wajib diisi
```
