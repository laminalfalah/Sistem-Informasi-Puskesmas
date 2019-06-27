<?php include_once '../master/header.php'; ?>
<!-- Full Width Column -->
<div class="content-wrapper">
    <div class="container">
        <!-- Main content -->
        <section class="content">
            <!-- Route -->
            <?php
                if (FIRST_PART == "home" && SECOND_PART == "" && THIRD_PART == "") {
                    ?>
                        <div class="box box-solid">
                            <div id="carousel-example-generic" class="carousel slide hidden-xs" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                    <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                                    <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                                </ol>
                                <div class="carousel-inner">
                                    <div class="item active">
                                        <img src="<?= BASE_URL . 'assets/img/default-slider.png' ?>" alt="First slide">
                                        <div class="carousel-caption"></div>
                                    </div>
                                    <div class="item">
                                        <img src="<?= BASE_URL . 'assets/img/default-slider.png' ?>" alt="Second slide">
                                        <div class="carousel-caption"></div>
                                    </div>
                                    <div class="item">
                                        <img src="<?= BASE_URL . 'assets/img/default-slider.png' ?>" alt="Third slide">
                                        <div class="carousel-caption"></div>
                                    </div>
                                </div>
                                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                    <span class="fa fa-angle-left"></span>
                                </a>
                                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                    <span class="fa fa-angle-right"></span>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-xs-12">
                                <div id="list_berita" data-remote="<?= base64_encode($enc['home']['remote']); ?>" data-target="<?= base64_encode($enc['home']['sha1'][0]); ?>">
                                    <div id="items"></div>
                                    <div class="text-center" id="box-load" style="display: none;" data-target="<?= base64_encode($enc['home']['sha1'][2]); ?>">
                                        <button id="loadmore" name="loadmore" type="button" class="btn btn-warning">Muat Lebih</button>
                                    </div>
                                    <div class="text-center" id="msg-empty" style="display: none;">
                                        <h3 id="msg-text">Data Kosong !</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <div class="box box-solid box-info hidden-xs">
                                    <div class="box-body">
                                        <form method="POST" id="form-cari" class="form-horizontal" data-remote="<?= base64_encode($enc['home']['remote'])?>" data-target="<?= base64_encode($enc['home']['sha1'][1])?>">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <input type="text" id="cari" name="cari" class="form-control" placeholder="Cari" required>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php
                                    $sql = "SELECT m.name_menu AS title, vm.content, vm.created_at FROM tb_view_menu AS vm 
                                            INNER JOIN tb_menu AS m ON m.id_menu = vm.id_menu 
                                            WHERE m.name_menu='Kontak' LIMIT 0,1";
                                    $q = mysqli_query($link,$sql);
                                    if (mysqli_num_rows($q) == 1) {
                                        $r = mysqli_fetch_assoc($q);
                                        ?>
                                            <div class="box box-solid box-info hidden-xs">
                                                <div class="box-header">
                                                    <h4 class="box-title">Kontak</h4>
                                                </div>
                                                <div class="box-body">
                                                    <div class="col-md-12 col-xs-12">
                                                        <?= $r['content'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <script src="<?=BASE_URL?>assets/js/page/home.js"></script>
                    <?php
                } elseif ($_REQUEST['menu'] == "detail") {
                    if ($_REQUEST['submenu'] != "") {
                        $id = $_REQUEST['submenu'];
                        $sql = "SELECT * FROM tb_berita WHERE slug='$id' LIMIT 0,1";
                        $q = mysqli_query($link,$sql);
                        if (mysqli_num_rows($q) == 1) {
                            $r = mysqli_fetch_assoc($q);
                            ?>
                                <div class="box box-solid box-default">
                                    <div class="box-header">
                                        <h4 class="box-title"><?= ucwords($r['title']) ?></h4>
                                        <h6 class="box-title pull-right"><small>Tanggal : <?= date('d-m-Y',strtotime($r['created_at'])) ?></small></h6>
                                    </div>
                                    <div class="box-body">
                                        <div class="col-md-12 col-xs-12">
                                            <?= $r['cover'] ?>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <?= $r['content'] ?>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <p>Form Komentar</p>
                                        <form class="form-horizontal" method="post" role="form" id="add-komentar" data-remote="<?= base64_encode($enc['detail']['remote']) ?>" data-target="<?= base64_encode($enc['detail']['sha1'][2]) ?>">
                                            <input type="hidden" name="id" value="<?= $r['id_berita'] ?>">
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nama</label>
                                                <div class="col-xs-9">
                                                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Komentar</label>
                                                <div class="col-xs-9">
                                                    <textarea class="form-control komentar" id="komentar" name="komentar" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Komentar"></textarea>
                                                </div>
                                            </div>
                                            <div class="pull-right">
                                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                                    <span class="fa fa-send"></span> &nbsp;Kirim
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="box box-solid box-default">
                                    <div class="box-header">
                                        <h4 class="box-title"><i class="fa fa-comment"></i> Komentar (<span id="jmlh">0</span>)</h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="komennyo" data-target="<?= base64_encode($enc['detail']['sha1'][0]) ?>"></div>
                                    </div>
                                </div>

                                <script src="<?=BASE_URL?>assets/js/page/detail.js"></script>
                            <?php
                        } else {
                            include_once '../master/error/404.php';
                        }
                    } else {
                        include_once '../master/error/404.php';
                    }
                } elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[0]))) {
                    if ($_REQUEST['submenu'] != "") {
                        $name = ucwords(str_replace('-',' ',$_REQUEST['submenu']));
                        $sql = "SELECT m.name_menu AS title, vm.content, vm.created_at FROM tb_view_menu AS vm 
                                INNER JOIN tb_menu AS m ON m.id_menu = vm.id_menu 
                                WHERE m.name_menu='$name' LIMIT 0,1";
                        $q = mysqli_query($link,$sql);
                        if (mysqli_num_rows($q) == 1) {
                            $r = mysqli_fetch_assoc($q);
                            ?>
                                <div class="box box-solid box-default">
                                    <div class="box-header">
                                        <h4 class="box-title"><?= ucwords($r['title']) ?></h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="col-md-12 col-xs-12">
                                            <?= $r['content'] ?>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <h6 class="pull-right">Tanggal <?= date('d-m-Y',strtotime($r['created_at'])) ?></h6>
                                    </div>
                                </div>
                            <?php
                        } else {
                            include_once '../master/error/404.php';
                        }
                    } else {
                        include_once '../master/error/404.php';
                    }
                } elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[1]))) {
                    if ($_REQUEST['submenu'] != "") {
                        $name = ucwords(str_replace('-',' ',$_REQUEST['submenu']));
                        $sql = "SELECT m.name_menu AS title, vm.content, vm.created_at FROM tb_view_menu AS vm 
                                INNER JOIN tb_menu AS m ON m.id_menu = vm.id_menu 
                                WHERE m.name_menu='$name' LIMIT 0,1";
                        $q = mysqli_query($link,$sql);
                        if (mysqli_num_rows($q) == 1) {
                            $r = mysqli_fetch_assoc($q);
                            ?>
                                <div class="box box-solid box-default">
                                    <div class="box-header">
                                        <h4 class="box-title"><?= ucwords($r['title']) ?></h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="col-md-12 col-xs-12">
                                            <?= $r['content'] ?>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <h6 class="pull-right">Tanggal <?= date('d-m-Y',strtotime($r['created_at'])) ?></h6>
                                    </div>
                                </div>
                            <?php
                        } else {
                            include_once '../master/error/404.php';
                        }
                    } else {
                        include_once '../master/error/404.php';
                    }
                } elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[2]))) {
                    if ($_REQUEST['submenu'] != "") {
                        $name = ucwords(str_replace('-',' ',$_REQUEST['submenu']));
                        $sql = "SELECT * FROM tb_poli WHERE name_poli='$name' LIMIT 0,1";
                        $poli = mysqli_query($link,$sql);
                        if (mysqli_num_rows($poli) == 1) {
                            $r = mysqli_fetch_assoc($poli);
                            ?>
                                <div class="box box-solid box-default">
                                    <div class="box-header">
                                        <h4 class="box-title"><?= ucwords($r['name_poli']) ?></h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="col-md-12 col-xs-12">
                                            <?= $r['description'] ?>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <h6 class="pull-right">Tanggal <?= date('d-m-Y',strtotime($r['created_at'])) ?></h6>
                                    </div>
                                </div>
                            <?php
                        } else {
                            include_once '../master/error/404.php';
                        }
                    } else {
                        include_once '../master/error/404.php';
                    }
                } elseif ($_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[3]))) {
                    if (@$_REQUEST['submenu'] == "") {
                        $sql = "SELECT p.name_poli, d.name, j.jadwal, j.created_at, j.id_jadwal FROM tb_jadwal AS j
                                INNER JOIN tb_poli AS p ON p.id_poli = j.id_poli
                                INNER JOIN tb_dokter AS d ON d.id_dokter = j.id_dokter";
                        $dokter = mysqli_query($link,$sql);
                        if (mysqli_num_rows($dokter) > 0) {
                            ?>
                                <div class="box box-solid box-default">
                                    <div class="box-body">
                                        <div class="col-md-12 col-xs-12 table-responsive">
                                            <table class="table table-bordered table-striped table-hover table_jadwal">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Nama Dokter</th>
                                                        <th>Poli</th>
                                                        <th>Jadwal</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $no = 1;
                                                        while ($r = mysqli_fetch_assoc($dokter)) {
                                                            ?>
                                                                <tr>
                                                                    <td><?= $no ?>.</td>
                                                                    <td><?= ucwords($r['name']) ?></td>
                                                                    <td><?= ucwords($r['name_poli']) ?></td>
                                                                    <td><?= str_replace(',','-',$r['jadwal']) ?></td>
                                                                    <td>Aktif</td>
                                                                </tr>
                                                            <?php
                                                            $no++;
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        } else {
                            ?>
                                <div class="text-center">
                                    <h3>Data Kosong !</h3>
                                </div>
                            <?php
                        }
                    } else {
                        include_once '../master/error/404.php';
                    }
                } else {
                    include_once '../master/error/404.php';
                }
            ?>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.container -->
</div>
<?php include_once '../master/footer.php'; ?>