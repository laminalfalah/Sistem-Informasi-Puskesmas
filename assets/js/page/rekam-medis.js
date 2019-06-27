$(function () {

    var remote = $('#table_rekam_medis').attr('data-remote');
    var target = $('#table_rekam_medis').attr('data-target');

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

    // Validation rekam_medis Start // 
    $('#add-rekam_medis').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            pasien: {
                required: true,
            },
            dokter: {
                required: true,
            },
            keterangan: {
                required: true,
            },
        },
        messages: {
            pasien: {
                required: "Nama Pasien diperlukan !"
            },
            dokter: {
                required: "Nama Dokter diperlukan !"
            },
            keterangan: {
                required: "Keterangan diperlukan !"
            },
        },
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').addClass(errorClass).removeClass(validClass);
            var elem = $(element);

            if (elem.hasClass('s-select2')) {
                var isMulti = (!!elem.attr('multiple')) ? 's' : '';
                elem.siblings('.sl').find('.select2-choice' + isMulti + '').addClass(errorClass);
            } else {
                elem.addClass(errorClass);
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').addClass(validClass).removeClass(errorClass);
            var elem = $(element);

            if (elem.hasClass('sl')) {
                elem.siblings('.sl').find('.select2-choice').removeClass(errorClass);
            } else {
                elem.removeClass(errorClass);
            }
        },
        submitHandler: function (form) {
            var uuid = $('#add-rekam_medis').attr('data-target');
            var rekam_medis = new FormData($('#add-rekam_medis')[0]);
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: rekam_medis,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.rekam_medis.code == 1) {
                            ajaxSuccess('#add-rekam_medis');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.rekam_medis.message);
                        } else {
                            hideLoading();
                            alertWarning(res.rekam_medis.message);
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

    $('#edit-rekam_medis').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            keterangan: {
                required: true,
            },
        },
        messages: {
            keterangan: {
                required: "Keterangan diperlukan !"
            },
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
            var uuid = $('#edit-rekam_medis').attr('data-target');
            var rekam_medis = new FormData($('#edit-rekam_medis')[0]);
            var uid = $('#edit-rekam_medis').find('input[type=hidden]').val();
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid + '&id=' + uid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: rekam_medis,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.rekam_medis.code == 1) {
                            ajaxSuccess('#edit-rekam_medis');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.rekam_medis.message);
                        } else {
                            hideLoading();
                            alertWarning(res.rekam_medis.message);
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

    $('#download-rekam_medis').validate({
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
            var page = $('#download-rekam_medis').attr('data-remote');
            var uuid = $('#download-rekam_medis').attr('data-target');
            var pdf = new FormData($('#download-rekam_medis')[0]);
            var uid = "rekam_medis";
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
                            ajaxSuccess('#download-rekam_medis');
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
    // Validation rekam_medis End //

    var dataTable = $("#table_rekam_medis").DataTable({
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
                $("#table_rekam_medis_processing").addClass("text-center").html('<i class="fa fa-spinner fa-pulse fa-fw text-blue"></i>&nbsp;<span>Memuat Data...</span>');
            },
            error: function () {
                $(".table_rekam_medis-error").html("");
                $("#table_rekam_medis").append('<tbody class="table_rekam_medis-error"><tr><td colspan="5" class="text-center">Tidak ada data</td></tr></tbody>');
                $("#table_rekam_medis_processing").css('display', 'none');
            }
        },
        "pageLength": 10,
        "order": [
            [0, "desc"]
        ],
        columnDefs: [
            {
                orderable: true,
                targets: [0, 1, 2, 3]
            },
            {
                orderable: false,
                targets: [4]
            },
            {
                searchable: true,
                targets: [1, 2]
            },
            {
                searchable: false,
                targets: [0, 3, 4]
            }
        ],
        "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Semua"]
        ]
    });

    $("#table_rekam_medis_filter").addClass("pull-right");
    $("#table_rekam_medis_paginate").addClass("pull-right");

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

    var targetDokter = $('#dokter').attr('data-target');

    $('#dokter').select2({
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Cari NIP atau Nama Dokter',
        width: '100%',
        dropdownParent: $('#addModal'),
        ajax: {
            url: http + 'fetch?f=' + remote + '&d=' + targetDokter,
            type: 'GET',
            async: true,
            dataType: 'json',
            delay: 800,
            data: function (params) {
                return {
                    q: params.term
                }
            },
            processResults: function (data, page) {
                return {
                    results: data
                };
            },
        }
    }).on('select2:select', function (evt) {
        var data = $(".select2 option:selected").val();
        //console.log(data);
    });

    var targetPasien = $('#pasien').attr('data-target');

    $('#pasien').select2({
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Cari NIK atau Nama Pasien',
        width: '100%',
        dropdownParent: $('#addModal'),
        ajax: {
            url: http + 'fetch?f=' + remote + '&d=' + targetPasien,
            type: 'GET',
            async: true,
            dataType: 'json',
            delay: 800,
            data: function (params) {
                return {
                    q: params.term
                }
            },
            processResults: function (data, page) {
                return {
                    results: data
                };
            },
        }
    }).on('select2:select', function (evt) {
        var data = $(".select2 option:selected").val();
        //console.log(data);
        loadbpjs(data);
    });

    $('select').on('change', function () {
        //$(this).valid();
    });

    function loadbpjs(d) {
        $.ajax({
            url: http + 'fetch?f=' + remote + '&d=' + targetPasien + '&c=' + d,
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
                    if (res.rekam_medis.code) {
                        hideLoading();
                        $('#bpjsnyo').html(res.rekam_medis.data);
                    } else {
                        hideLoading();
                        alertWarning(res.rekam_medis.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    }

    jQuery.noConflict();

    $("#create_rekam_medis").on('click', function (e) {
        e.preventDefault();
        $('#addModal').modal({
            'show': true,
            'backdrop': 'static'
        });
        return false;
    });

    $("#table_rekam_medis").on('click', '#edit', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        $a = $('#edit-rekam_medis').find('input[type=hidden],input[type=text], select, textarea');

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
                    if (res.rekam_medis.code) {
                        hideLoading();
                        $('#editModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        $a.eq(0).val(res.rekam_medis.data[0]);
                        $a.eq(1).val(res.rekam_medis.data[3]);
                        $('#pasiennyo').html(res.rekam_medis.data[1]);
                        $('#dokternyo').html(res.rekam_medis.data[2]);
                    } else {
                        hideLoading();
                        alertWarning(res.rekam_medis.message);
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

    $("#table_rekam_medis").on('click', '#detail', function (e) {
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
                    if (res.rekam_medis.code == 1) {
                        hideLoading();
                        $('#detailModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        let tr_str = '';
                        let jk = res.rekam_medis.data.jk == "P" ? "Pria" : "Wanita";
                        tr_str += "<tr><td>Jenis BPJS</td><td>" + res.rekam_medis.data.nmr + "</td></tr>" +
                                  "<tr><td>Nomor BPJS</td><td>" + res.rekam_medis.data.bpjs + "</td></tr>" +
                                  "<tr><td>Nama Pasien</td><td>" + res.rekam_medis.data.pasien + "</td></tr>" +
                                  "<tr><td>No. Kartu Keluarga</td><td>" + res.rekam_medis.data.kk + "</td></tr>" +
                                  "<tr><td>Jenis Kelamin</td><td>" + jk + "</td></tr>" +
                                  "<tr><td>Tempat, Tanggal Lahir</td><td>" + res.rekam_medis.data.lhr + ", " + moment(res.rekam_medis.data.tgl_lhr).format("DD-MMMM-YYYY") + "</td></tr>" +
                                  "<tr><td>Agama</td><td>" + res.rekam_medis.data.agm + "</td></tr>" +
                                  "<tr><td>Pekerjaan</td><td>" + res.rekam_medis.data.krj + "</td></tr>" +
                                  "<tr><td>Alamat</td><td>" + res.rekam_medis.data.almt + "</td></tr>" +
                                  "<tr><td>Nama Dokter</td><td>" + res.rekam_medis.data.dokter + "</td></tr>" +
                                  "<tr><td>Keterangan</td><td>" + res.rekam_medis.data.ket + "</td></tr>" +
                                  "<tr><td>Tanggal Buat</td><td>" + moment(res.rekam_medis.data.tgl).format("dddd, DD-MMMM-YYYY") + "</td></tr>";

                        $('#detail-table').append(tr_str);
                    } else {
                        hideLoading();
                        alertWarning(res.rekam_medis.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_rekam_medis").on('click', '#download', function (e) {
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
                    if (res.rekam_medis.code == 1) {
                        hideLoading();
                        alertSuccess(res.rekam_medis.message);
                        let win = window.open();
                        win.location = res.rekam_medis.url;
                        win.opener = null;
                        win.blur();
                        window.focus();
                    } else {
                        hideLoading();
                        alertWarning(res.rekam_medis.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_rekam_medis").on('click', '#hapus', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Menghapus data rekam medis <b>' + nm + '</b> ?',
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
                            if (res.rekam_medis.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
                                alertSuccess(res.rekam_medis.message);
                            } else {
                                hideLoading();
                                alertWarning(res.rekam_medis.message);
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

    $("#download_rekam_medis").on('click', function (e) {
        e.preventDefault();
        var rule = $(this).attr('data-remote');
        var uuid = $(this).attr('data-target');
        $.ajax({
            url: http + 'fetch?f=' + rule + '&d=' + uuid + '&tipe=rekam_medis',
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
                        $('#download-rekam_medis h4.pull-right').html('Jumlah data sebanyak : ' + res.download.count + ' rekam medis.');
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