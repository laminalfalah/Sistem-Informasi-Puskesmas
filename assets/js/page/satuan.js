$(function () {

    var remote = $('#table_satuan').attr('data-remote');
    var target = $('#table_satuan').attr('data-target');
    var addSatuan = $('#add-satuan').find('#nama').attr('data-target');
    var editSatuan = $('#edit-satuan').find('#nama').attr('data-target');

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

    // Validation satuan Start // 
    $('#add-satuan').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            nama: {
                required: true,
                rangelength: [2, 20],
                remote: {
                    url: http + 'fetch?f=' + remote + '&d=' + addSatuan,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        nama: function () {
                            return $('#add-satuan').find("#nama").val();
                        }
                    }
                }
            },
        },
        messages: {
            nama: {
                required: "Nama Satuan harus diisi !",
                rangelength: "Minimal 2 karakter dan maksimal 20 karakter !",
                remote: "Nama Satuan telah digunakan !"
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
            var uuid = $('#add-satuan').attr('data-target');
            var satuan = new FormData($('#add-satuan')[0]);
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: satuan,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.satuan.code == 1) {
                            ajaxSuccess('#add-satuan');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.satuan.message);
                        } else {
                            hideLoading();
                            alertWarning(res.satuan.message);
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

    $('#edit-satuan').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            nama_edit: {
                required: true,
                rangelength: [2, 20],
                remote: {
                    url: http + 'fetch?f=' + remote + '&d=' + editSatuan,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id: function () {
                            return $('#edit-satuan').find('input[type=hidden]').val();
                        },
                        nama: function () {
                            return $('#edit-satuan').find("#nama").val();
                        }
                    }
                }
            },
        },
        messages: {
            nama_edit: {
                required: "Nama Satuan harus diisi !",
                rangelength: "Minimal 3 karakter dan maksimal 20 karakter !",
                remote: "Nama Satuan telah digunakan !"
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
            var uuid = $('#edit-satuan').attr('data-target');
            var satuan = new FormData($('#edit-satuan')[0]);
            var uid = $('#edit-satuan').find('input[type=hidden]').val();
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: satuan,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.satuan.code == 1) {
                            ajaxSuccess('#edit-satuan');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.satuan.message);
                        } else {
                            hideLoading();
                            alertWarning(res.satuan.message);
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
    // Validation satuan End //

    var dataTable = $("#table_satuan").DataTable({
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
                $("#table_satuan_processing").addClass("text-center").html('<i class="fa fa-spinner fa-pulse fa-fw text-blue"></i>&nbsp;<span>Memuat Data...</span>');
            },
            error: function () {
                $(".table_satuan-error").html("");
                $("#table_satuan").append('<tbody class="table_satuan-error"><tr><td colspan="7" class="text-center">Tidak ada data</td></tr></tbody>');
                $("#table_satuan_processing").css('display', 'none');
            }
        },
        "pageLength": 10,
        "order": [
            [0, "desc"]
        ],
        columnDefs: [
            {
                orderable: true,
                targets: [0, 1]
            },
            {
                orderable: false,
                targets: [2]
            },
            {
                searchable: true,
                targets: [1]
            },
            {
                searchable: false,
                targets: [0, 2]
            }
        ],
        "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Semua"]
        ]
    });

    $("#table_satuan_filter").addClass("pull-right");
    $("#table_satuan_paginate").addClass("pull-right");

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

    $("#create_satuan").on('click', function (e) {
        e.preventDefault();
        $('#addModal').modal({
            'show': true,
            'backdrop': 'static'
        });
        return false;
    });

    $("#table_satuan").on('click', '#edit', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        $a = $('#edit-satuan').find('input[type=hidden],input[type=text], select, textarea');

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
                    if (res.satuan.code) {
                        hideLoading();
                        $('#editModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });

                        for (let i = 0; i < $a.length; i++) {
                            $a.eq(i).val(res.satuan.data[i]);
                        }
                    } else {
                        hideLoading();
                        alertWarning(res.satuan.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_satuan").on('click', '#hapus', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Menghapus data satuan <b>' + nm + '</b> ?',
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
                            if (res.satuan.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
                                alertSuccess(res.satuan.message);
                            } else {
                                hideLoading();
                                alertWarning(res.satuan.message);
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