<?php
require_once '../path.php';
require_once(ABSPATH . '../config/config.php');
require_once(ABSPATH . '../config/database.php');
require_once(ABSPATH . '../config/enkripsi.php');
require_once(ABSPATH . '../config/header-json.php');
require_once(ABSPATH . '../config/functions.php');
require_once(ABSPATH . '../vendor/autoload.php');

$data = array();
$requestData = $_REQUEST;
function sanitate($v){
    global $link;
    return mysqli_real_escape_string($link, $v);
  }
if (isset($_GET['f']) && isset($_GET['d'])) {
    $route = base64_decode($_GET['f']);
    $dest = base64_decode($_GET['d']);
    if ($route == $enc['home']['remote'] && $dest == $enc['home']['sha1'][0]) {
        $sql = "SELECT * FROM tb_berita WHERE status='1' ORDER BY created_at DESC LIMIT 0,6";
        $query = mysqli_query($link, $sql) or die("error1");
        if (mysqli_num_rows($query) > 0) {
            $a = array();
            $i = 0;
            while ($r = mysqli_fetch_assoc($query)) {
                $a[] = array(
                    'slug' => $r['slug'],
                    'title' => ucwords($r['title']),
                    'cover' => gambar($r['cover']) != "" ? gambar($r['cover']) : "assets/img/default.png",
                    'content' => strip_tags(substr($r['content'],0,500))
                );
                $i++;
            }
            $data['berita'] = array(
                'code' => 1,
                'total' => mysqli_num_rows(mysqli_query($link, "SELECT * FROM tb_berita WHERE status='1'")),
                'filter' => $i,
                'data' => $a
            );
        } else {
            $data['berita'] = array(
                'code' => 0,
                'message' => 'Berita Kosong !',
            );
        }
    } elseif ($route == $enc['home']['remote'] && ($dest == $enc['home']['sha1'][1] || $dest == $enc['home']['sha1'][2]) && isset($_GET['s']) && isset($_GET['q'])) {
        $awal = $requestData['s'];
        $query = $requestData['q'];
        if ($query == "") {
            
            $sql = "SELECT * FROM tb_berita WHERE status='1' ORDER BY created_at DESC LIMIT $awal,6";
            $query = mysqli_query($link, $sql) or die("error1");
            if (mysqli_num_rows($query) > 0) {
                $a = array();
                $i = $awal;
                while ($r = mysqli_fetch_assoc($query)) {
                    $i++;
                    $a[] = array(
                        'slug' => $r['slug'],
                        'title' => ucwords($r['title']),
                        'cover' => gambar($r['cover']) != "" ? gambar($r['cover']) : "assets/img/default.png",
                        'content' => strip_tags(substr($r['content'],0,500))
                    );
                }
                $data['berita'] = array(
                    'code' => 1,
                    'total' => mysqli_num_rows(mysqli_query($link, "SELECT * FROM tb_berita WHERE status='1'")),
                    'filter' => $i,
                    'data' => $a
                );
            } else {
                $data['berita'] = array(
                    'code' => 0,
                    'message' => 'Berita Kosong !',
                );
            }
        } else {
            $sql = "SELECT * FROM tb_berita WHERE status='1' AND title LIKE '%$query%' OR content LIKE '%$query%' ORDER BY created_at DESC LIMIT $awal,6";
            $query = mysqli_query($link, $sql) or die("error1");
            if (mysqli_num_rows($query) > 0) {
                $a = array();
                $i = $awal;
                while ($r = mysqli_fetch_assoc($query)) {
                    $i++;
                    $a[] = array(
                        'slug' => $r['slug'],
                        'title' => ucwords($r['title']),
                        'cover' => gambar($r['cover']) != "" ? gambar($r['cover']) : "assets/img/default.png",
                        'content' => strip_tags(substr($r['content'],0,500))
                    );
                }
                $data['berita'] = array(
                    'code' => 1,
                    'total' => mysqli_num_rows(mysqli_query($link, "SELECT * FROM tb_berita WHERE status='1'")),
                    'filter' => $i,
                    'data' => $a
                );
            } else {
                $data['berita'] = array(
                    'code' => 0,
                    'message' => 'Berita Kosong !',
                );
            }
        }
    } elseif ($route == $enc['detail']['remote'] && $dest == $enc['detail']['sha1'][0] && isset($_GET['id'])) {
        $id = sanitate($requestData['id']);
        $sql = mysqli_query($link,"SELECT * FROM tb_berita_komen WHERE id_berita='$id' ORDER BY created_at DESC");
        if (mysqli_num_rows($sql) > 0) {
          while ($r = mysqli_fetch_assoc($sql)) {
            $dt[] = array(
              'name' => $r['name'],
              'comment' => $r['content'],
              'tgl' => date('Y-m-d H:i:s',strtotime($r['created_at']))
            );
          }
          $data['komen'] = array(
            'code' => true,
            'data' => $dt
          );
        } else {
          $data['komen'] = array(
            'code' => false,
            'message' => "Tidak ada komentar"
          );
        }
    } elseif ($route == $enc['detail']['remote'] && $dest == $enc['detail']['sha1'][2]) {
        $nama = sanitate($requestData['nama']);
        $brta = sanitate($requestData['id']);
        $kmtr = sanitate($requestData['komentar']);
        $date = date('Y-m-d H:i:s',strtotime('now'));
  
        $query = mysqli_query($link,"INSERT INTO tb_berita_komen VALUES(NULL,'$brta','$nama','$kmtr','$date')");
  
        if ($query) {
          $data['komentar'] = array(
            'code' => true,
            'message' => 'Komentar telah ditambahkan'
          );
        } else {
          $data['komentar'] = array(
            'code' => false,
            'message' => mysqli_error($link)
          );
        }
    }
} else {
    $data = array('code' => 404, 'message' => 'Invalid Url');
}

echo json_encode($data);
