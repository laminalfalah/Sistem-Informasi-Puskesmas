$(function () {

    var remote = $('#table_dokter').attr('data-remote');
    var target = $('#table_dokter').attr('data-target');
    var addNip = $('#add-dokter').find('#nip').attr('data-target');
    var editNip = $('#edit-dokter').find('#nip').attr('data-target');
    var addDokter = $('#add-dokter').find('#username').attr('data-target');
    var editDokter = $('#edit-dokter').find('#username').attr('data-target');

    var sp = $('#add-dokter,#edit-dokter').find('#spesialis');
    sp.empty();

    $('#add-dokter').find('#nama').on('keyup', function(e) {
        e.preventDefault();
        var a = $(this).val();
        var b = a.replace(/[ ]/g,'_').toLowerCase();
        $('#add-dokter').find('#username').val(b);
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

    // Validation dokter Start // 
    $('#add-dokter').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            nip: {
                required: true,
                rangelength: [18, 18],
                digits: true,
                remote: {
                    url: http + 'fetch?f=' + remote + '&d=' + addNip,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        nip: function () {
                            return $('#add-dokter').find("#nip").val();
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
                    url: http + 'fetch?f=' + remote + '&d=' + addDokter,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        username: function () {
                            return $('#add-dokter').find("#username").val();
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
                rangelength: "Minimal 3 karakter dan Maksimal 35 karakter !",
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
            var uuid = $('#add-dokter').attr('data-target');
            var dokter = new FormData($('#add-dokter')[0]);
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid,
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
                            ajaxSuccess('#add-dokter');
                            dataTable.ajax.reload();
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

    $('#edit-dokter').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            nip: {
                required: true,
                rangelength: [18, 18],
                digits: true,
                remote: {
                    url: http + 'fetch?f=' + remote + '&d=' + editNip,
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
                    url: http + 'fetch?f=' + remote + '&d=' + editDokter,
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
                url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
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
                            dataTable.ajax.reload();
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

    $('#download-dokter').validate({
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
            var page = $('#download-dokter').attr('data-remote');
            var uuid = $('#download-dokter').attr('data-target');
            var pdf = new FormData($('#download-dokter')[0]);
            var uid = "dokter";
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
                            ajaxSuccess('#download-dokter');
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
    });
    // Validation dokter End //

    var dataTable = $("#table_dokter").DataTable({
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
                $("#table_dokter_processing").addClass("text-center").html('<i class="fa fa-spinner fa-pulse fa-fw text-blue"></i>&nbsp;<span>Memuat Data...</span>');
            },
            error: function () {
                $(".table_dokter-error").html("");
                $("#table_dokter").append('<tbody class="table_dokter-error"><tr><td colspan="7" class="text-center">Tidak ada data</td></tr></tbody>');
                $("#table_dokter_processing").css('display', 'none');
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

    $("#table_dokter_filter").addClass("pull-right");
    $("#table_dokter_paginate").addClass("pull-right");

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

    jQuery.noConflict();

    $("#create_dokter").on('click', function (e) {
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
            },
            success: function (res) {
                if (res.length == 0) {
                    hideLoading();
                    alertDanger('Invalid request');
                } else {
                    if (res.dokter.code) {
                        hideLoading();
                        $('#addModal').modal({
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

    $("#table_dokter").on('click', '#edit', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        $a = $('#edit-dokter').find('input[type=hidden],input[type=text], select, textarea');

        $.ajax({
            url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
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

    $("#table_dokter").on('click', '#detail', function (e) {
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
                    if (res.dokter.code == 1) {
                        hideLoading();
                        $('#detailModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        var s = '';
                        let stts = res.dokter.data.stts;
                        if (stts == 2) {
                            s = '<label class="label label-danger">Blok</label>';
                        } else if (stts == 1) {
                            s = '<label class="label label-success">Aktif</label>';
                        } else {
                            s = '<label class="label label-warning">Tidak Aktif</label>';
                        }
                        var j = '';
                        let jk = res.dokter.data.jk;
                        if (jk == "P") {
                            j = '<label class="label label-primary">Pria</label>';
                        } else {
                            j = '<label class="label label-info">Wanita</label>';
                        }
                        let tr_str = '';
                        tr_str += "<tr><td>NIP</td><td>" + res.dokter.data.nip + "</td></tr>" +
                            "<tr><td>Spesialis</td><td>" + res.dokter.data.spesialis + "</td></tr>" +
                            "<tr><td>Nama</td><td>" + res.dokter.data.nama + "</td></tr>" +
                            "<tr><td>Jenis Kelamin</td><td>" + j + "</td></tr>" +
                            "<tr><td>Alamat</td><td>" + res.dokter.data.almt + "</td></tr>" +
                            "<tr><td>Telepon</td><td>" + res.dokter.data.tlpn + "</td></tr>" +
                            "<tr><td>Username</td><td>" + res.dokter.data.user + "</td></tr>" +
                            "<tr><td>Status</td><td>" + s + "</td></tr>";
                        body.append(tr_str);
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

    $("#table_dokter").on('click', '#download', function (e) {
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
                    if (res.dokter.code == 1) {
                        hideLoading();
                        alertSuccess(res.dokter.message);
                        let win = window.open();
                        win.location = res.dokter.url;
                        win.opener = null;
                        win.blur();
                        window.focus();
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

    $("#table_dokter").on('click', '#hapus', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Menghapus data dokter <b>' + nm + '</b> ?',
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
                            if (res.dokter.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
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
            }
        });
        return false;
    });

    $("#table_dokter").on('click', '#reset', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');

        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Mereset password dokter <b>' + nm + '</b> ?',
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
                            if (res.dokter.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
                                alertSuccess(res.dokter.message)
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
            }
        });
        return false;
    });

    $("#download_dokter").on('click', function (e) {
        e.preventDefault();
        var rule = $(this).attr('data-remote');
        var uuid = $(this).attr('data-target');
        $.ajax({
            url: http + 'fetch?f=' + rule + '&d=' + uuid + '&tipe=dokter',
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
                        $('#download-dokter h4.pull-right').html('Jumlah data sebanyak : ' + res.download.count + ' dokter.');
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