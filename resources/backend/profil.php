<?php
$table = array();
if (isset($_SESSION['is_logged'])) {
    $id = $_SESSION['id'];
    $level = $_SESSION['level'];
    $check = mysqli_query($link, "SELECT * FROM tb_rules");
    $i = 1;
    $tipe = "";
    while ($r = mysqli_fetch_assoc($check)) {
        if ($level == $r['name_rule'] && $i != 6 && $i != 7) {
            $sql = "SELECT s.nip, r.display_rule AS level, s.name AS nama, s.sex AS jk, s.address AS almt, s.telp AS tlpn, u.username AS user FROM tb_staff AS s
                    INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff
                    INNER JOIN tb_users AS u ON u.id_user = us.id_user
                    INNER JOIN tb_rule_user AS ru ON ru.id_user = u.id_user
                    INNER JOIN tb_rules AS r ON r.id_rule = ru.id_rule
                    WHERE u.id_user='$id'";
            $exec = mysqli_query($link, $sql);
            $r = mysqli_fetch_assoc($exec);
            $table = array(
                'NIP' => $r['nip'],
                'Level' => ucwords($r['level']),
                'Nama' => ucwords($r['nama']),
                'Jenis Kelamin' => $r['jk'] == "P" ? "Pria" : "Wanita",
                'Alamat' => $r['almt'],
                'Telepon' => $r['tlpn'],
                'Username' => $r['user']
            );
            $tipe = "staff";
        } elseif ($level == $r['name_rule'] && $i == 6 && $i != 7) {
            $sql = "SELECT d.nip, s.name_spesialis AS spesialis, d.name AS nama, d.sex AS jk, d.address AS almt, d.telp AS tlpn, u.username AS user FROM tb_dokter AS d
                    INNER JOIN tb_spesialis AS s ON s.code_spesialis = d.code_spesialis
                    INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter
                    INNER JOIN tb_users AS u ON u.id_user = ud.id_user
                    WHERE u.id_user='$id'";
            $exec = mysqli_query($link, $sql);
            $r = mysqli_fetch_assoc($exec);
            $table = array(
                'NIP' => $r['nip'],
                'Spesialis' => ucwords($r['spesialis']),
                'Nama' => ucwords($r['nama']),
                'Jenis Kelamin' => $r['jk'] == "P" ? "Pria" : "Wanita",
                'Alamat' => $r['almt'],
                'Telepon' => $r['tlpn'],
                'Username' => $r['user']
            );
            $tipe = "dokter";
        } elseif ($level == $r['name_rule'] && $i != 6 && $i == 7) {
            $sql = "SELECT p.kk, p.nik, b.name_bpjs AS jns, bp.no_bpjs AS no, p.name AS nama, p.place_born AS tmpt, p.date_born AS tgl, p.sex AS jk, p.religion AS agm, p.worker AS pkr, 
                    d.name_darah AS darah, p.address AS almt, p.telp AS tlpn, u.username AS user FROM tb_pasien AS p
                    INNER JOIN tb_darah AS d ON d.id_darah = p.id_darah
                    INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien
                    INNER JOIN tb_users AS u ON u.id_user = up.id_user
                    INNER JOIN tb_bpjs_pasien AS bp ON bp.id_pasien = p.id_pasien
                    INNER JOIN tb_bpjs AS b ON b.id_bpjs = bp.id_bpjs
                    WHERE u.id_user='$id'";
            $exec = mysqli_query($link, $sql);
            $r = mysqli_fetch_assoc($exec);
            $table = array(
                'No. Kartu Keluarga' => $r['kk'],
                'NIK' => $r['nik'],
                'Jenis BPJS' => $r['jns'],
                'No. BPJS' => $r['no'],
                'Nama' => $r['nama'],
                'Tempat, Tanggal Lahir' => $r['tmpt'] . ", " . $r['tgl'],
                'Jenis Kelamin' => $r['jk'] == "P" ? "Pria" : "Wanita",
                'Agama' => $r['agm'],
                'Pekerjaan' => $r['pkr'],
                'Alamat' => $r['almt'],
                'Telepon' => $r['tlpn'],
                'Username' => $r['user']
            );
            $tipe = "pasien";
        }
        $i++;
    }
}
?>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <div class="pull-right">
                    <a id="edit" name="edit" class="btn btn-xs btn-warning" data-remote="<?= base64_encode($enc['data-profil']['remote']) ?>" data-target="<?= base64_encode($enc['data-profil']['sha1'][0]) ?>" data-profile="<?= base64_encode($_SESSION['id']) ?>">
                        <i class="fa fa-edit"></i>
                        <span>Edit Profil</span>
                    </a>
                </div>
            </div>
            <?php
            if ($tipe == "staff") {
                echo "<input type=\"hidden\" id=\"remotenyo\" value=\"" . base64_encode($enc['data-staff']['remote']) . "\">";
            } elseif ($tipe == "pasien") {
                echo "<input type=\"hidden\" id=\"remotenyo\" value=\"" . base64_encode($enc['data-pasien']['remote']) . "\">";
            } elseif ($tipe == "dokter") {
                echo "<input type=\"hidden\" id=\"remotenyo\" value=\"" . base64_encode($enc['data-dokter']['remote']) . "\">";
            }
            ?>
            <div class="box-body">
                <table class="table table-bordered table-striped table-hover">
                    <tbody id="detail-table">
                        <?php
                        foreach ($table as $key => $value) {
                            echo "<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal">
    <div class="modal-dialog modal-lg animated zoomIn">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title text-center">Edit Data Profil</h3>
            </div>
            <?php
            if ($tipe == "staff") {
                ?>
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
                            <input type="hidden" name="level" value="">
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
                            <input type="hidden" name="status" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                <span class="fa fa-refresh"></span> &nbsp;Ubah
                            </button>
                        </div>
                    </form>
                <?php
            } elseif ($tipe == "dokter") {
                ?>
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
                            <input type="hidden" name="status" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                <span class="fa fa-refresh"></span> &nbsp;Ubah
                            </button>
                        </div>
                    </form>
                <?php
            } elseif ($tipe == "pasien") {
                ?>
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
                                    <select class="form-control" id="bpjs" name="bpjs">
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
                            <input type="hidden" name="status" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="ubah" name="ubah" class="btn btn-primary">
                                <span class="fa fa-refresh"></span> &nbsp;Ubah
                            </button>
                        </div>
                    </form>
                <?php
            }
        ?>
        </div>
    </div>
</div>
<?php
if ($tipe == "staff") {
    ?>
    <script>
        $(function() {
            var rmt = $('#remotenyo').val();
            var remote = $('#edit').attr('data-remote');
            var target = $('#edit').attr('data-target');
            var editNip = $('#edit-staff').find('#nip').attr('data-target');
            var editStaff = $('#edit-staff').find('#username').attr('data-target');

            $.validator.addMethod("hurufbae", function(value, element) {
                return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
            });
            $.validator.addMethod("angkonyo", function(value, element) {
                return this.optional(element) || value.length > 9 && /^[0-9.,]*$/.test(value);
            });
            $.validator.addMethod("usernamenyo", function(value, element) {
                return this.optional(element) || /^[a-z0-9_]*$/.test(value);
            });

            // Hide modal & reset form
            $('[data-dismiss=modal]').on('click', function(e) {
                //e.preventDefault();
                var $t = $(this),
                    target = $t[0].href || $t.data("target") || $t.parents('.modal') || [];
                $(target).find('.form-group').attr('class', 'form-group');
                $(target).find('label.has-error').remove();
                $(target).find('span.glyphicon').remove();
                $(target).find('span.fa').remove();
                $(target).find('em.has-error').remove();
                $(target)
                    .find("input,textarea,select").val('').end()
                    .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
                closeModal();
            });

            // Animation modal bootstrap + library Animate.css
            function closeModal() {
                var timeoutHandler = null;
                $('#addModal,#editModal,#detailModal,#downloadModal').on('hide.bs.modal', function(e) {
                    var anim = $('.modal-dialog').removeClass('zoomIn').addClass('zoomOut');
                    if (timeoutHandler) clearTimeout(timeoutHandler);
                    timeoutHandler = setTimeout(function() {
                        $('.modal-dialog').removeClass('zoomOut').addClass('zoomIn');
                    }, 250); // some delay for complete Animation
                });
            }

            $('#edit-staff').validate({
                errorClass: 'has-error animated tada',
                validClass: 'has-success',
                rules: {
                    nip: {
                        required: true,
                        rangelength: [18, 18],
                        digits: true,
                        remote: {
                            url: http + 'fetch?f=' + rmt + '&d=' + editNip,
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                id: function() {
                                    return $('#edit-staff').find('input[type=hidden]').val();
                                },
                                nip: function() {
                                    return $('#edit-staff').find("#nip").val();
                                }
                            }
                        }
                    },
                    level: {
                        required: true,
                    },
                    nama: {
                        required: true,
                        rangelength: [3, 35],
                        hurufbae: true,
                    },
                    jk: {
                        required: true,
                    },
                    alamat: {
                        required: true,
                        rangelength: [15, 50],
                    },
                    tlpn: {
                        required: true,
                        angkonyo: true,
                    },
                    username: {
                        required: true,
                        rangelength: [8, 20],
                        usernamenyo: true,
                        remote: {
                            url: http + 'fetch?f=' + rmt + '&d=' + editStaff,
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                id: function() {
                                    return $('#edit-staff').find('input[type=hidden]').val();
                                },
                                username: function() {
                                    return $('#edit-staff').find("#username").val();
                                }
                            }
                        }
                    },
                    status: {
                        required: true,
                    },
                },
                messages: {
                    nip: {
                        required: "NIP harus diisi !",
                        rangelength: "NIP harus 18 digit angka !",
                        digits: "Tidak boleh mengandung simbol lain selain angka !",
                        remote: "NIP telah digunakan !"
                    },
                    level: {
                        required: "level diperlukan !"
                    },
                    nama: {
                        required: "Nama harus diisi !",
                        rangelength: "Minimal 3 huruf dan Maksimal 25 huruf !",
                        hurufbae: "Tidak boleh mengandung simbol lain selain huruf !",
                    },
                    jk: {
                        required: "Jenis kelamin diperlukan !"
                    },
                    alamat: {
                        required: "Alamat diperlukan !",
                        rangelength: "Minimal 15 karakter dan Maksimal 50 karakter !",
                    },
                    tlpn: {
                        required: "Nomor telepon diperlukan !",
                        angkonyo: "Nomor tidak benar !"
                    },
                    username: {
                        required: "Username harus diisi !",
                        rangelength: "Minimal 8 karakter dan maksimal 25 karakter !",
                        usernamenyo: "Gunakan huruf kecil atau angka atau <i>Underscore</i> _",
                        remote: "Username telah digunakan !"
                    },
                    status: {
                        required: "Silahan pilih status akun !",
                    },
                },
                errorElement: "em",
                errorPlacement: function(error, element) {
                    error.addClass("help-block");
                    element.parents(".col-xs-9").addClass("has-feedback");

                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.parent("label"));
                    } else {
                        error.insertAfter(element);
                    }
                    if (!element.next("span")[0]) {
                        $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass(errorClass).removeClass(validClass);
                    $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass(validClass).removeClass(errorClass);
                    $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
                },
                submitHandler: function(form) {
                    var uuid = $('#edit-staff').attr('data-target');
                    var staff = new FormData($('#edit-staff')[0]);
                    var uid = $('#edit-staff').find('input[type=hidden]').val();
                    $.ajax({
                        url: http + 'fetch?f=' + rmt + '&d=' + uuid + '&id=' + uid,
                        type: 'POST',
                        async: true,
                        cache: false,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        timeout: 3000,
                        data: staff,
                        beforeSend: function() {
                            showLoading();
                        },
                        success: function(res) {
                            if (res.length == 0) {
                                hideLoading();
                                alertDanger('Invalid request');
                            } else {
                                if (res.staff.code == 1) {
                                    ajaxSuccess('#edit-staff');
                                    window.location.href = '';
                                    hideLoading();
                                    alertSuccess(res.staff.message);
                                } else {
                                    hideLoading();
                                    alertWarning(res.staff.message);
                                }
                            }
                        },
                        error: function(jqXHR, status, error) {
                            hideLoading();
                            alertDanger(status);
                        }
                    });
                    return false;
                }
            });

            jQuery.noConflict();

            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                var uid = $(this).attr('data-profile');
                $a = $('#edit-staff').find('input[type=hidden],input[type=text], select, textarea');

                $.ajax({
                    url: http + 'fetch?f=' + remote + '&d=' + target + '&id=' + uid + '&tipe=staff',
                    type: 'POST',
                    async: true,
                    dataType: 'json',
                    beforeSend: function() {
                        showLoading();
                    },
                    success: function(res) {
                        if (res.length == 0) {
                            hideLoading();
                            alertDanger('Invalid request');
                        } else {
                            if (res.staff.code) {
                                hideLoading();
                                $('#editModal').modal({
                                    'show': true,
                                    'backdrop': 'static'
                                });

                                for (let i = 0; i < $a.length; i++) {
                                    $a.eq(i).val(res.staff.data[i]);
                                }
                            } else {
                                hideLoading();
                                alertWarning(res.staff.message);
                            }
                        }
                    },
                    error: function(jqXHR, status, error) {
                        hideLoading();
                        alertDanger(status);
                    }
                });
            });
        });
    </script>
    <?php
} elseif ($tipe == "dokter") {
    ?>
    <script>
        $(function() {
            var rmt = $('#remotenyo').val();
            var remote = $('#edit').attr('data-remote');
            var target = $('#edit').attr('data-target');
            var editNip = $('#edit-dokter').find('#nip').attr('data-target');
            var editDokter = $('#edit-dokter').find('#username').attr('data-target');

            var sp = $('#edit-dokter').find('#spesialis');
            sp.empty();

            $.validator.addMethod("hurufbae", function(value, element) {
                return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
            });
            $.validator.addMethod("angkonyo", function(value, element) {
                return this.optional(element) || value.length > 9 && /^[0-9.,]*$/.test(value);
            });
            $.validator.addMethod("usernamenyo", function(value, element) {
                return this.optional(element) || /^[a-z0-9_]*$/.test(value);
            });

            // Hide modal & reset form
            $('[data-dismiss=modal]').on('click', function(e) {
                //e.preventDefault();
                var $t = $(this),
                    target = $t[0].href || $t.data("target") || $t.parents('.modal') || [];
                $(target).find('.form-group').attr('class', 'form-group');
                $(target).find('label.has-error').remove();
                $(target).find('span.glyphicon').remove();
                $(target).find('span.fa').remove();
                $(target).find('em.has-error').remove();
                $(target)
                    .find("input,textarea,select").val('').end()
                    .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
                closeModal();
            });

            // Animation modal bootstrap + library Animate.css
            function closeModal() {
                var timeoutHandler = null;
                $('#addModal,#editModal,#detailModal,#downloadModal').on('hide.bs.modal', function(e) {
                    var anim = $('.modal-dialog').removeClass('zoomIn').addClass('zoomOut');
                    if (timeoutHandler) clearTimeout(timeoutHandler);
                    timeoutHandler = setTimeout(function() {
                        $('.modal-dialog').removeClass('zoomOut').addClass('zoomIn');
                    }, 250); // some delay for complete Animation
                });
            }

            $('#edit-dokter').validate({
                errorClass: 'has-error animated tada',
                validClass: 'has-success',
                rules: {
                    nip: {
                        required: true,
                        rangelength: [18, 18],
                        digits: true,
                        remote: {
                            url: http + 'fetch?f=' + rmt + '&d=' + editNip,
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                id: function () {
                                    return $('#edit-dokter').find('input[type=hidden]').val();
                                },
                                nip: function () {
                                    return $('#edit-dokter').find("#nip").val();
                                }
                            }
                        }
                    },
                    spesialis: {
                        required: true,
                    },
                    nama: {
                        required: true,
                        rangelength: [3, 35],
                        hurufbae: true,
                    },
                    jk: {
                        required: true,
                    },
                    alamat: {
                        required: true,
                        rangelength: [15, 50],
                    },
                    tlpn: {
                        required: true,
                        angkonyo: true,
                    },
                    username: {
                        required: true,
                        rangelength: [8, 20],
                        usernamenyo: true,
                        remote: {
                            url: http + 'fetch?f=' + rmt + '&d=' + editDokter,
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                id: function () {
                                    return $('#edit-dokter').find('input[type=hidden]').val();
                                },
                                username: function () {
                                    return $('#edit-dokter').find("#username").val();
                                }
                            }
                        }
                    },
                    status: {
                        required: true,
                    },
                },
                messages: {
                    nip: {
                        required: "NIP harus diisi !",
                        rangelength: "NIP harus 18 digit angka !",
                        digits: "Tidak boleh mengandung simbol lain selain angka !",
                        remote: "NIP telah digunakan !"
                    },
                    spesialis: {
                        required: "Spesialis diperlukan !"
                    },
                    nama: {
                        required: "Nama harus diisi !",
                        rangelength: "Minimal 3 huruf dan Maksimal 25 huruf !",
                        hurufbae: "Tidak boleh mengandung simbol lain selain huruf !",
                    },
                    jk: {
                        required: "Jenis kelamin diperlukan !"
                    },
                    alamat: {
                        required: "Alamat diperlukan !",
                        rangelength: "Minimal 15 karakter dan Maksimal 50 karakter !",
                    },
                    tlpn: {
                        required: "Nomor telepon diperlukan !",
                        angkonyo: "Nomor tidak benar !"
                    },
                    username: {
                        required: "Username harus diisi !",
                        rangelength: "Minimal 8 karakter dan maksimal 25 karakter !",
                        usernamenyo: "Gunakan huruf kecil atau angka atau <i>Underscore</i> _",
                        remote: "Username telah digunakan !"
                    },
                    status: {
                        required: "Silahan pilih status akun !",
                    },
                },
                errorElement: "em",
                errorPlacement: function (error, element) {
                    error.addClass("help-block");
                    element.parents(".col-xs-9").addClass("has-feedback");

                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.parent("label"));
                    } else {
                        error.insertAfter(element);
                    }
                    if (!element.next("span")[0]) {
                        $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass(errorClass).removeClass(validClass);
                    $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass(validClass).removeClass(errorClass);
                    $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
                },
                submitHandler: function (form) {
                    var uuid = $('#edit-dokter').attr('data-target');
                    var dokter = new FormData($('#edit-dokter')[0]);
                    var uid = $('#edit-dokter').find('input[type=hidden]').val();
                    $.ajax({
                        url: http + 'fetch?f=' + rmt + '&d=' + uuid + '&id=' + uid,
                        type: 'POST',
                        async: true,
                        cache: false,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        timeout: 3000,
                        data: dokter,
                        beforeSend: function () {
                            showLoading();
                        },
                        success: function (res) {
                            if (res.length == 0) {
                                hideLoading();
                                alertDanger('Invalid request');
                            } else {
                                if (res.dokter.code == 1) {
                                    ajaxSuccess('#edit-dokter');
                                    window.location.href = '';
                                    hideLoading();
                                    alertSuccess(res.dokter.message);
                                } else {
                                    hideLoading();
                                    alertWarning(res.dokter.message);
                                }
                            }
                        },
                        error: function (jqXHR, status, error) {
                            hideLoading();
                            alertDanger(status);
                        }
                    });
                    return false;
                }
            });

            jQuery.noConflict();

            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                var uid = $(this).attr('data-profile');
                $a = $('#edit-dokter').find('input[type=hidden],input[type=text], select, textarea');

                $.ajax({
                    url: http + 'fetch?f=' + remote + '&d=' + target + '&id=' + uid + '&tipe=dokter',
                    type: 'POST',
                    async: true,
                    dataType: 'json',
                    beforeSend: function () {
                        showLoading();
                        sp.empty();
                    },
                    success: function (res) {
                        if (res.length == 0) {
                            hideLoading();
                            alertDanger('Invalid request');
                        } else {
                            if (res.dokter.code) {
                                hideLoading();
                                $('#editModal').modal({
                                    'show': true,
                                    'backdrop': 'static'
                                });
                                let slct_sp = "<option selected disabled value=\"\">Pilih Spesialis</option>";
                                let sp_data = res.dokter.spesialis;
                                for (let i = 0; i < sp_data.length; i++) {
                                    let k = sp_data[i].key;
                                    let v = sp_data[i].value;
                                    slct_sp += "<option value=\"" + k + "\">" + v + "</option>";
                                }
                                sp.append(slct_sp);

                                for (let i = 0; i < $a.length; i++) {
                                    $a.eq(i).val(res.dokter.data[i]);
                                }
                            } else {
                                hideLoading();
                                alertWarning(res.dokter.message);
                            }
                        }
                    },
                    error: function (jqXHR, status, error) {
                        hideLoading();
                        alertDanger(status);
                    }
                });
                return false;
            });
        });
    </script>
    <?php
} elseif ($tipe == "pasien") {
    ?>
    <script>
        $(function() {
            var rmt = $('#remotenyo').val();
            var remote = $('#edit').attr('data-remote');
            var target = $('#edit').attr('data-target');
            var editNik = $('#edit-pasien').find('#nik').attr('data-target');
            var editPasien = $('#edit-pasien').find('#username').attr('data-target');

            var sp = $('#add-pasien,#edit-pasien').find('#darah');
            var bpjs = $('#add-pasien,#edit-pasien').find('#bpjs');
            var no_bpjs = $('#add-pasien,#edit-pasien').find('#no_bpjs');
            sp.empty();
            bpjs.empty();
            for (let i = 0; i < no_bpjs.length; i++) {
                no_bpjs[i].disabled = true;
            }

            $.validator.addMethod("hurufbae", function(value, element) {
                return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
            });
            $.validator.addMethod("angkonyo", function(value, element) {
                return this.optional(element) || value.length > 9 && /^[0-9.,]*$/.test(value);
            });
            $.validator.addMethod("usernamenyo", function(value, element) {
                return this.optional(element) || /^[a-z0-9_]*$/.test(value);
            });
            $.validator.addMethod("timenyo", function (value, element) {
                var validDate = !/Invalid|NaN/.test(new Date(value).toString());
                return this.optional(element) || validDate;
            }, "Please enter a valid date.");
            
            $('#tgl').datetimepicker({
                format: 'MM/DD/YYYY',
                maxDate: new Date(),
                viewMode: 'years',
                sideBySide: true,
            });

            // Hide modal & reset form
            $('[data-dismiss=modal]').on('click', function(e) {
                //e.preventDefault();
                var $t = $(this),
                    target = $t[0].href || $t.data("target") || $t.parents('.modal') || [];
                $(target).find('.form-group').attr('class', 'form-group');
                $(target).find('label.has-error').remove();
                $(target).find('span.glyphicon').remove();
                $(target).find('span.fa').remove();
                $(target).find('em.has-error').remove();
                $(target)
                    .find("input,textarea,select").val('').end()
                    .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
                closeModal();
            });

            // Animation modal bootstrap + library Animate.css
            function closeModal() {
                var timeoutHandler = null;
                $('#addModal,#editModal,#detailModal,#downloadModal').on('hide.bs.modal', function(e) {
                    var anim = $('.modal-dialog').removeClass('zoomIn').addClass('zoomOut');
                    if (timeoutHandler) clearTimeout(timeoutHandler);
                    timeoutHandler = setTimeout(function() {
                        $('.modal-dialog').removeClass('zoomOut').addClass('zoomIn');
                    }, 250); // some delay for complete Animation
                });
            }

            $('#edit-pasien').validate({
                errorClass: 'has-error animated tada',
                validClass: 'has-success',
                rules: {
                    kk: {
                        required: true,
                        rangelength: [16, 16],
                        digits: true,
                    },
                    nik: {
                        required: true,
                        rangelength: [16, 16],
                        digits: true,
                        remote: {
                            url: http + 'fetch?f=' + rmt + '&d=' + editNik,
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                id: function () {
                                    return $('#edit-pasien').find('input[type=hidden]').val();
                                },
                                nik: function () {
                                    return $('#edit-pasien').find("#nik").val();
                                }
                            }
                        }
                    },
                    nama: {
                        required: true,
                        rangelength: [3, 35],
                        hurufbae: true,
                    },
                    tmpt: {
                        required: true,
                    },
                    tgl: {
                        required: true,
                        timenyo: true,
                    },
                    jk: {
                        required: true,
                    },
                    darah: {
                        required: true,
                    },
                    alamat: {
                        required: true,
                        rangelength: [15, 50],
                    },
                    tlpn: {
                        required: true,
                        angkonyo: true,
                    },
                    bpjs: {
                        required: true,
                    },
                    no_bpjs: {
                        required: true,
                        rangelength: [13,13],
                        angkonyo: true,
                    },
                    username: {
                        required: true,
                        rangelength: [8, 20],
                        usernamenyo: true,
                        remote: {
                            url: http + 'fetch?f=' + rmt + '&d=' + editPasien,
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                id: function () {
                                    return $('#edit-pasien').find('input[type=hidden]').val();
                                },
                                username: function () {
                                    return $('#edit-pasien').find("#username").val();
                                }
                            }
                        }
                    },
                    status: {
                        required: true,
                    },
                },
                messages: {
                    kk: {
                        required: "Kartu Keluarga harus diisi !",
                        rangelength: "Kartu Keluarga harus 16 digit angka !",
                        digits: "Tidak boleh mengandung simbol lain selain angka !",
                    },
                    nik: {
                        required: "NIK harus diisi !",
                        rangelength: "NIK harus 16 digit angka !",
                        digits: "Tidak boleh mengandung simbol lain selain angka !",
                        remote: "NIK telah digunakan !"
                    },
                    nama: {
                        required: "Nama harus diisi !",
                        rangelength: "Minimal 3 karakter dan Maksimal 35 karakter !",
                        hurufbae: "Tidak boleh mengandung simbol lain selain huruf !",
                    },
                    tmpt: {
                        required: "Tempat lahir diperlukan !",
                    },
                    tgl: {
                        required: "Tanggal lahir diperlukan !",
                        timenyo: "Tanggal lahir tidak benar !",
                    },
                    jk: {
                        required: "Jenis kelamin diperlukan !"
                    },
                    darah: {
                        required: "Golongan darah diperlukan !"
                    },
                    alamat: {
                        required: "Alamat diperlukan !",
                        rangelength: "Minimal 15 karakter dan Maksimal 50 karakter !",
                    },
                    tlpn: {
                        required: "Nomor telepon diperlukan !",
                        angkonyo: "Nomor tidak benar !"
                    },
                    bpjs: {
                        required: "Pilih BPJS",
                    },
                    no_bpjs: {
                        required: "No. BPJS harus diperlukan !",
                        rangelength: "No. BPJS harus 13 digit !",
                        angkonyo: "No. BPJS harus angka !",
                    },
                    username: {
                        required: "Username harus diisi !",
                        rangelength: "Minimal 8 karakter dan maksimal 25 karakter !",
                        usernamenyo: "Gunakan huruf kecil atau angka atau <i>Underscore</i> _",
                        remote: "Username telah digunakan !"
                    },
                    status: {
                        required: "Silahan pilih status akun !",
                    },
                },
                errorElement: "em",
                errorPlacement: function (error, element) {
                    error.addClass("help-block");
                    element.parents(".col-xs-9").addClass("has-feedback");

                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.parent("label"));
                    } else {
                        error.insertAfter(element);
                    }
                    if (!element.next("span")[0]) {
                        $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass(errorClass).removeClass(validClass);
                    $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass(validClass).removeClass(errorClass);
                    $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
                },
                submitHandler: function (form) {
                    var uuid = $('#edit-pasien').attr('data-target');
                    var pasien = new FormData($('#edit-pasien')[0]);
                    var uid = $('#edit-pasien').find('input[type=hidden]').val();
                    $.ajax({
                        url: http + 'fetch?f=' + rmt + '&d=' + uuid + '&id=' + uid,
                        type: 'POST',
                        async: true,
                        cache: false,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        timeout: 3000,
                        data: pasien,
                        beforeSend: function () {
                            showLoading();
                        },
                        success: function (res) {
                            if (res.length == 0) {
                                hideLoading();
                                alertDanger('Invalid request');
                            } else {
                                if (res.pasien.code == 1) {
                                    ajaxSuccess('#edit-pasien');
                                    window.location.href = '';
                                    hideLoading();
                                    alertSuccess(res.pasien.message);
                                } else {
                                    hideLoading();
                                    alertWarning(res.pasien.message);
                                }
                            }
                        },
                        error: function (jqXHR, status, error) {
                            hideLoading();
                            alertDanger(status);
                        }
                    });
                    return false;
                }
            });

            jQuery.noConflict();

            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                var uid = $(this).attr('data-profile');
                $a = $('#edit-pasien').find('input[type=hidden],input[type=text], select, textarea');

                $.ajax({
                    url: http + 'fetch?f=' + remote + '&d=' + target + '&id=' + uid + '&tipe=pasien',
                    type: 'POST',
                    async: true,
                    dataType: 'json',
                    beforeSend: function () {
                        showLoading();
                        sp.empty();
                        bpjs.empty();
                    },
                    success: function (res) {
                        if (res.length == 0) {
                            hideLoading();
                            alertDanger('Invalid request');
                        } else {
                            if (res.pasien.code) {
                                hideLoading();
                                $('#editModal').modal({
                                    'show': true,
                                    'backdrop': 'static'
                                });
                                let slct_sp = "<option selected disabled value=\"\">Pilih Golongan Darah</option>";
                                let sp_data = res.pasien.darah;
                                for (let i = 0; i < sp_data.length; i++) {
                                    let k = sp_data[i].key;
                                    let v = sp_data[i].value;
                                    slct_sp += "<option value=\"" + k + "\">" + v + "</option>";
                                }
                                sp.append(slct_sp);

                                let slct_bpjs = "<option selected disabled value=\"\">Pilih BPJS</option>";
                                let sp_bpjs = res.pasien.bpjs;
                                for (let i = 0; i < sp_bpjs.length; i++) {
                                    let k = sp_bpjs[i].key;
                                    let v = sp_bpjs[i].value;
                                    slct_bpjs += "<option value=\"" + k + "\">" + v + "</option>";
                                }
                                bpjs.append(slct_bpjs);

                                for (let i = 0; i < $a.length; i++) {
                                    $a.eq(i).val(res.pasien.data[i]);
                                }
                            } else {
                                hideLoading();
                                alertWarning(res.pasien.message);
                            }
                        }
                    },
                    error: function (jqXHR, status, error) {
                        hideLoading();
                        alertDanger(status);
                    }
                });
                return false;
            });
        });
    </script>
    <?php
}
?>