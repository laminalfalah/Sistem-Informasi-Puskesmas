$(function() {

    var remote = $('#table_berita').attr('data-remote');
    var target = $('#table_berita').attr('data-target');

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

    function validate() {
        a = !tinymce.EditorManager.get('description').getContent() == '';
        if (!a) {
            alertWarning("Deskripsi tidak boleh kosong !");
        }
        return a;
    }

    function validate_edit() {
        a = !tinymce.EditorManager.get('description_edit').getContent() == '';
        if (!a) {
            alertWarning("Deskripsi tidak boleh kosong !");
        }
        return a;
    }

    // Validation Alat Start // 
    $('#add-berita').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            title: {
                required: true,
                rangelength: [3, 50],
            },
            status: {
                required: true
            },
        },
        messages: {
            title: {
                required: "Title Berita harus diisi !",
                rangelength: "Minimal 3 huruf dan Maksimal 50 huruf !",
            },
            satuan: {
                required: "Pilih status berita !"
            },
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            error.addClass("help-block");
            element.parents(".col-xs-9").addClass("has-feedback");

            if (element.prop( "type" ) === "checkbox") {
                error.insertAfter( element.parent("label"));
            } else {
                error.insertAfter( element);
            }
            if (!element.next( "span" )[ 0 ] ) {
                $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
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
            if (!validate()) return false;
            tinyMCE.triggerSave();
            var uuid = $('#add-berita').attr('data-target');
            var sp = new FormData($('#add-berita')[0]);
            $.ajax({
                url: http + 'fetch?f='+remote+'&d='+uuid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: sp,
                beforeSend: function() {
                    showLoading();
                },
                success: function(res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.berita.code == 1) {
                            ajaxSuccess('#add-berita');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.berita.message);
                        } else {
                            hideLoading();
                            alertWarning(res.berita.message);
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

    $('#edit-berita').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            title: {
                required: true,
                rangelength: [3, 50],
            },
            status: {
                required: true
            },
        },
        messages: {
            title: {
                required: "Title Berita harus diisi !",
                rangelength: "Minimal 3 huruf dan Maksimal 50 huruf !",
            },
            status: {
                required: "Pilih status berita !"
            },
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            error.addClass("help-block");
            element.parents(".col-xs-9").addClass("has-feedback");

            if (element.prop( "type" ) === "checkbox") {
                error.insertAfter( element.parent("label"));
            } else {
                error.insertAfter( element);
            }
            if (!element.next( "span" )[ 0 ]) {
                $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
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
            if (!validate_edit()) return false;
            var uuid = $('#edit-berita').attr('data-target');
            var sp = new FormData($('#edit-berita')[0]);
            var uid = $('#edit-berita').find('input[type=hidden]').val();
            $.ajax({
                url: http + 'fetch?f='+remote+'&d='+uuid+'&id='+uid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: sp,
                beforeSend: function() {
                    showLoading();
                },
                success: function(res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.berita.code == 1) {
                            ajaxSuccess('#edit-berita');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.berita.message);
                        } else {
                            hideLoading();
                            alertWarning(res.berita.message);
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

    $('#add-komentar').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            nama: {
                required: true,
                rangelength: [1, 30],
            },
            komentar: {
                required: true,
                rangelength: [10, 300],
            }
        },
        messages: {
            nama: {
                required: "Nama harus diisi !",
                rangelength: "Minimal 1 karakter dan maksimal 30 karakter !",
            },
            komentar: {
                required: "Komentar harus diisi !",
                rangelength: "Minimal 10 karakter dan maksimal 300 karakter !",
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
            var uuid = $('#add-komentar').attr('data-target');
            var darah = new FormData($('#add-komentar')[0]);
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: darah,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.komen.code) {
                            ajaxSuccess('#add-komentar');
                            hideLoading();
                            alertSuccess(res.komen.message);
                        } else {
                            hideLoading();
                            alertWarning(res.komen.message);
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
    // Validation Alat End //

    var dataTable = $("#table_berita").DataTable({
        "language": {
            "sEmptyTable":   "Tidak ada data yang tersedia pada tabel ini",
            "sProcessing":   "Sedang memproses...",
            "sLengthMenu":   "Tampilkan _MENU_ entri",
            "sZeroRecords":  "Tidak ditemukan data yang sesuai",
            "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
            "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
            "sInfoPostFix":  "",
            "sSearch":       "Cari:",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "Pertama",
                "sPrevious": "Sebelumnya",
                "sNext":     "Selanjutnya",
                "sLast":     "Terakhir"
            }
        },
        //"scrollY": true,
        "fixedHeader": true,
        "fixedColumns": true,
        //"autoWidth": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: http + 'fetch?f='+remote+'&d='+target,
            type: "POST",
            beforeSend: function () {
                $("#table_berita_processing").addClass("text-center").html('<i class="fa fa-spinner fa-pulse fa-fw text-blue"></i>&nbsp;<span>Memuat Data...</span>');
            },
            error: function () {
                $(".table_berita-error").html("");
                $("#table_berita").append('<tbody class="table_berita-error"><tr><td colspan="4" class="text-center">Tidak ada data</td></tr></tbody>');
                $("#table_berita_processing").css('display', 'none');
            }
        },
        "pageLength": 10,
        "order": [
            [0,"desc"]
        ],
        columnDefs: [
            {
                orderable: true,
                targets: [0,1,2]
            },
            {
                orderable: false,
                targets: [3]
            },
            {
                searchable: true,
                targets: [1,2]
            },
            {
                searchable: false,
                targets: [0,1]
            }
        ],
        "lengthMenu": [ 
            [5, 10, 25, 50, 100, -1], 
            [5, 10, 25, 50, 100, "Semua"]
        ]
    });

    $("#table_berita_filter").addClass("pull-right");
    $("#table_berita_paginate").addClass("pull-right");
    
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

    $("#create_berita").on('click', function (e) {
        e.preventDefault();
        $('#addModal').modal({
            'show': true,
            'backdrop': 'static'
        });
    });

    $("#table_berita").on('click', '#edit', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        $a = $('#edit-berita').find('input[type=hidden],input[type=text], select, textarea');

        $.ajax({
            url: http + 'fetch?f='+remote+'&d='+uuid+'&id='+uid,
            type: 'GET',
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
                    if (res.berita.code == 1) {
                        hideLoading();
                        $('#editModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        for (let i = 0; i < $a.length - 2; i++) {
                            $a.eq(i).val(res.berita.data[i]);
                        }
                        tinymce.get('cover_edit').setContent(res.berita.data[3]);
                        tinymce.get('description_edit').setContent(res.berita.data[4]);
                    } else {
                        hideLoading();
                        alertWarning(res.berita.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_berita").on('click', '#detail', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        var body = $('#detail-table').empty();

        $.ajax({
            url: http + 'fetch?f='+remote+'&d='+uuid+'&id='+uid,
            type: 'GET',
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
                    if (res.berita.code == 1) {
                        hideLoading();
                        $('#detailModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        let src = http + 'assets/img/default.png';
                        let img = '<img src=\"' + src + '\">';
                        let cover = res.berita.data.cover != "" ? res.berita.data.cover : img;
                        let stts = res.berita.data.status == 1 ? '<label class="label label-success">Publish</label>' : '<label class="label label-warning">Draft</label>';
                        let tr_str = '';
                        tr_str += "<tr><td colspan=\"2\">" + cover + "</td></tr>" +
                                  "<tr><td>Judul</td><td>" + res.berita.data.title + "</td></tr>" +
                                  "<tr><td>Status</td><td>" + stts + "</td></tr>" +
                                  "<tr><td>Deskripsi</td><td>" + res.berita.data.content + "</td></tr>" +
                                  "<tr><td>Tanggal Buat</td><td>" + moment(res.berita.data.created_at).format("dddd, DD-MMMM-YYYY HH:mm:ss") + "</td></tr>";

                        $('#detail-table').append(tr_str);
                    } else {
                        hideLoading();
                        alertWarning(res.berita.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_berita").on('click', '#komen', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        var body = $('#detail-table-komen').empty();
        $a = $('#add-komentar').find('input[type=hidden]');

        $.ajax({
            url: http + 'fetch?f='+remote+'&d='+uuid+'&id='+uid,
            type: 'GET',
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
                    let datanyo = "";
                    if (res.komen.code) {
                        if (res.komen.data.length > 0) {
                            hideLoading();
                            $a.eq(0).val(res.komen.berita);
                            for (let i = 0; i < res.komen.data.length; i++) {
                                let tgl = res.komen.data[i].tgl;
                                let title = res.komen.data[i].name;
                                let cont = res.komen.data[i].comment;
                                let hps = res.komen.data[i].hps;

                                datanyo += "<tr><td>Nama</td><td>" + title + "</td></tr>" +
                                            "<tr><td>Tanggal</td><td>" + moment(tgl).format("dddd, DD-MMMM-YYYY HH:mm:ss") + "</td></tr>" +
                                            "<tr><td>Komentar</td><td>" + cont + "</td></tr>" + 
                                            "<tr><td colspan=\"2\">" + hps + "</td></tr>";
                            }
                        }
                    } else {
                        hideLoading();
                        datanyo = "<tr><td colspan=\"2\">" + res.komen.message + "</td></tr>";
                    }
                    $('#komenModal').modal({
                        'show': true,
                        'backdrop': 'static'
                    });
                    $('#detail-table-komen').append(datanyo);
                    /*
                    if (res.berita.code == 1) {
                        hideLoading();
                        $('#komenModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        let src = http + 'assets/img/default.png';
                        let img = '<img src=\"' + src + '\">';
                        let cover = res.berita.data.cover != "" ? res.berita.data.cover : img;
                        let stts = res.berita.data.status == 1 ? '<label class="label label-success">Publish</label>' : '<label class="label label-warning">Draft</label>';
                        let tr_str = '';
                        tr_str += "<tr><td colspan=\"2\">" + cover + "</td></tr>" +
                                  "<tr><td>Judul</td><td>" + res.berita.data.title + "</td></tr>" +
                                  "<tr><td>Status</td><td>" + stts + "</td></tr>" +
                                  "<tr><td>Deskripsi</td><td>" + res.berita.data.content + "</td></tr>" +
                                  "<tr><td>Tanggal Buat</td><td>" + moment(res.berita.data.created_at).format("dddd, DD-MMMM-YYYY HH:mm:ss") + "</td></tr>";

                        $('#detail-table-komen').append(tr_str);
                    } else {
                        hideLoading();
                        alertWarning(res.berita.message);
                    }
                    */
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $('#detail-table-komen').on('click', '#hps', function(e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Menghapus komentar <b>' + nm + '</b> ?',
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
                    url: http + 'fetch?f='+remote+'&d='+uuid+'&id='+uid,
                    type: 'POST',
                    dataType: 'json',
                    async: true,
                    processData: false,
                    contentType: false,
                    timeout: 3000,
                    beforeSend: function () {
                        showLoading();
                    },
                    success: function (res) {
                        if (res.length == 0) {
                            hideLoading();
                            alertDanger('Invalid request');
                        } else {
                            if (res.komen.code == 1) {
                                ajaxSuccess('#add-komentar');
                                hideLoading();
                                alertSuccess(res.komen.message);
                            } else {
                                hideLoading();
                                alertWarning(res.komen.message);
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

    $("#table_berita").on('click', '#hapus', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Menghapus data berita <b>' + nm + '</b> ?',
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
                    url: http + 'fetch?f='+remote+'&d='+uuid+'&id='+uid,
                    type: 'POST',
                    dataType: 'json',
                    async: true,
                    processData: false,
                    contentType: false,
                    timeout: 3000,
                    beforeSend: function () {
                        showLoading();
                    },
                    success: function (res) {
                        if (res.length == 0) {
                            hideLoading();
                            alertDanger('Invalid request');
                        } else {
                            if (res.berita.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
                                alertSuccess(res.berita.message);
                            } else {
                                hideLoading();
                                alertWarning(res.berita.message);
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
});