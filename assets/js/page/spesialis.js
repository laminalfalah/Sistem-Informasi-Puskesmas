$(function () {

    var remote = $('#table_spesialis').attr('data-remote');
    var target = $('#table_spesialis').attr('data-target');
    var addKode = $('#add-spesialis').find('#kode').attr('data-target');
    var addSpesialis = $('#add-spesialis').find('#nama').attr('data-target');
    var editSpesialis = $('#edit-spesialis').find('#nama').attr('data-target');

    $.validator.addMethod("hurufbae", function (value, element) {
        return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
    });
    $.validator.addMethod("angkonyo", function (value, element) {
        return this.optional(element) || value.length > 9 && /^[0-9.,]*$/.test(value);
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

    // Validation spesialis Start // 
    $('#add-spesialis').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            kode: {
                required: true,
                rangelength: [3, 5],
                remote: {
                    url: http + 'fetch?f=' + remote + '&d=' + addKode,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        kode: function () {
                            return $('#add-spesialis').find("#kode").val();
                        }
                    }
                }
            },
            nama: {
                required: true,
                rangelength: [3, 20],
                remote: {
                    url: http + 'fetch?f=' + remote + '&d=' + addSpesialis,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        nama: function () {
                            return $('#add-spesialis').find("#nama").val();
                        }
                    }
                }
            },
        },
        messages: {
            kode: {
                required: "Kode harus diisi !",
                rangelength: "Kode minimal 3 karakter dan maksimal 5 karakter !",
                remote: "Kode telah digunakan !"
            },
            nama: {
                required: "Nama Spesialis harus diisi !",
                rangelength: "Minimal 3 karakter dan maksimal 20 karakter !",
                remote: "Nama Spesialis telah digunakan !"
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
            var uuid = $('#add-spesialis').attr('data-target');
            var spesialis = new FormData($('#add-spesialis')[0]);
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: spesialis,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.spesialis.code == 1) {
                            ajaxSuccess('#add-spesialis');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.spesialis.message);
                        } else {
                            hideLoading();
                            alertWarning(res.spesialis.message);
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

    $('#edit-spesialis').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            nama: {
                required: true,
                rangelength: [3, 20],
                remote: {
                    url: http + 'fetch?f=' + remote + '&d=' + editSpesialis,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id: function () {
                            return $('#edit-spesialis').find('input[type=hidden]').val();
                        },
                        nama: function () {
                            return $('#edit-spesialis').find("#nama").val();
                        }
                    }
                }
            },
        },
        messages: {
            nama: {
                required: "Nama Spesialis harus diisi !",
                rangelength: "Minimal 3 karakter dan maksimal 20 karakter !",
                remote: "Nama Spesialis telah digunakan !"
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
            var uuid = $('#edit-spesialis').attr('data-target');
            var spesialis = new FormData($('#edit-spesialis')[0]);
            var uid = $('#edit-spesialis').find('input[type=hidden]').val();
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: spesialis,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.spesialis.code == 1) {
                            ajaxSuccess('#edit-spesialis');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.spesialis.message);
                        } else {
                            hideLoading();
                            alertWarning(res.spesialis.message);
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
    // Validation spesialis End //

    var dataTable = $("#table_spesialis").DataTable({
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
                $("#table_spesialis_processing").addClass("text-center").html('<i class="fa fa-spinner fa-pulse fa-fw text-blue"></i>&nbsp;<span>Memuat Data...</span>');
            },
            error: function () {
                $(".table_spesialis-error").html("");
                $("#table_spesialis").append('<tbody class="table_spesialis-error"><tr><td colspan="7" class="text-center">Tidak ada data</td></tr></tbody>');
                $("#table_spesialis_processing").css('display', 'none');
            }
        },
        "pageLength": 10,
        "order": [
            [0, "desc"]
        ],
        columnDefs: [
            {
                orderable: true,
                targets: [0, 1, 2]
            },
            {
                orderable: false,
                targets: [3]
            },
            {
                searchable: true,
                targets: [2]
            },
            {
                searchable: false,
                targets: [0, 1, 3]
            }
        ],
        "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Semua"]
        ]
    });

    $("#table_spesialis_filter").addClass("pull-right");
    $("#table_spesialis_paginate").addClass("pull-right");

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

    $("#create_spesialis").on('click', function (e) {
        e.preventDefault();
        $('#addModal').modal({
            'show': true,
            'backdrop': 'static'
        });
        return false;
    });

    $("#table_spesialis").on('click', '#edit', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        $a = $('#edit-spesialis').find('input[type=hidden],input[type=text], select, textarea');

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
                    if (res.spesialis.code) {
                        hideLoading();
                        $('#editModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });

                        for (let i = 0; i < $a.length; i++) {
                            $a.eq(i).val(res.spesialis.data[i]);
                        }
                    } else {
                        hideLoading();
                        alertWarning(res.spesialis.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_spesialis").on('click', '#hapus', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Menghapus data spesialis <b>' + nm + '</b> ?',
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
                            if (res.spesialis.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
                                alertSuccess(res.spesialis.message);
                            } else {
                                hideLoading();
                                alertWarning(res.spesialis.message);
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