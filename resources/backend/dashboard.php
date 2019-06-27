<?php include_once '../master/header.php'; ?>

        <div class="content-wrapper">

            <section class="content-header">
                <h1>
                    <?php
                        if (FIRST_PART == "dashboard" && SECOND_PART == "" && THIRD_PART == "") {
                            echo ucwords(str_replace('-', ' ', FIRST_PART));
                        }
                        elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[0]))) {
                            if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[0]))) {
                                echo $menu[0] ." - ".$submenu[0];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[1]))) {
                                echo $menu[0] ." - ".$submenu[1];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[2]))) {
                                echo $menu[0] ." - ".$submenu[2];
                            }
                            else {
                                echo "Error";
                            }
                        }
                        elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[1]))) {
                            if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[3]))) {
                                echo $menu[1] ." - ".$submenu[3];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[4]))) {
                                echo $menu[1] ." - ".$submenu[4];
                            }
                            else {
                                echo "Error";
                            }
                        }
                        elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2]))) {
                            if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[5]))) {
                                echo $menu[2] ." - ".$submenu[5];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[6]))) {
                                echo $menu[2] ." - ".$submenu[6];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[7]))) {
                                echo $menu[2] ." - ".$submenu[7];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[8]))) {
                                echo $menu[0] ." - ".$submenu[8];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[9]))) {
                                echo $menu[2] ." - ".$submenu[9];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[10]))) {
                                echo $menu[2] ." - ".$submenu[10];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[11]))) {
                                echo $menu[2] ." - ".$submenu[11];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[12]))) {
                                echo $menu[2] ." - ".$submenu[12];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[13]))) {
                                echo $menu[2] ." - ".$submenu[13];
                            }
                            elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[14]))) {
                                echo $menu[2] ." - ".$submenu[14];
                            }
                            else {
                                echo "Error";
                            }
                        }
                        elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[3]))) {
                            if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[15]))) {
                                echo $menu[3] ." - ".$submenu[15];
                            }
                            else {
                                echo "Error";
                            }
                        }
                        elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[4]))) {
                            if ($_REQUEST['submenu'] == "@".strtolower($_SESSION['username'])) {
                                echo $menu[4];
                            }
                            else {
                                echo "Error";
                            }
                        }
                        elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[5]))) {
                            if ($_REQUEST['submenu'] == "@".strtolower($_SESSION['username'])) {
                                echo $menu[5];
                            }
                            else {
                                echo "Error";
                            }
                        }
                        else {
                            echo "Error";
                        }
                    ?>
                    <small><b><?= tanggal(date('N-Y-n-d',strtotime('now'))) ?> <span id="clock"></span></b></small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?= BASE_URL . 'dashboard/'; ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <?php
                        if (FIRST_PART == "dashboard" && SECOND_PART == "" && THIRD_PART == "") {
                            ?><li class="active">Here</li><?php
                        }
                        elseif (FIRST_PART == "dashboard" && SECOND_PART != "" && THIRD_PART != "") {
                            for ($i=0; $i < count($menu) - 2; $i++) { 
                                if ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[$i]))) {
                                    for ($j=0; $j < count($submenu); $j++) { 
                                        if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[$j]))) {
                                            ?>  
                                                <li><a href="javascript:void()"><?= ucwords(str_replace('-', ' ', $_REQUEST['menu'])) ?></a></li>
                                                <li><a href="javascript:void()"><?= ucwords(str_replace('-', ' ', $_REQUEST['submenu'])) ?></a></li>
                                                <li class="active">Here</li>
                                            <?php
                                            break;
                                        }
                                    }
                                    break;
                                }
                            }
                            if ($_REQUEST['menu'] == (strtolower(str_replace(' ', '-', $menu[4])) || strtolower(str_replace(' ', '-', $menu[5]))) && strpos(THIRD_PART, '@') !== false) {
                                ?>
                                    <li><a href="javascript:void()"><?= ucwords(str_replace('-', ' ', $_REQUEST['menu'])) ?></a></li>
                                    <li class="active">Here</li>
                                <?php
                            }
                        }
                        else {
                            ?><li><a href="javascript:void()">Error</a></li><?php
                        }
                    ?>
                </ol>
            </section>
    
            <!-- Main content -->
            <section class="content container-fluid" id="content-js">
                <?php
                    // Dashboard //
                    if (FIRST_PART == "dashboard" && SECOND_PART == "" && THIRD_PART == "") { ?>
                        <?php
                            if ($dashboard == "staff") {
                            ?>
                            <div class="callout callout-info">
                                <h4 class="text-center">Selamat Datang Di Sistem Informasi Puskesmas</h4>
                            </div>
                            <div class="row" id="data-box"></div>
                            <?php
                            } elseif ($dashboard == "pasien") {
                                include_once 'pasien.php';
                            } elseif ($dashboard == "dokter") {
                                include_once 'dokter.php';
                            }
                        ?>
                <?php }
                    // Manajemen Pengguna //
                    elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[0])) && hasPermit('menu_user')) {
                        if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[0]))) {
                            if (hasPermit('submenu_dokter')) {
                                if (hasPermit($static['data-dokter']['permissions'][0])) { 
                                    echo tombol_tambah(1,$static['data-dokter']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-dokter']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/dokter.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[1]))) {
                            if (hasPermit('submenu_pasien')) {
                                if (hasPermit($static['data-pasien']['permissions'][0])) { 
                                    echo tombol_tambah(1,$static['data-pasien']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-pasien']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/pasien.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[2]))) {
                            if (hasPermit('submenu_staff')) {
                                if (hasPermit($static['data-staff']['permissions'][0])) { 
                                    echo tombol_tambah(1,$static['data-staff']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-staff']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/staff.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        else {
                            include_once '../master/error/404.php';
                        }
                    }
                    // Manajemen Obat //
                    elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[1])) && hasPermit('menu_obat')) {
                        if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[3]))) {
                            if (hasPermit('submenu_obat')) {
                                if (hasPermit($static['data-obat']['permissions'][0])) { 
                                    echo tombol_tambah(1,$static['data-obat']['box-create']);
                                } else {
                                    echo tombol_download($static['data-obat']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-obat']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/obat.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[4]))) {
                            if (hasPermit('submenu_satuan')) {
                                echo tombol_tambah(0,$static['data-satuan']['box-create']);
                                echo "<div class=\"row\"><div class=\"col-md-6 col-xs-12\">" . table($static['data-satuan']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/satuan.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        else {
                            include_once '../master/error/404.php';
                        }
                    }
                    // Manajemen Puskesmas //
                    elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2])) && hasPermit('menu_puskesmas')) {
                        if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[5]))) {
                            if (hasPermit('submenu_berita')) {
                                if (hasPermit($static['data-berita']['permissions'][0])) { 
                                    echo tombol_tambah(0,$static['data-berita']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-berita']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/berita.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[6]))) {
                            if (hasPermit('submenu_poli')) {
                                if (hasPermit($static['data-poli']['permissions'][0])) { 
                                    echo tombol_tambah(0,$static['data-poli']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-6 col-xs-12\">" . table($static['data-poli']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/poli.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[7]))) {
                            if (hasPermit('submenu_darah')) {
                                if (hasPermit($static['data-darah']['permissions'][0])) { 
                                    echo tombol_tambah(0,$static['data-darah']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-6 col-xs-12\">" . table($static['data-darah']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/darah.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[8]))) {
                            if (hasPermit('submenu_spesialis')) {
                                if (hasPermit($static['data-spesialis']['permissions'][0])) { 
                                    echo tombol_tambah(0,$static['data-spesialis']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-6 col-xs-12\">" . table($static['data-spesialis']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/spesialis.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[9]))) {
                            if (hasPermit('submenu_jadwal')) {
                                if (hasPermit($static['data-jadwal']['permissions'][0])) { 
                                    echo tombol_tambah(1,$static['data-jadwal']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-jadwal']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/jadwal.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[10]))) {
                            if (hasPermit('submenu_resep')) {
                                if (hasPermit($static['data-resep']['permissions'][0])) { 
                                    echo tombol_tambah(1,$static['data-resep']['box-create']);
                                } else {
                                    echo tombol_download($static['data-resep']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-resep']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/resep.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[11]))) {
                            if (hasPermit('submenu_rekam_medis')) {
                                if (hasPermit($static['data-rekam_medis']['permissions'][0])) { 
                                    echo tombol_tambah(1,$static['data-rekam_medis']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-rekam_medis']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/rekam-medis.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[12]))) {
                            if (hasPermit('submenu_rawat_jalan')) {
                                if (hasPermit($static['data-rawat']['permissions'][0])) { 
                                    echo tombol_tambah(1,$static['data-rawat']['box-create']);
                                } else {
                                    echo tombol_download($static['data-rawat']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-rawat']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/rawat-jalan.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        else {
                            include_once '../master/error/404.php';
                        }
                    }
                    // Manajemen Sistem //
                    elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[3])) && hasPermit('menu_konfigurasi')) {
                        if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[15]))) {
                            if (hasPermit('submenu_beranda')) {
                                if (hasPermit($static['data-beranda']['permissions'][0])) { 
                                    echo tombol_tambah(0,$static['data-beranda']['box-create']);
                                }
                                echo "<div class=\"row\"><div class=\"col-md-6 col-xs-12\">" . table($static['data-beranda']['table']) . "</div></div>";
                                echo '<script src="'.BASE_URL.'assets/js/page/beranda.js"></script>';
                            } else {
                                include_once '../master/error/403.php';
                            }
                        }
                        else {
                            include_once '../master/error/404.php';
                        }
                    }
                    // Profil //
                    elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[4]))) {
                        if ($_REQUEST['submenu'] == "@".strtolower($_SESSION['username'])) {
                            include_once 'profil.php';
                        }
                        else {
                            include_once '../master/error/404.php';
                        }
                    }
                    // Password //
                    elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[5]))) {
                        if ($_REQUEST['submenu'] == "@".strtolower($_SESSION['username'])) {
                            include_once 'change_password.php';
                        }
                        else {
                            include_once '../master/error/404.php';
                        }
                    }
                    // Error Handling //
                    else {
                        include_once '../master/error/404.php';
                    }
                ?>
            </section>
            <!-- /.content -->
        </div>

<?php include_once '../master/footer.php'; ?>