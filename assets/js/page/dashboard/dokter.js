$(function () {
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

    var remoteResep = $('#table_resep').attr('data-remote');
    var targetResep = $('#table_resep').attr('data-target');
    var jumlahResep = $('#list_foot tr th:nth-last-child(2)');
    var totalResep = $('#list_foot tr th:last-child');
    $('#table_resep').css('width','100%');

    var remoteRekam = $('#table_rekam_medis').attr('data-remote');
    var targetRekam = $('#table_rekam_medis').attr('data-target');
    $('#table_rekam_medis').css('width','100%');

    var remoteRawat = $('#table_rawat_jalan').attr('data-remote');
    var targetRawat = $('#table_rawat_jalan').attr('data-target');
    var targetRawatResep = $('#resep_rawat').attr('data-target');
    var slctResep = $('#resep_rawat');
    slctResep.attr('disabled',true);
    $('#table_rawat_jalan').css('width','100%');

    var remotePasien = $('#table_pasien').attr('data-remote');
    var targetPasien = $('#table_pasien').attr('data-target');
    $('#table_pasien').css('width','100%');

    // resep
    // Validation resep Start // 
    $('#add-resep').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            dokter: {
                required: true,
            },
            pasien: {
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
            if (!validateResep()) return false;
            var uuid = $('#add-resep').attr('data-target');
            var resep = new FormData($('#add-resep')[0]);
            $.ajax({
                url: http + 'fetch?f=' + remoteResep + '&d=' + uuid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: resep,
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.resep.code == 1) {
                            ajaxSuccess('#add-resep');
                            dataTableResep.ajax.reload();
                            hideLoading();
                            alertSuccess(res.resep.message);
                        } else {
                            hideLoading();
                            alertWarning(res.resep.message);
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

    function validateResep() {
        valid = true;
        valid = !$('tbody#list_item').children().length == 0;
        if (!valid) {
            alertWarning("Data obat tidak boleh kosong !");
        }
        return valid;
    }
    // Validation resep End //

    var dataTableResep = $("#table_resep").DataTable({
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
            url: http + 'fetch?f=' + remoteResep + '&d=' + targetResep,
            type: "POST",
            beforeSend: function () {
                $("#table_resep_processing").addClass("text-center").html('<i class="fa fa-spinner fa-pulse fa-fw text-blue"></i>&nbsp;<span>Memuat Data...</span>');
            },
            error: function () {
                $(".table_resep-error").html("");
                $("#table_resep").append('<tbody class="table_resep-error"><tr><td colspan="5" class="text-center">Tidak ada data</td></tr></tbody>');
                $("#table_resep_processing").css('display', 'none');
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

    $("#table_resep_filter").addClass("pull-right");
    $("#table_resep_paginate").addClass("pull-right");

    dataTableResep.on("draw.dt", function () {
        var info = dataTableResep.page.info();
        dataTableResep.column(0, {
            search: "applied",
            order: "applied",
            page: "applied"
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1 + info.start + ".";
        });
    });

    var targetPasienResep = $('#add-resep').find('#pasien_resep').attr('data-target');

    $('#pasien_resep').select2({
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Cari NIK atau Nama Pasien',
        width: '100%',
        dropdownParent: $('#addModalResep'),
        ajax: {
            url: http + 'fetch?f=' + remoteResep + '&d=' + targetPasienResep,
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
        var data = $("#pasien_resep option:selected").val();
        //console.log(data);
    });

    var targetObatResep = $('#add-resep').find('#obat_resep').attr('data-target');

    $('#obat_resep').select2({
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Cari Kode Obat atau Nama Obat',
        width: '100%',
        dropdownParent: $('#addModalResep'),
        ajax: {
            url: http + 'fetch?f=' + remoteResep + '&d=' + targetObatResep,
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
        var data = $("#obat_resep option:selected").val();
        loadobat(data);
    });

    function loadobat(obatnyo) {
        var body = $('tbody#list_item');
        let no = parseInt($('#list_item').children().length) + 1;
        $.ajax({
            url: http + 'fetch?f=' + remoteResep + '&d=' + targetObatResep + '&c=' + obatnyo,
            type: 'GET',
            async: true,
            dataType: 'json',
            beforeSend: function () {
                //body.empty();
                showLoading();
            },
            success: function (res) {
                if (res.length == 0) {
                    hideLoading();
                    alertDanger('Invalid request');
                } else {
                    if (res.resep.code == 1) {
                        $('#table_list_item').css('display', 'block');
                        let code = res.resep.data.code;
                        let name = res.resep.data.name;
                        let hrga = res.resep.data.price;
                        let stok = res.resep.data.stock;
                        let anjr = '<input type=\"text\" id=\"anjr\" name=\"anjr[' + code + ']\" class=\"form-control input-sm\" placeholder=\"Anjuran\"required>';
                        let jmlh = '<input type=\"number\" id=\"jumlah\" name=\"jumlah[' + code + ']\" class=\"form-control input-sm\" min=\"1\" max=\"' + stok + '\" value=\"1\" required>'
                        let sub = hrga * 1;
                        let hps = '<a id=\"hps\" name=\"hps[' + code + ']\" class=\"btn btn-xs btn-danger\" title=\"Hapus List\"><i class=\"fa fa-remove\"></i></a>';
                        let tr_data = '';
                        tr_data += "<tr>" +
                                    "<td>" + hps + "</td>" +
                                    "<td>" + name + "</td>" +
                                    "<td>" + anjr + "</td>" +
                                    "<td>Rp. " + formatAngka(hrga) + "</td>" +
                                    "<td>" + jmlh +"</td>" +
                                    "<td>Rp. " + formatAngka(sub) + "</td>" +
                                   "</tr>";
                        body.append(tr_data);
                        load_session(sub);
                        hideLoading();
                    } else {
                        hideLoading();
                        alertWarning(res.resep.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    }
    
    function load_session(sub) {
        let no = parseInt($('#list_item').children().length);
        let a = jumlahResep.html();
        let b = totalResep.html().split(' ');
        let c = b[1].replace(/[.]/g, '');
        let d = 0;
        let e = parseInt(sub);
        let f = parseInt(c);
        if (no == 1) {
            jumlahResep.html(formatAngka(1));
            totalResep.html("Rp. " + formatAngka(e));
        } else {
            d = parseInt(a) + 1;
            jumlahResep.html(formatAngka(d));
            totalResep.html("Rp. " + formatAngka(f+=e));
        }
    }

    $(document).on('click change','#list_item tr td a#hps', function(e) {
        e.preventDefault();
        var a = $('#list_item tr td a#hps');
        if (a.length === 1) {
            $('#table_list_item').css('display', 'none');
            $('tbody#list_item').empty();
            jumlah.html(formatAngka(0));
            total.html("Rp. " + formatAngka(0));
        }

        $(this).closest('tr').remove();
        updateDatanyo();
    });

    $(document).on('keyup change', '#list_item tr td input[type=number]', function(e) {
        e.preventDefault();
        updateDatanyo();
    });

    function updateDatanyo() {
        var hrg = $('#list_item tr td:nth-child(4)');
        var sub = $('#list_item tr td:last-child');
        var jlh = $('#list_item tr td:nth-child(5) input#jumlah');

        let subtotal = 0;
        let totalall = 0;

        for (let i = 0; i < jlh.length; i++) {
            var a = hrg[i].textContent;
            var b = a.split(' ');
            var c = b[1].replace(/[.]/g, '');
            sub[i].innerText = 'Rp. ' + formatAngka(parseInt(jlh[i].value) * parseInt(c));
            subtotal += parseInt(jlh[i].value);
        }
        jumlahResep.html(formatAngka(subtotal));

        for (let i = 0; i < sub.length; i++) {
            var a = sub[i].textContent;
            var b = a.split(' ');
            let c = b[1].replace(/[.]/g, '');
            totalall += parseInt(c);
        }
        totalResep.html("Rp. " + formatAngka(totalall));
    }

    // rekam
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
                url: http + 'fetch?f=' + remoteRekam + '&d=' + uuid,
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
                            dataTableRekam.ajax.reload();
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
                url: http + 'fetch?f=' + remoteRekam + '&d=' + uuid + '&id=' + uid,
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
                            dataTableRekam.ajax.reload();
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
    // Validation rekam_medis End //

    var dataTableRekam = $("#table_rekam_medis").DataTable({
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
            url: http + 'fetch?f=' + remoteRekam + '&d=' + targetRekam,
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

    dataTableRekam.on("draw.dt", function () {
        var info = dataTableRekam.page.info();
        dataTableRekam.column(0, {
            search: "applied",
            order: "applied",
            page: "applied"
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1 + info.start + ".";
        });
    });

    var targetPasienRekam = $('#pasien_rekam').attr('data-target');

    $('#pasien_rekam').select2({
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Cari NIK atau Nama Pasien',
        width: '100%',
        dropdownParent: $('#addModalRekam'),
        ajax: {
            url: http + 'fetch?f=' + remoteRekam + '&d=' + targetPasienRekam,
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
        var data = $("#pasien_rekam option:selected").val();
        loadbpjs(data);
    });

    function loadbpjs(d) {
        $.ajax({
            url: http + 'fetch?f=' + remoteRekam + '&d=' + targetPasienRekam + '&c=' + d,
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

    // rawat
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
                url: http + 'fetch?f=' + remoteRawat + '&d=' + uuid,
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
                            dataTableRawat.ajax.reload();
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
                url: http + 'fetch?f=' + remoteRawat + '&d=' + uuid + '&id=' + uid,
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
                            dataTableRawat.ajax.reload();
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
    // Validation rawat_jalan End //

    var dataTableRawat = $("#table_rawat_jalan").DataTable({
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
            url: http + 'fetch?f=' + remoteRawat + '&d=' + targetRawat,
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

    dataTableRawat.on("draw.dt", function () {
        var info = dataTableRawat.page.info();
        dataTableRawat.column(0, {
            search: "applied",
            order: "applied",
            page: "applied"
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1 + info.start + ".";
        });
    });

    var targetPasienRawat = $('#pasien_rawat').attr('data-target');

    $('#pasien_rawat').select2({
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Cari NIK atau Nama Pasien',
        width: '100%',
        dropdownParent: $('#addModalRawat'),
        ajax: {
            url: http + 'fetch?f=' + remoteRawat + '&d=' + targetPasienRawat,
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
        var data = $("#pasien_rawat option:selected").val();
        //console.log(data);
    });

    $(document).on('change','.select2', function(e) {
        e.preventDefault();
        var d = $('#dokter').val();
        var p = $('#pasien_rawat option:selected').val();
        if (d != "" && p != "") {
            loadresep(d,p);
        }
    });

    function loadresep(d,p) {
        let slct_sp = "<option selected disabled value=\"\">Pilih Resep</option>";
        $.ajax({
            url: http + 'fetch?f=' + remoteRawat + '&d=' + targetRawatResep,
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
                        $('select#resep_rawat').parents('.form-group').attr('class','form-group');
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
        a = !$('select#resep_rawat option:selected').val() == "";
        if (!a) {
            alertWarning('Data resep diperlukan');
        }
        return a;
    }

    // pasien
    var dataTablePasien = $("#table_pasien").DataTable({
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
            url: http + 'fetch?f=' + remotePasien + '&d=' + targetPasien,
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

    dataTablePasien.on("draw.dt", function () {
        var info = dataTablePasien.page.info();
        dataTablePasien.column(0, {
            search: "applied",
            order: "applied",
            page: "applied"
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1 + info.start + ".";
        });
    });

    $('select').on('change', function () {
        //$(this).valid();
    });

    jQuery.noConflict();

    //resep
    $("#create_resep").on('click', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        $.ajax({
            url: http + 'fetch?f=' + remoteResep + '&d=' + uuid,
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
                    if (res.resep.code) {
                        hideLoading();
                        $('#addModalResep').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                    } else {
                        hideLoading();
                        alertWarning(res.resep.message);
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

    $("#table_resep").on('click', '#detail', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        var body = $('#detail-table');
        var obat = $('tbody#list_item_detail');
        var jmlhItem = $('#list_foot_detail tr th:nth-last-child(2)');
        var totalItem = $('#list_foot_detail tr th:last-child');

        $.ajax({
            url: http + 'fetch?f='+remoteResep+'&d='+uuid+'&id='+uid,
            type: 'GET',
            async: true,
            dataType: 'json',
            beforeSend: function () {
                showLoading();
                obat.empty();
                body.empty();
            },
            success: function (res) {
                if (res.length == 0) {
                    hideLoading();
                    alertDanger('Invalid request');
                } else {
                    if (res.resep.code == 1) {
                        hideLoading();
                        $('#detailModalResep').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        let tr_str = '';
                        tr_str += "<tr><td>Kode Resep</td><td>" + res.resep.data.pasien.kode + "</td></tr>" +
                                  "<tr><td>Nama Pasien</td><td>" + res.resep.data.pasien.pasien + "</td></tr>" +
                                  "<tr><td>Jenis BPJS</td><td>" + res.resep.data.pasien.bpjs + "</td></tr>" +
                                  "<tr><td>No. BPJS</td><td>" + res.resep.data.pasien.nmr + "</td></tr>" +
                                  "<tr><td>Nama Dokter</td><td>" + res.resep.data.pasien.dokter + "</td></tr>" +
                                  "<tr><td>Tanggal Buat</td><td>" + moment(res.resep.data.pasien.tgl).format("dddd, DD-MMMM-YYYY") + "</td></tr>";
                        body.append(tr_str);
                        let a = res.resep.data.obat;
                        $('#table_list_item_detail').css('display', 'block');
                        let tr_data = '';
                        let total = 0;
                        let item = 0;
                        let no = 1;
                        for (let i = 0; i < a.length; i++) {
                            let name = res.resep.data.obat[i].obat;
                            let anjr = res.resep.data.obat[i].anjr;
                            let hrga = res.resep.data.obat[i].hrga;
                            let jmlh = res.resep.data.obat[i].jmlh;
                            let unit = res.resep.data.obat[i].unit;
                            let sub = res.resep.data.obat[i].sub;
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
                        alertWarning(res.resep.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_resep").on('click', '#download', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');

        $.ajax({
            url: http + 'fetch?f=' + remoteResep + '&d=' + uuid + '&id=' + uid,
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
                    if (res.resep.code == 1) {
                        hideLoading();
                        alertSuccess(res.resep.message);
                        let win = window.open();
                        win.location = res.resep.url;
                        win.opener = null;
                        win.blur();
                        window.focus();
                    } else {
                        hideLoading();
                        alertWarning(res.resep.message);
                    }
                }
            },
            error: function (jqXHR, status, error) {
                hideLoading();
                alertDanger(status);
            }
        });
    });

    $("#table_resep").on('click', '#hapus', function (e) {
        e.preventDefault();
        var nm = $(this).attr('title-content');
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        Swal.fire({
            title: 'Apa Anda Yakin?',
            html: 'Menghapus data resep <b>' + nm + '</b> ?',
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
                    url: http + 'fetch?f=' + remoteResep + '&d=' + uuid + '&id=' + uid,
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
                            if (res.resep.code == 1) {
                                hideLoading();
                                dataTableResep.ajax.reload();
                                alertSuccess(res.resep.message);
                            } else {
                                hideLoading();
                                alertWarning(res.resep.message);
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

    //rekam medis
    $("#create_rekam_medis").on('click', function (e) {
        e.preventDefault();
        $('#addModalRekam').modal({
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
            url: http + 'fetch?f=' + remoteRekam + '&d=' + uuid + '&id=' + uid,
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
                        $('#editModalRekam').modal({
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
        var body = $('#detail-table-rekam').empty();

        $.ajax({
            url: http + 'fetch?f='+remoteRekam+'&d='+uuid+'&id='+uid,
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
                        $('#detailModalRekam').modal({
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

                        $('#detail-table-rekam').append(tr_str);
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
            url: http + 'fetch?f=' + remoteRekam + '&d=' + uuid + '&id=' + uid,
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
                    url: http + 'fetch?f=' + remoteRekam + '&d=' + uuid + '&id=' + uid,
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
                                dataTableRekam.ajax.reload();
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

    $("#create_rawat_jalan").on('click', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        $.ajax({
            url: http + 'fetch?f=' + remoteRawat + '&d=' + uuid,
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
                        $('#addModalRawat').modal({
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
            url: http + 'fetch?f=' + remoteRawat + '&d=' + uuid + '&id=' + uid,
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
                        $('#editModalRawat').modal({
                            'show': true,
                            'backdrop': 'static'
                        });
                        $a.eq(0).val(res.rawat_jalan.data[0]);
                        $a.eq(1).val(res.rawat_jalan.data[4]);
                        $('#pasiennyo_rawat').html(res.rawat_jalan.data[1]);
                        $('#dokternyo_rawat').html(res.rawat_jalan.data[2]);
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
        var body = $('#detail-table-rawat');
        var obat = $('tbody#list_item_detail');
        var jmlhItem = $('#list_foot_detail tr th:nth-last-child(2)');
        var totalItem = $('#list_foot_detail tr th:last-child');

        $.ajax({
            url: http + 'fetch?f='+remoteRawat+'&d='+uuid+'&id='+uid,
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
                        $('#detailModalRawat').modal({
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
            url: http + 'fetch?f=' + remoteRawat + '&d=' + uuid + '&id=' + uid,
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
                    url: http + 'fetch?f=' + remoteRawat + '&d=' + uuid + '&id=' + uid,
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
                                dataTableRawat.ajax.reload();
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

    $("#table_pasien").on('click', '#detail', function (e) {
        e.preventDefault();
        var uuid = $(this).attr('data-target');
        var uid = $(this).attr('data-content');
        var body = $('#detail-table-pasien');

        $.ajax({
            url: http + 'fetch?f=' + remotePasien + '&d=' + uuid + '&id=' + uid,
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
                        $('#detailModalPasien').modal({
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
            url: http + 'fetch?f=' + remotePasien + '&d=' + uuid + '&id=' + uid,
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
});