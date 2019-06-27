$(function () {

    var remote = $('#table_staff').attr('data-remote');
    var target = $('#table_staff').attr('data-target');
    var addNip = $('#add-staff').find('#nip').attr('data-target');
    var editNip = $('#edit-staff').find('#nip').attr('data-target');
    var addStaff = $('#add-staff').find('#username').attr('data-target');
    var editStaff = $('#edit-staff').find('#username').attr('data-target');

    var sp = $('#add-staff,#edit-staff').find('#level');
    sp.empty();

    $('#add-staff').find('#nama').on('keyup', function(e) {
        e.preventDefault();
        var a = $(this).val();
        var b = a.replace(/[ ]/g,'_').toLowerCase();
        $('#add-staff').find('#username').val(b);
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

    // Validation staff Start // 
    $('#add-staff').validate({
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
                            return $('#add-staff').find("#nip").val();
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
                    url: http + 'fetch?f=' + remote + '&d=' + addStaff,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        username: function () {
                            return $('#add-staff').find("#username").val();
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
            level: {
                required: "Level diperlukan !"
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
            var uuid = $('#add-staff').attr('data-target');
            var staff = new FormData($('#add-staff')[0]);
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: staff,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.staff.code == 1) {
                            ajaxSuccess('#add-staff');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.staff.message);
                        } else {
                            hideLoading();
                            alertWarning(res.staff.message);
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

    $('#edit-staff').validate({
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
                            return $('#edit-staff').find('input[type=hidden]').val();
                        },
                        nip: function () {
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
                    url: http + 'fetch?f=' + remote + '&d=' + editStaff,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id: function () {
                            return $('#edit-staff').find('input[type=hidden]').val();
                        },
                        username: function () {
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
            var uuid = $('#edit-staff').attr('data-target');
            var staff = new FormData($('#edit-staff')[0]);
            var uid = $('#edit-staff').find('input[type=hidden]').val();
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: staff,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.staff.code == 1) {
                            ajaxSuccess('#edit-staff');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.staff.message);
                        } else {
                            hideLoading();
                            alertWarning(res.staff.message);
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

    $('#download-staff').validate({
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
            var page = $('#download-staff').attr('data-remote');
            var uuid = $('#download-staff').attr('data-target');
            var pdf = new FormData($('#download-staff')[0]);
            var uid = "staff";
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
                            ajaxSuccess('#download-staff');
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
    // Validation staff End //

    var dataTable = $("#table_staff").DataTable({
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
                $("#table_staff_processing").addClass("text-center").html('<i class="fa fa-spinner fa-pulse fa-fw text-blue"></i>&nbsp;<span>Memuat Data...</span>');
            },
            error: function () {
                $(".table_staff-error").html("");
                $("#table_staff").append('<tbody class="table_staff-error"><tr><td colspan="7" class="text-center">Tidak ada data</td></tr></tbody>');
                $("#table_staff_processing").css('display', 'none');
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

    $("#table_staff_filter").addClass("pull-right");
    $("#table_staff_paginate").addClass("pull-right");

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

    $("#create_staff").on('click', function (e) {
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
                    if (res.staff.code) {
                        hideLoading();
                        $('#addModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        let slct_sp = "<option selected disabled value=\"\">Pilih Level</option>";
                        let sp_data = res.staff.level;
                        for (let i = 0; i < sp_data.length; i++) {
                            let k = sp_data[i].key;
                            let v = sp_data[i].value;
                            slct_sp += "<option value=\"" + k + "\">" + v + "</option>";
                        }
                        sp.append(slct_sp);
                    } else {
                        hideLoading();
                        alertWarning(res.staff.message);
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

    $("#table_staff").on('click', '#edit', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        $a = $('#edit-staff').find('input[type=hidden],input[type=text], select, textarea');

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
                    if (res.staff.code) {
                        hideLoading();
                        $('#editModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        let slct_sp = "<option selected disabled value=\"\">Pilih Level</option>";
                        let sp_data = res.staff.level;
                        for (let i = 0; i < sp_data.length; i++) {
                            let k = sp_data[i].key;
                            let v = sp_data[i].value;
                            slct_sp += "<option value=\"" + k + "\">" + v + "</option>";
                        }
                        sp.append(slct_sp);

                        for (let i = 0; i < $a.length; i++) {
                            $a.eq(i).val(res.staff.data[i]);
                        }
                    } else {
                        hideLoading();
                        alertWarning(res.staff.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_staff").on('click', '#detail', function (e) {
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
                    if (res.staff.code == 1) {
                        hideLoading();
                        $('#detailModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        var s = '';
                        let stts = res.staff.data.stts;
                        if (stts == 2) {
                            s = '<label class="label label-danger">Blok</label>';
                        } else if (stts == 1) {
                            s = '<label class="label label-success">Aktif</label>';
                        } else {
                            s = '<label class="label label-warning">Tidak Aktif</label>';
                        }
                        var j = '';
                        let jk = res.staff.data.jk;
                        if (jk == "P") {
                            j = '<label class="label label-primary">Pria</label>';
                        } else {
                            j = '<label class="label label-info">Wanita</label>';
                        }
                        let tr_str = '';
                        tr_str += "<tr><td>NIP</td><td>" + res.staff.data.nip + "</td></tr>" +
                            "<tr><td>Level</td><td>" + res.staff.data.level + "</td></tr>" +
                            "<tr><td>Nama</td><td>" + res.staff.data.nama + "</td></tr>" +
                            "<tr><td>Jenis Kelamin</td><td>" + j + "</td></tr>" +
                            "<tr><td>Alamat</td><td>" + res.staff.data.almt + "</td></tr>" +
                            "<tr><td>Telepon</td><td>" + res.staff.data.tlpn + "</td></tr>" +
                            "<tr><td>Username</td><td>" + res.staff.data.user + "</td></tr>" +
                            "<tr><td>Status</td><td>" + s + "</td></tr>";
                        body.append(tr_str);
                    } else {
                        hideLoading();
                        alertWarning(res.staff.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_staff").on('click', '#download', function (e) {
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
                    if (res.staff.code == 1) {
                        hideLoading();
                        alertSuccess(res.staff.message);
                        let win = window.open();
                        win.location = res.staff.url;
                        win.opener = null;
                        win.blur();
                        window.focus();
                    } else {
                        hideLoading();
                        alertWarning(res.staff.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_staff").on('click', '#hapus', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Menghapus data staff <b>' + nm + '</b> ?',
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
                            if (res.staff.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
                                alertSuccess(res.staff.message);
                            } else {
                                hideLoading();
                                alertWarning(res.staff.message);
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

    $("#table_staff").on('click', '#reset', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');

        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Mereset password staff <b>' + nm + '</b> ?',
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
                            if (res.staff.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
                                alertSuccess(res.staff.message)
                            } else {
                                hideLoading();
                                alertWarning(res.staff.message);
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

    $("#download_staff").on('click', function (e) {
        e.preventDefault();
        var rule = $(this).attr('data-remote');
        var uuid = $(this).attr('data-target');
        $.ajax({
            url: http + 'fetch?f=' + rule + '&d=' + uuid + '&tipe=staff',
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
                        $('#download-staff h4.pull-right').html('Jumlah data sebanyak : ' + res.download.count + ' staff.');
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