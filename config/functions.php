<?php

function tanggal($tgl)
{
    $hari = array(
        1 => 'Senen', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Minggu'
    );
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $arr = explode('-', $tgl);
    return $hari[(int)$arr[0]] . ', ' . $arr[3] . '-' . $bulan[(int)$arr[2]] . '-' . $arr[1];
}

function bulan($bln)
{
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    return $bulan[(int)$bln];
}

function hasPermit($p)
{
    global $link;
    $r = $_SESSION['level'];
    $sqlevel = "SELECT tb_rules.*, tb_rule_permission.*, tb_permissions.* FROM tb_rules
                INNER JOIN tb_rule_permission ON tb_rule_permission.id_rule = tb_rules.id_rule
                INNER JOIN tb_permissions ON tb_permissions.id_permission = tb_rule_permission.id_permission
                WHERE tb_rules.name_rule='$r' AND tb_permissions.name_permission='$p'";
    $exc = mysqli_query($link, $sqlevel);
    return mysqli_num_rows($exc) == 1 ? true : false;
}

function execute($sql)
{
    global $link;
    $query = null;
    $query = mysqli_query($link, $sql);
    return $query;
}

function result($code, $msg)
{
    return array(
        'code' => $code,
        'message' => $msg,
    );
}

function tombol_tambah($ex = 1, array $box = null)
{
    $dl = "";
    if ($ex == 1) {
        $dl .= '
        <a id="' . $box['box-download']['id'] . '" name="' . $box['box-download']['name'] . '" class="' . $box['box-download']['class'] . '" title="' . $box['box-download']['title'] . '" data-remote="' . $box['box-download']['data-remote'] . '" data-target="' . $box['box-download']['data-target'] . '">
            <i class="fa fa-file-pdf-o"></i>&nbsp;
            <span>' . $box['box-download']['title'] . '</span>
        </a>
        ';
    }
    $html = '
        <div class="box box-solid box-default">
            <div class="box-body">
                <a id="' . $box['box-add']['id'] . '" name="' . $box['box-add']['name'] . '" class="' . $box['box-add']['class'] . '" title="' . $box['box-add']['title'] . '" data-target="' . $box['box-add']['data-target'] . '">
                    <i class="fa fa-plus"></i>&nbsp;
                    <span>' . $box['box-add']['title'] . '</span>
                </a>
                ' . $dl . '
            </div>
        </div>
    ';
    return $html;
}

function tombol_download(array $box = null) {
    $dl = '
        <div class="box box-solid box-default">
            <div class="box-body">
                <a id="' . $box['box-download']['id'] . '" name="' . $box['box-download']['name'] . '" class="' . $box['box-download']['class'] . '" title="' . $box['box-download']['title'] . '" data-remote="' . $box['box-download']['data-remote'] . '" data-target="' . $box['box-download']['data-target'] . '">
                    <i class="fa fa-file-pdf-o"></i>&nbsp;
                    <span>' . $box['box-download']['title'] . '</span>
                </a>
            </div>
        </div>';
    return $dl;
}

/**
 * Method table
 * @param array $data table
 * @return $html
 */
function table(array $data = null)
{
    $th = null;
    foreach ($data['field'] as $k => $v) {
        $th .= "<th>" . $v . "</th>\n";
    }
    $html = '<div id="boxnyo" class="box box-solid box-default">
                <div class="box-body">
                    <div class="col-md-12 col-xs-12 table-responsive">
                        <table id="' . $data['id'] . '" name="' . $data['name'] . '" class="' . $data['class'] . '" data-remote="' . $data['data-remote'] . '" data-target="' . $data['data-target'] . '" data-laporan="' . @$data['data-laporan'] . '">
                            <thead>
                                <tr> 
                                    ' . $th . '
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>';
    return $html;
}

