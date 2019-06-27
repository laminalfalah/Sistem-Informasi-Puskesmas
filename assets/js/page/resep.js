$(function () {

    var remote = $('#table_resep').attr('data-remote');
    var target = $('#table_resep').attr('data-target');
    var jumlah = $('#list_foot tr th:nth-last-child(2)');
    var total = $('#list_foot tr th:last-child');

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
        jumlah.html(formatAngka(0));
        total.html("Rp. " + formatAngka(0));
    }

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
            if (!validate()) return false;
            var uuid = $('#add-resep').attr('data-target');
            var resep = new FormData($('#add-resep')[0]);
            $.ajax({
                url: http + 'fetch?f=' + remote + '&d=' + uuid,
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
                            dataTable.ajax.reload();
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

    $('#download-resep').validate({
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
            var page = $('#download-resep').attr('data-remote');
            var uuid = $('#download-resep').attr('data-target');
            var pdf = new FormData($('#download-resep')[0]);
            var uid = "resep";
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
                            ajaxSuccess('#download-resep');
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

    function validate() {
        valid = true;
        valid = !$('tbody#list_item').children().length == 0;
        if (!valid) {
            alertWarning("Data obat tidak boleh kosong !");
        }
        return valid;
    }
    // Validation resep End //

    var dataTable = $("#table_resep").DataTable({
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
                $("#table_resep_processing").addClass("text-center").html('<i class="fa fa-spinner fa-pulse fa-fw text-blue"></i>&nbsp;<span>Memuat Data...</span>');
            },
            error: function () {
                $(".table_resep-error").html("");
                $("#table_resep").append('<tbody class="table_resep-error"><tr><td colspan="6" class="text-center">Tidak ada data</td></tr></tbody>');
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

    var targetObat = $('#obat').attr('data-target');

    $('#obat').select2({
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Cari Kode Obat atau Nama Obat',
        width: '100%',
        dropdownParent: $('#addModal'),
        ajax: {
            url: http + 'fetch?f=' + remote + '&d=' + targetObat,
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
        var data = $("#obat option:selected").val();
        loadobat(data);
    });

    $('select').on('change', function () {
        //$(this).valid();
    });

    function loadobat(obatnyo) {
        var body = $('tbody#list_item');
        let no = parseInt($('#list_item').children().length) + 1;
        $.ajax({
            url: http + 'fetch?f=' + remote + '&d=' + targetObat + '&c=' + obatnyo,
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
        let a = jumlah.html();
        let b = total.html().split(' ');
        let c = b[1].replace(/[.]/g, '');
        let d = 0;
        let e = parseInt(sub);
        let f = parseInt(c);
        if (no == 1) {
            jumlah.html(formatAngka(1));
            total.html("Rp. " + formatAngka(e));
        } else {
            d = parseInt(a) + 1;
            jumlah.html(formatAngka(d));
            total.html("Rp. " + formatAngka(f+=e));
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
        jumlah.html(formatAngka(subtotal));

        for (let i = 0; i < sub.length; i++) {
            var a = sub[i].textContent;
            var b = a.split(' ');
            let c = b[1].replace(/[.]/g, '');
            totalall += parseInt(c);
        }
        total.html("Rp. " + formatAngka(totalall));
    }

    jQuery.noConflict();

    $("#create_resep").on('click', function (e) {
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
                    if (res.resep.code) {
                        hideLoading();
                        $('#addModal').modal({
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
            url: http + 'fetch?f='+remote+'&d='+uuid+'&id='+uid,
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
                        $('#detailModal').modal({
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
                            if (res.resep.code == 1) {
                                hideLoading();
                                dataTable.ajax.reload();
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

    $("#download_resep").on('click', function (e) {
        e.preventDefault();
        var rule = $(this).attr('data-remote');
        var uuid = $(this).attr('data-target');
        $.ajax({
            url: http + 'fetch?f=' + rule + '&d=' + uuid + '&tipe=resep',
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
                        $('#download-resep h4.pull-right').html('Jumlah data sebanyak : ' + res.download.count + ' resep.');
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