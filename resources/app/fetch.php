<?php
session_start();
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

require_once '../path.php';
require_once(ABSPATH . '../config/config.php');
require_once(ABSPATH . '../config/database.php');
require_once(ABSPATH . '../config/enkripsi.php');
require_once(ABSPATH . '../config/header-json.php');
require_once(ABSPATH . '../config/functions.php');
require_once(ABSPATH . '../vendor/autoload.php');

$data = array();
function sanitate($v){
  global $link;
  return mysqli_real_escape_string($link, $v);
}

if (isset($_SESSION['is_logged'])) {
  $id = $_SESSION['id'];
  $level = $_SESSION['level'];
  $check = mysqli_query($link,"SELECT * FROM tb_rules");
  $i = 1;
  while ($r = mysqli_fetch_assoc($check)) {
    if ($level == $r['name_rule'] && $i != 6 && $i != 7) {
      $sql = "SELECT s.id_staff FROM tb_staff AS s
              INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff
              WHERE us.id_user='$id'";
      $query = mysqli_query($link,$sql);
      $chSql = mysqli_fetch_assoc($query);
      $idStaffnyo = $chSql['id_staff'];
      $dashboard = "staff";
    } elseif ($level == $r['name_rule'] && $i == 6 && $i != 7) {
      $sql = "SELECT d.id_dokter FROM tb_dokter AS d
              INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter
              WHERE ud.id_user='$id'";
      $query = mysqli_query($link,$sql);
      $chSql = mysqli_fetch_assoc($query);
      $idDokternyo = $chSql['id_dokter'];
      $dashboard = "dokter";
    } elseif ($level == $r['name_rule'] && $i != 6 && $i == 7) {
      $sql = "SELECT p.id_pasien FROM tb_pasien AS p
              INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien
              WHERE up.id_user='$id'";
      $query = mysqli_query($link,$sql);
      $chSql = mysqli_fetch_assoc($query);
      $idPasiennyo = $chSql['id_pasien'];
      $dashboard = "pasien";
    }
    $i++;
  }
  $requestData = $_REQUEST;
  if (isset($_GET['f']) && isset($_GET['d'])) {
    $route = base64_decode($_GET['f']);
    $dest = base64_decode($_GET['d']);

    // API Export Start //
    if ($route == $enc['export'][0] && $dest == $enc['export'][1] && isset($_GET['tipe'])) {
      $tipe = $_GET['tipe'];
      if ($tipe == "dokter") {
        $sql = mysqli_query($link, "SELECT * FROM tb_dokter");
        $data['download'] = array(
          'code' => 1,
          'count' => mysqli_num_rows($sql)
        );
      } elseif ($tipe == "pasien") {
        $sql = mysqli_query($link, "SELECT * FROM tb_pasien");
        $data['download'] = array(
          'code' => 1,
          'count' => mysqli_num_rows($sql)
        );
      } elseif ($tipe == "staff") {
        $sql = mysqli_query($link, "SELECT * FROM tb_staff");
        $data['download'] = array(
          'code' => 1,
          'count' => mysqli_num_rows($sql)
        );
      } elseif ($tipe == "obat") {
        $sql = mysqli_query($link, "SELECT * FROM tb_obat");
        $data['download'] = array(
          'code' => 1,
          'count' => mysqli_num_rows($sql)
        );
      } elseif ($tipe == "jadwal") {
        $sql = mysqli_query($link, "SELECT * FROM tb_jadwal");
        $data['download'] = array(
          'code' => 1,
          'count' => mysqli_num_rows($sql)
        );
      } elseif ($tipe == "resep") {
        $sql = mysqli_query($link, "SELECT * FROM tb_resep");
        $data['download'] = array(
          'code' => 1,
          'count' => mysqli_num_rows($sql)
        );
      } elseif ($tipe == "rawat_jalan") {
        $sql = mysqli_query($link, "SELECT * FROM tb_rawat_jalan");
        $data['download'] = array(
          'code' => 1,
          'count' => mysqli_num_rows($sql)
        );
      } elseif ($tipe == "rekam_medis") {
        $sql = mysqli_query($link, "SELECT * FROM tb_rekam_medis");
        $data['download'] = array(
          'code' => 1,
          'count' => mysqli_num_rows($sql)
        );
      } else {
        $data['download'] = array(
          'code' => 0,
          'message' => 'Request invalid'
        );
      }
    } elseif ($route == $enc['export'][0] && $dest == $enc['export'][2] && isset($_GET['id'])) {
      $tipe = $_GET['id'];
      $awal = $requestData['awal'];
      $akhir = $requestData['akhir'];
      $filter = $requestData['filter'];
      $url = "";
      $awal -= 1;
      if ($tipe == "dokter") {
        $a = base64_encode($enc['data-dokter']['remote']);
        $b = base64_encode($enc['data-dokter']['download']);
        if ($filter == "1" || $filter == "2") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=';
        } elseif ($filter == "3") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=' . $requestData['range'];
        } else {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=&range=';
        }
        $data['download'] = array(
          'code' => 1,
          'url' => $url
        );
      } elseif ($tipe == "pasien") {
        $a = base64_encode($enc['data-pasien']['remote']);
        $b = base64_encode($enc['data-pasien']['download']);
        if ($filter == "1" || $filter == "2") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=';
        } elseif ($filter == "3") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=' . $requestData['range'];
        } else {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=&range=';
        }
        $data['download'] = array(
          'code' => 1,
          'url' => $url
        );
      } elseif ($tipe == "staff") {
        $a = base64_encode($enc['data-staff']['remote']);
        $b = base64_encode($enc['data-staff']['download']);
        if ($filter == "1" || $filter == "2") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=';
        } elseif ($filter == "3") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=' . $requestData['range'];
        } else {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=&range=';
        }
        $data['download'] = array(
          'code' => 1,
          'url' => $url
        );
      } elseif ($tipe == "obat") {
        $a = base64_encode($enc['data-obat']['remote']);
        $b = base64_encode($enc['data-obat']['download']);
        if ($filter == "1" || $filter == "2") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=';
        } elseif ($filter == "3") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=' . $requestData['range'];
        } else {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=&range=';
        }
        $data['download'] = array(
          'code' => 1,
          'url' => $url
        );
      } elseif ($tipe == "jadwal") {
        $a = base64_encode($enc['data-jadwal']['remote']);
        $b = base64_encode($enc['data-jadwal']['download']);
        if ($filter == "1" || $filter == "2") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=';
        } elseif ($filter == "3") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=' . $requestData['range'];
        } else {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=&range=';
        }
        $data['download'] = array(
          'code' => 1,
          'url' => $url
        );
      } elseif ($tipe == "resep") {
        $a = base64_encode($enc['data-resep']['remote']);
        $b = base64_encode($enc['data-resep']['download']);
        if ($filter == "1" || $filter == "2") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=';
        } elseif ($filter == "3") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=' . $requestData['range'];
        } else {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=&range=';
        }
        $data['download'] = array(
          'code' => 1,
          'url' => $url
        );
      } elseif ($tipe == "rawat_jalan") {
        $a = base64_encode($enc['data-rawat']['remote']);
        $b = base64_encode($enc['data-rawat']['download']);
        if ($filter == "1" || $filter == "2") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=';
        } elseif ($filter == "3") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=' . $requestData['range'];
        } else {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=&range=';
        }
        $data['download'] = array(
          'code' => 1,
          'url' => $url
        );
      } elseif ($tipe == "rekam_medis") {
        $a = base64_encode($enc['data-rekam_medis']['remote']);
        $b = base64_encode($enc['data-rekam_medis']['download']);
        if ($filter == "1" || $filter == "2") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=';
        } elseif ($filter == "3") {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=' . $filter . '&range=' . $requestData['range'];
        } else {
          $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&start=' . $awal . '&end=' . $akhir . '&filter=&range=';
        }
        $data['download'] = array(
          'code' => 1,
          'url' => $url
        );
      } else {
        $data['download'] = array(
          'code' => 0,
          'message' => 'Request invalid'
        );
      }
    }
    // API Export End //

    // API Manajemen Data Dokter Start //
    elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['sha1'][0]) {
      $columns = array(
        'd.created_at',
        'd.nip',
        'd.name',
        'u.username',
        'd.sex',
        'u.status'
      );
      $sql = "SELECT d.created_at, d.nip, d.name, u.username, d.sex, u.status, d.id_dokter FROM tb_dokter AS d 
              INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter
              INNER JOIN tb_users AS u ON u.id_user = ud.id_user";
      $query = mysqli_query($link, $sql) or die("error1");
      $totalData = mysqli_num_rows($query);
      $totalFiltered = $totalData;
      if (!empty(sanitate($requestData['search']['value']))) {
        $sql .= " WHERE d.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $sql .= " OR u.username LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $sql .= " OR d.nip LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $query = mysqli_query($link, $sql) or die("error2");
        $totalFiltered = mysqli_num_rows($query);
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error3");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error4");
        }
      } else {
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error5");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error6");
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-dokter']['sha1'][3]);
        $detail = base64_encode($enc['data-dokter']['sha1'][5]);
        $delete = base64_encode($enc['data-dokter']['sha1'][6]);
        $reset = base64_encode($enc['data-dokter']['sha1'][7]);
        $download = base64_encode($enc['data-dokter']['unduh']);

        if ($row['status'] == 2) {
          $stts = '<label class="label label-danger">Blok</label>';
        } elseif ($row['status'] == 1) {
          $stts = '<label class="label label-success">Aktif</label>';
        } else {
          $stts = '<label class="label label-warning">Tidak Aktif</label>';
        }
        
        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["nip"];
        $nestedData[] = $row["name"];
        $nestedData[] = $row["username"];
        $nestedData[] = $row["sex"] == "P" ? '<label class="label label-primary">Pria</label>' : '<label class="label label-info">Wanita</label>';
        $nestedData[] = $stts;
        if (hasPermit('update_dokter') && hasPermit('delete_dokter')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['id_dokter'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="detail" name="detail" class="btn btn-xs btn-success" title="Detail Data" data-content="' . $row['id_dokter'] . '" data-target="' . $detail . '">
              <i class="fa fa-list"></i>
            </a>&nbsp;
            <a id="reset" name="reset" class="btn btn-xs btn-info" title="Reset Data" title-content="' . $row['name'] . '" data-content="' . $row['id_dokter'] . '" data-target="' . $reset . '">
              <i class="fa fa-refresh"></i>
            </a>&nbsp;
            <a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['id_dokter'] . '" data-target="' . $download . '">
              <i class="fa fa-download"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['name'] . '" data-content="' . $row['id_dokter'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = "";
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['sha1'][1]) {
      $check = mysqli_query($link,"SELECT * FROM tb_spesialis");
      if (mysqli_num_rows($check) > 0) {
        while ($r = mysqli_fetch_assoc($check)) {
          $a[] = array(
            'key' => $r['code_spesialis'],
            'value' => $r['name_spesialis']
          );
        }
        $data['dokter'] = array(
          'code' => true,
          'spesialis' => $a
        );
      } else {
        $data['dokter'] = array(
          'code' => false,
          'message' => "Data spesialis kosong !"
        );
      }
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['sha1'][2]) {
      $dokterKode = Uuid::uuid4();
      $idDokter = $dokterKode->toString();
      $nip = sanitate($requestData['nip']);
      $spesialis = sanitate($requestData['spesialis']);
      $name = ucwords(sanitate($requestData['nama']));
      $jk = sanitate($requestData['jk']);
      $almt = sanitate($requestData['alamat']);
      $tlpn = sanitate($requestData['tlpn']);

      $userKode = Uuid::uuid4();
      $idUser = $userKode->toString();
      $user = sanitate($requestData['username']);
      $pass = sanitate($requestData['password']);
      $conf = sanitate($requestData['password_confirm']);
      $stts = sanitate($requestData['status']);

      $date = date('Y-m-d H:i:s', strtotime('now'));

      if ($pass == $conf) {
        if (strpos($jk,"P") == true || strpos($jk,"W") == true) {
          $data['dokter'] = array(
            'code' => 0,
            'message' => 'Invalid request',
          );
        } else {
          $passnew = hash('sha512', $pass);
          $queryDokter = "INSERT INTO tb_dokter VALUES('$idDokter','$nip','$spesialis','$name','$jk','$almt','$tlpn','$date','$date')";
          $queryUser = "INSERT INTO tb_users VALUES('$idUser','$user','$passnew','$stts','$date','$date')";
          $sqlDokter = mysqli_query($link,$queryDokter);
          $sqlUser = mysqli_query($link,$queryUser);
          $linkDokterUser = mysqli_query($link,"INSERT INTO tb_users_dokter VALUES('$idUser','$idDokter')");
          $linkUserRule = mysqli_query($link,"INSERT INTO tb_rule_user VALUES('6','$idUser')");
          if ($sqlDokter && $sqlUser && $linkDokterUser && $linkUserRule) {
            $data['dokter'] = array(
              'code' => 1,
              'message' => 'Dokter telah ditambahkan !'
            );
          } else {
            $data['dokter'] = array(
              'code' => 0,
              'message' => mysqli_error($link)
            );
          }
        }
      } else {
        $data['dokter'] = array(
          'code' => 0,
          'message' => 'Konfirmasi password tidak sama dengan field password'
        );
      }
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['sha1'][3] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $check = mysqli_query($link,"SELECT * FROM tb_spesialis");
      if (mysqli_num_rows($check) > 0) {
        $query = "SELECT d.id_dokter, d.nip, s.code_spesialis, d.name, d.sex, d.address, d.telp, u.username, u.status FROM tb_dokter AS d
                  INNER JOIN tb_spesialis AS s ON s.code_spesialis = d.code_spesialis
                  INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter
                  INNER JOIN tb_users AS u ON u.id_user = ud.id_user
                  WHERE d.id_dokter='$id'";
        $sql = mysqli_query($link, $query);
        if (mysqli_num_rows($sql) == 1) {
          $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
          while ($r = mysqli_fetch_assoc($check)) {
            $sp[] = array(
              'key' => $r['code_spesialis'],
              'value' => $r['name_spesialis']
            );
          }
          $data['dokter'] = array(
            'code' => true,
            'spesialis' => $sp,
            'data' => $dt
          );
        } else {
          $data['dokter'] = array(
            'code' => false,
            'message' => 'Data tidak ditemukan !',
          );
        }
      } else {
        $data['dokter'] = array(
          'code' => false,
          'message' => "Data spesialis kosong !"
        );
      }
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['sha1'][4] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $nip = sanitate($requestData['nip']);
      $spesialis = sanitate($requestData['spesialis']);
      $name = ucwords(sanitate($requestData['nama']));
      $jk = sanitate($requestData['jk']);
      $almt = sanitate($requestData['alamat']);
      $tlpn = sanitate($requestData['tlpn']);
      $user = sanitate($requestData['username']);
      $stts = sanitate($requestData['status']);

      $date = date('Y-m-d H:i:s', strtotime('now'));

      if (strpos($jk,"P") == true || strpos($jk,"W") == true) {
        $data['dokter'] = array(
          'code' => 0,
          'message' => 'Invalid request',
        );
      } else {
        $query = "UPDATE tb_dokter AS d INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter INNER JOIN tb_users AS u ON u.id_user = ud.id_user 
                  SET d.nip='$nip', d.code_spesialis='$spesialis', d.name='$name', d.sex='$jk', d.address='$almt', d.telp='$tlpn', d.updated_at='$date', 
                  u.username='$user', u.status='$stts', u.updated_at='$date' WHERE d.id_dokter='$id'";
        $sql = mysqli_query($link, $query);
        if ($sql) {
          $data['dokter'] = array(
            'code' => 1,
            'message' => 'Data berhasil diubah !'
          );
        } else {
          $data['dokter'] = array(
            'code' => 0,
            'message' => mysqli_error($link)
          );
        }
      }
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['sha1'][5] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT d.nip, s.name_spesialis AS spesialis, d.name AS nama, d.sex AS jk, d.address AS almt, d.telp AS tlpn, u.username AS user, u.status AS stts FROM tb_dokter AS d
                INNER JOIN tb_spesialis AS s ON s.code_spesialis = d.code_spesialis
                INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter
                INNER JOIN tb_users AS u ON u.id_user = ud.id_user
                WHERE d.id_dokter='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $r = mysqli_fetch_assoc($sql);
        $data['dokter'] = array(
          'code' => 1,
          'data' => $r,
        );
      } else {
        $data['dokter'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['sha1'][6] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "DELETE d, u FROM tb_dokter AS d
                INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter
                INNER JOIN tb_users AS u ON u.id_user = ud.id_user
                WHERE d.id_dokter='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['dokter'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['dokter'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['sha1'][7] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $passnew = hash('sha512', '12345678');
      $date = date('Y-m-d H:i:s', strtotime('now'));
      $query = "UPDATE tb_dokter AS d INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter INNER JOIN tb_users AS u ON u.id_user = ud.id_user 
                SET u.password='$passnew', u.updated_at='$date' WHERE d.id_dokter='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['dokter'] = array(
          'code' => 1,
          'message' => '<p>Password sudah direset &nbsp;"<b>12345678</b></p>"'
        );
      } else {
        $data['dokter'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['check'][0] && isset($_GET['username'])) {
      $username = sanitate($requestData['username']);
      $sql = mysqli_query($link, "SELECT username FROM tb_users WHERE username='$username'");
      if (mysqli_num_rows($sql) == 1) {
        $data = false;
      } else {
        $data = true;
      }
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['check'][1] && isset($_GET['id']) && isset($_GET['username'])) {
      $id = sanitate($requestData['id']);
      $username = sanitate($requestData['username']);
      $query = "SELECT d.id_dokter,u.username FROM tb_dokter AS d 
                INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter 
                INNER JOIN tb_users AS u ON u.id_user = ud.id_user
                WHERE d.id_dokter='$id' AND u.username='$username'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $data = true;
      } else {
        $sql = mysqli_query($link, "SELECT username FROM tb_users WHERE username='$username'");
        if (mysqli_num_rows($sql) == 1) {
          $data = false;
        } else {
          $data = true;
        }
      }
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['check'][2] && isset($_GET['nip'])) {
      $nip = sanitate($requestData['nip']);
      $sql = mysqli_query($link, "SELECT nip FROM tb_dokter WHERE nip='$nip'");
      if (mysqli_num_rows($sql) == 1) {
        $data = false;
      } else {
        $data = true;
      }
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['check'][3] && isset($_GET['id']) && isset($_GET['nip'])) {
      $id = sanitate($requestData['id']);
      $nip = sanitate($requestData['nip']);
      $sql = mysqli_query($link, "SELECT id_dokter,nip FROM tb_dokter WHERE id_dokter='$id' AND nip='$nip'");
      if (mysqli_num_rows($sql) == 1) {
        $data = true;
      } else {
        $sql = mysqli_query($link, "SELECT nip FROM tb_dokter WHERE nip='$nip'");
        if (mysqli_num_rows($sql) == 1) {
          $data = false;
        } else {
          $data = true;
        }
      }
    } elseif ($route == $enc['data-dokter']['remote'] && $dest == $enc['data-dokter']['unduh'] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $sql = mysqli_query($link,"SELECT id_dokter FROM tb_dokter WHERE id_dokter='$id'");
      $a = base64_encode($enc['data-dokter']['remote']);
      $b = base64_encode($enc['data-dokter']['unduh']);
      if (mysqli_num_rows($sql) == 1) {
        $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&id=' . $id;
        $data['dokter'] = array(
          'code' => 1,
          'url' => $url,
          'message' => 'Tunggu sebentar !',
        );
      } else {
        $data['dokter'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    }
    // API Manajemen Data Dokter End //

    // API Manajemen Data Pasien Start //
    elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['sha1'][0]) {
      $columns = array(
        'p.created_at',
        'p.nik',
        'p.name',
        'u.username',
        'p.sex',
        'u.status'
      );
      $sql = "SELECT p.created_at, p.nik, p.name, u.username, p.sex, u.status, p.id_pasien FROM tb_pasien AS p
              INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien
              INNER JOIN tb_users AS u ON u.id_user = up.id_user";
      $query = mysqli_query($link, $sql) or die("error1");
      $totalData = mysqli_num_rows($query);
      $totalFiltered = $totalData;
      if (!empty(sanitate($requestData['search']['value']))) {
        $sql .= " WHERE p.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $sql .= " OR u.username LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $sql .= " OR p.nik LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $query = mysqli_query($link, $sql) or die("error2");
        $totalFiltered = mysqli_num_rows($query);
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error3");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error4");
        }
      } else {
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error5");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error6");
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-pasien']['sha1'][3]);
        $detail = base64_encode($enc['data-pasien']['sha1'][5]);
        $delete = base64_encode($enc['data-pasien']['sha1'][6]);
        $reset = base64_encode($enc['data-pasien']['sha1'][7]);
        $download = base64_encode($enc['data-pasien']['unduh']);

        if ($row['status'] == 2) {
          $stts = '<label class="label label-danger">Blok</label>';
        } elseif ($row['status'] == 1) {
          $stts = '<label class="label label-success">Aktif</label>';
        } else {
          $stts = '<label class="label label-warning">Tidak Aktif</label>';
        }
        
        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["nik"];
        $nestedData[] = $row["name"];
        $nestedData[] = $row["username"];
        $nestedData[] = $row["sex"] == "P" ? '<label class="label label-primary">Pria</label>' : '<label class="label label-info">Wanita</label>';
        $nestedData[] = $stts;
        if (hasPermit('update_pasien') && hasPermit('delete_pasien')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['id_pasien'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="detail" name="detail" class="btn btn-xs btn-success" title="Detail Data" data-content="' . $row['id_pasien'] . '" data-target="' . $detail . '">
              <i class="fa fa-list"></i>
            </a>&nbsp;
            <a id="reset" name="reset" class="btn btn-xs btn-info" title="Reset Data" title-content="' . $row['name'] . '" data-content="' . $row['id_pasien'] . '" data-target="' . $reset . '">
              <i class="fa fa-refresh"></i>
            </a>&nbsp;
            <a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['id_pasien'] . '" data-target="' . $download . '">
              <i class="fa fa-download"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['name'] . '" data-content="' . $row['id_pasien'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = 
            '<a id="detail" name="detail" class="btn btn-xs btn-success" title="Detail Data" data-content="' . $row['id_pasien'] . '" data-target="' . $detail . '">
              <i class="fa fa-list"></i>
            </a>&nbsp;
            <a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['id_pasien'] . '" data-target="' . $download . '">
              <i class="fa fa-download"></i>
            </a>';
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['sha1'][1]) {
      $check = mysqli_query($link,"SELECT * FROM tb_darah");
      if (mysqli_num_rows($check) > 0) {
        $bpjs = mysqli_query($link,"SELECT * FROM tb_bpjs");
        while ($r = mysqli_fetch_assoc($check)) {
          $a[] = array(
            'key' => $r['id_darah'],
            'value' => $r['name_darah']
          );
        }
        while($r = mysqli_fetch_assoc($bpjs)) {
          $b[] = array(
            'key' => $r['id_bpjs'],
            'value' => $r['name_bpjs']
          );
        }
        $data['pasien'] = array(
          'code' => true,
          'darah' => $a,
          'bpjs' => $b
        );
      } else {
        $data['pasien'] = array(
          'code' => false,
          'message' => "Data darah kosong !"
        );
      }
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['sha1'][2]) {
      $pasienKode = Uuid::uuid4();
      $idPasien = $pasienKode->toString();
      $kk = sanitate($requestData['kk']);
      $nik = sanitate($requestData['nik']);
      $name = ucwords(sanitate($requestData['nama']));
      $tmpt = ucwords(sanitate($requestData['tmpt']));
      $tgl = sanitate($requestData['tgl']);
      $jk = sanitate($requestData['jk']);
      $agm = sanitate($requestData['agama']);
      $pkj = sanitate($requestData['pekerjaan']);
      $drh = sanitate($requestData['darah']);
      $almt = sanitate($requestData['alamat']);
      $tlpn = sanitate($requestData['tlpn']);
      $bpjs = sanitate($requestData['bpjs']);
      $no_bpjs = sanitate(@$requestData['no_bpjs']);

      $userKode = Uuid::uuid4();
      $idUser = $userKode->toString();
      $user = sanitate($requestData['username']);
      $pass = sanitate($requestData['password']);
      $conf = sanitate($requestData['password_confirm']);
      $stts = sanitate($requestData['status']);

      $date = date('Y-m-d H:i:s', strtotime('now'));

      if ($pass == $conf) {
        if (strpos($jk,"P") == true || strpos($jk,"W") == true) {
          $data['pasien'] = array(
            'code' => 0,
            'message' => 'Invalid request',
          );
        } else {
          $passnew = hash('sha512', $pass);
          $queryPasien = "INSERT INTO tb_pasien VALUES('$idPasien','$kk','$nik','$name','$tmpt','$tgl','$jk','$drh','$agm','$pkj','$almt','$tlpn','$date','$date')";
          $queryUser = "INSERT INTO tb_users VALUES('$idUser','$user','$passnew','$stts','$date','$date')";
          $sqlPasien = mysqli_query($link,$queryPasien);
          $sqlUser = mysqli_query($link,$queryUser);
          $linkPasienUser = mysqli_query($link,"INSERT INTO tb_users_pasien VALUES('$idUser','$idPasien')");
          $linkUserRule = mysqli_query($link,"INSERT INTO tb_rule_user VALUES('7','$idUser')");
          if ($bpjs == 1) {
            $queryBpjs = "INSERT INTO tb_bpjs_pasien VALUES(NULL,'$bpjs','$idPasien','-')";
          } else {
            $queryBpjs = "INSERT INTO tb_bpjs_pasien VALUES(NULL,'$bpjs','$idPasien','$no_bpjs')";
          }
          $sqlBpjs = mysqli_query($link,$queryBpjs);
          if ($sqlPasien && $sqlUser && $linkPasienUser && $linkUserRule && $sqlBpjs) {
            $data['pasien'] = array(
              'code' => 1,
              'message' => 'Pasien telah ditambahkan !'
            );
          } else {
            $data['pasien'] = array(
              'code' => 0,
              'message' => mysqli_error($link)
            );
          }
        }
      } else {
        $data['pasien'] = array(
          'code' => 0,
          'message' => 'Konfirmasi password tidak sama dengan field password'
        );
      }
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['sha1'][3] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $check = mysqli_query($link,"SELECT * FROM tb_darah");
      if (mysqli_num_rows($check) > 0) {
        $query = "SELECT p.id_pasien, p.kk, p.nik, p.name, p.place_born, p.date_born, p.sex, p.religion, p.worker, d.id_darah, p.address, p.telp, b.id_bpjs, bp.no_bpjs, u.username, u.status FROM tb_pasien AS p
                  INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                  INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs
                  INNER JOIN tb_darah AS d ON d.id_darah = p.id_darah
                  INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien
                  INNER JOIN tb_users AS u ON u.id_user = up.id_user
                  WHERE p.id_pasien='$id'";
        $sql = mysqli_query($link, $query);
        if (mysqli_num_rows($sql) == 1) {
          $bpjs = mysqli_query($link,"SELECT * FROM tb_bpjs");
          $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
          while ($r = mysqli_fetch_assoc($check)) {
            $sp[] = array(
              'key' => $r['id_darah'],
              'value' => $r['name_darah']
            );
          }
          while($r = mysqli_fetch_assoc($bpjs)) {
            $b[] = array(
              'key' => $r['id_bpjs'],
              'value' => $r['name_bpjs']
            );
          }
          $data['pasien'] = array(
            'code' => true,
            'darah' => $sp,
            'bpjs' => $b,
            'data' => $dt,
          );
        } else {
          $data['pasien'] = array(
            'code' => false,
            'message' => 'Data tidak ditemukan !',
          );
        }
      } else {
        $data['pasien'] = array(
          'code' => false,
          'message' => "Data darah kosong !"
        );
      }
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['sha1'][4] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $kk = sanitate($requestData['kk']);
      $nik = sanitate($requestData['nik']);
      $name = ucwords(sanitate($requestData['nama']));
      $tmpt = ucwords(sanitate($requestData['tmpt']));
      $tgl = sanitate($requestData['tgl']);
      $jk = sanitate($requestData['jk']);
      $agm = sanitate($requestData['agama']);
      $pkj = sanitate($requestData['pekerjaan']);
      $drh = sanitate($requestData['darah']);
      $almt = sanitate($requestData['alamat']);
      $tlpn = sanitate($requestData['tlpn']);
      $bpjs = sanitate($requestData['bpjs']);
      $no_bpjs = sanitate(@$requestData['no_bpjs']);
      $user = sanitate($requestData['username']);
      $stts = sanitate($requestData['status']);

      $date = date('Y-m-d H:i:s', strtotime('now'));

      if (strpos($jk,"P") == true || strpos($jk,"W") == true) {
        $data['pasien'] = array(
          'code' => 0,
          'message' => 'Invalid request',
        );
      } else {
        if ($bpjs == 1) {
          $no_bpjs = '-';
        }
        $query = "UPDATE tb_pasien AS p INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien INNER JOIN tb_users AS u ON u.id_user = up.id_user
                  INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien SET p.kk='$kk', p.nik='$nik', p.name='$name', p.place_born='$tmpt', p.date_born='$tgl', 
                  p.sex='$jk', p.religion='$agm', p.worker='$pkj', p.id_darah='$drh', p.address='$almt', p.telp='$tlpn', p.updated_at='$date', u.username='$user', u.status='$stts', u.updated_at='$date',
                  bp.id_bpjs='$bpjs', bp.no_bpjs='$no_bpjs' WHERE p.id_pasien='$id'";
        $sql = mysqli_query($link, $query);
        if ($sql) {
          $data['pasien'] = array(
            'code' => 1,
            'message' => 'Data berhasil diubah !'
          );
        } else {
          $data['pasien'] = array(
            'code' => 0,
            'message' => mysqli_error($link)
          );
        }
      }
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['sha1'][5] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT p.kk, p.nik, b.name_bpjs AS jns, bp.no_bpjs AS no, p.name AS nama, p.place_born AS tmpt, p.date_born AS tgl, p.sex AS jk, 
                p.religion AS agm, p.worker AS pkj, d.name_darah AS darah, p.address AS almt, p.telp AS tlpn, u.username AS user, u.status AS stts FROM tb_pasien AS p
                INNER JOIN tb_darah AS d ON d.id_darah = p.id_darah
                INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien
                INNER JOIN tb_users AS u ON u.id_user = up.id_user
                INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs
                WHERE p.id_pasien='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $r = mysqli_fetch_assoc($sql);
        $data['pasien'] = array(
          'code' => 1,
          'data' => $r,
        );
      } else {
        $data['pasien'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['sha1'][6] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "DELETE p, u FROM tb_pasien AS p
                INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien
                INNER JOIN tb_users AS u ON u.id_user = up.id_user
                WHERE p.id_pasien='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['pasien'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['pasien'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['sha1'][7] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $passnew = hash('sha512', '12345678');
      $date = date('Y-m-d H:i:s', strtotime('now'));
      $query = "UPDATE tb_pasien AS p INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien INNER JOIN tb_users AS u ON u.id_user = up.id_user 
                SET u.password='$passnew', u.updated_at='$date' WHERE p.id_pasien='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['pasien'] = array(
          'code' => 1,
          'message' => '<p>Password sudah direset &nbsp;"<b>12345678</b></p>"'
        );
      } else {
        $data['pasien'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['check'][0] && isset($_GET['username'])) {
      $username = sanitate($requestData['username']);
      $sql = mysqli_query($link, "SELECT username FROM tb_users WHERE username='$username'");
      if (mysqli_num_rows($sql) == 1) {
        $data = false;
      } else {
        $data = true;
      }
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['check'][1] && isset($_GET['id']) && isset($_GET['username'])) {
      $id = sanitate($requestData['id']);
      $username = sanitate($requestData['username']);
      $query = "SELECT p.id_pasien,u.username FROM tb_pasien AS p 
                INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien 
                INNER JOIN tb_users AS u ON u.id_user = up.id_user
                WHERE p.id_pasien='$id' AND u.username='$username'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $data = true;
      } else {
        $sql = mysqli_query($link, "SELECT username FROM tb_users WHERE username='$username'");
        if (mysqli_num_rows($sql) == 1) {
          $data = false;
        } else {
          $data = true;
        }
      }
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['check'][2] && isset($_GET['nik'])) {
      $nik = sanitate($requestData['nik']);
      $sql = mysqli_query($link, "SELECT nik FROM tb_pasien WHERE nik='$nik'");
      if (mysqli_num_rows($sql) == 1) {
        $data = false;
      } else {
        $data = true;
      }
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['check'][3] && isset($_GET['id']) && isset($_GET['nik'])) {
      $id = sanitate($requestData['id']);
      $nik = sanitate($requestData['nik']);
      $sql = mysqli_query($link, "SELECT id_pasien,nik FROM tb_pasien WHERE id_pasien='$id' AND nik='$nik'");
      if (mysqli_num_rows($sql) == 1) {
        $data = true;
      } else {
        $sql = mysqli_query($link, "SELECT nik FROM tb_pasien WHERE nik='$nik'");
        if (mysqli_num_rows($sql) == 1) {
          $data = false;
        } else {
          $data = true;
        }
      }
    } elseif ($route == $enc['data-pasien']['remote'] && $dest == $enc['data-pasien']['unduh'] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $sql = mysqli_query($link,"SELECT id_pasien FROM tb_pasien WHERE id_pasien='$id'");
      $a = base64_encode($enc['data-pasien']['remote']);
      $b = base64_encode($enc['data-pasien']['unduh']);
      if (mysqli_num_rows($sql) == 1) {
        $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&id=' . $id;
        $data['pasien'] = array(
          'code' => 1,
          'url' => $url,
          'message' => 'Tunggu sebentar !',
        );
      } else {
        $data['pasien'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    }
    // API Manajemen Data Pasien End //

    // API Manajemen Data Staff Start //
    elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['sha1'][0]) {
      $columns = array(
        's.created_at',
        's.nip',
        's.name',
        'u.username',
        's.sex',
        'u.status'
      );
      $sql = "SELECT s.created_at, s.nip, s.name, u.username, s.sex, u.status, s.id_staff FROM tb_staff AS s
              INNER JOIN tb_users_staff AS ud ON ud.id_staff = s.id_staff
              INNER JOIN tb_users AS u ON u.id_user = ud.id_user";
      $query = mysqli_query($link, $sql) or die("error1");
      $totalData = mysqli_num_rows($query);
      $totalFiltered = $totalData;
      if (!empty(sanitate($requestData['search']['value']))) {
        $sql .= " WHERE s.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $sql .= " OR u.username LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $sql .= " OR s.nip LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $query = mysqli_query($link, $sql) or die("error2");
        $totalFiltered = mysqli_num_rows($query);
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error3");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error4");
        }
      } else {
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error5");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error6");
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-staff']['sha1'][3]);
        $detail = base64_encode($enc['data-staff']['sha1'][5]);
        $delete = base64_encode($enc['data-staff']['sha1'][6]);
        $reset = base64_encode($enc['data-staff']['sha1'][7]);
        $download = base64_encode($enc['data-staff']['unduh']);

        if ($row['status'] == 2) {
          $stts = '<label class="label label-danger">Blok</label>';
        } elseif ($row['status'] == 1) {
          $stts = '<label class="label label-success">Aktif</label>';
        } else {
          $stts = '<label class="label label-warning">Tidak Aktif</label>';
        }
        
        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["nip"];
        $nestedData[] = $row["name"];
        $nestedData[] = $row["username"];
        $nestedData[] = $row["sex"] == "P" ? '<label class="label label-primary">Pria</label>' : '<label class="label label-info">Wanita</label>';
        $nestedData[] = $stts;
        if (hasPermit('update_staff') && hasPermit('delete_staff')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['id_staff'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="detail" name="detail" class="btn btn-xs btn-success" title="Detail Data" data-content="' . $row['id_staff'] . '" data-target="' . $detail . '">
              <i class="fa fa-list"></i>
            </a>&nbsp;
            <a id="reset" name="reset" class="btn btn-xs btn-info" title="Reset Data" title-content="' . $row['name'] . '" data-content="' . $row['id_staff'] . '" data-target="' . $reset . '">
              <i class="fa fa-refresh"></i>
            </a>&nbsp;
            <a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['id_staff'] . '" data-target="' . $download . '">
              <i class="fa fa-download"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['name'] . '" data-content="' . $row['id_staff'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = "";
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['sha1'][1]) {
      $check = mysqli_query($link,"SELECT * FROM tb_rules LIMIT 0,5");
      if (mysqli_num_rows($check) > 0) {
        while ($r = mysqli_fetch_assoc($check)) {
          $a[] = array(
            'key' => $r['id_rule'],
            'value' => $r['display_rule']
          );
        }
        $data['staff'] = array(
          'code' => true,
          'level' => $a
        );
      } else {
        $data['staff'] = array(
          'code' => false,
          'message' => "Data rule kosong !"
        );
      }
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['sha1'][2]) {
      $staffKode = Uuid::uuid4();
      $idStaff = $staffKode->toString();
      $nip = sanitate($requestData['nip']);
      $level = sanitate($requestData['level']);
      $name = ucwords(sanitate($requestData['nama']));
      $jk = sanitate($requestData['jk']);
      $almt = sanitate($requestData['alamat']);
      $tlpn = sanitate($requestData['tlpn']);

      $userKode = Uuid::uuid4();
      $idUser = $userKode->toString();
      $user = sanitate($requestData['username']);
      $pass = sanitate($requestData['password']);
      $conf = sanitate($requestData['password_confirm']);
      $stts = sanitate($requestData['status']);

      $date = date('Y-m-d H:i:s', strtotime('now'));

      if ($pass == $conf) {
        if (strpos($jk,"P") == true || strpos($jk,"W") == true) {
          $data['staff'] = array(
            'code' => 0,
            'message' => 'Invalid request',
          );
        } else {
          $passnew = hash('sha512', $pass);
          $queryStaff = "INSERT INTO tb_staff VALUES('$idStaff','$nip','$name','$jk','$almt','$tlpn','$date','$date')";
          $queryUser = "INSERT INTO tb_users VALUES('$idUser','$user','$passnew','$stts','$date','$date')";
          $sqlStaff = mysqli_query($link,$queryStaff);
          $sqlUser = mysqli_query($link,$queryUser);
          $linkStaffUser = mysqli_query($link,"INSERT INTO tb_users_staff VALUES('$idUser','$idStaff')");
          $linkUserRule = mysqli_query($link,"INSERT INTO tb_rule_user VALUES('$level','$idUser')");
          if ($sqlStaff && $sqlUser && $linkStaffUser && $linkUserRule) {
            $data['staff'] = array(
              'code' => 1,
              'message' => 'Staff telah ditambahkan !'
            );
          } else {
            $data['staff'] = array(
              'code' => 0,
              'message' => mysqli_error($link)
            );
          }
        }
      } else {
        $data['staff'] = array(
          'code' => 0,
          'message' => 'Konfirmasi password tidak sama dengan field password'
        );
      }
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['sha1'][3] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $check = mysqli_query($link,"SELECT * FROM tb_rules LIMIT 0,5");
      if (mysqli_num_rows($check) > 0) {
        $query = "SELECT s.id_staff, s.nip, r.id_rule, s.name, s.sex, s.address, s.telp, u.username, u.status FROM tb_staff AS s
                  INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff
                  INNER JOIN tb_users AS u ON u.id_user = us.id_user
                  INNER JOIN tb_rule_user AS ru ON ru.id_user = u.id_user
                  INNER JOIN tb_rules AS r ON r.id_rule = ru.id_rule
                  WHERE s.id_staff='$id'";
        $sql = mysqli_query($link, $query);
        if (mysqli_num_rows($sql) == 1) {
          $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
          while ($r = mysqli_fetch_assoc($check)) {
            $sp[] = array(
              'key' => $r['id_rule'],
              'value' => $r['display_rule']
            );
          }
          $data['staff'] = array(
            'code' => true,
            'level' => $sp,
            'data' => $dt
          );
        } else {
          $data['staff'] = array(
            'code' => false,
            'message' => 'Data tidak ditemukan !',
          );
        }
      } else {
        $data['staff'] = array(
          'code' => false,
          'message' => "Data rule kosong !"
        );
      }
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['sha1'][4] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $nip = sanitate($requestData['nip']);
      $level = sanitate($requestData['level']);
      $name = ucwords(sanitate($requestData['nama']));
      $jk = sanitate($requestData['jk']);
      $almt = sanitate($requestData['alamat']);
      $tlpn = sanitate($requestData['tlpn']);
      $user = sanitate($requestData['username']);
      $stts = sanitate($requestData['status']);

      $date = date('Y-m-d H:i:s', strtotime('now'));

      if (strpos($jk,"P") == true || strpos($jk,"W") == true) {
        $data['staff'] = array(
          'code' => 0,
          'message' => 'Invalid request',
        );
      } else {
        $query = "UPDATE tb_staff AS s INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff 
                  INNER JOIN tb_users AS u ON u.id_user = us.id_user INNER JOIN tb_rule_user AS ru ON ru.id_user = u.id_user
                  SET s.nip='$nip', s.name='$name', s.sex='$jk', s.address='$almt', s.telp='$tlpn', s.updated_at='$date', 
                  u.username='$user', u.status='$stts', u.updated_at='$date', ru.id_rule='$level' WHERE s.id_staff='$id'";
        $sql = mysqli_query($link, $query);
        if ($sql) {
          $data['staff'] = array(
            'code' => 1,
            'message' => 'Data berhasil diubah !'
          );
        } else {
          $data['staff'] = array(
            'code' => 0,
            'message' => mysqli_error($link)
          );
        }
      }
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['sha1'][5] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT s.nip, r.display_rule AS level, s.name AS nama, s.sex AS jk, s.address AS almt, s.telp AS tlpn, u.username AS user, u.status AS stts FROM tb_staff AS s
                INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff
                INNER JOIN tb_users AS u ON u.id_user = us.id_user
                INNER JOIN tb_rule_user AS ru ON ru.id_user = u.id_user
                INNER JOIN tb_rules AS r ON r.id_rule = ru.id_rule
                WHERE s.id_staff='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $r = mysqli_fetch_assoc($sql);
        $data['staff'] = array(
          'code' => 1,
          'data' => $r,
        );
      } else {
        $data['staff'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['sha1'][6] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "DELETE s, u FROM tb_staff AS s
                INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff
                INNER JOIN tb_users AS u ON u.id_user = us.id_user
                WHERE s.id_staff='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['staff'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['staff'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['sha1'][7] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $passnew = hash('sha512', '12345678');
      $date = date('Y-m-d H:i:s', strtotime('now'));
      $query = "UPDATE tb_staff AS s INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff INNER JOIN tb_users AS u ON u.id_user = us.id_user 
                SET u.password='$passnew', u.updated_at='$date' WHERE s.id_staff='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['staff'] = array(
          'code' => 1,
          'message' => '<p>Password sudah direset &nbsp;"<b>12345678</b></p>"'
        );
      } else {
        $data['staff'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['check'][0] && isset($_GET['username'])) {
      $username = sanitate($requestData['username']);
      $sql = mysqli_query($link, "SELECT username FROM tb_users WHERE username='$username'");
      if (mysqli_num_rows($sql) == 1) {
        $data = false;
      } else {
        $data = true;
      }
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['check'][1] && isset($_GET['id']) && isset($_GET['username'])) {
      $id = sanitate($requestData['id']);
      $username = sanitate($requestData['username']);
      $query = "SELECT s.id_staff,u.username FROM tb_staff AS s 
                INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff 
                INNER JOIN tb_users AS u ON u.id_user = us.id_user
                WHERE s.id_staff='$id' AND u.username='$username'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $data = true;
      } else {
        $sql = mysqli_query($link, "SELECT username FROM tb_users WHERE username='$username'");
        if (mysqli_num_rows($sql) == 1) {
          $data = false;
        } else {
          $data = true;
        }
      }
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['check'][2] && isset($_GET['nip'])) {
      $nip = sanitate($requestData['nip']);
      $sql = mysqli_query($link, "SELECT nip FROM tb_staff WHERE nip='$nip'");
      if (mysqli_num_rows($sql) == 1) {
        $data = false;
      } else {
        $data = true;
      }
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['check'][3] && isset($_GET['id']) && isset($_GET['nip'])) {
      $id = sanitate($requestData['id']);
      $nip = sanitate($requestData['nip']);
      $sql = mysqli_query($link, "SELECT id_staff,nip FROM tb_staff WHERE id_staff='$id' AND nip='$nip'");
      if (mysqli_num_rows($sql) == 1) {
        $data = true;
      } else {
        $sql = mysqli_query($link, "SELECT nip FROM tb_staff WHERE nip='$nip'");
        if (mysqli_num_rows($sql) == 1) {
          $data = false;
        } else {
          $data = true;
        }
      }
    } elseif ($route == $enc['data-staff']['remote'] && $dest == $enc['data-staff']['unduh'] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $sql = mysqli_query($link,"SELECT id_staff FROM tb_staff WHERE id_staff='$id'");
      $a = base64_encode($enc['data-staff']['remote']);
      $b = base64_encode($enc['data-staff']['unduh']);
      if (mysqli_num_rows($sql) == 1) {
        $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&id=' . $id;
        $data['staff'] = array(
          'code' => 1,
          'url' => $url,
          'message' => 'Tunggu sebentar !',
        );
      } else {
        $data['staff'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    }
    // API Manajemen Data Staff End //

    // API Manajemen Data Obat Start //
    elseif ($route == $enc['data-obat']['remote'] && $dest == $enc['data-obat']['sha1'][0]) {
      $columns = array(
        'o.created_at',
        'o.code_obat',
        'o.name_obat',
        'o.stock',
        'o.price_buy',
        'o.price_sale'
      );
      $sql = "SELECT o.created_at, o.code_obat, o.name_obat, o.stock, u.name_unit, o.price_buy, o.price_sale FROM tb_obat AS o
              INNER JOIN tb_unit AS u ON u.id_unit = o.id_unit";
      $query = mysqli_query($link, $sql) or die("error1");
      $totalData = mysqli_num_rows($query);
      $totalFiltered = $totalData;
      if (!empty(sanitate($requestData['search']['value']))) {
        $sql .= " WHERE o.code_obat LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $sql .= " OR o.name_obat LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $query = mysqli_query($link, $sql) or die("error2");
        $totalFiltered = mysqli_num_rows($query);
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error3");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error4");
        }
      } else {
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error5");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error6");
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-obat']['sha1'][3]);
        $detail = base64_encode($enc['data-obat']['sha1'][5]);
        $delete = base64_encode($enc['data-obat']['sha1'][6]);
        $download = base64_encode($enc['data-obat']['unduh']);
        
        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["code_obat"];
        $nestedData[] = $row["name_obat"];
        $nestedData[] = number_format($row["stock"],0,'.','.') . " " . ucwords($row["name_unit"]);
        $nestedData[] = "Rp. " . number_format($row["price_buy"],0,'.','.');
        $nestedData[] = "Rp. " . number_format($row["price_sale"],0,'.','.');
        if (hasPermit('update_obat') && hasPermit('delete_obat')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['code_obat'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="detail" name="detail" class="btn btn-xs btn-success" title="Detail Data" data-content="' . $row['code_obat'] . '" data-target="' . $detail . '">
              <i class="fa fa-list"></i>
            </a>&nbsp;
            <a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['code_obat'] . '" data-target="' . $download . '">
              <i class="fa fa-download"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['name_obat'] . '" data-content="' . $row['code_obat'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = 
          '<a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['code_obat'] . '" data-target="' . $download . '">
              <i class="fa fa-download"></i>
            </a>
          ';
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-obat']['remote'] && $dest == $enc['data-obat']['sha1'][1]) {
      $check = mysqli_query($link,"SELECT * FROM tb_unit");
      if (mysqli_num_rows($check) > 0) {
        while ($r = mysqli_fetch_assoc($check)) {
          $a[] = array(
            'key' => $r['id_unit'],
            'value' => $r['name_unit']
          );
        }
        $data['obat'] = array(
          'code' => true,
          'satuan' => $a
        );
      } else {
        $data['obat'] = array(
          'code' => false,
          'message' => "Data Satuan kosong !"
        );
      }
    } elseif ($route == $enc['data-obat']['remote'] && $dest == $enc['data-obat']['sha1'][2]) {
      $name = ucwords(sanitate($requestData['nama']));
      $kode = kodeobat($name);
      $unit = sanitate($requestData['satuan']);
      $stok = str_replace('.', '', sanitate($requestData['stok']));
      $beli = str_replace('.', '', sanitate($requestData['harga_beli']));
      $jual = str_replace('.', '', sanitate($requestData['harga_jual']));
      $desc = sanitate($requestData['deskripsi']);
      $date = date('Y-m-d H:i:s', strtotime('now'));

      $queryObat = "INSERT INTO tb_obat VALUES('$kode','$unit','$name','$stok','$beli','$jual','$desc','$date','$date')";
      $sqlObat = mysqli_query($link, $queryObat);
      if ($sqlObat) {
        $data['obat'] = array(
          'code' => 1,
          'message' => 'Obat telah ditambahkan !'
        );
      } else {
        $data['obat'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-obat']['remote'] && $dest == $enc['data-obat']['sha1'][3] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $check = mysqli_query($link,"SELECT * FROM tb_unit");
      if (mysqli_num_rows($check) > 0) {
        $query = "SELECT o.code_obat, o.name_obat, u.id_unit, o.stock, o.price_buy, o.price_sale, o.description FROM tb_obat AS o
                  INNER JOIN tb_unit AS u ON u.id_unit = o.id_unit
                  WHERE o.code_obat='$id'";
        $sql = mysqli_query($link, $query);
        if (mysqli_num_rows($sql) == 1) {
          $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
          while ($r = mysqli_fetch_assoc($check)) {
            $sp[] = array(
              'key' => $r['id_unit'],
              'value' => $r['name_unit']
            );
          }
          $data['obat'] = array(
            'code' => true,
            'satuan' => $sp,
            'data' => $dt
          );
        } else {
          $data['obat'] = array(
            'code' => false,
            'message' => 'Data tidak ditemukan !',
          );
        }
      } else {
        $data['obat'] = array(
          'code' => false,
          'message' => "Data satuan kosong !"
        );
      }
    } elseif ($route == $enc['data-obat']['remote'] && $dest == $enc['data-obat']['sha1'][4] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $name = ucwords(sanitate($requestData['nama']));
      $unit = sanitate($requestData['satuan']);
      $stok = str_replace('.', '', sanitate($requestData['stok_edit']));
      $beli = str_replace('.', '', sanitate($requestData['harga_beli_edit']));
      $jual = str_replace('.', '', sanitate($requestData['harga_jual_edit']));
      $desc = sanitate($requestData['deskripsi']);

      $date = date('Y-m-d H:i:s', strtotime('now'));

      $query = "UPDATE tb_obat SET name_obat='$name', id_unit='$unit', stock='$stok', price_buy='$beli', price_sale='$jual', updated_at='$date' WHERE code_obat='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['obat'] = array(
          'code' => 1,
          'message' => 'Data berhasil diubah !'
        );
      } else {
        $data['obat'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-obat']['remote'] && $dest == $enc['data-obat']['sha1'][5] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT o.code_obat AS kode, o.name_obat AS nama, o.stock AS stok, u.name_unit AS unit, o.price_buy AS beli, o.price_sale AS jual, o.description AS desk FROM tb_obat AS o
                INNER JOIN tb_unit AS u ON u.id_unit = o.id_unit
                WHERE o.code_obat='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $r = mysqli_fetch_assoc($sql);
        $data['obat'] = array(
          'code' => 1,
          'data' => $r,
        );
      } else {
        $data['obat'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-obat']['remote'] && $dest == $enc['data-obat']['sha1'][6] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "DELETE FROM tb_obat WHERE code_obat='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['obat'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['obat'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-obat']['remote'] && $dest == $enc['data-obat']['check'][0] && isset($_GET['nama'])) {
      $name = sanitate($requestData['nama']);
      $sql = mysqli_query($link, "SELECT name_obat FROM tb_obat WHERE name_obat='$name'");
      if (mysqli_num_rows($sql) == 1) {
        $data = false;
      } else {
        $data = true;
      }
    } elseif ($route == $enc['data-obat']['remote'] && $dest == $enc['data-obat']['check'][1] && isset($_GET['id']) && isset($_GET['nama'])) {
      $id = sanitate($requestData['id']);
      $name = sanitate($requestData['nama']);
      $query = "SELECT code_obat, name_obat FROM tb_obat WHERE code_obat='$id' AND name_obat='$name'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $data = true;
      } else {
        $sql = mysqli_query($link, "SELECT name_obat FROM tb_obat WHERE name_obat='$name'");
        if (mysqli_num_rows($sql) == 1) {
          $data = false;
        } else {
          $data = true;
        }
      }
    } elseif ($route == $enc['data-obat']['remote'] && $dest == $enc['data-obat']['unduh'] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $sql = mysqli_query($link,"SELECT code_obat FROM tb_obat WHERE code_obat='$id'");
      $a = base64_encode($enc['data-obat']['remote']);
      $b = base64_encode($enc['data-obat']['unduh']);
      if (mysqli_num_rows($sql) == 1) {
        $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&id=' . $id;
        $data['obat'] = array(
          'code' => 1,
          'url' => $url,
          'message' => 'Tunggu sebentar !',
        );
      } else {
        $data['obat'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    }
    // API Manajemen Data Obat End //

    // API Manajemen Data Satuan Start //
    elseif ($route == $enc['data-satuan']['remote'] && $dest == $enc['data-satuan']['sha1'][0]) {
      $columns = array(
        'name_unit',
        'name_unit',
      );
      $sql = "SELECT * FROM tb_unit";
      $query = mysqli_query($link, $sql) or die("error1");
      $totalData = mysqli_num_rows($query);
      $totalFiltered = $totalData;
      if (!empty(sanitate($requestData['search']['value']))) {
        $sql .= " WHERE name_unit LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $query = mysqli_query($link, $sql) or die("error2");
        $totalFiltered = mysqli_num_rows($query);
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error3");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error4");
        }
      } else {
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error5");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error6");
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-satuan']['sha1'][3]);
        $delete = base64_encode($enc['data-satuan']['sha1'][6]);

        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["name_unit"];
        if (hasPermit('submenu_satuan')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['id_unit'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['name_unit'] . '" data-content="' . $row['id_unit'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = '';
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-satuan']['remote'] && $dest == $enc['data-satuan']['sha1'][2]) {
      $name = ucwords(sanitate($requestData['nama']));
      $sql = mysqli_query($link,"INSERT INTO tb_unit VALUES(NULL,'$name')");
      if ($sql) {
        $data['satuan'] = array(
          'code' => 1,
          'message' => 'Data berhasil ditambahkan !'
        );
      } else {
        $data['satuan'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-satuan']['remote'] && $dest == $enc['data-satuan']['sha1'][3] && isset($_GET['id'])) {
      $id = $requestData['id'];
      $sql = mysqli_query($link,"SELECT * FROM tb_unit WHERE id_unit='$id'");
      if (mysqli_num_rows($sql) == 1) {
        $r = mysqli_fetch_array($sql, MYSQLI_NUM);
        $data['satuan'] = array(
          'code' => 1,
          'data' => $r
        );
      } else {
        $data['satuan'] = array(
          'code' => 1,
          'message' => "Data ditemukan"
        );
      }
    } elseif ($route == $enc['data-satuan']['remote'] && $dest == $enc['data-satuan']['sha1'][4] && isset($_GET['id'])) {
      $id = $requestData['id'];
      $nama = ucwords(sanitate($requestData['nama']));
      $sql = mysqli_query($link,"UPDATE tb_unit SET name_unit='$nama' WHERE id_unit='$id'");
      if ($sql) {
        $data['satuan'] = array(
          'code' => 1,
          'message' => 'Data berhasil diubah !'
        );
      } else {
        $data['satuan'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-satuan']['remote'] && $dest == $enc['data-satuan']['sha1'][6] && isset($_GET['id'])) {
      $id = $requestData['id'];
      $check = mysqli_query($link,"SELECT id_unit FROM tb_obat WHERE id_unit='$id'");
      if (mysqli_num_rows($check) > 0) {
        $data['satuan'] = array(
          'code' => 0,
          'message' => 'Data tidak bisa dihapus !'
        );
      } else {
        $sql = mysqli_query($link,"DELETE FROM tb_unit WHERE id_unit='$id'");
        if ($sql) {
          $data['satuan'] = array(
            'code' => 1,
            'message' => 'Data berhasil di hapus !'
          );
        } else {
          $data['satuan'] = array(
            'code' => 0,
            'message' => mysqli_error($link)
          );
        }
      }
    } elseif ($route == $enc['data-satuan']['remote'] && $dest == $enc['data-satuan']['check'][0] && isset($_GET['nama'])) {
      $nama = sanitate($requestData['nama']);
      $sql = mysqli_query($link, "SELECT name_unit FROM tb_unit WHERE name_unit='$nama'");
      if (mysqli_num_rows($sql) == 1) {
        $data = false;
      } else {
        $data = true;
      }
    } elseif ($route == $enc['data-satuan']['remote'] && $dest == $enc['data-satuan']['check'][1] && isset($_GET['id']) && isset($_GET['nama'])) {
      $id = sanitate($requestData['id']);
      $nama = sanitate($requestData['nama']);
      $query = "SELECT * FROM tb_unit WHERE id_unit='$id' AND name_unit='$nama'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $data = true;
      } else {
        $sql = mysqli_query($link, "SELECT name_unit FROM tb_unit WHERE name_unit='$nama'");
        if (mysqli_num_rows($sql) == 1) {
          $data = false;
        } else {
          $data = true;
        }
      }
    }
    // API Manajemen Data Satuan End //

    // API Manajemen Data Berita Start //
    elseif ($route == $enc['data-berita']['remote'] && $dest == $enc['data-berita']['sha1'][0]) {
      $columns = array(
        'created_at',
        'title',
        'status',
      );
      $sql = "SELECT * FROM tb_berita";
      $query = mysqli_query($link, $sql) or die("error1");
      $totalData = mysqli_num_rows($query);
      $totalFiltered = $totalData;
      if (!empty(sanitate($requestData['search']['value']))) {
        $sql .= " WHERE title LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $query = mysqli_query($link, $sql) or die("error2");
        $totalFiltered = mysqli_num_rows($query);
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error3");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error4");
        }
      } else {
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error5");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error6");
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-berita']['sha1'][3]);
        $detail = base64_encode($enc['data-berita']['sha1'][5]);
        $delete = base64_encode($enc['data-berita']['sha1'][6]);
        $komen = base64_encode($enc['data-berita']['sha1'][7]);

        if ($row['status'] == 1) {
          $stts = '<label class="label label-success">Publish</label>';
        } else {
          $stts = '<label class="label label-warning">Draft</label>';
        }
        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["title"];
        $nestedData[] = $stts;
        if (hasPermit('update_berita') && hasPermit('delete_berita')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['id_berita'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="detail" name="detail" class="btn btn-xs btn-success" title="Detail Data" data-content="' . $row['id_berita'] . '" data-target="' . $detail . '">
              <i class="fa fa-list"></i>
            </a>&nbsp;
            <a id="komen" name="komen" class="btn btn-xs btn-info" title="Komentar" data-content="' . $row['id_berita'] . '" data-target="' . $komen . '">
              <i class="fa fa-comment"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['title'] . '" data-content="' . $row['id_berita'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = "";
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-berita']['remote'] && $dest == $enc['data-berita']['sha1'][2]) {
      $title = sanitate(ucwords($requestData['title']));
      $slug = slug_title($title);
      $stts = sanitate($requestData['status']);
      $cover = check_gambar(sanitate($requestData['cover']));
      $desc = sanitate($requestData['description']);
      $date = date('Y-m-d H:i:s', strtotime('now'));

      $queryBerita = "INSERT INTO tb_berita VALUES(NULL,'$slug','$title','$cover','$desc','$stts','$date')";
      $sqlBerita = mysqli_query($link, $queryBerita);

      if ($sqlBerita) {
        $data['berita'] = array(
          'code' => 1,
          'message' => 'Berita telah ditambahkan !'
        );
      } else {
        $data['berita'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-berita']['remote'] && $dest == $enc['data-berita']['sha1'][3] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT id_berita, title, status, cover, content FROM tb_berita WHERE id_berita='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
        $data['berita'] = array(
          'code' => true,
          'data' => $dt
        );
      } else {
        $data['berita'] = array(
          'code' => false,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-berita']['remote'] && $dest == $enc['data-berita']['sha1'][4] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $title = sanitate(ucwords($requestData['title']));
      $stts = sanitate($requestData['status']);
      $cover = check_gambar(sanitate($requestData['cover']));
      $desc = sanitate($requestData['description']);

      $check = mysqli_fetch_assoc(mysqli_query($link,"SELECT title FROM tb_berita WHERE id_berita='$id'"));
      $query = "UPDATE tb_berita SET title='$title',content='$desc',status='$stts',cover='$cover'";
      if ($check['title'] != $title) {
        $slug = slug_title($title);
        $query .= ", slug='$slug'";
      }
      $query .= " WHERE id_berita='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['berita'] = array(
          'code' => 1,
          'message' => 'Data berhasil diubah !'
        );
      } else {
        $data['berita'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-berita']['remote'] && $dest == $enc['data-berita']['sha1'][5] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT cover,title,status,content,created_at FROM tb_berita WHERE id_berita='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $r = mysqli_fetch_assoc($sql);
        $data['berita'] = array(
          'code' => 1,
          'data' => $r,
        );
      } else {
        $data['berita'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-berita']['remote'] && $dest == $enc['data-berita']['sha1'][6] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "DELETE FROM tb_berita WHERE id_berita='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['berita'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['berita'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-berita']['remote'] && $dest == $enc['data-berita']['sha1'][7] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $sql = mysqli_query($link,"SELECT * FROM tb_berita_komen WHERE id_berita='$id' ORDER BY created_at DESC");
      if (mysqli_num_rows($sql) > 0) {
        while ($r = mysqli_fetch_assoc($sql)) {
          $delete = base64_encode($enc['data-berita']['sha1'][9]);
          $hps = '<a id="hps" name="hps" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $r['name'] . '" data-content="' . $r['id_komen'] . '" data-target="' . $delete . '">
                    <i class="fa fa-trash"></i>
                    <span>Hapus Komentar</span>
                  </a>';
          $dt[] = array(
            'name' => $r['name'],
            'comment' => $r['content'],
            'tgl' => date('Y-m-d H:i:s',strtotime($r['created_at'])),
            'hps' => $hps
          );
          $data['komen'] = array(
            'code' => true,
            'berita' => $id,
            'data' => $dt
          );
        }
      } else {
        $data['komen'] = array(
          'code' => false,
          'message' => "Tidak ada komentar"
        );
      }
    } elseif ($route == $enc['data-berita']['remote'] && $dest == $enc['data-berita']['sha1'][8]) {
      $nama = sanitate($requestData['nama']);
      $brta = sanitate($requestData['id']);
      $kmtr = sanitate($requestData['komentar']);
      $date = date('Y-m-d H:i:s',strtotime('now'));

      $query = mysqli_query($link,"INSERT INTO tb_berita_komen VALUES(NULL,'$brta','$nama','$kmtr','$date')");

      if ($query) {
        $data['komen'] = array(
          'code' => true,
          'message' => 'Komentar telah ditambahkan'
        );
      } else {
        $data['komen'] = array(
          'code' => false,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-berita']['remote'] && $dest == $enc['data-berita']['sha1'][9] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "DELETE FROM tb_berita_komen WHERE id_komen='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['komen'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['komen'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    }
    // API Manajemen Data Berita End //

    // API Manajemen Data Poli Start //
    elseif ($route == $enc['data-poli']['remote'] && $dest == $enc['data-poli']['sha1'][0]) {
      $columns = array(
        'created_at',
        'name_poli',
      );
      $sql = "SELECT * FROM tb_poli";
      $query = mysqli_query($link, $sql) or die("error1");
      $totalData = mysqli_num_rows($query);
      $totalFiltered = $totalData;
      if (!empty(sanitate($requestData['search']['value']))) {
        $sql .= " WHERE name_poli LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $query = mysqli_query($link, $sql) or die("error2");
        $totalFiltered = mysqli_num_rows($query);
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error3");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error4");
        }
      } else {
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error5");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error6");
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-poli']['sha1'][3]);
        $detail = base64_encode($enc['data-poli']['sha1'][5]);
        $delete = base64_encode($enc['data-poli']['sha1'][6]);

        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["name_poli"];
        if (hasPermit('update_poli') && hasPermit('delete_poli')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['id_poli'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="detail" name="detail" class="btn btn-xs btn-success" title="Detail Data" data-content="' . $row['id_poli'] . '" data-target="' . $detail . '">
              <i class="fa fa-list"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['name_poli'] . '" data-content="' . $row['id_poli'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = "";
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-poli']['remote'] && $dest == $enc['data-poli']['sha1'][2]) {
      $title = sanitate(ucwords($requestData['title']));
      $desc = sanitate($requestData['description']);
      $date = date('Y-m-d H:i:s', strtotime('now'));

      $queryPoli = "INSERT INTO tb_poli VALUES(NULL,'$title','$desc','$date')";
      $sqlPoli = mysqli_query($link, $queryPoli);

      if ($sqlPoli) {
        $data['poli'] = array(
          'code' => 1,
          'message' => 'Berita telah ditambahkan !'
        );
      } else {
        $data['poli'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-poli']['remote'] && $dest == $enc['data-poli']['sha1'][3] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT id_poli, name_poli, description FROM tb_poli WHERE id_poli='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
        $data['poli'] = array(
          'code' => true,
          'data' => $dt
        );
      } else {
        $data['poli'] = array(
          'code' => false,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-poli']['remote'] && $dest == $enc['data-poli']['sha1'][4] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $title = sanitate(ucwords($requestData['title']));
      $desc = sanitate($requestData['description']);

      $query = "UPDATE tb_poli SET name_poli='$title',description='$desc' WHERE id_poli='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['poli'] = array(
          'code' => 1,
          'message' => 'Data berhasil diubah !'
        );
      } else {
        $data['poli'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-poli']['remote'] && $dest == $enc['data-poli']['sha1'][5] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT name_poli AS title,description AS content,created_at FROM tb_poli WHERE id_poli='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $r = mysqli_fetch_assoc($sql);
        $data['poli'] = array(
          'code' => 1,
          'data' => $r,
        );
      } else {
        $data['poli'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-poli']['remote'] && $dest == $enc['data-poli']['sha1'][6] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "DELETE FROM tb_poli WHERE id_poli='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['poli'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['poli'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    }
    // API Manajemen Data Poli End //

    // API Manajemen Data Darah Start //
    elseif ($route == $enc['data-darah']['remote'] && $dest == $enc['data-darah']['sha1'][0]) {
      $columns = array(
        'name_darah',
        'name_darah',
      );
      $sql = "SELECT * FROM tb_darah";
      $query = mysqli_query($link, $sql) or die("error1");
      $totalData = mysqli_num_rows($query);
      $totalFiltered = $totalData;
      if (!empty(sanitate($requestData['search']['value']))) {
        $sql .= " WHERE name_darah LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $query = mysqli_query($link, $sql) or die("error2");
        $totalFiltered = mysqli_num_rows($query);
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error3");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error4");
        }
      } else {
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error5");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error6");
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $delete = base64_encode($enc['data-darah']['sha1'][6]);

        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["name_darah"];
        if (hasPermit('delete_darah')) {
          $nestedData[] =
            '<a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['name_darah'] . '" data-content="' . $row['id_darah'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = '';
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-darah']['remote'] && $dest == $enc['data-darah']['sha1'][2]) {
      $name = ucwords(sanitate($requestData['nama']));
      $sql = mysqli_query($link,"INSERT INTO tb_darah VALUES(NULL,'$name')");
      if ($sql) {
        $data['darah'] = array(
          'code' => 1,
          'message' => 'Data berhasil ditambahkan !'
        );
      } else {
        $data['darah'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-darah']['remote'] && $dest == $enc['data-darah']['sha1'][6] && isset($_GET['id'])) {
      $id = $requestData['id'];
      $check = mysqli_query($link,"SELECT id_darah FROM tb_pasien WHERE id_darah='$id'");
      if (mysqli_num_rows($check) > 0) {
        $data['darah'] = array(
          'code' => 0,
          'message' => 'Data tidak bisa dihapus !'
        );
      } else {
        $sql = mysqli_query($link,"DELETE FROM tb_darah WHERE id_darah='$id'");
        if ($sql) {
          $data['darah'] = array(
            'code' => 1,
            'message' => 'Data berhasil di hapus !'
          );
        } else {
          $data['darah'] = array(
            'code' => 0,
            'message' => mysqli_error($link)
          );
        }
      }
    } elseif ($route == $enc['data-darah']['remote'] && $dest == $enc['data-darah']['check'][0] && isset($_GET['nama'])) {
      $nama = sanitate($requestData['nama']);
      $sql = mysqli_query($link, "SELECT name_darah FROM tb_darah WHERE name_darah='$nama'");
      if (mysqli_num_rows($sql) == 1) {
        $data = false;
      } else {
        $data = true;
      }
    }
    // API Manajemen Data Darah End //
    
    // API Manajemen Data Spesialis Start //
    elseif ($route == $enc['data-spesialis']['remote'] && $dest == $enc['data-spesialis']['sha1'][0]) {
      $columns = array(
        'name_spesialis',
        'name_spesialis',
      );
      $sql = "SELECT * FROM tb_spesialis";
      $query = mysqli_query($link, $sql) or die("error1");
      $totalData = mysqli_num_rows($query);
      $totalFiltered = $totalData;
      if (!empty(sanitate($requestData['search']['value']))) {
        $sql .= " WHERE name_spesialis LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $query = mysqli_query($link, $sql) or die("error2");
        $totalFiltered = mysqli_num_rows($query);
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error3");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error4");
        }
      } else {
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error5");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error6");
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-spesialis']['sha1'][3]);
        $delete = base64_encode($enc['data-spesialis']['sha1'][6]);

        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["code_spesialis"];
        $nestedData[] = $row["name_spesialis"];
        if (hasPermit('update_spesialis') && hasPermit('delete_spesialis')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['code_spesialis'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['name_spesialis'] . '" data-content="' . $row['code_spesialis'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = '';
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-spesialis']['remote'] && $dest == $enc['data-spesialis']['sha1'][2]) {
      $kode = strtolower(sanitate($requestData['kode']));
      $name = ucwords(sanitate($requestData['nama']));
      $sql = mysqli_query($link,"INSERT INTO tb_spesialis VALUES('$kode','$name')");
      if ($sql) {
        $data['spesialis'] = array(
          'code' => 1,
          'message' => 'Data berhasil ditambahkan !'
        );
      } else {
        $data['spesialis'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-spesialis']['remote'] && $dest == $enc['data-spesialis']['sha1'][3] && isset($_GET['id'])) {
      $id = $requestData['id'];
      $sql = mysqli_query($link,"SELECT * FROM tb_spesialis WHERE code_spesialis='$id'");
      if (mysqli_num_rows($sql) == 1) {
        $r = mysqli_fetch_array($sql, MYSQLI_NUM);
        $data['spesialis'] = array(
          'code' => 1,
          'data' => $r
        );
      } else {
        $data['spesialis'] = array(
          'code' => 1,
          'message' => "Data tidak ditemukan"
        );
      }
    } elseif ($route == $enc['data-spesialis']['remote'] && $dest == $enc['data-spesialis']['sha1'][4] && isset($_GET['id'])) {
      $id = $requestData['id'];
      $nama = ucwords(sanitate($requestData['nama']));
      $sql = mysqli_query($link,"UPDATE tb_spesialis SET name_spesialis='$nama' WHERE code_spesialis='$id'");
      if ($sql) {
        $data['spesialis'] = array(
          'code' => 1,
          'message' => 'Data berhasil diubah !'
        );
      } else {
        $data['spesialis'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-spesialis']['remote'] && $dest == $enc['data-spesialis']['sha1'][6] && isset($_GET['id'])) {
      $id = $requestData['id'];
      $check = mysqli_query($link,"SELECT code_spesialis FROM tb_dokter WHERE code_spesialis='$id'");
      if (mysqli_num_rows($check) > 0) {
        $data['spesialis'] = array(
          'code' => 0,
          'message' => 'Data tidak bisa dihapus !'
        );
      } else {
        $sql = mysqli_query($link,"DELETE FROM tb_spesialis WHERE code_spesialis='$id'");
        if ($sql) {
          $data['spesialis'] = array(
            'code' => 1,
            'message' => 'Data berhasil di hapus !'
          );
        } else {
          $data['spesialis'] = array(
            'code' => 0,
            'message' => mysqli_error($link)
          );
        }
      }
    } elseif ($route == $enc['data-spesialis']['remote'] && $dest == $enc['data-spesialis']['check'][0] && isset($_GET['nama'])) {
      $nama = sanitate($requestData['nama']);
      $sql = mysqli_query($link, "SELECT name_spesialis FROM tb_spesialis WHERE name_spesialis='$nama'");
      if (mysqli_num_rows($sql) == 1) {
        $data = false;
      } else {
        $data = true;
      }
    } elseif ($route == $enc['data-spesialis']['remote'] && $dest == $enc['data-spesialis']['check'][1] && isset($_GET['id']) && isset($_GET['nama'])) {
      $id = sanitate($requestData['id']);
      $nama = sanitate($requestData['nama']);
      $query = "SELECT * FROM tb_spesialis WHERE code_spesialis='$id' AND name_spesialis='$nama'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $data = true;
      } else {
        $sql = mysqli_query($link, "SELECT name_spesialis FROM tb_spesialis WHERE name_spesialis='$nama'");
        if (mysqli_num_rows($sql) == 1) {
          $data = false;
        } else {
          $data = true;
        }
      }
    } elseif ($route == $enc['data-spesialis']['remote'] && $dest == $enc['data-spesialis']['check'][2] && isset($_GET['kode'])) {
      $kode = sanitate($requestData['kode']);
      $sql = mysqli_query($link, "SELECT code_spesialis FROM tb_spesialis WHERE code_spesialis='$kode'");
      if (mysqli_num_rows($sql) == 1) {
        $data = false;
      } else {
        $data = true;
      }
    }
    // API Manajemen Data Spesialis End //

    // API Manajemen Data Jadwal Dokter Start //
    elseif ($route == $enc['data-jadwal']['remote'] && $dest == $enc['data-jadwal']['sha1'][0]) {
      $columns = array(
        'j.created_at',
        'p.name_poli',
        'd.name',
        'j.jadwal'
      );
      $sql = "SELECT p.name_poli, d.name, j.jadwal, j.created_at, j.id_jadwal FROM tb_jadwal AS j
              INNER JOIN tb_poli AS p ON p.id_poli = j.id_poli
              INNER JOIN tb_dokter AS d ON d.id_dokter = j.id_dokter";
      $query = mysqli_query($link, $sql) or die("error1");
      $totalData = mysqli_num_rows($query);
      $totalFiltered = $totalData;
      if (!empty(sanitate($requestData['search']['value']))) {
        $sql .= " WHERE p.name_poli LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $sql .= " OR d.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $query = mysqli_query($link, $sql) or die("error2");
        $totalFiltered = mysqli_num_rows($query);
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error3");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error4");
        }
      } else {
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error5");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error6");
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-jadwal']['sha1'][3]);
        $delete = base64_encode($enc['data-jadwal']['sha1'][6]);
        
        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["name_poli"];
        $nestedData[] = $row["name"];
        $nestedData[] = str_replace(',','-',$row["jadwal"]);
        if (hasPermit('update_jadwal') && hasPermit('delete_jadwal')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['id_jadwal'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['name'] . '" data-content="' . $row['id_jadwal'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = "";
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-jadwal']['remote'] && $dest == $enc['data-jadwal']['sha1'][1]) {
      $check = mysqli_query($link,"SELECT * FROM tb_poli");
      if (mysqli_num_rows($check) > 0) {
        while ($r = mysqli_fetch_assoc($check)) {
          $a[] = array(
            'key' => $r['id_poli'],
            'value' => $r['name_poli']
          );
        }
        $data['jadwal'] = array(
          'code' => true,
          'poli' => $a
        );
      } else {
        $data['jadwal'] = array(
          'code' => false,
          'message' => "Data Poli kosong !"
        );
      }
    } elseif ($route == $enc['data-jadwal']['remote'] && $dest == $enc['data-jadwal']['sha1'][2]) {
      $dokter = sanitate($requestData['dokter']);
      $poli = sanitate($requestData['poli']);
      $awal = sanitate($requestData['awal']);
      $akhir = sanitate($requestData['akhir']);
      $hari = $awal . "," . $akhir;
      $date = date('Y-m-d H:i:s', strtotime('now'));

      $queryJadwal = "INSERT INTO tb_jadwal VALUES(NULL,'$dokter','$poli','$hari','$date')";
      $sqlJadwal = mysqli_query($link, $queryJadwal);
      if ($sqlJadwal) {
        $data['jadwal'] = array(
          'code' => 1,
          'message' => 'Obat telah ditambahkan !'
        );
      } else {
        $data['jadwal'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-jadwal']['remote'] && $dest == $enc['data-jadwal']['sha1'][3] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $check = mysqli_query($link,"SELECT * FROM tb_poli");
      if (mysqli_num_rows($check) > 0) {
        $query = "SELECT j.id_jadwal, p.id_poli, j.jadwal, d.name FROM tb_jadwal AS j
                  INNER JOIN tb_poli AS p ON p.id_poli = j.id_poli
                  INNER JOIN tb_dokter AS d ON d.id_dokter = j.id_dokter
                  WHERE j.id_jadwal='$id'";
        $sql = mysqli_query($link, $query);
        if (mysqli_num_rows($sql) == 1) {
          $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
          while ($r = mysqli_fetch_assoc($check)) {
            $sp[] = array(
              'key' => $r['id_poli'],
              'value' => $r['name_poli']
            );
          }
          $data['jadwal'] = array(
            'code' => true,
            'poli' => $sp,
            'data' => $dt
          );
        } else {
          $data['jadwal'] = array(
            'code' => false,
            'message' => 'Data tidak ditemukan !',
          );
        }
      } else {
        $data['jadwal'] = array(
          'code' => false,
          'message' => "Data poli kosong !"
        );
      }
    } elseif ($route == $enc['data-jadwal']['remote'] && $dest == $enc['data-jadwal']['sha1'][4] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $poli = sanitate($requestData['poli']);
      $awal = sanitate($requestData['awal']);
      $akhir = sanitate($requestData['akhir']);
      $hari = $awal . "," . $akhir;

      $query = "UPDATE tb_jadwal SET id_poli='$poli', jadwal='$hari' WHERE id_jadwal='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['jadwal'] = array(
          'code' => 1,
          'message' => 'Data berhasil diubah !'
        );
      } else {
        $data['jadwal'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-jadwal']['remote'] && $dest == $enc['data-jadwal']['sha1'][6] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "DELETE FROM tb_jadwal WHERE id_jadwal='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['jadwal'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['jadwal'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-jadwal']['remote'] && $dest == $enc['data-jadwal']['sha1'][7] && isset($_GET['q'])) {
      $q = $requestData['q'];
      $sql = "SELECT id_dokter,nip,name FROM tb_dokter WHERE nip LIKE '%$q%' OR name LIKE '%$q%' ORDER BY created_at DESC";
      $check = mysqli_query($link,$sql);
      if (mysqli_num_rows($check) > 0) {
        $list = array();
        $i = 0;
        while ($r = mysqli_fetch_assoc($check)) {
          $list[] = array(
            'id' => $r['id_dokter'],
            'text' => $r['nip'] . " - " . $r['name']
          );
          $i++;
        }
        $data = $list;
      } else {
        $data = "";
      }
    } 
    // API Manajemen Data Jadwal Dokter End //
    
    // API Manajemen Data Resep Start //
    elseif ($route == $enc['data-resep']['remote'] && $dest == $enc['data-resep']['sha1'][0]) {
      $columns = array(
        'r.created_at',
        'r.code_resep',
        'd.name',
        'p.name',
      );
      if ($dashboard == "staff") {
        $sql = "SELECT p.name AS pasien, d.name AS dokter, r.created_at, r.code_resep FROM tb_resep AS r
                INNER JOIN tb_pasien AS p ON p.id_pasien = r.id_pasien
                INNER JOIN tb_dokter AS d ON d.id_dokter = r.id_dokter";
        $query = mysqli_query($link, $sql) or die("error1");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;
        if (!empty(sanitate($requestData['search']['value']))) {
          $sql .= " WHERE p.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR r.code_resep LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR d.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $query = mysqli_query($link, $sql) or die("error2");
          $totalFiltered = mysqli_num_rows($query);
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error3");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error4");
          }
        } else {
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error5");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error6");
          }
        }
      } elseif ($dashboard == "dokter") {
        $sql = "SELECT p.name AS pasien, d.name AS dokter, r.created_at, r.code_resep FROM tb_resep AS r
                INNER JOIN tb_pasien AS p ON p.id_pasien = r.id_pasien
                INNER JOIN tb_dokter AS d ON d.id_dokter = r.id_dokter
                WHERE d.id_dokter='$idDokternyo'";
        $query = mysqli_query($link, $sql) or die("error1");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;
        if (!empty(sanitate($requestData['search']['value']))) {
          $sql .= " AND p.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR r.code_resep LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR d.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $query = mysqli_query($link, $sql) or die("error2");
          $totalFiltered = mysqli_num_rows($query);
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error3");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error4");
          }
        } else {
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error5");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error6");
          }
        }
      } elseif ($dashboard == "pasien") {
        $sql = "SELECT p.name AS pasien, d.name AS dokter, r.created_at, r.code_resep FROM tb_resep AS r
                INNER JOIN tb_pasien AS p ON p.id_pasien = r.id_pasien
                INNER JOIN tb_dokter AS d ON d.id_dokter = r.id_dokter
                WHERE p.id_pasien='$idPasiennyo'";
        $query = mysqli_query($link, $sql) or die("error1");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;
        if (!empty(sanitate($requestData['search']['value']))) {
          $sql .= " AND p.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR r.code_resep LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR d.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $query = mysqli_query($link, $sql) or die("error2");
          $totalFiltered = mysqli_num_rows($query);
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error3");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error4");
          }
        } else {
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error5");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error6");
          }
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-resep']['sha1'][3]);
        $detail = base64_encode($enc['data-resep']['sha1'][5]);
        $delete = base64_encode($enc['data-resep']['sha1'][6]);
        $download = base64_encode($enc['data-resep']['unduh']);

        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["code_resep"];
        $nestedData[] = $row["dokter"];
        $nestedData[] = $row["pasien"];
        $nestedData[] = date('d-m-Y',strtotime($row["created_at"]));
        if (hasPermit('update_resep') && hasPermit('delete_resep')) {
          $nestedData[] =
            '<a id="detail" name="detail" class="btn btn-xs btn-success" title="Detail Data" data-content="' . $row['code_resep'] . '" data-target="' . $detail . '">
              <i class="fa fa-list"></i>
            </a>&nbsp;
            <a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['code_resep'] . '" data-target="' . $download . '">
              <i class="fa fa-download"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['pasien'] . '" data-content="' . $row['code_resep'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = 
          '<a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['code_resep'] . '" data-target="' . $download . '">
            <i class="fa fa-download"></i>
          </a>';
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-resep']['remote'] && $dest == $enc['data-resep']['sha1'][1]) {
      $sql = mysqli_query($link, "SELECT * FROM tb_obat WHERE stock > 0");
      if (mysqli_num_rows($sql) > 0) {
        $data['resep'] = array(
          'code' => true,
        );
      } else {
        $data['resep'] = array(
          'code' => false,
          'message' => "Data obat kosong"
        );
      }
    } elseif ($route == $enc['data-resep']['remote'] && $dest == $enc['data-resep']['sha1'][2]) {
      $kode = koderesep();
      $dokter = sanitate($requestData['dokter']);
      $pasien = sanitate($requestData['pasien']);
      $anjr = $requestData['anjr'];
      $obat = $requestData['jumlah'];
      $total = 0;
      $i = 0;
      $date = date('Y-m-d H:i:s', strtotime('now'));
      $done = false;
      $sql = mysqli_query($link,"INSERT INTO tb_resep VALUES('$kode','$pasien','$dokter','0','$date')");
      if ($sql) {
        foreach ($obat as $k => $v) {
          $r = mysqli_fetch_assoc(mysqli_query($link,"SELECT price_sale FROM tb_obat WHERE code_obat='$k'"));
          $hrg = $r['price_sale'];
          $sum = $v * $hrg;
          $anj = $anjr[$k];
          mysqli_query($link,"UPDATE tb_obat SET stock=stock-'$v', updated_at='$date' WHERE code_obat='$k'");
          mysqli_query($link,"INSERT INTO tb_resep_detail VALUES(NULL,'$kode','$k','$v','$sum','$anj')");
          $total+=$sum;
          $i++;
        }
      }

      if (count($obat) == $i) {
        $sql = mysqli_query($link,"UPDATE tb_resep SET total='$total' WHERE code_resep='$kode'");
        if ($sql) {
          $done = true;
        }
      }

      if ($done) {
        $data['resep'] = array(
          'code' => 1,
          'message' => 'Resep telah ditambahkan !',
        );
      } else {
        $data['resep'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-resep']['remote'] && $dest == $enc['data-resep']['sha1'][5] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT r.code_resep AS kode, d.name AS dokter, p.name AS pasien, o.name_obat AS obat, rd.amount AS jmlh, bp.no_bpjs AS nmr,
                o.price_sale AS hrg, rd.subtotal AS sub, name_unit AS unit, r.created_at AS tgl, rd.anjuran AS anjr, b.name_bpjs AS bpjs FROM tb_resep AS r
                INNER JOIN tb_dokter AS d ON d.id_dokter = r.id_dokter
                INNER JOIN tb_pasien AS p ON p.id_pasien = r.id_pasien
                INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs
                INNER JOIN tb_resep_detail AS rd ON rd.code_resep = r.code_resep
                INNER JOIN tb_obat AS o ON o.code_obat = rd.code_obat
                INNER JOIN tb_unit AS u ON u.id_unit = o.id_unit
                WHERE r.code_resep='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) > 0) {
        while ($r = mysqli_fetch_assoc($sql)) {
          $a = array(
            'kode' => $r['kode'],
            'dokter' => $r['dokter'],
            'pasien' => $r['pasien'],
            'bpjs' => $r['bpjs'],
            'nmr' => $r['nmr'],
            'tgl' => $r['tgl'],
          );
          $b[] = array(
            'obat' => $r['obat'],
            'anjr' => $r['anjr'],
            'hrga' => $r['hrg'],
            'jmlh' => $r['jmlh'],
            'unit' => $r['unit'],
            'sub' => $r['sub']
          );
        }
        $data['resep'] = array(
          'code' => 1,
          'data' => array('pasien' => $a, 'obat' => $b)
        );
      } else {
        $data['resep'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-resep']['remote'] && $dest == $enc['data-resep']['sha1'][6] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "DELETE FROM tb_resep WHERE code_resep='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['resep'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['resep'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-resep']['remote'] && $dest == $enc['data-resep']['sha1'][7] && isset($_GET['q'])) {
      $q = $requestData['q'];
      $sql = "SELECT id_dokter,nip,name FROM tb_dokter WHERE nip LIKE '%$q%' OR name LIKE '%$q%' ORDER BY created_at DESC";
      $check = mysqli_query($link,$sql);
      if (mysqli_num_rows($check) > 0) {
        $list = array();
        $i = 0;
        while ($r = mysqli_fetch_assoc($check)) {
          $list[] = array(
            'id' => $r['id_dokter'],
            'text' => $r['nip'] . " - " . $r['name']
          );
          $i++;
        }
        $data = $list;
      } else {
        $data = "";
      }
    } elseif ($route == $enc['data-resep']['remote'] && $dest == $enc['data-resep']['sha1'][8] && isset($_GET['q'])) {
      $q = $requestData['q'];
      $sql = "SELECT id_pasien,nik,name FROM tb_pasien WHERE nik LIKE '%$q%' OR name LIKE '%$q%' ORDER BY created_at DESC";
      $check = mysqli_query($link,$sql);
      if (mysqli_num_rows($check) > 0) {
        $list = array();
        $i = 0;
        while ($r = mysqli_fetch_assoc($check)) {
          $list[] = array(
            'id' => $r['id_pasien'],
            'text' => $r['nik'] . " - " . $r['name']
          );
          $i++;
        }
        $data = $list;
      } else {
        $data = "";
      }
    } elseif ($route == $enc['data-resep']['remote'] && $dest == $enc['data-resep']['sha1'][9] && isset($_GET['q'])) {
      $q = $requestData['q'];
      $sql = "SELECT code_obat,name_obat FROM tb_obat WHERE code_obat LIKE '%$q%' OR name_obat LIKE '%$q%' ORDER BY created_at DESC";
      $check = mysqli_query($link,$sql);
      if (mysqli_num_rows($check) > 0) {
        $list = array();
        $i = 0;
        while ($r = mysqli_fetch_assoc($check)) {
          $list[] = array(
            'id' => $r['code_obat'],
            'text' => $r['code_obat'] . " - " . $r['name_obat']
          );
          $i++;
        }
        $data = $list;
      } else {
        $data = "";
      }
    } elseif ($route == $enc['data-resep']['remote'] && $dest == $enc['data-resep']['sha1'][9] && isset($_GET['c'])) {
      $q = $requestData['c'];
      $sql = "SELECT * FROM tb_obat WHERE code_obat='$q'";
      $check = mysqli_query($link,$sql);
      if (mysqli_num_rows($check) > 0) {
        $a = array();
        $r = mysqli_fetch_assoc($check);
        $a = array(
          'code' => $r['code_obat'],
          'name' => $r['name_obat'],
          'price' => $r['price_sale'],
          'stock' => $r['stock'],
        );
        $data['resep'] = array(
          'code' => 1,
          'data' => $a
        );
      } else {
        $data['resep'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-resep']['remote'] && $dest == $enc['data-resep']['unduh'] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $sql = mysqli_query($link,"SELECT * FROM tb_resep WHERE code_resep='$id'");
      $a = base64_encode($enc['data-resep']['remote']);
      $b = base64_encode($enc['data-resep']['unduh']);
      if (mysqli_num_rows($sql) == 1) {
        $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&id=' . $id;
        $data['resep'] = array(
          'code' => 1,
          'url' => $url,
          'message' => 'Tunggu sebentar !',
        );
      } else {
        $data['resep'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    }
    // API Manajemen Data Resep End //

    // API Manajemen Data Rawat Jalan Start //
    elseif ($route == $enc['data-rawat']['remote'] && $dest == $enc['data-rawat']['sha1'][0]) {
      $columns = array(
        'rj.created_at',
        'p.name',
        'd.name',
        'r.code_resep',
      );
      if ($dashboard == "staff") {
        $sql = "SELECT p.name AS pasien, d.name AS dokter, rj.created_at, r.code_resep AS resep, rj.id_rawat_jalan FROM tb_rawat_jalan AS rj
              INNER JOIN tb_resep AS r ON r.code_resep = rj.code_resep
              INNER JOIN tb_pasien AS p ON p.id_pasien = r.id_pasien
              INNER JOIN tb_dokter AS d ON d.id_dokter = r.id_dokter";
        $query = mysqli_query($link, $sql) or die("error1");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;
        if (!empty(sanitate($requestData['search']['value']))) {
          $sql .= " WHERE p.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR r.code_resep LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR d.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $query = mysqli_query($link, $sql) or die("error2");
          $totalFiltered = mysqli_num_rows($query);
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error3");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error4");
          }
        } else {
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error5");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error6");
          }
        }
      } elseif ($dashboard == "dokter") {
        $sql = "SELECT p.name AS pasien, d.name AS dokter, rj.created_at, r.code_resep AS resep, rj.id_rawat_jalan FROM tb_rawat_jalan AS rj
              INNER JOIN tb_resep AS r ON r.code_resep = rj.code_resep
              INNER JOIN tb_pasien AS p ON p.id_pasien = r.id_pasien
              INNER JOIN tb_dokter AS d ON d.id_dokter = r.id_dokter
              WHERE d.id_dokter='$idDokternyo'";
        $query = mysqli_query($link, $sql) or die("error1");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;
        if (!empty(sanitate($requestData['search']['value']))) {
          $sql .= " AND p.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR r.code_resep LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR d.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $query = mysqli_query($link, $sql) or die("error2");
          $totalFiltered = mysqli_num_rows($query);
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error3");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error4");
          }
        } else {
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error5");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error6");
          }
        }
      } elseif ($dashboard == "pasien") {
        $sql = "SELECT p.name AS pasien, d.name AS dokter, rj.created_at, r.code_resep AS resep, rj.id_rawat_jalan FROM tb_rawat_jalan AS rj
                INNER JOIN tb_resep AS r ON r.code_resep = rj.code_resep
                INNER JOIN tb_pasien AS p ON p.id_pasien = r.id_pasien
                INNER JOIN tb_dokter AS d ON d.id_dokter = r.id_dokter
                WHERE p.id_pasien='$idPasiennyo'";
        $query = mysqli_query($link, $sql) or die("error1");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;
        if (!empty(sanitate($requestData['search']['value']))) {
          $sql .= " AND p.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR r.code_resep LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR d.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $query = mysqli_query($link, $sql) or die("error2");
          $totalFiltered = mysqli_num_rows($query);
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error3");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error4");
          }
        } else {
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error5");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error6");
          }
        }
      }
      
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-rawat']['sha1'][3]);
        $detail = base64_encode($enc['data-rawat']['sha1'][5]);
        $delete = base64_encode($enc['data-rawat']['sha1'][6]);
        $download = base64_encode($enc['data-rawat']['unduh']);

        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["pasien"];
        $nestedData[] = $row["dokter"];
        $nestedData[] = $row["resep"];
        $nestedData[] = date('d-m-Y',strtotime($row["created_at"]));
        if (hasPermit('update_rawat_jalan') && hasPermit('delete_rawat_jalan')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['id_rawat_jalan'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="detail" name="detail" class="btn btn-xs btn-success" title="Detail Data" data-content="' . $row['id_rawat_jalan'] . '" data-target="' . $detail . '">
              <i class="fa fa-list"></i>
            </a>&nbsp;
            <a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['id_rawat_jalan'] . '" data-target="' . $download . '">
              <i class="fa fa-download"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['pasien'] . '" data-content="' . $row['id_rawat_jalan'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = 
          '<a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['id_rawat_jalan'] . '" data-target="' . $download . '">
            <i class="fa fa-download"></i>
          </a>';
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-rawat']['remote'] && $dest == $enc['data-rawat']['sha1'][1]) {
      $sql = mysqli_query($link, "SELECT * FROM tb_resep");
      if (mysqli_num_rows($sql) > 0) {
        $data['rawat_jalan'] = array(
          'code' => true,
        );
      } else {
        $data['rawat_jalan'] = array(
          'code' => false,
          'message' => "Data obat kosong"
        );
      }
    } elseif ($route == $enc['data-rawat']['remote'] && $dest == $enc['data-rawat']['sha1'][2]) {
      $pasien = sanitate($requestData['pasien']);
      $dokter = sanitate($requestData['dokter']);
      $resep = sanitate($requestData['resep']);
      $ket = sanitate($requestData['ket']);
      $date = date('Y-m-d H:i:s', strtotime('now'));

      $queryRekam = "INSERT INTO tb_rawat_jalan VALUES(NULL,'$pasien','$dokter','$resep','$ket','$date','$date')";
      $sqlRekam = mysqli_query($link, $queryRekam);
      if ($sqlRekam) {
        $data['rawat_jalan'] = array(
          'code' => 1,
          'message' => 'Rawat jalan telah ditambahkan !',
        );
      } else {
        $data['rawat_jalan'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-rawat']['remote'] && $dest == $enc['data-rawat']['sha1'][3] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT rj.id_rawat_jalan, p.name AS pasien, d.name AS dokter, rj.code_resep AS resep, rj.keterangan AS ket FROM tb_rawat_jalan AS rj
                INNER JOIN tb_pasien AS p ON p.id_pasien = rj.id_pasien
                INNER JOIN tb_dokter AS d ON d.id_dokter = rj.id_dokter
                WHERE rj.id_rawat_jalan='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
        $data['rawat_jalan'] = array(
          'code' => true,
          'data' => $dt
        );
      } else {
        $data['rawat_jalan'] = array(
          'code' => false,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-rawat']['remote'] && $dest == $enc['data-rawat']['sha1'][4] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $ket = sanitate($requestData['ket']);
      $date = date('Y-m-d H:i:s', strtotime('now'));
      $query = "UPDATE tb_rawat_jalan SET keterangan='$ket', updated_at='$date' WHERE id_rawat_jalan='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['rawat_jalan'] = array(
          'code' => 1,
          'message' => 'Data berhasil diubah !'
        );
      } else {
        $data['rawat_jalan'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-rawat']['remote'] && $dest == $enc['data-rawat']['sha1'][5] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT p.name AS pasien, p.kk, p.sex AS jk, p.religion AS agm, p.worker AS krj, p.place_born AS lhr, p.date_born AS tgl_lhr, p.address AS almt,
                d.name AS dokter, rj.keterangan AS ket, rj.created_at AS tgl, bp.no_bpjs AS nmr, b.name_bpjs AS bpjs, rj.code_resep AS kode FROM tb_rawat_jalan AS rj
                INNER JOIN tb_pasien AS p ON p.id_pasien = rj.id_pasien
                INNER JOIN tb_dokter AS d ON d.id_dokter = rj.id_dokter
                INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs
                WHERE rj.id_rawat_jalan='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $r = mysqli_fetch_assoc($sql);
        $kode = $r['kode'];
        $query = "SELECT name_obat AS obat, anjuran AS anjr, price_sale AS hrg, amount AS jmlh, subtotal AS sub, name_unit AS unit FROM tb_resep_detail AS rd
                  INNER JOIN tb_obat AS o ON o.code_obat = rd.code_obat
                  INNER JOIN tb_unit AS u ON u.id_unit = o.id_unit
                  WHERE code_resep='$kode'";
        $check = mysqli_query($link,$query);
        $d = array();
        while($row = mysqli_fetch_assoc($check)) {
          $d[] = array(
            'obat' => $row['obat'],
            'anjr' => $row['anjr'],
            'hrga' => $row['hrg'],
            'jmlh' => $row['jmlh'],
            'sub' => $row['sub'],
            'unit' => $row['unit']
          );
        }
        $data['rawat_jalan'] = array(
          'code' => 1,
          'data' => $r,
          'resep' => $d
        );
      } else {
        $data['rawat_jalan'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-rawat']['remote'] && $dest == $enc['data-rawat']['sha1'][6] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "DELETE FROM tb_rawat_jalan WHERE id_rawat_jalan='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['rawat_jalan'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['rawat_jalan'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-rawat']['remote'] && $dest == $enc['data-rawat']['sha1'][7] && isset($_GET['q'])) {
      $q = $requestData['q'];
      $sql = "SELECT id_dokter,nip,name FROM tb_dokter WHERE nip LIKE '%$q%' OR name LIKE '%$q%' ORDER BY created_at DESC";
      $check = mysqli_query($link,$sql);
      if (mysqli_num_rows($check) > 0) {
        $list = array();
        $i = 0;
        while ($r = mysqli_fetch_assoc($check)) {
          $list[] = array(
            'id' => $r['id_dokter'],
            'text' => $r['nip'] . " - " . $r['name']
          );
          $i++;
        }
        $data = $list;
      } else {
        $data = "";
      }
    } elseif ($route == $enc['data-rawat']['remote'] && $dest == $enc['data-rawat']['sha1'][8] && isset($_GET['q'])) {
      $q = $requestData['q'];
      $sql = "SELECT id_pasien,nik,name FROM tb_pasien WHERE nik LIKE '%$q%' OR name LIKE '%$q%' ORDER BY created_at DESC";
      $check = mysqli_query($link,$sql);
      if (mysqli_num_rows($check) > 0) {
        $list = array();
        $i = 0;
        while ($r = mysqli_fetch_assoc($check)) {
          $list[] = array(
            'id' => $r['id_pasien'],
            'text' => $r['nik'] . " - " . $r['name']
          );
          $i++;
        }
        $data = $list;
      } else {
        $data = "";
      }
    } elseif ($route == $enc['data-rawat']['remote'] && $dest == $enc['data-rawat']['sha1'][9]) {
      $p = $requestData['p'];
      $d = $requestData['d'];
      $sql = "SELECT r.code_resep AS kode, p.name AS pasien FROM tb_resep AS r
              INNER JOIN tb_pasien AS p ON p.id_pasien = r.id_pasien
              WHERE r.id_pasien='$p' AND r.id_dokter='$d' ORDER BY r.created_at DESC";
      $check = mysqli_query($link,$sql);
      if (mysqli_num_rows($check) > 0) {
        while ($r = mysqli_fetch_assoc($check)) {
          $b[] = array(
            'key' => $r['kode'],
            'value' => $r['kode'] . " - " . $r['pasien']
          );
        }
        $data['rawat_jalan'] = array(
          'code' => true,
          'resep' => $b
        );
      } else {
        $data['rawat_jalan'] = array(
          'code' => false,
          'message' => "Data Resep kosong !"
        );
      }
    } elseif ($route == $enc['data-rawat']['remote'] && $dest == $enc['data-rawat']['unduh'] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $sql = mysqli_query($link,"SELECT * FROM tb_rawat_jalan WHERE id_rawat_jalan='$id'");
      $a = base64_encode($enc['data-rawat']['remote']);
      $b = base64_encode($enc['data-rawat']['unduh']);
      if (mysqli_num_rows($sql) == 1) {
        $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&id=' . $id;
        $data['rawat_jalan'] = array(
          'code' => 1,
          'url' => $url,
          'message' => 'Tunggu sebentar !',
        );
      } else {
        $data['rawat_jalan'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    }
    // API Manajemen Data Rawat Jalan End //

    // API Manajemen Data Rekam Medis Start //
    elseif ($route == $enc['data-rekam_medis']['remote'] && $dest == $enc['data-rekam_medis']['sha1'][0]) {
      $columns = array(
        'rm.created_at',
        'p.name',
        'd.name'
      );
      if ($dashboard == "staff") {
        $sql = "SELECT p.name AS pasien, d.name AS dokter, rm.created_at, rm.id_rekam_medis FROM tb_rekam_medis AS rm
                INNER JOIN tb_pasien AS p ON p.id_pasien = rm.id_pasien
                INNER JOIN tb_dokter AS d ON d.id_dokter = rm.id_dokter";
        $query = mysqli_query($link, $sql) or die("error1");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;
        if (!empty(sanitate($requestData['search']['value']))) {
          $sql .= " WHERE p.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR d.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $query = mysqli_query($link, $sql) or die("error2");
          $totalFiltered = mysqli_num_rows($query);
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error3");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error4");
          }
        } else {
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error5");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error6");
          }
        }
      } elseif ($dashboard == "dokter") {
        $sql = "SELECT p.name AS pasien, d.name AS dokter, rm.created_at, rm.id_rekam_medis FROM tb_rekam_medis AS rm
                INNER JOIN tb_pasien AS p ON p.id_pasien = rm.id_pasien
                INNER JOIN tb_dokter AS d ON d.id_dokter = rm.id_dokter
                WHERE d.id_dokter='$idDokternyo'";
        $query = mysqli_query($link, $sql) or die("error1");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;
        if (!empty(sanitate($requestData['search']['value']))) {
          $sql .= " AND p.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR d.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $query = mysqli_query($link, $sql) or die("error2");
          $totalFiltered = mysqli_num_rows($query);
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error3");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error4");
          }
        } else {
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error5");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error6");
          }
        }
      } elseif ($dashboard == "pasien") {
        $sql = "SELECT p.name AS pasien, d.name AS dokter, rm.created_at, rm.id_rekam_medis FROM tb_rekam_medis AS rm
                INNER JOIN tb_pasien AS p ON p.id_pasien = rm.id_pasien
                INNER JOIN tb_dokter AS d ON d.id_dokter = rm.id_dokter
                WHERE p.id_pasien='$idPasiennyo'";
        $query = mysqli_query($link, $sql) or die("error1");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;
        if (!empty(sanitate($requestData['search']['value']))) {
          $sql .= " AND p.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $sql .= " OR d.name LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
          $query = mysqli_query($link, $sql) or die("error2");
          $totalFiltered = mysqli_num_rows($query);
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error3");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error4");
          }
        } else {
          if ($requestData['length'] != -1) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
            $query = mysqli_query($link, $sql) or die("error5");
          } else {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
            $query = mysqli_query($link, $sql) or die("error6");
          }
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-rekam_medis']['sha1'][3]);
        $detail = base64_encode($enc['data-rekam_medis']['sha1'][5]);
        $delete = base64_encode($enc['data-rekam_medis']['sha1'][6]);
        $download = base64_encode($enc['data-rekam_medis']['unduh']);

        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["pasien"];
        $nestedData[] = $row["dokter"];
        $nestedData[] = date('d-m-Y',strtotime($row["created_at"]));
        if (hasPermit('update_rekam_medis') && hasPermit('delete_rekam_medis')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['id_rekam_medis'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="detail" name="detail" class="btn btn-xs btn-success" title="Detail Data" data-content="' . $row['id_rekam_medis'] . '" data-target="' . $detail . '">
              <i class="fa fa-list"></i>
            </a>&nbsp;
            <a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['id_rekam_medis'] . '" data-target="' . $download . '">
              <i class="fa fa-download"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['pasien'] . '" data-content="' . $row['id_rekam_medis'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = 
          '<a id="download" name="download" class="btn btn-xs btn-warning" title="Download Data" data-content="' . $row['id_rekam_medis'] . '" data-target="' . $download . '">
            <i class="fa fa-download"></i>
          </a>';
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-rekam_medis']['remote'] && $dest == $enc['data-rekam_medis']['sha1'][2]) {
      $pasien = sanitate($requestData['pasien']);
      $dokter = sanitate($requestData['dokter']);
      $ket = sanitate($requestData['keterangan']);
      $date = date('Y-m-d H:i:s', strtotime('now'));

      $queryRekamMedis = "INSERT INTO tb_rekam_medis VALUES(NULL,'$pasien','$dokter','$ket','$date','$date')";
      $sqlRekamMedis = mysqli_query($link, $queryRekamMedis);
      if ($sqlRekamMedis) {
        $data['rekam_medis'] = array(
          'code' => 1,
          'message' => 'Rekam Medis telah ditambahkan !'
        );
      } else {
        $data['rekam_medis'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-rekam_medis']['remote'] && $dest == $enc['data-rekam_medis']['sha1'][3] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT rm.id_rekam_medis, p.name AS pasien, d.name AS dokter, rm.keterangan AS ket FROM tb_rekam_medis AS rm
                INNER JOIN tb_pasien AS p ON p.id_pasien = rm.id_pasien
                INNER JOIN tb_dokter AS d ON d.id_dokter = rm.id_dokter
                WHERE rm.id_rekam_medis='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
        $data['rekam_medis'] = array(
          'code' => true,
          'data' => $dt
        );
      } else {
        $data['rekam_medis'] = array(
          'code' => false,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-rekam_medis']['remote'] && $dest == $enc['data-rekam_medis']['sha1'][4] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $ket = sanitate($requestData['keterangan']);
      $date = date('Y-m-d H:i:s', strtotime('now'));
      $query = "UPDATE tb_rekam_medis SET keterangan='$ket', updated_at='$date' WHERE id_rekam_medis='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['rekam_medis'] = array(
          'code' => 1,
          'message' => 'Data berhasil diubah !'
        );
      } else {
        $data['rekam_medis'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-rekam_medis']['remote'] && $dest == $enc['data-rekam_medis']['sha1'][5] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT p.name AS pasien, p.kk, p.sex AS jk, p.religion AS agm, p.worker AS krj, p.place_born AS lhr, p.date_born AS tgl_lhr, p.address AS almt,
                d.name AS dokter, rm.keterangan AS ket, rm.created_at AS tgl, bp.no_bpjs AS nmr, b.name_bpjs AS bpjs FROM tb_rekam_medis AS rm
                INNER JOIN tb_pasien AS p ON p.id_pasien = rm.id_pasien
                INNER JOIN tb_dokter AS d ON d.id_dokter = rm.id_dokter
                INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs
                WHERE rm.id_rekam_medis='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $r = mysqli_fetch_assoc($sql);
        $data['rekam_medis'] = array(
          'code' => 1,
          'data' => $r,
        );
      } else {
        $data['rekam_medis'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-rekam_medis']['remote'] && $dest == $enc['data-rekam_medis']['sha1'][6] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "DELETE FROM tb_rekam_medis WHERE id_rekam_medis='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['rekam_medis'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['rekam_medis'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-rekam_medis']['remote'] && $dest == $enc['data-rekam_medis']['sha1'][7] && isset($_GET['q'])) {
      $q = $requestData['q'];
      $sql = "SELECT id_pasien,nik,name FROM tb_pasien WHERE nik LIKE '%$q%' OR name LIKE '%$q%' ORDER BY created_at DESC";
      $check = mysqli_query($link,$sql);
      if (mysqli_num_rows($check) > 0) {
        $list = array();
        $i = 0;
        while ($r = mysqli_fetch_assoc($check)) {
          $list[] = array(
            'id' => $r['id_pasien'],
            'text' => $r['nik'] . " - " . $r['name']
          );
          $i++;
        }
        $data = $list;
      } else {
        $data = "";
      }
    } elseif ($route == $enc['data-rekam_medis']['remote'] && $dest == $enc['data-rekam_medis']['sha1'][7] && isset($_GET['c'])) {
      $q = $requestData['c'];
      $sql = "SELECT b.name_bpjs AS bpjs, bp.no_bpjs AS nmr FROM tb_bpjs_pasien AS bp INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs WHERE bp.id_pasien='$q'";
      $check = mysqli_query($link,$sql);
      if (mysqli_num_rows($check) == 1) {
        $r = mysqli_fetch_assoc($check);
        $data['rekam_medis'] = array(
          'code' => true,
          'data' => $r['bpjs'] . " - " . $r['nmr']
        );
      } else {
        $data['rekam_medis'] = array(
          'code' => false,
          'message' => "Tidak ada bpjs"
        );
      }
    } elseif ($route == $enc['data-rekam_medis']['remote'] && $dest == $enc['data-rekam_medis']['sha1'][8] && isset($_GET['q'])) {
      $q = $requestData['q'];
      $sql = "SELECT id_dokter,nip,name FROM tb_dokter WHERE nip LIKE '%$q%' OR name LIKE '%$q%' ORDER BY created_at DESC";
      $check = mysqli_query($link,$sql);
      if (mysqli_num_rows($check) > 0) {
        $list = array();
        $i = 0;
        while ($r = mysqli_fetch_assoc($check)) {
          $list[] = array(
            'id' => $r['id_dokter'],
            'text' => $r['nip'] . " - " . $r['name']
          );
          $i++;
        }
        $data = $list;
      } else {
        $data = "";
      }
    } elseif ($route == $enc['data-rekam_medis']['remote'] && $dest == $enc['data-rekam_medis']['unduh'] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $sql = mysqli_query($link,"SELECT id_rekam_medis FROM tb_rekam_medis WHERE id_rekam_medis='$id'");
      $a = base64_encode($enc['data-rekam_medis']['remote']);
      $b = base64_encode($enc['data-rekam_medis']['unduh']);
      if (mysqli_num_rows($sql) == 1) {
        $url = BASE_URL . 'download?f=' . $a . '&d=' . $b . '&id=' . $id;
        $data['rekam_medis'] = array(
          'code' => 1,
          'url' => $url,
          'message' => 'Tunggu sebentar !',
        );
      } else {
        $data['rekam_medis'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    }
    // API Manajemen Data Rekam Medis End //

    // API Manajemen Data Menu Start //
    elseif ($route == $enc['data-beranda']['remote'] && $dest == $enc['data-beranda']['sha1'][0]) {
      $columns = array(
        'vm.created_at',
        'm.name_menu',
      );
      $sql = "SELECT vm.id_view_menu, m.name_menu FROM tb_view_menu AS vm INNER JOIN tb_menu AS m ON m.id_menu = vm.id_menu";
      $query = mysqli_query($link, $sql) or die("error1");
      $totalData = mysqli_num_rows($query);
      $totalFiltered = $totalData;
      if (!empty(sanitate($requestData['search']['value']))) {
        $sql .= " WHERE m.name_menu LIKE '%" . sanitate($requestData['search']['value']) . "%' ";
        $query = mysqli_query($link, $sql) or die("error2");
        $totalFiltered = mysqli_num_rows($query);
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error3");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error4");
        }
      } else {
        if ($requestData['length'] != -1) {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
          $query = mysqli_query($link, $sql) or die("error5");
        } else {
          $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . ", $totalData";
          $query = mysqli_query($link, $sql) or die("error6");
        }
      }
      $i = 1;
      $dataTable = array();
      while ($row = mysqli_fetch_array($query)) {
        $edit = base64_encode($enc['data-beranda']['sha1'][3]);
        $detail = base64_encode($enc['data-beranda']['sha1'][5]);
        $delete = base64_encode($enc['data-beranda']['sha1'][6]);

        $nestedData = array();
        $nestedData[] = "";
        $nestedData[] = $row["name_menu"];
        if (hasPermit('update_sistem') && hasPermit('delete_sistem')) {
          $nestedData[] =
            '<a id="edit" name="edit" class="btn btn-xs btn-primary" title="Edit Data" data-content="' . $row['id_view_menu'] . '" data-target="' . $edit . '">
              <i class="fa fa-edit"></i>
            </a>&nbsp;
            <a id="detail" name="detail" class="btn btn-xs btn-success" title="Detail Data" data-content="' . $row['id_view_menu'] . '" data-target="' . $detail . '">
              <i class="fa fa-list"></i>
            </a>&nbsp;
            <a id="hapus" name="hapus" class="btn btn-xs btn-danger" title="Hapus Data" title-content="' . $row['name_menu'] . '" data-content="' . $row['id_view_menu'] . '" data-target="' . $delete . '">
              <i class="fa fa-trash"></i>
            </a>';
        } else {
          $nestedData[] = "";
        }
        $dataTable[] = $nestedData;
      }

      $data = array(
        "draw"            => intval($requestData['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $dataTable
      );
    } elseif ($route == $enc['data-beranda']['remote'] && $dest == $enc['data-beranda']['sha1'][1]) {
      $check = mysqli_query($link,"SELECT * FROM tb_menu WHERE used='0'");
      if (mysqli_num_rows($check) > 0) {
        while ($r = mysqli_fetch_assoc($check)) {
          $b[] = array(
            'key' => $r['id_menu'],
            'value' => $r['name_menu']
          );
        }
        $data['beranda'] = array(
          'code' => true,
          'menu' => $b
        );
      } else {
        $data['beranda'] = array(
          'code' => false,
          'message' => "Data Menu kosong atau sudah digunakan !"
        );
      }
    } elseif ($route == $enc['data-beranda']['remote'] && $dest == $enc['data-beranda']['sha1'][2]) {
      $menu = sanitate($requestData['menu']);
      $desc = sanitate($requestData['description']);
      $date = date('Y-m-d H:i:s', strtotime('now'));

      $queryBeranda = "INSERT INTO tb_view_menu VALUES(NULL,'$menu','$desc','$date')";
      $sqlBeranda = mysqli_query($link, $queryBeranda);

      if ($sqlBeranda) {
        mysqli_query($link,"UPDATE tb_menu SET used='1' WHERE id_menu='$menu'");
        $data['beranda'] = array(
          'code' => 1,
          'message' => 'Berita telah ditambahkan !'
        );
      } else {
        $data['beranda'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-beranda']['remote'] && $dest == $enc['data-beranda']['sha1'][3] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $check = mysqli_query($link,"SELECT m.id_menu,m.name_menu FROM tb_menu AS m INNER JOIN tb_view_menu AS vm ON vm.id_menu = m.id_menu WHERE vm.id_view_menu='$id'");
      if (mysqli_num_rows($check) > 0) {
        $query = "SELECT vm.id_view_menu, m.id_menu, vm.content FROM tb_view_menu AS vm INNER JOIN tb_menu AS m ON m.id_menu = vm.id_menu WHERE vm.id_view_menu='$id'";
        $sql = mysqli_query($link, $query);
        if (mysqli_num_rows($sql) == 1) {
          $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
          while ($r = mysqli_fetch_assoc($check)) {
            $sp[] = array(
              'key' => $r['id_menu'],
              'value' => $r['name_menu']
            );
          }
          $data['beranda'] = array(
            'code' => true,
            'menu' => $sp,
            'data' => $dt
          );
        } else {
          $data['beranda'] = array(
            'code' => false,
            'message' => 'Data tidak ditemukan !',
          );
        }
      } else {
        $data['obat'] = array(
          'code' => false,
          'message' => "Data satuan kosong !"
        );
      }
    } elseif ($route == $enc['data-beranda']['remote'] && $dest == $enc['data-beranda']['sha1'][4] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $menu = sanitate($requestData['menu']);
      $desc = sanitate($requestData['description']);

      $query = "UPDATE tb_view_menu SET id_menu='$menu',content='$desc' WHERE id_view_menu='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['beranda'] = array(
          'code' => 1,
          'message' => 'Data berhasil diubah !'
        );
      } else {
        $data['beranda'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    } elseif ($route == $enc['data-beranda']['remote'] && $dest == $enc['data-beranda']['sha1'][5] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      $query = "SELECT m.name_menu AS title, vm.content, vm.created_at FROM tb_view_menu AS vm INNER JOIN tb_menu AS m ON m.id_menu = vm.id_menu WHERE vm.id_view_menu='$id'";
      $sql = mysqli_query($link, $query);
      if (mysqli_num_rows($sql) == 1) {
        $r = mysqli_fetch_assoc($sql);
        $data['beranda'] = array(
          'code' => 1,
          'data' => $r,
        );
      } else {
        $data['beranda'] = array(
          'code' => 0,
          'message' => 'Data tidak ditemukan !',
        );
      }
    } elseif ($route == $enc['data-beranda']['remote'] && $dest == $enc['data-beranda']['sha1'][6] && isset($_GET['id'])) {
      $id = sanitate($requestData['id']);
      mysqli_query($link,"UPDATE tb_view_menu AS vm INNER JOIN tb_menu AS m ON m.id_menu = vm.id_menu SET m.used='0' WHERE vm.id_view_menu='$id'");
      $query = "DELETE FROM tb_view_menu WHERE id_view_menu='$id'";
      $sql = mysqli_query($link, $query);
      if ($sql) {
        $data['beranda'] = array(
          'code' => 1,
          'message' => 'Data berhasil dihapus'
        );
      } else {
        $data['beranda'] = array(
          'code' => 0,
          'message' => mysqli_error($link)
        );
      }
    }
    // API Manajemen Data Menu End //

    // API Profil Start //
    elseif ($route == $enc['data-profil']['remote'] && $dest == $enc['data-profil']['sha1'][0] && isset($_GET['id']) && isset($_GET['tipe'])) {
      $idnyo = sanitate($requestData['id']);
      $id = base64_decode($idnyo);
      $tipe = sanitate($requestData['tipe']);
      if ($tipe == "staff") {
        $check = mysqli_query($link,"SELECT * FROM tb_rules LIMIT 0,5");
        if (mysqli_num_rows($check) > 0) {
          $query = "SELECT s.id_staff, s.nip, r.id_rule, s.name, s.sex, s.address, s.telp, u.username, u.status FROM tb_staff AS s
                    INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff
                    INNER JOIN tb_users AS u ON u.id_user = us.id_user
                    INNER JOIN tb_rule_user AS ru ON ru.id_user = u.id_user
                    INNER JOIN tb_rules AS r ON r.id_rule = ru.id_rule
                    WHERE u.id_user='$id'";
          $sql = mysqli_query($link, $query);
          if (mysqli_num_rows($sql) == 1) {
            $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
            $data['staff'] = array(
              'code' => true,
              'data' => $dt
            );
          } else {
            $data['staff'] = array(
              'code' => false,
              'message' => 'Data tidak ditemukan !',
            );
          }
        } else {
          $data['staff'] = array(
            'code' => false,
            'message' => "Data rule kosong !"
          );
        }
      } elseif ($tipe == "dokter") {
        $check = mysqli_query($link,"SELECT * FROM tb_spesialis");
        if (mysqli_num_rows($check) > 0) {
          $query = "SELECT d.id_dokter, d.nip, s.code_spesialis, d.name, d.sex, d.address, d.telp, u.username, u.status FROM tb_dokter AS d
                    INNER JOIN tb_spesialis AS s ON s.code_spesialis = d.code_spesialis
                    INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter
                    INNER JOIN tb_users AS u ON u.id_user = ud.id_user
                    WHERE u.id_user='$id'";
          $sql = mysqli_query($link, $query);
          if (mysqli_num_rows($sql) == 1) {
            $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
            while ($r = mysqli_fetch_assoc($check)) {
              $sp[] = array(
                'key' => $r['code_spesialis'],
                'value' => $r['name_spesialis']
              );
            }
            $data['dokter'] = array(
              'code' => true,
              'spesialis' => $sp,
              'data' => $dt
            );
          } else {
            $data['dokter'] = array(
              'code' => false,
              'message' => 'Data tidak ditemukan !',
            );
          }
        } else {
          $data['dokter'] = array(
            'code' => false,
            'message' => "Data spesialis kosong !"
          );
        }
      } elseif ($tipe == "pasien") {
        $check = mysqli_query($link,"SELECT * FROM tb_darah");
        if (mysqli_num_rows($check) > 0) {
          $query = "SELECT p.id_pasien, p.kk, p.nik, p.name, p.place_born, p.date_born, p.sex, d.id_darah, p.address, p.telp, b.id_bpjs, bp.no_bpjs, u.username, u.status FROM tb_pasien AS p
                    INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                    INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs
                    INNER JOIN tb_darah AS d ON d.id_darah = p.id_darah
                    INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien
                    INNER JOIN tb_users AS u ON u.id_user = up.id_user
                    WHERE u.id_user='$id'";
          $sql = mysqli_query($link, $query);
          if (mysqli_num_rows($sql) == 1) {
            $bpjs = mysqli_query($link,"SELECT * FROM tb_bpjs");
            $dt = mysqli_fetch_array($sql, MYSQLI_NUM);
            while ($r = mysqli_fetch_assoc($check)) {
              $sp[] = array(
                'key' => $r['id_darah'],
                'value' => $r['name_darah']
              );
            }
            while($r = mysqli_fetch_assoc($bpjs)) {
              $b[] = array(
                'key' => $r['id_bpjs'],
                'value' => $r['name_bpjs']
              );
            }
            $data['pasien'] = array(
              'code' => true,
              'darah' => $sp,
              'bpjs' => $b,
              'data' => $dt,
            );
          } else {
            $data['pasien'] = array(
              'code' => false,
              'message' => 'Data tidak ditemukan !',
            );
          }
        } else {
          $data['pasien'] = array(
            'code' => false,
            'message' => "Data darah kosong !"
          );
        }
      }
    } 
    // API Profil End //

    // API Ubah Password Start //
    elseif ($route == $enc['data-password']['remote'] && $dest == $enc['data-password']['sha1'][1] && isset($_GET['u'])) {
      $id = base64_decode($requestData['u']);
      $pass = hash('sha512', $requestData['passwordlama']);
      $sql = mysqli_query($link, "SELECT username,password FROM tb_users WHERE username='$id' AND password='$pass' LIMIT 1");
      if (mysqli_num_rows($sql) == 1) {
        $data = true;
      } else {
        $data = false;
      }
    } elseif ($route == $enc['data-password']['remote'] && $dest == $enc['data-password']['sha1'][0] && isset($_GET['u'])) {
      $id = base64_decode($requestData['u']);
      $passnew = $requestData['passwordbaru'];
      $passcon = $requestData['konfirmasipassword'];
      if ($passnew == $passcon) {
        $a = hash('sha512', $passnew);
        $sql = mysqli_query($link, "UPDATE tb_users SET password='$a' WHERE username='$id'");
        if ($sql) {
          $data['password'] = array(
            'code' => 1,
            'message' => 'Password berhasil diubah !'
          );
        } else {
          $data['password'] = array(
            'code' => 0,
            'message' => mysqli_error($link)
          );
        }
      } else {
        $data['password'] = array(
          'code' => 0,
          'message' => "Invalid request"
        );
      }
    }
    // API Ubah Password End //
  } else {
    $data = array('code' => 404, 'message' => 'Invalid Url');
  }
} else {
  $data = array('code' => 403, 'message' => 'Access Forbidden');
}

echo json_encode($data);