function kodeobat($nama)
{
    global $link;
    try {
        if (!empty($nama)) {
            $delSpace = trim(ucwords($nama));
            $rplc = preg_replace("([^A-Za-z0-9\s+]+)", "", $delSpace);
            $pecahkanNama = explode(' ', $rplc);
            $joinNama = "";
            $i = 0;
            foreach ($pecahkanNama as $key => $value) {
                $i++;
                if ($i < 3) $joinNama .= substr($pecahkanNama[$key], 0, 1);
            }
            $thn = date('Y', strtotime('now'));
            $cari = "/" . $joinNama;
            $thnny = "/" . $thn;
            $sqlJmlh = "SELECT COUNT(code_obat) AS jmlh FROM tb_obat";
            $sqlCari = "SELECT MAX(code_obat) as TERAKHIR FROM tb_obat";
            $sqlJmlh .= " WHERE SUBSTRING_INDEX(code_obat,'/',2) LIKE '%$cari%' AND SUBSTRING_INDEX(code_obat,'/',3) LIKE '%$thnny%'";
            $sqlCari .= " WHERE SUBSTRING_INDEX(code_obat,'/',2) LIKE '%$cari%' AND SUBSTRING_INDEX(code_obat,'/',3) LIKE '%$thnny%'";
            $execJmlh = mysqli_query($link, $sqlJmlh);
            $execCari = mysqli_query($link, $sqlCari);
            $r = mysqli_fetch_assoc($execJmlh);
            if ($r['jmlh'] > 0) {
                $c = mysqli_fetch_assoc($execCari);
                $s = explode('/', $c['TERAKHIR']);
                $las = $s[3];
            } else {
                $las = 0;
            }
            $next = $las + 1;
            $kode = "OBAT/" . $joinNama . "/" . $thn . "/" . sprintf('%05s', $next);
            return $kode;
        } else {
            throw new Exception("Parameter {0} is null !", 1);
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

function slug_title($title) {
    global $link;
    $slug = str_replace(' ','-',strtolower($title));
    $sqlJmlh = "SELECT COUNT(slug) AS jmlh FROM tb_berita";
    //$sqlCari = "SELECT MAX(slug) as TERAKHIR FROM tb_berita";
    $sqlJmlh .= " WHERE slug LIKE '$slug%'";
    //$sqlCari .= " WHERE slug LIKE '$slug%'";
    $execJmlh = mysqli_query($link, $sqlJmlh);
    //$execCari = mysqli_query($link, $sqlCari);
    $r = mysqli_fetch_assoc($execJmlh);
    if (mysqli_num_rows($execJmlh) > 0) {
        $las = $r['jmlh'];
    } else {
        $las = 0;
    }
    if ($las == 0) {
        $kode = $slug;
    } else {
        $next = $las + 1;
        $kode = $slug . "-" . $next;
    }
    return $kode;
}

function gambar($cover) {
    $image = '';
    if ($cover != "") {
        if (strpos($cover, 'img') == true) {
            $a = explode('<img', $cover);
            $b = explode('=\"\"', $a[1]);
            $c = explode('"', $b[0]);
            $d = explode('../', $c[1])[3];
            $e = explode('\\', $d);
            $image = $e[0];
        } else {
            $image = '';
        }
    }
    return $image;
}

function check_gambar($cover) {
    $html = '';
    if ($cover != "") {
        if (strpos($cover, 'img') == true) {
            $html = $cover;
        } else {
            $html = '';
        }
    }
    return $html;
}

function koderesep() {
    global $link;
    try {
        $thn = date('Y', strtotime('now'));
        $thnny = "/" . $thn;
        $sqlJmlh = "SELECT COUNT(code_resep) AS jmlh FROM tb_resep";
        $sqlCari = "SELECT MAX(code_resep) as TERAKHIR FROM tb_resep";
        $sqlJmlh .= " WHERE SUBSTRING_INDEX(code_resep,'/',3) LIKE '%$thnny%'";
        $sqlCari .= " WHERE SUBSTRING_INDEX(code_resep,'/',3) LIKE '%$thnny%'";
        $execJmlh = mysqli_query($link, $sqlJmlh);
        $execCari = mysqli_query($link, $sqlCari);
        $r = mysqli_fetch_assoc($execJmlh);
        if ($r['jmlh'] > 0) {
            $c = mysqli_fetch_assoc($execCari);
            $s = explode('/', $c['TERAKHIR']);
            $las = $s[3];
        } else {
            $las = 0;
        }
        $next = $las + 1;
        $kode = "TRK/RESEP/" . $thn . "/" . sprintf('%05s', $next);
        return $kode;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

function kodepayment() {
    global $link;
    try {
        $thn = date('Y', strtotime('now'));
        $thnny = "/" . $thn;
        $sqlJmlh = "SELECT COUNT(code_pembayaran) AS jmlh FROM tb_pembayaran";
        $sqlCari = "SELECT MAX(code_pembayaran) as TERAKHIR FROM tb_pembayaran";
        $sqlJmlh .= " WHERE SUBSTRING_INDEX(code_pembayaran,'/',3) LIKE '%$thnny%'";
        $sqlCari .= " WHERE SUBSTRING_INDEX(code_pembayaran,'/',3) LIKE '%$thnny%'";
        $execJmlh = mysqli_query($link, $sqlJmlh);
        $execCari = mysqli_query($link, $sqlCari);
        $r = mysqli_fetch_assoc($execJmlh);
        if ($r['jmlh'] > 0) {
            $c = mysqli_fetch_assoc($execCari);
            $s = explode('/', $c['TERAKHIR']);
            $las = $s[3];
        } else {
            $las = 0;
        }
        $next = $las + 1;
        $kode = "TRK/PEMBAYARAN/" . $thn . "/" . sprintf('%05s', $next);
        return $kode;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

