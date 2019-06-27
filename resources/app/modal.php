<?php
if (@$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[0]))) {
    if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[0]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[0] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-dokter" data-target="<?= base64_encode($enc['data-dokter']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <h5>Biodata Diri</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">NIP</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nip" name="nip" class="form-control" placeholder="NIP" data-target="<?= base64_encode($enc['data-dokter']['check'][2]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Spesialis</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="spesialis" name="spesialis">
                                            <option selected disabled value="">Pilih Spesialis</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Lengkap</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Lengkap">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Jenis Kelamin</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="jk" name="jk">
                                            <option selected disabled value="">Pilih Jenis Kelamin</option>
                                            <option value="P">Pria</option>
                                            <option value="W">Wanita</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Alamat</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control alamat" id="alamat" name="alamat" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Alamat"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Telepon</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="tlpn" name="tlpn" class="form-control" placeholder="Telepon">
                                    </div>
                                </div>
                                <h5>Akun</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Username</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" data-target="<?= base64_encode($enc['data-dokter']['check'][0]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Password</label>
                                    <div class="col-xs-9">
                                        <input type="password" id="password1" name="password" class="form-control" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Konfirmasi Password</label>
                                    <div class="col-xs-9">
                                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Konfirmasi Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Status</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="status" name="status">
                                            <option selected disabled value="">Pilih Status</option>
                                            <option value="0">Tidak Aktif</option>
                                            <option value="1">Aktif</option>
                                            <option value="2">Blok</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[0] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-dokter" data-target="<?= base64_encode($enc['data-dokter']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <h5>Biodata Diri</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">NIP</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nip" name="nip" class="form-control" placeholder="NIP" data-target="<?= base64_encode($enc['data-dokter']['check'][3]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Spesialis</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="spesialis" name="spesialis">
                                            <option selected disabled value="">Pilih Spesialis</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Lengkap</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Lengkap">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Jenis Kelamin</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="jk" name="jk">
                                            <option selected disabled value="">Pilih Jenis Kelamin</option>
                                            <option value="P">Pria</option>
                                            <option value="W">Wanita</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Alamat</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control alamat" id="alamat" name="alamat" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Alamat"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Telepon</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="tlpn" name="tlpn" class="form-control" placeholder="Telepon">
                                    </div>
                                </div>
                                <h5>Akun</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Username</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" data-target="<?= base64_encode($enc['data-dokter']['check'][1]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Status</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="status" name="status">
                                            <option selected disabled value="">Pilih Status</option>
                                            <option value="0">Tidak Aktif</option>
                                            <option value="1">Aktif</option>
                                            <option value="2">Blok</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-refresh"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[0] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="downloadModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Export Data <?= $submenu[0] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="download-dokter" data-remote="<?= base64_encode($enc['export'][0]) ?>" data-target="<?= base64_encode($enc['export'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <h4 class="pull-right">Jumlah Data : 0 Data.</h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Awal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="awal" name="awal" class="form-control" placeholder="1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Akhir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="akhir" name="akhir" class="form-control" placeholder="100">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Filter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="filter" name="filter">
                                            <option selected value="">Filter</option>
                                            <option value="1">Harian</option>
                                            <option value="2">Bulanan</option>
                                            <option value="3">Rentang Tanggal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Tanggal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="range" name="range" class="form-control" placeholder="Rentang tanggal" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="download" name="download" class="btn btn-primary">
                                    <span class="fa fa-download"></span> &nbsp;Export
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    } elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[1]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[1] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-pasien" data-target="<?= base64_encode($enc['data-pasien']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <h5>Biodata Diri</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Kartu Keluarga</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="kk" name="kk" class="form-control" placeholder="No. Kartu Keluarga">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">NIK</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nik" name="nik" class="form-control" placeholder="No. NIK" data-target="<?= base64_encode($enc['data-pasien']['check'][2]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Lengkap</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Lengkap">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Tempat Lahir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="tmpt" name="tmpt" class="form-control" placeholder="Tempat Lahir">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Tanggal Lahir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="tgl" name="tgl" class="form-control" placeholder="Tanggal Lahir">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Jenis Kelamin</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="jk" name="jk">
                                            <option selected disabled value="">Pilih Jenis Kelamin</option>
                                            <option value="P">Pria</option>
                                            <option value="W">Wanita</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Agama</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="agama" name="agama">
                                            <option selected disabled value="">Pilih Agama</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen Protestan">Kristen Protestan</option>
                                            <option value="Katholik">Katholik</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Pekerjaan</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="pekerjaan" name="pekerjaan" class="form-control" placeholder="Pekerjaan">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Golongan Darah</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="darah" name="darah">
                                            <option selected disabled value="">Pilih Golongan Darah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Alamat</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control alamat" id="alamat" name="alamat" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Alamat"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Telepon</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="tlpn" name="tlpn" class="form-control" placeholder="Telepon">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">BPJS</label>
                                    <div class="col-xs-9">
                                        <select class="form-control bpjs" id="bpjs" name="bpjs">
                                            <option selected disabled value="">Pilih BPJS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">No. BPJS</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="no_bpjs" name="no_bpjs" class="form-control" placeholder="No. BPJS">
                                    </div>
                                </div>
                                <h5>Akun</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Username</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" data-target="<?= base64_encode($enc['data-pasien']['check'][0]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Password</label>
                                    <div class="col-xs-9">
                                        <input type="password" id="password1" name="password" class="form-control" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Konfirmasi Password</label>
                                    <div class="col-xs-9">
                                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Konfirmasi Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Status</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="status" name="status">
                                            <option selected disabled value="">Pilih Status</option>
                                            <option value="0">Tidak Aktif</option>
                                            <option value="1">Aktif</option>
                                            <option value="2">Blok</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[1] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-pasien" data-target="<?= base64_encode($enc['data-pasien']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <h5>Biodata Diri</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Kartu Keluarga</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="kk" name="kk" class="form-control" placeholder="No. Kartu Keluarga">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">NIK</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nik" name="nik" class="form-control" placeholder="No. NIK" data-target="<?= base64_encode($enc['data-pasien']['check'][3]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Lengkap</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Lengkap">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Tempat Lahir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="tmpt" name="tmpt" class="form-control" placeholder="Tempat Lahir">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Tanggal Lahir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="tgl" name="tgl" class="form-control" placeholder="Tanggal Lahir">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Jenis Kelamin</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="jk" name="jk">
                                            <option selected disabled value="">Pilih Jenis Kelamin</option>
                                            <option value="P">Pria</option>
                                            <option value="W">Wanita</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Agama</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="agama" name="agama">
                                            <option selected disabled value="">Pilih Agama</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen Protestan">Kristen Protestan</option>
                                            <option value="Katholik">Katholik</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Pekerjaan</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="pekerjaan" name="pekerjaan" class="form-control" placeholder="Pekerjaan">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Golongan Darah</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="darah" name="darah">
                                            <option selected disabled value="">Pilih Golongan Darah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Alamat</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control alamat" id="alamat" name="alamat" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Alamat"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Telepon</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="tlpn" name="tlpn" class="form-control" placeholder="Telepon">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">BPJS</label>
                                    <div class="col-xs-9">
                                        <select class="form-control bpjs" id="bpjs" name="bpjs">
                                            <option selected disabled value="">Pilih BPJS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">No. BPJS</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="no_bpjs" name="no_bpjs" class="form-control" placeholder="No. BPJS" disabled="disabled">
                                    </div>
                                </div>
                                <h5>Akun</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Username</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" data-target="<?= base64_encode($enc['data-pasien']['check'][1]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Status</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="status" name="status">
                                            <option selected disabled value="">Pilih Status</option>
                                            <option value="0">Tidak Aktif</option>
                                            <option value="1">Aktif</option>
                                            <option value="2">Blok</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-refresh"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[1] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="downloadModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Export Data <?= $submenu[1] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="download-pasien" data-remote="<?= base64_encode($enc['export'][0]) ?>" data-target="<?= base64_encode($enc['export'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <h4 class="pull-right">Jumlah Data : 0 Data.</h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Awal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="awal" name="awal" class="form-control" placeholder="1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Akhir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="akhir" name="akhir" class="form-control" placeholder="100">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Filter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="filter" name="filter">
                                            <option selected value="">Filter</option>
                                            <option value="1">Harian</option>
                                            <option value="2">Bulanan</option>
                                            <option value="3">Rentang Tanggal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Tanggal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="range" name="range" class="form-control" placeholder="Rentang tanggal" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="download" name="download" class="btn btn-primary">
                                    <span class="fa fa-download"></span> &nbsp;Export
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    } elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[2]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[2] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-staff" data-target="<?= base64_encode($enc['data-staff']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <h5>Biodata Diri</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">NIP</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nip" name="nip" class="form-control" placeholder="NIP" data-target="<?= base64_encode($enc['data-staff']['check'][2]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Level</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="level" name="level">
                                            <option selected disabled value="">Pilih Level</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Lengkap</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Lengkap">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Jenis Kelamin</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="jk" name="jk">
                                            <option selected disabled value="">Pilih Jenis Kelamin</option>
                                            <option value="P">Pria</option>
                                            <option value="W">Wanita</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Alamat</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control alamat" id="alamat" name="alamat" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Alamat"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Telepon</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="tlpn" name="tlpn" class="form-control" placeholder="Telepon">
                                    </div>
                                </div>
                                <h5>Akun</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Username</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" data-target="<?= base64_encode($enc['data-staff']['check'][0]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Password</label>
                                    <div class="col-xs-9">
                                        <input type="password" id="password1" name="password" class="form-control" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Konfirmasi Password</label>
                                    <div class="col-xs-9">
                                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Konfirmasi Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Status</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="status" name="status">
                                            <option selected disabled value="">Pilih Status</option>
                                            <option value="0">Tidak Aktif</option>
                                            <option value="1">Aktif</option>
                                            <option value="2">Blok</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[2] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-staff" data-target="<?= base64_encode($enc['data-staff']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <h5>Biodata Diri</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">NIP</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nip" name="nip" class="form-control" placeholder="NIP" data-target="<?= base64_encode($enc['data-staff']['check'][3]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Level</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="level" name="level">
                                            <option selected disabled value="">Pilih Level</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Lengkap</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Lengkap">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Jenis Kelamin</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="jk" name="jk">
                                            <option selected disabled value="">Pilih Jenis Kelamin</option>
                                            <option value="P">Pria</option>
                                            <option value="W">Wanita</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Alamat</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control alamat" id="alamat" name="alamat" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Alamat"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Telepon</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="tlpn" name="tlpn" class="form-control" placeholder="Telepon">
                                    </div>
                                </div>
                                <h5>Akun</h5>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Username</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" data-target="<?= base64_encode($enc['data-staff']['check'][1]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Status</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="status" name="status">
                                            <option selected disabled value="">Pilih Status</option>
                                            <option value="0">Tidak Aktif</option>
                                            <option value="1">Aktif</option>
                                            <option value="2">Blok</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-refresh"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[2] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="downloadModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Export Data <?= $submenu[2] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="download-staff" data-remote="<?= base64_encode($enc['export'][0]) ?>" data-target="<?= base64_encode($enc['export'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <h4 class="pull-right">Jumlah Data : 0 Data.</h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Awal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="awal" name="awal" class="form-control" placeholder="1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Akhir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="akhir" name="akhir" class="form-control" placeholder="100">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Filter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="filter" name="filter">
                                            <option selected value="">Filter</option>
                                            <option value="1">Harian</option>
                                            <option value="2">Bulanan</option>
                                            <option value="3">Rentang Tanggal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Tanggal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="range" name="range" class="form-control" placeholder="Rentang tanggal" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="download" name="download" class="btn btn-primary">
                                    <span class="fa fa-download"></span> &nbsp;Export
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    }
} elseif (@$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[1]))) {
    if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[3]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[3] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-obat" data-target="<?= base64_encode($enc['data-obat']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Obat</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Obat" data-target="<?= base64_encode($enc['data-obat']['check'][0]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Satuan</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="satuan" name="satuan">
                                            <option selected disabled value="">Pilih Satuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Stok</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="stok" name="stok" class="form-control auto" placeholder="Stok">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Harga Beli</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="harga_beli" name="harga_beli" class="form-control auto" placeholder="Harga Beli">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Harga Jual</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="harga_jual" name="harga_jual" class="form-control auto" placeholder="Harga jual">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Deskripsi</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control deskripsi" id="deskripsi" name="deskripsi" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[3] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-obat" data-target="<?= base64_encode($enc['data-obat']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Obat</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Obat" data-target="<?= base64_encode($enc['data-obat']['check'][1]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Satuan</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="satuan" name="satuan">
                                            <option selected disabled value="">Pilih Satuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Stok</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="stok_edit" name="stok_edit" class="form-control auto" placeholder="Stok">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Harga Beli</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="harga_beli_edit" name="harga_beli_edit" class="form-control auto" placeholder="Harga Beli">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Harga Jual</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="harga_jual_edit" name="harga_jual_edit" class="form-control auto" placeholder="Harga jual">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Deskripsi</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control deskripsi" id="deskripsi" name="deskripsi" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-refresh"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[3] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="downloadModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Export Data <?= $submenu[3] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="download-obat" data-remote="<?= base64_encode($enc['export'][0]) ?>" data-target="<?= base64_encode($enc['export'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <h4 class="pull-right">Jumlah Data : 0 Data.</h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Awal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="awal" name="awal" class="form-control" placeholder="1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Akhir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="akhir" name="akhir" class="form-control" placeholder="100">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Filter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="filter" name="filter">
                                            <option selected value="">Filter</option>
                                            <option value="1">Harian</option>
                                            <option value="2">Bulanan</option>
                                            <option value="3">Rentang Tanggal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Tanggal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="range" name="range" class="form-control" placeholder="Rentang tanggal" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="download" name="download" class="btn btn-primary">
                                    <span class="fa fa-download"></span> &nbsp;Export
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    } elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[4]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-xs animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[4] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-satuan" data-target="<?= base64_encode($enc['data-satuan']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Satuan</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Satuan" data-target="<?= base64_encode($enc['data-satuan']['check'][0]) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-xs animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[4] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-satuan" data-target="<?= base64_encode($enc['data-satuan']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Satuan</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama_edit" name="nama" class="form-control" placeholder="Nama Spesialis" data-target="<?= base64_encode($enc['data-satuan']['check'][1]) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    }
} elseif (@$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2]))) {
    if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[5]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[5] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-berita" data-target="<?= base64_encode($enc['data-berita']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Judul Berita</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="title" name="title" class="form-control" placeholder="Judul Berita">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Status</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="status" name="status">
                                            <option selected disabled value="">Pilih Status</option>
                                            <option value="0">Draft</option>
                                            <option value="1">Publish</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Cover</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control cover" id="cover" name="cover" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Cover"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Deskripsi</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control description" id="description" name="description" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[3] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-berita" data-target="<?= base64_encode($enc['data-berita']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Judul Berita</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="title" name="title" class="form-control" placeholder="Judul Berita">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Status</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="status" name="status">
                                            <option selected disabled value="">Pilih Status</option>
                                            <option value="0">Draft</option>
                                            <option value="1">Publish</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Cover</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control cover_edit" id="cover_edit" name="cover" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Cover"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Deskripsi</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control description_edit" id="description_edit" name="description" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-refresh"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[5] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="komenModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Data Komentar <?= $submenu[5] ?></h3>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" method="post" role="form" id="add-komentar" data-target="<?= base64_encode($enc['data-berita']['sha1'][8]) ?>">
                                <input type="hidden" name="id" value="">
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
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table-komen"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
        <?php
    } elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[6]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[6] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-poli" data-target="<?= base64_encode($enc['data-poli']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Judul Poli</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="title" name="title" class="form-control" placeholder="Judul Poli">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Deskripsi</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control description" id="description" name="description" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[6] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-poli" data-target="<?= base64_encode($enc['data-poli']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Judul Poli</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="title" name="title" class="form-control" placeholder="Judul Poli">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Deskripsi</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control description_edit" id="description_edit" name="description" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-refresh"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[6] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
        <?php
    } elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[7]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-xs animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[3] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-darah" data-target="<?= base64_encode($enc['data-darah']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Darah</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Darah" data-target="<?= base64_encode($enc['data-darah']['check'][0]) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    } elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[8]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-xs animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[8] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-spesialis" data-target="<?= base64_encode($enc['data-spesialis']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Kode Spesialis</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="kode" name="kode" class="form-control" placeholder="Kode Spesialis" data-target="<?= base64_encode($enc['data-spesialis']['check'][2]) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Spesialis</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Spesialis" data-target="<?= base64_encode($enc['data-spesialis']['check'][0]) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-xs animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[8] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-spesialis" data-target="<?= base64_encode($enc['data-spesialis']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Spesialis</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Spesialis" data-target="<?= base64_encode($enc['data-spesialis']['check'][1]) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    } elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[9]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[9] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-jadwal" data-target="<?= base64_encode($enc['data-jadwal']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Dokter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control select2" id="dokter" name="dokter" data-target="<?= base64_encode($enc['data-jadwal']['sha1'][7]) ?>">
                                            <option selected disabled value="">Pilih Dokter</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Poli</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="poli" name="poli">
                                            <option selected disabled value="">Pilih Poli</option>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
                                ?>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Awal Hari</label>
                                    <div class="col-xs-9">
                                        <select class="form-control select2" id="awal" name="awal">
                                            <option selected disabled value="">Pilih Awal Hari</option>
                                            <?php
                                            foreach ($hari as $key => $value) {
                                                echo "<option value=\"" . $value . "\">" . $value . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Akhir Hari</label>
                                    <div class="col-xs-9">
                                        <select class="form-control select2" id="akhir" name="akhir">
                                            <option selected disabled value="">Pilih Akhir Hari</option>
                                            <?php
                                            foreach ($hari as $key => $value) {
                                                echo "<option value=\"" . $value . "\">" . $value . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[9] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-jadwal" data-target="<?= base64_encode($enc['data-jadwal']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Dokter</label>
                                    <div class="col-xs-9">
                                        <h4 id="dokternyo"></h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Poli</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="poli" name="poli">
                                            <option selected disabled value="">Pilih Poli</option>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
                                ?>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Awal Hari</label>
                                    <div class="col-xs-9">
                                        <select class="form-control select2" id="awal" name="awal">
                                            <option selected disabled value="">Pilih Awal Hari</option>
                                            <?php
                                            foreach ($hari as $key => $value) {
                                                echo "<option value=\"" . $value . "\">" . $value . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Akhir Hari</label>
                                    <div class="col-xs-9">
                                        <select class="form-control select2" id="akhir" name="akhir">
                                            <option selected disabled value="">Pilih Akhir Hari</option>
                                            <?php
                                            foreach ($hari as $key => $value) {
                                                echo "<option value=\"" . $value . "\">" . $value . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-refresh"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="downloadModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Export Data <?= $submenu[9] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="download-jadwal" data-remote="<?= base64_encode($enc['export'][0]) ?>" data-target="<?= base64_encode($enc['export'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <h4 class="pull-right">Jumlah Data : 0 Data.</h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Awal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="awal" name="awal" class="form-control" placeholder="1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Akhir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="akhir" name="akhir" class="form-control" placeholder="100">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Filter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="filter" name="filter">
                                            <option selected value="">Filter</option>
                                            <option value="1">Harian</option>
                                            <option value="2">Bulanan</option>
                                            <option value="3">Rentang Tanggal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Tanggal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="range" name="range" class="form-control" placeholder="Rentang tanggal" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="download" name="download" class="btn btn-primary">
                                    <span class="fa fa-download"></span> &nbsp;Export
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    } elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[10]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[10] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-resep" data-target="<?= base64_encode($enc['data-resep']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Dokter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control select2" id="dokter" name="dokter" data-target="<?= base64_encode($enc['data-resep']['sha1'][7]) ?>">
                                            <option selected disabled value="">Pilih Dokter</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Pasien</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="pasien" name="pasien" data-target="<?= base64_encode($enc['data-resep']['sha1'][8]) ?>">
                                            <option selected disabled value="">Pilih Pasien</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Obat</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="obat" name="obat" data-target="<?= base64_encode($enc['data-resep']['sha1'][9]) ?>">
                                            <option selected disabled value="">Pilih Obat</option>
                                        </select>
                                    </div>
                                </div>
                                <table id="table_list_item" class="table table-bordered table-striped table-hover table_list_item" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nama Obat</th>
                                            <th>Anjuran</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list_item"></tbody>
                                    <tfoot id="list_foot">
                                        <tr>
                                            <th colspan="4" style="text-align: right !important">Total</th>
                                            <th style="text-align: center !important">0</th>
                                            <th style="text-align: center !important">Rp 0</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[10] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table"></tbody>
                            </table>
                            <table id="table_list_item_detail" class="table table-bordered table-striped table-hover table_list_item" style="display: none;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Obat</th>
                                        <th>Anjuran</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="list_item_detail"></tbody>
                                <tfoot id="list_foot_detail">
                                    <tr>
                                        <th colspan="4" style="text-align: right !important">Total</th>
                                        <th style="text-align: center !important">0</th>
                                        <th style="text-align: center !important">Rp 0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="downloadModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Export Data <?= $submenu[10] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="download-resep" data-remote="<?= base64_encode($enc['export'][0]) ?>" data-target="<?= base64_encode($enc['export'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <h4 class="pull-right">Jumlah Data : 0 Data.</h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Awal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="awal" name="awal" class="form-control" placeholder="1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Akhir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="akhir" name="akhir" class="form-control" placeholder="100">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Filter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="filter" name="filter">
                                            <option selected value="">Filter</option>
                                            <option value="1">Harian</option>
                                            <option value="2">Bulanan</option>
                                            <option value="3">Rentang Tanggal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Tanggal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="range" name="range" class="form-control" placeholder="Rentang tanggal" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="download" name="download" class="btn btn-primary">
                                    <span class="fa fa-download"></span> &nbsp;Export
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    } elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[11]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[11] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-rekam_medis" data-target="<?= base64_encode($enc['data-rekam_medis']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Nama Pasien</label>
                                    <div class="col-xs-9">
                                        <select class="form-control select2" id="pasien" name="pasien" data-target="<?= base64_encode($enc['data-rekam_medis']['sha1'][7]) ?>">
                                            <option selected disabled value="">Pilih Pasien</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Jenis dan No. BPJS</label>
                                    <div class="col-xs-9">
                                        <h4 id="bpjsnyo">-</h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Dokter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control select2" id="dokter" name="dokter" data-target="<?= base64_encode($enc['data-rekam_medis']['sha1'][8]) ?>">
                                            <option selected disabled value="">Pilih Dokter</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Keterangan</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Keterangan"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[11] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-rekam_medis" data-target="<?= base64_encode($enc['data-rekam_medis']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Pasien</label>
                                    <div class="col-xs-9">
                                        <h4 id="pasiennyo"></h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Dokter</label>
                                    <div class="col-xs-9">
                                        <h4 id="dokternyo"></h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Keterangan</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Keterangan"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-refresh"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[11] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="downloadModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Export Data <?= $submenu[12] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="download-rekam_medis" data-remote="<?= base64_encode($enc['export'][0]) ?>" data-target="<?= base64_encode($enc['export'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <h4 class="pull-right">Jumlah Data : 0 Data.</h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Awal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="awal" name="awal" class="form-control" placeholder="1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Akhir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="akhir" name="akhir" class="form-control" placeholder="100">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Filter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="filter" name="filter">
                                            <option selected value="">Filter</option>
                                            <option value="1">Harian</option>
                                            <option value="2">Bulanan</option>
                                            <option value="3">Rentang Tanggal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Tanggal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="range" name="range" class="form-control" placeholder="Rentang tanggal" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="download" name="download" class="btn btn-primary">
                                    <span class="fa fa-download"></span> &nbsp;Export
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php

    } elseif ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[12]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[12] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-rawat_jalan" data-target="<?= base64_encode($enc['data-rawat']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Pasien</label>
                                    <div class="col-xs-9">
                                        <select class="form-control select2" id="pasien" name="pasien" data-target="<?= base64_encode($enc['data-rawat']['sha1'][8]) ?>">
                                            <option selected disabled value="">Pilih Pasien</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Dokter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control select2" id="dokter" name="dokter" data-target="<?= base64_encode($enc['data-rawat']['sha1'][7]) ?>">
                                            <option selected disabled value="">Pilih Dokter</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Kode Resep</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="resep" name="resep" data-target="<?= base64_encode($enc['data-rawat']['sha1'][9]) ?>">
                                            <option selected disabled value="">Pilih Resep</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Keterangan</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control" id="ket" name="ket" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Keterangan"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[12] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-rawat_jalan" data-target="<?= base64_encode($enc['data-rawat']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Pasien</label>
                                    <div class="col-xs-9">
                                        <h4 id="pasiennyo"></h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Dokter</label>
                                    <div class="col-xs-9">
                                        <h4 id="dokternyo"></h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Kode Resep</label>
                                    <div class="col-xs-9">
                                        <h4 id="resepnyo"></h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Keterangan</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control" id="ket" name="ket" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Keterangan"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-refresh"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[12] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table"></tbody>
                            </table>
                            <table id="table_list_item_detail" class="table table-bordered table-striped table-hover table_list_item">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Obat</th>
                                        <th>Anjuran</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="list_item_detail"></tbody>
                                <tfoot id="list_foot_detail">
                                    <tr>
                                        <th colspan="4" style="text-align: right !important">Total</th>
                                        <th style="text-align: center !important">0</th>
                                        <th style="text-align: center !important">Rp 0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="downloadModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Export Data <?= $submenu[12] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="download-rawat_jalan" data-remote="<?= base64_encode($enc['export'][0]) ?>" data-target="<?= base64_encode($enc['export'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <h4 class="pull-right">Jumlah Data : 0 Data.</h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Awal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="awal" name="awal" class="form-control" placeholder="1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Jumlah Akhir</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="akhir" name="akhir" class="form-control" placeholder="100">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Filter</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="filter" name="filter">
                                            <option selected value="">Filter</option>
                                            <option value="1">Harian</option>
                                            <option value="2">Bulanan</option>
                                            <option value="3">Rentang Tanggal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Rentang Tanggal</label>
                                    <div class="col-xs-9">
                                        <input type="text" id="range" name="range" class="form-control" placeholder="Rentang tanggal" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="download" name="download" class="btn btn-primary">
                                    <span class="fa fa-download"></span> &nbsp;Export
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    }
} elseif (@$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[3]))) {
    if ($_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[15]))) {
        ?>
            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Tambah Data <?= $submenu[15] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="add-beranda" data-target="<?= base64_encode($enc['data-beranda']['sha1'][2]) ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Menu</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="menu" name="menu">
                                            <option selected disabled value="">Pilih Menu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Deskripsi</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control description" id="description" name="description" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                    <span class="fa fa-save"></span> &nbsp;Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Edit Data <?= $submenu[15] ?></h3>
                        </div>
                        <form class="form-horizontal" method="post" role="form" id="edit-beranda" data-target="<?= base64_encode($enc['data-beranda']['sha1'][4]) ?>">
                            <input type="hidden" name="id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Menu</label>
                                    <div class="col-xs-9">
                                        <select class="form-control" id="menu" name="menu">
                                            <option selected disabled value="">Pilih Menu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Deskripsi</label>
                                    <div class="col-xs-9">
                                        <textarea class="form-control description_edit" id="description_edit" name="description" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                    <span class="fa fa-refresh"></span> &nbsp;Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailModal">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[15] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
} elseif ($dashboard == "dokter") {
    ?>
        <div class="modal fade" id="addModalResep">
            <div class="modal-dialog modal-lg animated zoomIn">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title text-center">Tambah Data <?= $submenu[10] ?></h3>
                    </div>
                    <form class="form-horizontal" method="post" role="form" id="add-resep" data-target="<?= base64_encode($enc['data-resep']['sha1'][2]) ?>">
                        <div class="modal-body">
                            <input type="hidden" name="dokter" value="<?= $idDokternyo ?>">
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Nama Pasien</label>
                                <div class="col-xs-9">
                                    <select class="form-control" id="pasien_resep" name="pasien" data-target="<?= base64_encode($enc['data-resep']['sha1'][8]) ?>">
                                        <option selected disabled value="">Pilih Pasien</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Nama Obat</label>
                                <div class="col-xs-9">
                                    <select class="form-control" id="obat_resep" name="obat" data-target="<?= base64_encode($enc['data-resep']['sha1'][9]) ?>">
                                        <option selected disabled value="">Pilih Obat</option>
                                    </select>
                                </div>
                            </div>
                            <table id="table_list_item" class="table table-bordered table-striped table-hover table_list_item" style="display: none;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nama Obat</th>
                                        <th>Anjuran</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="list_item"></tbody>
                                <tfoot id="list_foot">
                                    <tr>
                                        <th colspan="4" style="text-align: right !important">Total</th>
                                        <th style="text-align: center !important">0</th>
                                        <th style="text-align: center !important">Rp 0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                <span class="fa fa-save"></span> &nbsp;Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailModalResep">
            <div class="modal-dialog modal-lg animated zoomIn">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title text-center">Detail Data <?= $submenu[10] ?></h3>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody id="detail-table"></tbody>
                        </table>
                        <table id="table_list_item_detail" class="table table-bordered table-striped table-hover table_list_item" style="display: none;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Obat</th>
                                    <th>Anjuran</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="list_item_detail"></tbody>
                            <tfoot id="list_foot_detail">
                                <tr>
                                    <th colspan="4" style="text-align: right !important">Total</th>
                                    <th style="text-align: center !important">0</th>
                                    <th style="text-align: center !important">Rp 0</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addModalRekam">
            <div class="modal-dialog modal-lg animated zoomIn">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title text-center">Tambah Data <?= $submenu[11] ?></h3>
                    </div>
                    <form class="form-horizontal" method="post" role="form" id="add-rekam_medis" data-target="<?= base64_encode($enc['data-rekam_medis']['sha1'][2]) ?>">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Nama Pasien</label>
                                <div class="col-xs-9">
                                    <select class="form-control select2" id="pasien_rekam" name="pasien" data-target="<?= base64_encode($enc['data-rekam_medis']['sha1'][7]) ?>">
                                        <option selected disabled value="">Pilih Pasien</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Jenis dan No. BPJS</label>
                                <div class="col-xs-9">
                                    <h4 id="bpjsnyo">-</h4>
                                </div>
                            </div>
                            <input type="hidden" id="dokter" name="dokter" value="<?= $idDokternyo ?>">
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Keterangan</label>
                                <div class="col-xs-9">
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                <span class="fa fa-save"></span> &nbsp;Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModalRekam">
            <div class="modal-dialog modal-lg animated zoomIn">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title text-center">Edit Data <?= $submenu[11] ?></h3>
                    </div>
                    <form class="form-horizontal" method="post" role="form" id="edit-rekam_medis" data-target="<?= base64_encode($enc['data-rekam_medis']['sha1'][4]) ?>">
                        <input type="hidden" name="id" value="">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Pasien</label>
                                <div class="col-xs-9">
                                    <h4 id="pasiennyo"></h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Dokter</label>
                                <div class="col-xs-9">
                                    <h4 id="dokternyo"></h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Keterangan</label>
                                <div class="col-xs-9">
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                <span class="fa fa-refresh"></span> &nbsp;Ubah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailModalRekam">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[11] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table-rekam"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
        </div>
        <div class="modal fade" id="addModalRawat">
            <div class="modal-dialog modal-lg animated zoomIn">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title text-center">Tambah Data <?= $submenu[12] ?></h3>
                    </div>
                    <form class="form-horizontal" method="post" role="form" id="add-rawat_jalan" data-target="<?= base64_encode($enc['data-rawat']['sha1'][2]) ?>">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Pasien</label>
                                <div class="col-xs-9">
                                    <select class="form-control select2" id="pasien_rawat" name="pasien" data-target="<?= base64_encode($enc['data-rawat']['sha1'][8]) ?>">
                                        <option selected disabled value="">Pilih Pasien</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="dokter" name="dokter" value="<?= $idDokternyo ?>">
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Kode Resep</label>
                                <div class="col-xs-9">
                                    <select class="form-control" id="resep_rawat" name="resep" data-target="<?= base64_encode($enc['data-rawat']['sha1'][9]) ?>">
                                        <option selected disabled value="">Pilih Resep</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Keterangan</label>
                                <div class="col-xs-9">
                                    <textarea class="form-control" id="ket" name="ket" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="simpan" name="simpan" class="btn btn-primary">
                                <span class="fa fa-save"></span> &nbsp;Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModalRawat">
            <div class="modal-dialog modal-lg animated zoomIn">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title text-center">Edit Data <?= $submenu[12] ?></h3>
                    </div>
                    <form class="form-horizontal" method="post" role="form" id="edit-rawat_jalan" data-target="<?= base64_encode($enc['data-rawat']['sha1'][4]) ?>">
                        <input type="hidden" name="id" value="">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Pasien</label>
                                <div class="col-xs-9">
                                    <h4 id="pasiennyo_rawat"></h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Dokter</label>
                                <div class="col-xs-9">
                                    <h4 id="dokternyo_rawat"></h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Kode Resep</label>
                                <div class="col-xs-9">
                                    <h4 id="resepnyo"></h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Keterangan</label>
                                <div class="col-xs-9">
                                    <textarea class="form-control" id="ket" name="ket" rows="2" cols="32" resize="none" style="resize: none;" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                <span class="fa fa-refresh"></span> &nbsp;Ubah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailModalRawat">
            <div class="modal-dialog modal-lg animated zoomIn">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title text-center">Detail Data <?= $submenu[12] ?></h3>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody id="detail-table-rawat"></tbody>
                        </table>
                        <table id="table_list_item_detail" class="table table-bordered table-striped table-hover table_list_item">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Obat</th>
                                    <th>Anjuran</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="list_item_detail"></tbody>
                            <tfoot id="list_foot_detail">
                                <tr>
                                    <th colspan="4" style="text-align: right !important">Total</th>
                                    <th style="text-align: center !important">0</th>
                                    <th style="text-align: center !important">Rp 0</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailModalPasien">
                <div class="modal-dialog modal-lg animated zoomIn">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center">Detail Data <?= $submenu[1] ?></h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody id="detail-table-pasien"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
    <?php
}
