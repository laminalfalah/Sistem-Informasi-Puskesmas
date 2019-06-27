$(function () {

    var remote = $('#table_pasien').attr('data-remote');
    var target = $('#table_pasien').attr('data-target');
    var addNik = $('#add-pasien').find('#nik').attr('data-target');
    var editNik = $('#edit-pasien').find('#nik').attr('data-target');
    var addPasien = $('#add-pasien').find('#username').attr('data-target');
    var editPasien = $('#edit-pasien').find('#username').attr('data-target');

    var sp = $('#add-pasien,#edit-pasien').find('#darah');
    var bpjs = $('#add-pasien,#edit-pasien').find('#bpjs');
    var no_bpjs = $('#add-pasien,#edit-pasien').find('#no_bpjs');
    sp.empty();
    bpjs.empty();

    $('#add-pasien').find('#nama').on('keyup', function(e) {
        e.preventDefault();
        var a = $(this).val();
        var b = a.replace(/[ ]/g,'_').toLowerCase();
        $('#add-pasien').find('#username').val(b);
    });

    $.validator.addMethod("hurufbae", function (value, element) {
        return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
    });
    $.validator.addMethod("angkonyo", function (value, element) {
        return this.optional(element) || value.length > 9 && /^[0-9.,]*$/.test(value);
    });
    $.validator.addMethod("usernamenyo", function (value, element) {
        return this.optional(element) || /^[a-z0-9_]*$/.test(value);
    });
    $.validator.addMethod("timenyo", function (value, element) {
        var validDate = !/Invalid|NaN/.test(new Date(value).toString());
        return this.optional(element) || validDate;
    }, "Please enter a valid date.");

    // Hide modal & reset form
    $('[data-dismiss=modal]').on('click', function (e) {
        //e.preventDefault();
        var $t = $(this), target = $t[0].href || $t.data("target") || $t.parents('.modal') || [];
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
        $('#addModal,#editModal,#detailModal,#downloadModal').on('hide.bs.modal', function (e) {
            var anim = $('.modal-dialog').removeClass('zoomIn').addClass('zoomOut');
            if (timeoutHandler) clearTimeout(timeoutHandler);
            timeoutHandler = setTimeout(function () {
                $('.modal-dialog').removeClass('zoomOut').addClass('zoomIn');
            }, 250); // some delay for complete Animation
        });
    }

    $('#tgl').datetimepicker({
        format: 'MM/DD/YYYY',
        maxDate: new Date(),
        viewMode: 'years',
        sideBySide: true,
    });

    // Validation pasien Start // 
    $('#add-pasien').validate({
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
                    url: http + 'fetch?f=' + remote + '&d=' + addNik,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        nik: function () {
                            return $('#add-pasien').find("#nik").val();
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
            agama: {
                required: true,
            },
            pekerjaan: {
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
                    url: http + 'fetch?f=' + remote + '&d=' + addPasien,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        username: function () {
                            return $('#add-pasien').find("#username").val();
                        }
                    }
                }
            },
            password: {
                required: true,
                rangelength: [8, 25],
            },
            password_confirm: {
                required: true,
                rangelength: [8, 25],
                equalTo: '#password1',
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
            agama: {
                required: "Agama diperlukan !"
            },
            pekerjaan: {
                required: "Pekerjaan diperlukan !"
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
            password: {
                required: "Password harus diisi",
                rangelength: "Minimal password 8 karakter dan maksimal 25 karakter",
            },
            password_confirm: {
                required: "Konfirmasi password harus diisi",
                rangelength: "Minimal password 8 karakter dan maksimal 25 karakter",
                equalTo: "Konfirmasi password harus sama dengan field password",
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
            var uuid = $('#add-pasien').attr('data-target');
            var pasien = new FormData($('#add-pasien')[0]);
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid,
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
                            ajaxSuccess('#add-pasien');
                            dataTable.ajax.reload();
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
                    url: http + 'fetch?f=' + remote + '&d=' + editNik,
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
            agama: {
                required: true,
            },
            pekerjaan: {
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
                    url: http + 'fetch?f=' + remote + '&d=' + editPasien,
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
            agama: {
                required: "Agama diperlukan !"
            },
            pekerjaan: {
                required: "Pekerjaan diperlukan !"
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
                url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
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
                            dataTable.ajax.reload();
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

    $('#download-pasien').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            awal: {
                required: true,
                digits: true,
            },
            akhir: {
                required: true,
                digits: true,
            },
            filter: {
                required: false
            },
            range: {
                required: false
            }
        },
        messages: {
            awal: {
                required: "Field harus diisi ! Minimal 1",
                digits: "Field tidak boleh mengandung karakter selain angka !"
            },
            akhir: {
                required: "Field harus diisi ! Maksimal 100",
                digits: "Field tidak boleh mengandung karakter selain angka !"
            }
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
            var page = $('#download-pasien').attr('data-remote');
            var uuid = $('#download-pasien').attr('data-target');
            var pdf = new FormData($('#download-pasien')[0]);
            var uid = "pasien";
            $.ajax({
                url: http + 'fetch?f=' + page + '&d=' + uuid + '&id=' + uid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: pdf,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == "") {
                        alertDanger('Invalid request');
                    } else {
                        if (res.download.code == 1) {
                            hideLoading();
                            ajaxSuccess('#download-pasien');
                            alertSuccess('Tunggu Sebentar ... ');
                            //window.open(res.download.url);
                            let win = window.open();
                            win.location = res.download.url;
                            win.opener = null;
                            win.blur();
                            window.focus();
                        } else {
                            hideLoading();
                            alertWarning(res.download.message);
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
    })
    // Validation pasien End //

    var dataTable = $("#table_pasien").DataTable({
        "language": {
            "sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
            "sProcessing": "Sedang memproses...",
            "sLengthMenu": "Tampilkan _MENU_ entri",
            "sZeroRecords": "Tidak ditemukan data yang sesuai",
            "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
            "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
            "sInfoPostFix": "",
            "sSearch": "Cari:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Pertama",
                "sPrevious": "Sebelumnya",
                "sNext": "Selanjutnya",
                "sLast": "Terakhir"
            }
        },
        "fixedHeader": true,
        "fixedColumns": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: http + 'fetch?f=' + remote + '&d=' + target,
            type: "POST",
            beforeSend: function () {
                $("#table_pasien_processing").addClass("text-center").html('<i class="fa fa-spinner fa-pulse fa-fw text-blue"></i>&nbsp;<span>Memuat Data...</span>');
            },
            error: function () {
                $(".table_pasien-error").html("");
                $("#table_pasien").append('<tbody class="table_pasien-error"><tr><td colspan="7" class="text-center">Tidak ada data</td></tr></tbody>');
                $("#table_pasien_processing").css('display', 'none');
            }
        },
        "pageLength": 10,
        "order": [
            [0, "desc"]
        ],
        columnDefs: [
            {
                orderable: true,
                targets: [0, 1, 2, 3, 4, 5]
            },
            {
                orderable: false,
                targets: [6]
            },
            {
                searchable: true,
                targets: [1, 2, 3]
            },
            {
                searchable: false,
                targets: [0, 4, 5, 6]
            }
        ],
        "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Semua"]
        ]
    });

    $("#table_pasien_filter").addClass("pull-right");
    $("#table_pasien_paginate").addClass("pull-right");

    dataTable.on("draw.dt", function () {
        var info = dataTable.page.info();
        dataTable.column(0, {
            search: "applied",
            order: "applied",
            page: "applied"
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1 + info.start + ".";
        });
    });

    bpjs.on('change',function(e) {
        e.preventDefault();
        if ((bpjs[0].value == "" || bpjs[0].value == "1") && (bpjs[1].value == "" || bpjs[1].value == "1")) {
            for (let j = 0; j < no_bpjs.length; j++) {
                no_bpjs[j].disabled = true;
                //no_bpjs[j].value = '-';
            }
        } else {
            for (let k = 0; k < no_bpjs.length; k++) {
                no_bpjs[k].disabled = false;
                //no_bpjs[k].value = '';
            }
        }
        return false;
    });

    jQuery.noConflict();

    $("#create_pasien").on('click', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        $.ajax({
            url: http + 'fetch?f=' + remote + '&d=' + uuid,
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
                        $('#addModal').modal({
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

    $("#table_pasien").on('click', '#edit', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        $a = $('#edit-pasien').find('input[type=hidden],input[type=text], select, textarea');

        $.ajax({
            url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
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

                        if (bpjs[1].value == "" || bpjs[1].value == "1") {
                            for (let j = 0; j < no_bpjs.length; j++) {
                                no_bpjs[j].disabled = true;
                            }
                        } else {
                            for (let k = 0; k < no_bpjs.length; k++) {
                                no_bpjs[k].disabled = false;
                            }
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
    });

    $("#table_pasien").on('click', '#detail', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        var body = $('#detail-table');

        $.ajax({
            url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function () {
                showLoading();
                body.empty();
            },
            success: function (res) {
                if (res.length == 0) {
                    hideLoading();
                    alertDanger('Invalid request');
                } else {
                    if (res.pasien.code == 1) {
                        hideLoading();
                        $('#detailModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        var s = '';
                        let stts = res.pasien.data.stts;
                        if (stts == 2) {
                            s = '<label class="label label-danger">Blok</label>';
                        } else if (stts == 1) {
                            s = '<label class="label label-success">Aktif</label>';
                        } else {
                            s = '<label class="label label-warning">Tidak Aktif</label>';
                        }
                        var j = '';
                        let jk = res.pasien.data.jk;
                        if (jk == "P") {
                            j = '<label class="label label-primary">Pria</label>';
                        } else {
                            j = '<label class="label label-info">Wanita</label>';
                        }
                        let tr_str = '';
                        tr_str += "<tr><td>Kartu Keluarga</td><td>" + res.pasien.data.kk + "</td></tr>" +
                            "<tr><td>NIK</td><td>" + res.pasien.data.nik + "</td></tr>" +
                            "<tr><td>Jenis BPJS</td><td>" + res.pasien.data.jns + "</td></tr>" +
                            "<tr><td>No. BPJS</td><td>" + res.pasien.data.no + "</td></tr>" +
                            "<tr><td>Nama</td><td>" + res.pasien.data.nama + "</td></tr>" +
                            "<tr><td>Tempat, Tanggal Lahir</td><td>" + res.pasien.data.tmpt + ", " + moment(res.pasien.data.tgl).format("DD-MMMM-YYYY") + "</td></tr>" +
                            "<tr><td>Jenis Kelamin</td><td>" + j + "</td></tr>" +
                            "<tr><td>Agama</td><td>" + res.pasien.data.agm + "</td></tr>" +
                            "<tr><td>Pekerjaan</td><td>" + res.pasien.data.pkj + "</td></tr>" +
                            "<tr><td>Golongan Darah</td><td>" + res.pasien.data.darah + "</td></tr>" +
                            "<tr><td>Alamat</td><td>" + res.pasien.data.almt + "</td></tr>" +
                            "<tr><td>Telepon</td><td>" + res.pasien.data.tlpn + "</td></tr>" +
                            "<tr><td>Username</td><td>" + res.pasien.data.user + "</td></tr>" +
                            "<tr><td>Status</td><td>" + s + "</td></tr>";
                        body.append(tr_str);
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
    });

    $("#table_pasien").on('click', '#download', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');

        $.ajax({
            url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function () {
                showLoading();
            },
            success: function (res) {
                if (res.length == 0) {
                    hideLoading();
                    alertDanger('Invalid request');
                } else {
                    if (res.pasien.code == 1) {
                        hideLoading();
                        alertSuccess(res.pasien.message);
                        let win = window.open();
                        win.location = res.pasien.url;
                        win.opener = null;
                        win.blur();
                        window.focus();
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
    });

    $("#table_pasien").on('click', '#hapus', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Menghapus data pasien <b>' + nm + '</b> ?',
            type: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then(function (isConfirm) {
            if (isConfirm.value) {
                $.ajax({
                    url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
                    type: 'POST',
                    dataType: 'json',
                    async: true,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        showLoading();
                    },
                    success: function (res) {
                        if (res.length == 0) {
                            hideLoading();
                            alertDanger('Invalid request');
                        } else {
                            if (res.pasien.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
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
            }
        });
    });

    $("#table_pasien").on('click', '#reset', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');

        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Mereset password pasien <b>' + nm + '</b> ?',
            type: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then(function (isConfirm) {
            if (isConfirm.value) {
                $.ajax({
                    url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
                    type: 'POST',
                    dataType: 'json',
                    async: true,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        showLoading();
                    },
                    success: function (res) {
                        if (res.length == 0) {
                            hideLoading();
                            alertDanger('Invalid request');
                        } else {
                            if (res.pasien.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
                                alertSuccess(res.pasien.message)
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
            }
        });
    });

    $("#download_pasien").on('click', function (e) {
        e.preventDefault();
        var rule = $(this).attr('data-remote');
        var uuid = $(this).attr('data-target');
        $.ajax({
            url: http + 'fetch?f=' + rule + '&d=' + uuid + '&tipe=pasien',
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function () {
                showLoading();
            },
            success: function (res) {
                if (res.length == 0) {
                    hideLoading();
                    alertDanger('Invalid request');
                } else {
                    if (res.download.code == 1) {
                        hideLoading();
                        $('#downloadModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        $('#download-pasien h4.pull-right').html('Jumlah data sebanyak : ' + res.download.count + ' pasien.');
                    } else {
                        hideLoading();
                        alertWarning(res.download.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $('#filter').on('click change', function () {
        $a = $(this);
        if ($a.val() == 3) {
            $('#range').prop('disabled', false).focus();
        } else {
            $('#range').val('').attr('disabled', true).closest('.form-group').removeClass('has-success');
        }
    });

    $('#range').daterangepicker({
        locale: {
            format: "DD/MM/YYYY"
        },
        showDropdowns: true,
        autoApply: true,
        startDate: start,
        endDate: end,
        maxDate: end,
        opens: "center",
        drops: "down",
    }, function (start, end, label) {
        //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });
});