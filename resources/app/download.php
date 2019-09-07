<?php
    session_start();
    require_once '../path.php';
    require_once (ABSPATH . '../config/config.php');
    require_once (ABSPATH . '../config/database.php');
    require_once (ABSPATH . '../config/pdf.php');
    require_once (ABSPATH . '../config/enkripsi.php');
    require_once (ABSPATH . '../config/functions.php');
    $file = hash('sha1',strtotime('now'));
    header('Content-type: application/pdf');
    header('Content-type: application/force-download');

    if (!isset($_SESSION['is_logged'])) {
        echo "<script>window.location.href= '".$url."login/';</script>";
        exit();
    } else {
        if (isset($_GET['f']) && isset($_GET['d'])) {
            $f = base64_decode($_GET['f']);
            $d = base64_decode($_GET['d']);
            
            if ($f == $enc['data-dokter']['remote'] && $d == $enc['data-dokter']['download'] && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['filter']) && isset($_GET['range'])) {
                $awal = $_GET['start'];
                $akhir = $_GET['end'];
                $filter = $_GET['filter'];
                $sql = "SELECT d.nip, s.name_spesialis AS spesialis, d.name AS nama, d.sex AS jk, d.address AS almt, d.telp AS tlpn, u.username AS user, u.status AS stts FROM tb_dokter AS d
                        INNER JOIN tb_spesialis AS s ON s.code_spesialis = d.code_spesialis
                        INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter
                        INNER JOIN tb_users AS u ON u.id_user = ud.id_user";
                $res = "";
                $pdf = new PDF('P','cm','A4');
                if ($filter == 1) {
                    $day = date('d',strtotime('now'));
                    $sql .= " WHERE DAY(d.created_at) = '$day' ORDER BY d.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan harian data dokter pada hari ".tanggal(date('N-Y-n-d',strtotime('now'))));
                } 
                elseif ($filter == 2) {
                    $mnt = date('m',strtotime('now'));
                    $yr = date('Y',strtotime('now'));
                    $sql .= " WHERE MONTH(d.created_at) = '$mnt' AND YEAR(d.created_at) = '$yr' ORDER BY d.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan bulanan data dokter pada bulan ".bulan(date('m',strtotime('now')))." tahun ".$yr);
                }
                elseif ($filter == 3) {
                    $range = $_GET['range'];
                    $ganti = str_replace('/','-',$range);
                    $pch = explode(' - ',$ganti);
                    $range1 = strtotime($pch[0]);
                    $range2 = strtotime($pch[1]);
                    $sql .= " WHERE d.created_at BETWEEN FROM_UNIXTIME($range1) AND FROM_UNIXTIME($range2+(((60*60)*23)+(60*59))) LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan data dokter pada hari ".tanggal(date('N-Y-n-d',strtotime($pch[0])))." sampai dengan hari ".tanggal(date('N-Y-n-d',strtotime($pch[1]))));
                }
                else {
                    $pdf->setJudul("Laporan data dokter");
                    $sql .= " ORDER BY d.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                }
                $pdf->AddPage();
                $pdf->AliasNbPages();
                $pdf->SetFont('Arial','B',8);
                $no = 1;
                while ($r = mysqli_fetch_assoc($res)) {
                    $status = $r['stts'];
                    if ($status == 2) {
                        $msg = "Blok";
                    } elseif ($status == 1) {
                        $msg = "Aktif";
                    } else {
                        $msg = "Tidak Aktif";
                    }
                    
                    $pdf->SetX(1);
                    $pdf->Cell(0.62,0.5,'No.',0,0,'L');
                    $pdf->Cell(2.85,0.5,$no,0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'NIP',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['nip'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Spesialis',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,ucwords($r['spesialis']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Nama Dokter',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,ucwords($r['nama']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Jenis Kelamin',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['jk'] == "P" ? "Pria" : "Wanita",0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Username',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['user'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Status',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$msg,0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Alamat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.89,0.5,$r['almt'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Telepon',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['tlpn'],0,1,'L');
                    
                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                    $no++;
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-dokter']['remote'] && $d == $enc['data-dokter']['unduh'] && isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT d.nip, s.name_spesialis AS spesialis, d.name AS nama, d.sex AS jk, d.address AS almt, d.telp AS tlpn, u.username AS user, u.status AS stts FROM tb_dokter AS d
                        INNER JOIN tb_spesialis AS s ON s.code_spesialis = d.code_spesialis
                        INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter
                        INNER JOIN tb_users AS u ON u.id_user = ud.id_user
                        WHERE d.id_dokter='$id'";
                $query = mysqli_query($link,$sql);
                if (mysqli_num_rows($query) > 0) {
                    $r = mysqli_fetch_assoc($query);
                    $pdf = new PDF('P','cm','A4');
                    $pdf->setJudul("Laporan data dokter " . $r['nama']);
                    $pdf->AddPage();
                    $pdf->AliasNbPages();
                    $pdf->SetFont('Arial','B',8);

                    $status = $r['stts'];
                    if ($status == 2) {
                        $msg = "Blok";
                    } elseif ($status == 1) {
                        $msg = "Aktif";
                    } else {
                        $msg = "Tidak Aktif";
                    }

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'NIP',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['nip'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Spesialis',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,ucwords($r['spesialis']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Nama Dokter',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,ucwords($r['nama']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Jenis Kelamin',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['jk'] == "P" ? "Pria" : "Wanita",0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Username',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['user'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Status',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$msg,0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Alamat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.89,0.5,$r['almt'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Telepon',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['tlpn'],0,1,'L');

                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-pasien']['remote'] && $d == $enc['data-pasien']['download'] && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['filter']) && isset($_GET['range'])) {
                $awal = $_GET['start'];
                $akhir = $_GET['end'];
                $filter = $_GET['filter'];
                $sql = "SELECT p.kk, p.nik, p.name AS nama, p.place_born AS tmpt, p.date_born AS tgl, p.sex AS jk, p.religion AS agm, p.worker AS pkj, d.name_darah AS darah, p.address AS almt, p.telp AS tlpn, u.username AS user, u.status AS stts FROM tb_pasien AS p
                        INNER JOIN tb_darah AS d ON d.id_darah = p.id_darah
                        INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien
                        INNER JOIN tb_users AS u ON u.id_user = up.id_user";
                $res = "";
                $pdf = new PDF('P','cm','A4');
                if ($filter == 1) {
                    $day = date('d',strtotime('now'));
                    $sql .= " WHERE DAY(p.created_at) = '$day' ORDER BY p.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan harian data pasien pada hari ".tanggal(date('N-Y-n-d',strtotime('now'))));
                } 
                elseif ($filter == 2) {
                    $mnt = date('m',strtotime('now'));
                    $yr = date('Y',strtotime('now'));
                    $sql .= " WHERE MONTH(p.created_at) = '$mnt' AND YEAR(p.created_at) = '$yr' ORDER BY p.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan bulanan data pasien pada bulan ".bulan(date('m',strtotime('now')))." tahun ".$yr);
                }
                elseif ($filter == 3) {
                    $range = $_GET['range'];
                    $ganti = str_replace('/','-',$range);
                    $pch = explode(' - ',$ganti);
                    $range1 = strtotime($pch[0]);
                    $range2 = strtotime($pch[1]);
                    $sql .= " WHERE p.created_at BETWEEN FROM_UNIXTIME($range1) AND FROM_UNIXTIME($range2+(((60*60)*23)+(60*59))) LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan data pasien pada hari ".tanggal(date('N-Y-n-d',strtotime($pch[0])))." sampai dengan hari ".tanggal(date('N-Y-n-d',strtotime($pch[1]))));
                }
                else {
                    $pdf->setJudul("Laporan data pasien");
                    $sql .= " ORDER BY p.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                }
                $pdf->AddPage();
                $pdf->AliasNbPages();
                $pdf->SetFont('Arial','B',8);
                $no = 1;
                while ($r = mysqli_fetch_assoc($res)) {
                    $status = $r['stts'];
                    if ($status == 2) {
                        $msg = "Blok";
                    } elseif ($status == 1) {
                        $msg = "Aktif";
                    } else {
                        $msg = "Tidak Aktif";
                    }

                    $pdf->SetX(1);
                    $pdf->Cell(0.62,0.5,'No.',0,0,'L');
                    $pdf->Cell(2.85,0.5,$no,0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Kartu Keluarga',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['kk'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'NIK',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['nik'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Nama Pasien',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['nama']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Tempat, Tanggal Lahir',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['tmpt'] . ", " . $r['tgl'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Jenis Kelamin',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['jk'] == "P" ? "Pria" : "Wanita",0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Golongan Darah',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['darah'],0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Agama',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['agm'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Pekerjaan',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['pkj'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Username',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['user'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Status',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$msg,0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Alamat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.69,0.5,$r['almt'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Telepon',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['tlpn'],0,1,'L');

                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                    $no++;
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-pasien']['remote'] && $d == $enc['data-pasien']['unduh'] && isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT p.kk, p.nik, p.name AS nama, p.place_born AS tmpt, p.date_born AS tgl, p.sex AS jk, p.religion AS agm, p.worker AS pkj, d.name_darah AS darah, p.address AS almt, p.telp AS tlpn, u.username AS user, u.status AS stts FROM tb_pasien AS p
                        INNER JOIN tb_darah AS d ON d.id_darah = p.id_darah
                        INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien
                        INNER JOIN tb_users AS u ON u.id_user = up.id_user
                        WHERE p.id_pasien='$id'";
                $query = mysqli_query($link,$sql);
                if (mysqli_num_rows($query) > 0) {
                    $r = mysqli_fetch_assoc($query);
                    $pdf = new PDF('P','cm','A4');
                    $pdf->setJudul("Laporan data pasien " . ucwords($r['nama']));
                    $pdf->AddPage();
                    $pdf->AliasNbPages();
                    $pdf->SetFont('Arial','B',8);

                    $status = $r['stts'];
                    if ($status == 2) {
                        $msg = "Blok";
                    } elseif ($status == 1) {
                        $msg = "Aktif";
                    } else {
                        $msg = "Tidak Aktif";
                    }

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Kartu Keluarga',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['kk'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'NIK',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['nik'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Nama Pasien',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['nama']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Tempat, Tanggal Lahir',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['tmpt'] . ", " . $r['tgl'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Jenis Kelamin',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['jk'] == "P" ? "Pria" : "Wanita",0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Agama',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['agm'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Pekerjaan',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['pkj'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Golongan Darah',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['darah'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Username',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['user'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Status',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$msg,0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Alamat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.69,0.5,$r['almt'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Telepon',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,$r['tlpn'],0,1,'L');

                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-staff']['remote'] && $d == $enc['data-staff']['download'] && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['filter']) && isset($_GET['range'])) {
                $awal = $_GET['start'];
                $akhir = $_GET['end'];
                $filter = $_GET['filter'];
                $sql = "SELECT s.nip, r.display_rule AS level, s.name AS nama, s.sex AS jk, s.address AS almt, s.telp AS tlpn, u.username AS user, u.status AS stts FROM tb_staff AS s
                        INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff
                        INNER JOIN tb_users AS u ON u.id_user = us.id_user
                        INNER JOIN tb_rule_user AS ru ON ru.id_user = u.id_user
                        INNER JOIN tb_rules AS r ON r.id_rule = ru.id_rule";
                $res = "";
                $pdf = new PDF('P','cm','A4');
                if ($filter == 1) {
                    $day = date('d',strtotime('now'));
                    $sql .= " WHERE DAY(s.created_at) = '$day' ORDER BY s.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan harian data staff pada hari ".tanggal(date('N-Y-n-d',strtotime('now'))));
                } 
                elseif ($filter == 2) {
                    $mnt = date('m',strtotime('now'));
                    $yr = date('Y',strtotime('now'));
                    $sql .= " WHERE MONTH(s.created_at) = '$mnt' AND YEAR(s.created_at) = '$yr' ORDER BY s.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan bulanan data staff pada bulan ".bulan(date('m',strtotime('now')))." tahun ".$yr);
                }
                elseif ($filter == 3) {
                    $range = $_GET['range'];
                    $ganti = str_replace('/','-',$range);
                    $pch = explode(' - ',$ganti);
                    $range1 = strtotime($pch[0]);
                    $range2 = strtotime($pch[1]);
                    $sql .= " WHERE s.created_at BETWEEN FROM_UNIXTIME($range1) AND FROM_UNIXTIME($range2+(((60*60)*23)+(60*59))) LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan data staff pada hari ".tanggal(date('N-Y-n-d',strtotime($pch[0])))." sampai dengan hari ".tanggal(date('N-Y-n-d',strtotime($pch[1]))));
                }
                else {
                    $pdf->setJudul("Laporan data staff");
                    $sql .= " ORDER BY s.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                }
                $pdf->AddPage();
                $pdf->AliasNbPages();
                $pdf->SetFont('Arial','B',8);
                $no = 1;
                while ($r = mysqli_fetch_assoc($res)) {
                    $status = $r['stts'];
                    if ($status == 2) {
                        $msg = "Blok";
                    } elseif ($status == 1) {
                        $msg = "Aktif";
                    } else {
                        $msg = "Tidak Aktif";
                    }
                    
                    $pdf->SetX(1);
                    $pdf->Cell(0.62,0.5,'No.',0,0,'L');
                    $pdf->Cell(2.85,0.5,$no,0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'NIP',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['nip'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Level',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,ucwords($r['level']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Nama Staff',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,ucwords($r['nama']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Jenis Kelamin',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['jk'] == "P" ? "Pria" : "Wanita",0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Username',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['user'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Status',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$msg,0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Alamat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.89,0.5,$r['almt'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Telepon',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['tlpn'],0,1,'L');
                    
                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                    $no++;
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-staff']['remote'] && $d == $enc['data-staff']['unduh'] && isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT s.nip, r.display_rule AS level, s.name AS nama, s.sex AS jk, s.address AS almt, s.telp AS tlpn, u.username AS user, u.status AS stts FROM tb_staff AS s
                        INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff
                        INNER JOIN tb_users AS u ON u.id_user = us.id_user
                        INNER JOIN tb_rule_user AS ru ON ru.id_user = u.id_user
                        INNER JOIN tb_rules AS r ON r.id_rule = ru.id_rule
                        WHERE s.id_staff='$id'";
                $query = mysqli_query($link,$sql);
                if (mysqli_num_rows($query) > 0) {
                    $r = mysqli_fetch_assoc($query);
                    $pdf = new PDF('P','cm','A4');
                    $pdf->setJudul("Laporan data staff " . ucwords($r['nama']));
                    $pdf->AddPage();
                    $pdf->AliasNbPages();
                    $pdf->SetFont('Arial','B',8);

                    $status = $r['stts'];
                    if ($status == 2) {
                        $msg = "Blok";
                    } elseif ($status == 1) {
                        $msg = "Aktif";
                    } else {
                        $msg = "Tidak Aktif";
                    }

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'NIP',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['nip'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Level',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,ucwords($r['level']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Nama Staff',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,ucwords($r['nama']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Jenis Kelamin',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['jk'] == "P" ? "Pria" : "Wanita",0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Username',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['user'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Status',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$msg,0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Alamat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.89,0.5,$r['almt'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Telepon',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['tlpn'],0,1,'L');

                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-obat']['remote'] && $d == $enc['data-obat']['download'] && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['filter']) && isset($_GET['range'])) {
                $awal = $_GET['start'];
                $akhir = $_GET['end'];
                $filter = $_GET['filter'];
                $sql = "SELECT code_obat,name_obat,stock,price_buy,price_sale,name_unit,created_at,IF(TOTAL IS NULL, 0, TOTAL) AS DIPAKAI
                        FROM tb_obat AS o 
                        INNER JOIN tb_unit USING(id_unit)
                        LEFT JOIN (
                            SELECT code_obat, SUM(amount) AS TOTAL FROM tb_resep_detail GROUP BY code_obat
                        ) AS RD USING(code_obat)";
                $res = "";
                $pdf = new PDF('P','cm','A4');
                if ($filter == 1) {
                    $day = date('d',strtotime('now'));
                    $sql .= " WHERE DAY(created_at) = '$day' ORDER BY created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan harian data obat pada hari ".tanggal(date('N-Y-n-d',strtotime('now'))));
                } 
                elseif ($filter == 2) {
                    $mnt = date('m',strtotime('now'));
                    $yr = date('Y',strtotime('now'));
                    $sql .= " WHERE MONTH(created_at) = '$mnt' AND YEAR(created_at) = '$yr' ORDER BY created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan bulanan data obat pada bulan ".bulan(date('m',strtotime('now')))." tahun ".$yr);
                }
                elseif ($filter == 3) {
                    $range = $_GET['range'];
                    $ganti = str_replace('/','-',$range);
                    $pch = explode(' - ',$ganti);
                    $range1 = strtotime($pch[0]);
                    $range2 = strtotime($pch[1]);
                    $sql .= " WHERE created_at BETWEEN FROM_UNIXTIME($range1) AND FROM_UNIXTIME($range2+(((60*60)*23)+(60*59))) LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan data obat pada hari ".tanggal(date('N-Y-n-d',strtotime($pch[0])))." sampai dengan hari ".tanggal(date('N-Y-n-d',strtotime($pch[1]))));
                }
                else {
                    $pdf->setJudul("Laporan data obat");
                    $sql .= " ORDER BY created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                }

                $pdf->AddPage();
                $pdf->AliasNbPages();
                $pdf->SetFont('Arial','B',8);
                $pdf->SetX(1);
                $pdf->Cell(0.8,0.5,'No.',1,0,'C');
                $pdf->Cell(2.85,0.5,'Kode Obat',1,0,'C');
                $pdf->Cell(6.34,0.5,'Nama Obat',1,0,'C');
                $pdf->Cell(2.0,0.5,'Stok',1,0,'C');
                $pdf->Cell(2.0,0.5,'Harga Beli',1,0,'C');
                $pdf->Cell(2.0,0.5,'Harga Jual',1,0,'C');
                $pdf->Cell(3.1,0.5,'Keterangan',1,1,'C');
                $pdf->SetFont('Arial','',6.5);
                $i = 1;
                while ($r = mysqli_fetch_assoc($res)) {
                    $pdf->SetX(1);
                    $pdf->Cell(0.8,0.5,$i++.".",1,0,'C');
                    $pdf->Cell(2.85,0.5,$r['code_obat'],1,0,'L');
                    $pdf->Cell(6.34,0.5,ucwords(substr($r['name_obat'],0,25)),1,0,'L');
                    $pdf->Cell(2.0,0.5,number_format($r['stock'],0,'.','.') . " " . $r['name_unit'],1,0,'C');
                    $pdf->Cell(2.0,0.5,"Rp " . number_format($r['price_buy'],0,'.','.'),1,0,'C');
                    $pdf->Cell(2.0,0.5,"Rp " . number_format($r['price_sale'],0,'.','.'),1, 0,'C');
                    $dipakai = $r['DIPAKAI'] > 0 ? number_format($r['DIPAKAI'],0,'.','.') . " telah dijual" : "-";
                    $pdf->Cell(3.1,0.5,$dipakai,1, 1, 'C');
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-obat']['remote'] && $d == $enc['data-obat']['unduh'] && isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT code_obat,name_obat,stock,price_buy,price_sale,name_unit,description,created_at, IF(TOTAL IS NULL, 0, TOTAL) AS DIPAKAI 
                        FROM tb_obat AS o 
                        INNER JOIN tb_unit AS u USING(id_unit)
                        LEFT JOIN (
                            SELECT code_obat, SUM(amount) AS TOTAL FROM tb_resep_detail GROUP BY code_obat
                        ) AS RD USING(code_obat)
                        WHERE o.code_obat='$id'";
                $query = mysqli_query($link,$sql);
                if (mysqli_num_rows($query) > 0) {
                    $r = mysqli_fetch_assoc($query);
                    $pdf = new PDF('P','cm','A4');
                    $pdf->setJudul("Laporan data obat " . ucwords($r['name_obat']));
                    $pdf->AddPage();
                    $pdf->AliasNbPages();
                    $pdf->SetFont('Arial','B',8);

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Kode Obat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$r['code_obat'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Nama Obat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,ucwords($r['name_obat']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Stok',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,number_format($r['stock'],0,'.','.') . " " . $r['name_unit'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Harga Beli',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,"Rp " . number_format($r['price_buy'],0,'.','.'),0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Harga Jual',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,"Rp " . number_format($r['price_sale'],0,'.','.'),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Deskripsi',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.89,0.5,$r['description'],0,'J');
                    
                    $dipakai = $r['DIPAKAI'] > 0 ? number_format($r['DIPAKAI'],0,'.','.') . " " . $r['name_unit'] : "-";

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Telah dijual',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.89,0.5,$dipakai,0,1,'L');

                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-jadwal']['remote'] && $d == $enc['data-jadwal']['download'] && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['filter']) && isset($_GET['range'])) {
                $awal = $_GET['start'];
                $akhir = $_GET['end'];
                $filter = $_GET['filter'];
                $sql = "SELECT p.name_poli, d.name, j.jadwal, j.created_at, j.id_jadwal FROM tb_jadwal AS j
                        INNER JOIN tb_poli AS p ON p.id_poli = j.id_poli
                        INNER JOIN tb_dokter AS d ON d.id_dokter = j.id_dokter";
                $res = "";
                $pdf = new PDF('P','cm','A4');
                if ($filter == 1) {
                    $day = date('d',strtotime('now'));
                    $sql .= " WHERE DAY(j.created_at) = '$day' ORDER BY j.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan harian data jadwal dokter pada hari ".tanggal(date('N-Y-n-d',strtotime('now'))));
                } 
                elseif ($filter == 2) {
                    $mnt = date('m',strtotime('now'));
                    $yr = date('Y',strtotime('now'));
                    $sql .= " WHERE MONTH(j.created_at) = '$mnt' AND YEAR(j.created_at) = '$yr' ORDER BY j.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan bulanan data jadwal dokter pada bulan ".bulan(date('m',strtotime('now')))." tahun ".$yr);
                }
                elseif ($filter == 3) {
                    $range = $_GET['range'];
                    $ganti = str_replace('/','-',$range);
                    $pch = explode(' - ',$ganti);
                    $range1 = strtotime($pch[0]);
                    $range2 = strtotime($pch[1]);
                    $sql .= " WHERE j.created_at BETWEEN FROM_UNIXTIME($range1) AND FROM_UNIXTIME($range2+(((60*60)*23)+(60*59))) LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan data jadwal dokter pada hari ".tanggal(date('N-Y-n-d',strtotime($pch[0])))." sampai dengan hari ".tanggal(date('N-Y-n-d',strtotime($pch[1]))));
                }
                else {
                    $pdf->setJudul("Laporan data jadwal dokter");
                    $sql .= " ORDER BY j.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                }
                $pdf->AddPage();
                $pdf->AliasNbPages();
                $pdf->SetFont('Arial','B',8);
                $pdf->SetX(1);
                $pdf->Cell(0.8,0.5,'No.',1,0,'C');
                $pdf->Cell(7.0,0.5,'Nama Poli',1,0,'C');
                $pdf->Cell(7.0,0.5,'Nama Dokter',1,0,'C');
                $pdf->Cell(4.2,0.5,'Jadwal',1,1,'C');
                $pdf->SetFont('Arial','',6.5);
                $i = 1;
                while ($r = mysqli_fetch_assoc($res)) {
                    $pdf->SetX(1);
                    $pdf->Cell(0.8,0.5,$i++.".",1,0,'C');
                    $pdf->Cell(7.0,0.5,ucwords($r['name_poli']),1,0,'L');
                    $pdf->Cell(7.0,0.5,ucwords($r['name']),1,0,'L');
                    $pdf->Cell(4.2,0.5,str_replace(',','-',$r['jadwal']),1,1,'C');
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-rawat']['remote'] && $d == $enc['data-rawat']['download'] && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['filter']) && isset($_GET['range'])) {
                $awal = $_GET['start'];
                $akhir = $_GET['end'];
                $filter = $_GET['filter'];
                $sql = "SELECT p.name AS pasien, p.kk, p.sex AS jk, p.religion AS agm, p.worker AS krj, p.place_born AS lhr, p.date_born AS tgl_lhr, p.address AS almt,
                        d.name AS dokter, rj.keterangan AS ket, rj.created_at AS tgl, bp.no_bpjs AS nmr, b.name_bpjs AS bpjs, rj.code_resep AS kode FROM tb_rawat_jalan AS rj
                        INNER JOIN tb_pasien AS p ON p.id_pasien = rj.id_pasien
                        INNER JOIN tb_dokter AS d ON d.id_dokter = rj.id_dokter
                        INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                        INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs";
                $res = "";
                $pdf = new PDF('P','cm','A4');
                if ($filter == 1) {
                    $day = date('d',strtotime('now'));
                    $sql .= " WHERE DAY(rj.created_at) = '$day' ORDER BY rj.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan harian data rawat jalan pada hari ".tanggal(date('N-Y-n-d',strtotime('now'))));
                } 
                elseif ($filter == 2) {
                    $mnt = date('m',strtotime('now'));
                    $yr = date('Y',strtotime('now'));
                    $sql .= " WHERE MONTH(rj.created_at) = '$mnt' AND YEAR(rj.created_at) = '$yr' ORDER BY rj.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan bulanan data rawat jalan pada bulan ".bulan(date('m',strtotime('now')))." tahun ".$yr);
                }
                elseif ($filter == 3) {
                    $range = $_GET['range'];
                    $ganti = str_replace('/','-',$range);
                    $pch = explode(' - ',$ganti);
                    $range1 = strtotime($pch[0]);
                    $range2 = strtotime($pch[1]);
                    $sql .= " WHERE rj.created_at BETWEEN FROM_UNIXTIME($range1) AND FROM_UNIXTIME($range2+(((60*60)*23)+(60*59))) LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan data rawat jalan pada hari ".tanggal(date('N-Y-n-d',strtotime($pch[0])))." sampai dengan hari ".tanggal(date('N-Y-n-d',strtotime($pch[1]))));
                }
                else {
                    $pdf->setJudul("Laporan data rawat jalan");
                    $sql .= " ORDER BY rj.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                }
                $pdf->AddPage();
                $pdf->AliasNbPages();
                $pdf->SetFont('Arial','B',8);
                $no = 1;
                while ($r = mysqli_fetch_assoc($res)) {
                    $pdf->SetX(1);
                    $pdf->Cell(0.62,0.5,'No.',0,0,'L');
                    $pdf->Cell(2.85,0.5,$no,0,1,'L');

                    if ($r['jk'] == "P") {
                        $jk = "Pria";
                    } else {
                        $jk = "Wanita";
                    }

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Jenis BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.79,0.5,ucwords($r['bpjs']),0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'No. BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['nmr']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Kode Resep',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['kode']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Nama Pasien',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['pasien']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'No. Kartu Keluarga',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.79,0.5,ucwords($r['kk']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Jenis Kelamin',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($jk),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Tempat, Tanggal Lahir',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['lhr']) . ", " . date('d-m-Y',strtotime($r['tgl_lhr'])),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Agama',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['agm']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Pekerjaan',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['krj']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Alamat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.69,0.5,$r['almt'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Nama Dokter',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['dokter']),0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Keterangan',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.69,0.5,$r['ket'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Tanggal',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,date('d-m-Y',strtotime($r['tgl'])),0,1,'L');
                    
                    $pdf->ln(0.25);

                    $pdf->SetX(1);
                    $pdf->Cell(0.8,0.5,'No.',1,0,'C');
                    $pdf->Cell(9.0,0.5,'Nama Obat',1,0,'C');
                    $pdf->Cell(2.0,0.5,'Anjuran',1,0,'C');
                    $pdf->Cell(2.2,0.5,'Jumlah',1,0,'C');
                    $pdf->Cell(2.50,0.5,'Harga',1,0,'C');
                    $pdf->Cell(2.50,0.5,'Subtotal',1,1,'C');

                    $pdf->SetFont('Arial','B',8);

                    $kode = $r['kode'];
                    $query = "SELECT name_obat AS obat, anjuran AS anjr, price_sale AS hrg, amount AS jmlh, subtotal AS sub, name_unit AS unit FROM tb_resep_detail AS rd
                                INNER JOIN tb_obat AS o ON o.code_obat = rd.code_obat
                                INNER JOIN tb_unit AS u ON u.id_unit = o.id_unit
                                WHERE code_resep='$kode'";
                    $tablenyo = mysqli_query($link,$query);
                    $pdf->SetFont('Arial','',6.5);
                    $items = 0;
                    $total = 0;
                    $t = 1;
                    while ($row = mysqli_fetch_assoc($tablenyo)) {
                        $pdf->SetX(1);
                        $pdf->Cell(0.8, 0.5, $t . ".", 1, 0, 'C');
                        $pdf->Cell(9.0, 0.5, ucwords($row['obat']), 1, 0, 'L');
                        $pdf->Cell(2.0, 0.5, ucwords($row['anjr']), 1, 0, 'C');
                        $pdf->Cell(2.2, 0.5, $row['jmlh'] . " " . ucwords($row['unit']), 1, 0, 'C');
                        $pdf->Cell(2.50, 0.5, "Rp. " . number_format($row['hrg'], 0, ".", ","), 1, 0, 'C');
                        $pdf->Cell(2.50, 0.5, "Rp. " . number_format($row['sub'], 0, ".", ","), 1, 1, 'C');
                        $items += $row['jmlh'];
                        $total += $row['sub'];
                        $t++;
                    }
                    $pdf->SetX(1);
                    $pdf->Cell(11.8,0.5,'Jumlah',1,0,'R');
                    $pdf->Cell(2.2,0.5,$items,1,0,'C');
                    $pdf->Cell(2.50,0.5,"Total",1,0,'R');
                    $pdf->Cell(2.50,0.5,"Rp. " . number_format($total,0,".",","),1,1,'C');

                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                    
                    $no++;
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-rawat']['remote'] && $d == $enc['data-rawat']['unduh'] && isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT p.name AS pasien, p.kk, p.sex AS jk, p.religion AS agm, p.worker AS krj, p.place_born AS lhr, p.date_born AS tgl_lhr, p.address AS almt,
                        d.name AS dokter, rj.keterangan AS ket, rj.created_at AS tgl, bp.no_bpjs AS nmr, b.name_bpjs AS bpjs, rj.code_resep AS kode FROM tb_rawat_jalan AS rj
                        INNER JOIN tb_pasien AS p ON p.id_pasien = rj.id_pasien
                        INNER JOIN tb_dokter AS d ON d.id_dokter = rj.id_dokter
                        INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                        INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs
                        WHERE rj.id_rawat_jalan='$id'";
                $query = mysqli_query($link,$sql);
                if (mysqli_num_rows($query) > 0) {
                    $r = mysqli_fetch_assoc($query);
                    $pdf = new PDF('P','cm','A4');
                    $pdf->setJudul("Laporan data rawat jalan " . ucwords($r['pasien']));
                    $pdf->AddPage();
                    $pdf->AliasNbPages();
                    $pdf->SetFont('Arial','B',8);

                    if ($r['jk'] == "P") {
                        $jk = "Pria";
                    } else {
                        $jk = "Wanita";
                    }

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Jenis BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.79,0.5,ucwords($r['bpjs']),0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'No. BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['nmr']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Kode Resep',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['kode']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Nama Pasien',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['pasien']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'No. Kartu Keluarga',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.79,0.5,ucwords($r['kk']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Jenis Kelamin',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($jk),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Tempat, Tanggal Lahir',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['lhr']) . ", " . date('d-m-Y',strtotime($r['tgl_lhr'])),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Agama',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['agm']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Pekerjaan',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['krj']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Alamat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.69,0.5,$r['almt'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Nama Dokter',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['dokter']),0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Keterangan',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.69,0.5,$r['ket'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Tanggal',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,date('d-m-Y',strtotime($r['tgl'])),0,1,'L');

                    $pdf->ln(0.25);

                    $pdf->SetX(1);
                    $pdf->Cell(0.8,0.5,'No.',1,0,'C');
                    $pdf->Cell(9.0,0.5,'Nama Obat',1,0,'C');
                    $pdf->Cell(2.0,0.5,'Anjuran',1,0,'C');
                    $pdf->Cell(2.2,0.5,'Jumlah',1,0,'C');
                    $pdf->Cell(2.50,0.5,'Harga',1,0,'C');
                    $pdf->Cell(2.50,0.5,'Subtotal',1,1,'C');

                    $pdf->SetFont('Arial','B',8);

                    $kode = $r['kode'];
                    $query = "SELECT name_obat AS obat, anjuran AS anjr, price_sale AS hrg, amount AS jmlh, subtotal AS sub, name_unit AS unit FROM tb_resep_detail AS rd
                                INNER JOIN tb_obat AS o ON o.code_obat = rd.code_obat
                                INNER JOIN tb_unit AS u ON u.id_unit = o.id_unit
                                WHERE code_resep='$kode'";
                    $tablenyo = mysqli_query($link,$query);
                    $pdf->SetFont('Arial','',6.5);
                    $items = 0;
                    $total = 0;
                    $t = 1;
                    while ($row = mysqli_fetch_assoc($tablenyo)) {
                        $pdf->SetX(1);
                        $pdf->Cell(0.8, 0.5, $t . ".", 1, 0, 'C');
                        $pdf->Cell(9.0, 0.5, ucwords($row['obat']), 1, 0, 'L');
                        $pdf->Cell(2.0, 0.5, ucwords($row['anjr']), 1, 0, 'C');
                        $pdf->Cell(2.2, 0.5, $row['jmlh'] . " " . ucwords($row['unit']), 1, 0, 'C');
                        $pdf->Cell(2.50, 0.5, "Rp. " . number_format($row['hrg'], 0, ".", ","), 1, 0, 'C');
                        $pdf->Cell(2.50, 0.5, "Rp. " . number_format($row['sub'], 0, ".", ","), 1, 1, 'C');
                        $items += $row['jmlh'];
                        $total += $row['sub'];
                        $t++;
                    }
                    $pdf->SetX(1);
                    $pdf->Cell(11.8,0.5,'Jumlah',1,0,'R');
                    $pdf->Cell(2.2,0.5,$items,1,0,'C');
                    $pdf->Cell(2.50,0.5,"Total",1,0,'R');
                    $pdf->Cell(2.50,0.5,"Rp. " . number_format($total,0,".",","),1,1,'C');

                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-resep']['remote'] && $d == $enc['data-resep']['download'] && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['filter']) && isset($_GET['range'])) {
                $awal = $_GET['start'];
                $akhir = $_GET['end'];
                $filter = $_GET['filter'];
                $sql = "SELECT r.code_resep AS kode, d.name AS dokter, p.name AS pasien, r.created_at AS tgl, b.name_bpjs AS bpjs, bp.no_bpjs AS nmr FROM tb_resep AS r
                        INNER JOIN tb_dokter AS d ON d.id_dokter = r.id_dokter
                        INNER JOIN tb_pasien AS p ON p.id_pasien = r.id_pasien
                        INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                        INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs";
                $res = "";
                $pdf = new PDF('P','cm','A4');
                if ($filter == 1) {
                    $day = date('d',strtotime('now'));
                    $sql .= " WHERE DAY(r.created_at) = '$day' ORDER BY r.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan harian data resep pada hari ".tanggal(date('N-Y-n-d',strtotime('now'))));
                } 
                elseif ($filter == 2) {
                    $mnt = date('m',strtotime('now'));
                    $yr = date('Y',strtotime('now'));
                    $sql .= " WHERE MONTH(r.created_at) = '$mnt' AND YEAR(r.created_at) = '$yr' ORDER BY r.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan bulanan data resep pada bulan ".bulan(date('m',strtotime('now')))." tahun ".$yr);
                }
                elseif ($filter == 3) {
                    $range = $_GET['range'];
                    $ganti = str_replace('/','-',$range);
                    $pch = explode(' - ',$ganti);
                    $range1 = strtotime($pch[0]);
                    $range2 = strtotime($pch[1]);
                    $sql .= " WHERE r.created_at BETWEEN FROM_UNIXTIME($range1) AND FROM_UNIXTIME($range2+(((60*60)*23)+(60*59))) LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan data resep pada hari ".tanggal(date('N-Y-n-d',strtotime($pch[0])))." sampai dengan hari ".tanggal(date('N-Y-n-d',strtotime($pch[1]))));
                }
                else {
                    $pdf->setJudul("Laporan data resep");
                    $sql .= " ORDER BY r.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                }
                $pdf->AddPage();
                $pdf->AliasNbPages();
                $pdf->SetFont('Arial','B',8);
                $no = 1;
                while ($r = mysqli_fetch_assoc($res)) {
                    $pdf->SetX(1);
                    $pdf->Cell(0.62,0.5,'No.',0,0,'L');
                    $pdf->Cell(2.85,0.5,$no,0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Kode Resep',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,$r['kode'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Nama Pasien',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,$r['pasien'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Jenis BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,$r['bpjs'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'No. BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,$r['nmr'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Nama Dokter',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,$r['dokter'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Tanggal Pembelian',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,date('d-m-Y, H:i',strtotime($r['tgl'])),0,1,'L');

                    $pdf->ln(0.25);

                    $pdf->SetX(1);
                    $pdf->Cell(0.8,0.5,'No.',1,0,'C');
                    $pdf->Cell(9.0,0.5,'Nama Obat',1,0,'C');
                    $pdf->Cell(2.0,0.5,'Anjuran',1,0,'C');
                    $pdf->Cell(2.2,0.5,'Jumlah',1,0,'C');
                    $pdf->Cell(2.50,0.5,'Harga',1,0,'C');
                    $pdf->Cell(2.50,0.5,'Subtotal',1,1,'C');
                    
                    $kode = $r['kode'];
                    $sqlTable ="SELECT r.code_resep, o.name_obat AS obat, rd.amount AS jmlh, o.price_sale AS hrg, rd.subtotal AS sub, name_unit AS unit, rd.anjuran AS anjr FROM tb_resep AS r
                                INNER JOIN tb_resep_detail AS rd ON rd.code_resep = r.code_resep
                                INNER JOIN tb_obat AS o ON o.code_obat = rd.code_obat
                                INNER JOIN tb_unit AS u ON u.id_unit = o.id_unit
                                WHERE r.code_resep='$kode'";
                    $tablenyo = mysqli_query($link,$sqlTable);
                    $pdf->SetFont('Arial','',6.5);
                    $items = 0;
                    $total = 0;
                    if (mysqli_num_rows($tablenyo) > 0) {
                        $t = 1;
                        while ($row = mysqli_fetch_assoc($tablenyo)) {
                            $pdf->SetX(1);
                            $pdf->Cell(0.8,0.5,$t.".",1,0,'C');
                            $pdf->Cell(9.0, 0.5, ucwords($row['obat']), 1, 0, 'L');
                            $pdf->Cell(2.0, 0.5, ucwords($row['anjr']), 1, 0, 'C');
                            $pdf->Cell(2.2,0.5,$row['jmlh'] . " " . ucwords($row['unit']),1,0,'C');
                            $pdf->Cell(2.50,0.5,"Rp. ". number_format($row['hrg'],0,".",","),1,0,'C');
                            $pdf->Cell(2.50,0.5,"Rp. ". number_format($row['sub'],0,".",","),1,1,'C');
                            $items+=$row['jmlh'];
                            $total+=$row['sub'];
                            $t++;
                        }
                    } else {
                        $pdf->SetX(1);
                        $pdf->Cell(19,0.5,'Data tidak tersedia',1,1,'C');
                    }
                    $pdf->SetX(1);
                    $pdf->Cell(11.8,0.5,'Jumlah',1,0,'R');
                    $pdf->Cell(2.2,0.5,$items,1,0,'C');
                    $pdf->Cell(2.50,0.5,"",1,0,'C');
                    $pdf->Cell(2.50,0.5,"Rp. " . number_format($total,0,".",","),1,1,'C');

                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                    $no++;
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-resep']['remote'] && $d == $enc['data-resep']['unduh'] && isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT r.code_resep AS kode, d.name AS dokter, p.name AS pasien, o.name_obat AS obat, rd.amount AS jmlh, bp.no_bpjs AS nmr,
                        o.price_sale AS hrg, rd.subtotal AS sub, name_unit AS unit, r.created_at AS tgl, rd.anjuran AS anjr, b.name_bpjs AS bpjs FROM tb_resep AS r
                        INNER JOIN tb_dokter AS d ON d.id_dokter = r.id_dokter
                        INNER JOIN tb_pasien AS p ON p.id_pasien = r.id_pasien
                        INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                        INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs
                        INNER JOIN tb_resep_detail AS rd ON rd.code_resep = r.code_resep
                        INNER JOIN tb_obat AS o ON o.code_obat = rd.code_obat
                        INNER JOIN tb_unit AS u ON u.id_unit = o.id_unit
                        WHERE r.code_resep='$id'";
                $query = mysqli_query($link,$sql);
                if (mysqli_num_rows($query) > 0) {
                    $r = mysqli_fetch_assoc($query);
                    $pdf = new PDF('P','cm','A4');
                    $pdf->setJudul("Laporan resep " . ucwords($r['pasien']));
                    $pdf->AddPage();
                    $pdf->AliasNbPages();
                    $pdf->SetFont('Arial','B',8);
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Kode Resep',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,$r['kode'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Nama Pasien',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,$r['pasien'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Jenis BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,$r['bpjs'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'No. BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,$r['nmr'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Nama Dokter',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,$r['dokter'],0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.01,0.5,'Tanggal Pembelian',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(6.0,0.5,date('d-m-Y, H:i',strtotime($r['tgl'])),0,1,'L');
                    
                    $pdf->ln(0.25);

                    $pdf->SetX(1);
                    $pdf->Cell(0.8,0.5,'No.',1,0,'C');
                    $pdf->Cell(9.0,0.5,'Nama Obat',1,0,'C');
                    $pdf->Cell(2.0,0.5,'Anjuran',1,0,'C');
                    $pdf->Cell(2.2,0.5,'Jumlah',1,0,'C');
                    $pdf->Cell(2.50,0.5,'Harga',1,0,'C');
                    $pdf->Cell(2.50,0.5,'Subtotal',1,1,'C');

                    $pdf->SetFont('Arial','',6.5);

                    $kode = $r['kode'];
                    $sqlTable ="SELECT r.code_resep, o.name_obat AS obat, rd.amount AS jmlh, o.price_sale AS hrg, rd.subtotal AS sub, name_unit AS unit, rd.anjuran AS anjr FROM tb_resep AS r
                                INNER JOIN tb_resep_detail AS rd ON rd.code_resep = r.code_resep
                                INNER JOIN tb_obat AS o ON o.code_obat = rd.code_obat
                                INNER JOIN tb_unit AS u ON u.id_unit = o.id_unit
                                WHERE r.code_resep='$kode'";
                    $tablenyo = mysqli_query($link,$sqlTable);
                    $pdf->SetFont('Arial','',6.5);
                    $items = 0;
                    $total = 0;
                    $t = 1;
                    while ($row = mysqli_fetch_assoc($tablenyo)) {
                        $pdf->SetX(1);
                        $pdf->Cell(0.8, 0.5, $t . ".", 1, 0, 'C');
                        $pdf->Cell(9.0, 0.5, ucwords($row['obat']), 1, 0, 'L');
                        $pdf->Cell(2.0, 0.5, ucwords($row['anjr']), 1, 0, 'C');
                        $pdf->Cell(2.2, 0.5, $row['jmlh'] . " " . ucwords($row['unit']), 1, 0, 'C');
                        $pdf->Cell(2.50, 0.5, "Rp. " . number_format($row['hrg'], 0, ".", ","), 1, 0, 'C');
                        $pdf->Cell(2.50, 0.5, "Rp. " . number_format($row['sub'], 0, ".", ","), 1, 1, 'C');
                        $items += $row['jmlh'];
                        $total += $row['sub'];
                        $t++;
                    }
                    $pdf->SetX(1);
                    $pdf->Cell(11.8,0.5,'Jumlah',1,0,'R');
                    $pdf->Cell(2.2,0.5,$items,1,0,'C');
                    $pdf->Cell(2.50,0.5,"Total",1,0,'R');
                    $pdf->Cell(2.50,0.5,"Rp. " . number_format($total,0,".",","),1,1,'C');

                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-rekam_medis']['remote'] && $d == $enc['data-rekam_medis']['download'] && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['filter']) && isset($_GET['range'])) {
                $awal = $_GET['start'];
                $akhir = $_GET['end'];
                $filter = $_GET['filter'];
                $sql = "SELECT p.name AS pasien, p.kk, p.sex AS jk, p.religion AS agm, p.worker AS krj, p.place_born AS lhr, p.date_born AS tgl_lhr, p.address AS almt,
                        d.name AS dokter, rm.keterangan AS ket, rm.created_at AS tgl, bp.no_bpjs AS nmr, b.name_bpjs AS bpjs FROM tb_rekam_medis AS rm
                        INNER JOIN tb_pasien AS p ON p.id_pasien = rm.id_pasien
                        INNER JOIN tb_dokter AS d ON d.id_dokter = rm.id_dokter
                        INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                        INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs";
                $res = "";
                $pdf = new PDF('P','cm','A4');
                if ($filter == 1) {
                    $day = date('d',strtotime('now'));
                    $sql .= " WHERE DAY(rm.created_at) = '$day' ORDER BY rm.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan harian data rekam medis pada hari ".tanggal(date('N-Y-n-d',strtotime('now'))));
                } 
                elseif ($filter == 2) {
                    $mnt = date('m',strtotime('now'));
                    $yr = date('Y',strtotime('now'));
                    $sql .= " WHERE MONTH(rm.created_at) = '$mnt' AND YEAR(rm.created_at) = '$yr' ORDER BY rm.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan bulanan data rekam medis pada bulan ".bulan(date('m',strtotime('now')))." tahun ".$yr);
                }
                elseif ($filter == 3) {
                    $range = $_GET['range'];
                    $ganti = str_replace('/','-',$range);
                    $pch = explode(' - ',$ganti);
                    $range1 = strtotime($pch[0]);
                    $range2 = strtotime($pch[1]);
                    $sql .= " WHERE rm.created_at BETWEEN FROM_UNIXTIME($range1) AND FROM_UNIXTIME($range2+(((60*60)*23)+(60*59))) LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                    $pdf->setJudul("Laporan data rekam medis pada hari ".tanggal(date('N-Y-n-d',strtotime($pch[0])))." sampai dengan hari ".tanggal(date('N-Y-n-d',strtotime($pch[1]))));
                }
                else {
                    $pdf->setJudul("Laporan data rekam medis");
                    $sql .= " ORDER BY rm.created_at DESC LIMIT $awal,$akhir";
                    $res = mysqli_query($link,$sql);
                }
                $pdf->AddPage();
                $pdf->AliasNbPages();
                $pdf->SetFont('Arial','B',8);
                $no = 1;
                while ($r = mysqli_fetch_assoc($res)) {
                    if ($r['jk'] == "P") {
                        $jk = "Pria";
                    } else {
                        $jk = "Wanita";
                    }
                    
                    $pdf->SetX(1);
                    $pdf->Cell(0.62,0.5,'No.',0,0,'L');
                    $pdf->Cell(2.85,0.5,$no,0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Jenis BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.79,0.5,ucwords($r['bpjs']),0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'No. BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['nmr']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Nama Pasien',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['pasien']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'No. Kartu Keluarga',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.79,0.5,ucwords($r['kk']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Jenis Kelamin',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($jk),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Tempat, Tanggal Lahir',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['lhr']) . ", " . date('d-m-Y',strtotime($r['tgl_lhr'])),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Agama',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['agm']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Pekerjaan',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['krj']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Alamat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.69,0.5,$r['almt'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Nama Dokter',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['dokter']),0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Keterangan',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.69,0.5,$r['ket'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Tanggal',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,date('d-m-Y',strtotime($r['tgl'])),0,1,'L');
                    
                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                    $no++;
                }
                $pdf->Output('',$file.".pdf","I",true);
            } elseif ($f == $enc['data-rekam_medis']['remote'] && $d == $enc['data-rekam_medis']['unduh'] && isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT p.name AS pasien, p.kk, p.sex AS jk, p.religion AS agm, p.worker AS krj, p.place_born AS lhr, p.date_born AS tgl_lhr, p.address AS almt,
                        d.name AS dokter, rm.keterangan AS ket, rm.created_at AS tgl, bp.no_bpjs AS nmr, b.name_bpjs AS bpjs FROM tb_rekam_medis AS rm
                        INNER JOIN tb_pasien AS p ON p.id_pasien = rm.id_pasien
                        INNER JOIN tb_dokter AS d ON d.id_dokter = rm.id_dokter
                        INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                        INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs
                        WHERE rm.id_rekam_medis='$id'";
                $query = mysqli_query($link,$sql);
                if (mysqli_num_rows($query) > 0) {
                    $r = mysqli_fetch_assoc($query);
                    $pdf = new PDF('P','cm','A4');
                    $pdf->setJudul("Laporan data rekam medis " . ucwords($r['pasien']));
                    $pdf->AddPage();
                    $pdf->AliasNbPages();
                    $pdf->SetFont('Arial','B',8);

                    if ($r['jk'] == "P") {
                        $jk = "Pria";
                    } else {
                        $jk = "Wanita";
                    }

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Jenis BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.79,0.5,ucwords($r['bpjs']),0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'No. BPJS',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['nmr']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Nama Pasien',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['pasien']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'No. Kartu Keluarga',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.79,0.5,ucwords($r['kk']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Jenis Kelamin',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($jk),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Tempat, Tanggal Lahir',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['lhr']) . ", " . date('d-m-Y',strtotime($r['tgl_lhr'])),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Agama',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['agm']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Pekerjaan',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['krj']),0,1,'L');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Alamat',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.69,0.5,$r['almt'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Nama Dokter',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,ucwords($r['dokter']),0,1,'L');
                    
                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Keterangan',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->MultiCell(15.69,0.5,$r['ket'],0,'J');

                    $pdf->SetX(1);
                    $pdf->Cell(3.21,0.5,'Tanggal',0,0,'L');
                    $pdf->Cell(0.1,0.5,':',0,0,'C');
                    $pdf->Cell(15.69,0.5,date('d-m-Y',strtotime($r['tgl'])),0,1,'L');

                    $pdf->ln(0.25);
                    $pdf->SetFont('Arial','B',8);
                }
                $pdf->Output('',$file.".pdf","I",true);
            } else {
                $pdf = new PDF('P','cm','A4');
            }
            closedb();
        } else {
            echo "Invalid Url";
        }
    }
    