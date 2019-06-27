$(function () {

    var remote = $('#table_rawat_jalan').attr('data-remote');
    var target = $('#table_rawat_jalan').attr('data-target');
    var targetResep = $('#resep').attr('data-target');
    var slctResep = $('#resep');
    slctResep.attr('disabled',true);

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
        $('#table_list_item').css('display', 'none');
        $("#pasien").val([]).trigger("change"); 
        $("#obat").val([]).trigger("change"); 
        $('tbody#list_item').empty();
    }

    // Validation rawat_jalan Start // 
    $('#add-rawat_jalan').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            dokter: {
                required: true,
            },
            pasien: {
                required: true,
            },
            ket: {
                required: true,
            },
        },
        messages: {
            dokter: {
                required: "Nama Dokter diperlukan !"
            },
            pasien: {
                required: "Nama Pasien diperlukan !"
            },
            ket: {
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
            if (!validate()) return false;
            var uuid = $('#add-rawat_jalan').attr('data-target');
            var rawat_jalan = new FormData($('#add-rawat_jalan')[0]);
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: rawat_jalan,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.rawat_jalan.code == 1) {
                            ajaxSuccess('#add-rawat_jalan');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.rawat_jalan.message);
                        } else {
                            hideLoading();
                            alertWarning(res.rawat_jalan.message);
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

    $('#edit-rawat_jalan').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            ket: {
                required: true,
            },
        },
        messages: {
            ket: {
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
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        },
        submitHandler: function (form) {
            var uuid = $('#edit-rawat_jalan').attr('data-target');
            var dokter = new FormData($('#edit-rawat_jalan')[0]);
            var uid = $('#edit-rawat_jalan').find('input[type=hidden]').val();
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
                        if (res.rawat_jalan.code == 1) {
                            ajaxSuccess('#edit-rawat_jalan');
                            dataTable.ajax.reload();
                            hideLoading();
                            alertSuccess(res.rawat_jalan.message);
                        } else {
                            hideLoading();
                            alertWarning(res.rawat_jalan.message);
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

    $('#download-rawat_jalan').validate({
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
            var page = $('#download-rawat_jalan').attr('data-remote');
            var uuid = $('#download-rawat_jalan').attr('data-target');
            var pdf = new FormData($('#download-rawat_jalan')[0]);
            var uid = "rawat_jalan";
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
                            ajaxSuccess('#download-rawat_jalan');
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
    // Validation rawat_jalan End //

    var dataTable = $("#table_rawat_jalan").DataTable({
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
                $("#table_rawat_jalan_processing").addClass("text-center").html('<i class="fa fa-spinner fa-pulse fa-fw text-blue"></i>&nbsp;<span>Memuat Data...</span>');
            },
            error: function () {
                $(".table_rawat_jalan-error").html("");
                $("#table_rawat_jalan").append('<tbody class="table_rawat_jalan-error"><tr><td colspan="6" class="text-center">Tidak ada data</td></tr></tbody>');
                $("#table_rawat_jalan_processing").css('display', 'none');
            }
        },
        "pageLength": 10,
        "order": [
            [0, "desc"]
        ],
        columnDefs: [
            {
                orderable: true,
                targets: [0, 1, 2, 3, 4]
            },
            {
                orderable: false,
                targets: [5]
            },
            {
                searchable: true,
                targets: [1, 2, 3]
            },
            {
                searchable: false,
                targets: [0, 4, 5]
            }
        ],
        "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Semua"]
        ]
    });

    $("#table_rawat_jalan_filter").addClass("pull-right");
    $("#table_rawat_jalan_paginate").addClass("pull-right");

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
        var data = $("#dokter option:selected").val();
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
        var data = $("#pasien option:selected").val();
        //console.log(data);
    });

    $('select').on('change', function () {
        //$(this).valid();
    });

    $(document).on('change','.select2', function(e) {
        e.preventDefault();
        var d = $('#dokter option:selected').val();
        var p = $('#pasien option:selected').val();
        if (d != "" && p != "") {
            loadresep(d,p);
        }
    });

    function loadresep(d,p) {
        let slct_sp = "<option selected disabled value=\"\">Pilih Resep</option>";
        $.ajax({
            url: http + 'fetch?f=' + remote + '&d=' + targetResep,
            type: 'POST',
            async: true,
            data: {d:d, p:p},
            dataType: 'json',
            beforeSend: function () {
                showLoading();
                slctResep.empty();
            },
            success: function (res) {
                if (res.length == 0) {
                    hideLoading();
                    alertDanger('Invalid request');
                } else {
                    if (res.rawat_jalan.code) {
                        slctResep.attr('disabled',false);
                        hideLoading();
                        let sp_data = res.rawat_jalan.resep;
                        for (let i = 0; i < sp_data.length; i++) {
                            let k = sp_data[i].key;
                            let v = sp_data[i].value;
                            slct_sp += "<option value=\"" + k + "\">" + v + "</option>";
                        }
                    } else {
                        hideLoading();
                        slctResep.attr('disabled',true);
                        $('select#resep').parents('.form-group').attr('class','form-group');
                        alertWarning(res.rawat_jalan.message);
                    }
                }
                slctResep.append(slct_sp);
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    }

    function validate() {
        var a = true;
        a = !$('select#resep option:selected').val() == "";
        if (!a) {
            alertWarning('Data resep diperlukan');
        }
        return a;
    }

    jQuery.noConflict();

    $("#create_rawat_jalan").on('click', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        $.ajax({
            url: http + 'fetch?f=' + remote + '&d=' + uuid,
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
                    if (res.rawat_jalan.code) {
                        hideLoading();
                        $('#addModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                    } else {
                        hideLoading();
                        alertWarning(res.rawat_jalan.message);
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

    $("#table_rawat_jalan").on('click', '#edit', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        $a = $('#edit-rawat_jalan').find('input[type=hidden],input[type=text], select, textarea');

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
                    if (res.rawat_jalan.code) {
                        hideLoading();
                        $('#editModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        $a.eq(0).val(res.rawat_jalan.data[0]);
                        $a.eq(1).val(res.rawat_jalan.data[4]);
                        $('#pasiennyo').html(res.rawat_jalan.data[1]);
                        $('#dokternyo').html(res.rawat_jalan.data[2]);
                        $('#resepnyo').html(res.rawat_jalan.data[3]);
                    } else {
                        hideLoading();
                        alertWarning(res.rawat_jalan.message);
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

    $("#table_rawat_jalan").on('click', '#detail', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        var body = $('#detail-table');
        var obat = $('tbody#list_item_detail');
        var jmlhItem = $('#list_foot_detail tr th:nth-last-child(2)');
        var totalItem = $('#list_foot_detail tr th:last-child');

        $.ajax({
            url: http + 'fetch?f='+remote+'&d='+uuid+'&id='+uid,
            type: 'GET',
            async: true,
            dataType: 'json',
            beforeSend: function () {
                showLoading();
                body.empty();
                obat.empty();
            },
            success: function (res) {
                if (res.length == 0) {
                    hideLoading();
                    alertDanger('Invalid request');
                } else {
                    if (res.rawat_jalan.code == 1) {
                        hideLoading();
                        $('#detailModal').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        let tr_str = '';
                        let jk = res.rawat_jalan.data.jk == "P" ? "Pria" : "Wanita";
                        tr_str += "<tr><td>Jenis BPJS</td><td>" + res.rawat_jalan.data.nmr + "</td></tr>" +
                                    "<tr><td>Nomor BPJS</td><td>" + res.rawat_jalan.data.bpjs + "</td></tr>" +
                                    "<tr><td>Kode Resep</td><td>" + res.rawat_jalan.data.kode + "</td></tr>" +
                                    "<tr><td>Nama Pasien</td><td>" + res.rawat_jalan.data.pasien + "</td></tr>" +
                                    "<tr><td>No. Kartu Keluarga</td><td>" + res.rawat_jalan.data.kk + "</td></tr>" +
                                    "<tr><td>Jenis Kelamin</td><td>" + jk + "</td></tr>" +
                                    "<tr><td>Tempat, Tanggal Lahir</td><td>" + res.rawat_jalan.data.lhr + ", " + moment(res.rawat_jalan.data.tgl_lhr).format("DD-MMMM-YYYY") + "</td></tr>" +
                                    "<tr><td>Agama</td><td>" + res.rawat_jalan.data.agm + "</td></tr>" +
                                    "<tr><td>Pekerjaan</td><td>" + res.rawat_jalan.data.krj + "</td></tr>" +
                                    "<tr><td>Alamat</td><td>" + res.rawat_jalan.data.almt + "</td></tr>" +
                                    "<tr><td>Nama Dokter</td><td>" + res.rawat_jalan.data.dokter + "</td></tr>" +
                                    "<tr><td>Keterangan</td><td>" + res.rawat_jalan.data.ket + "</td></tr>" +
                                    "<tr><td>Tanggal Buat</td><td>" + moment(res.rawat_jalan.data.tgl).format("dddd, DD-MMMM-YYYY") + "</td></tr>";
                        body.append(tr_str);

                        let a = res.rawat_jalan.resep;
                        let tr_data = '';
                        let total = 0;
                        let item = 0;
                        let no = 1;
                        for (let i = 0; i < a.length; i++) {
                            let name = res.rawat_jalan.resep[i].obat;
                            let anjr = res.rawat_jalan.resep[i].anjr;
                            let hrga = res.rawat_jalan.resep[i].hrga;
                            let jmlh = res.rawat_jalan.resep[i].jmlh;
                            let unit = res.rawat_jalan.resep[i].unit;
                            let sub = res.rawat_jalan.resep[i].sub;
                            let a = parseInt(jmlh);
                            let b = parseInt(sub);
                            tr_data +=  "<tr>" +
                                            "<td>" + no + ".</td>" +
                                            "<td>" + name + "</td>" +
                                            "<td>" + anjr + "</td>" +
                                            "<td>Rp. " + formatAngka(hrga) + "</td>" +
                                            "<td>" + jmlh + " " + unit + "</td>" +
                                            "<td>Rp. " + formatAngka(sub) + "</td>" +
                                        "</tr>";
                            item+=a;
                            total+=b;
                            no++;
                        }
                        jmlhItem.html(formatAngka(item));
                        totalItem.html("Rp. " + formatAngka(total));
                        obat.append(tr_data);

                    } else {
                        hideLoading();
                        alertWarning(res.rawat_jalan.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_rawat_jalan").on('click', '#download', function (e) {
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
                    if (res.rawat_jalan.code == 1) {
                        hideLoading();
                        alertSuccess(res.rawat_jalan.message);
                        let win = window.open();
                        win.location = res.rawat_jalan.url;
                        win.opener = null;
                        win.blur();
                        window.focus();
                    } else {
                        hideLoading();
                        alertWarning(res.rawat_jalan.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_rawat_jalan").on('click', '#hapus', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Menghapus data rawat jalan <b>' + nm + '</b> ?',
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
                            if (res.rawat_jalan.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
                                alertSuccess(res.rawat_jalan.message);
                            } else {
                                hideLoading();
                                alertWarning(res.rawat_jalan.message);
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

    $("#download_rawat_jalan").on('click', function (e) {
        e.preventDefault();
        var rule = $(this).attr('data-remote');
        var uuid = $(this).attr('data-target');
        $.ajax({
            url: http + 'fetch?f=' + rule + '&d=' + uuid + '&tipe=rawat_jalan',
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
                        $('#download-rawat_jalan h4.pull-right').html('Jumlah data sebanyak : ' + res.download.count + ' rawat jalan.');
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