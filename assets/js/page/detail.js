$(function () {

    var remote = $('#add-komentar').attr('data-remote');
    var target = $('#add-komentar').attr('data-target');
    var jmlh = $('#jmlh');
    var komen = $('.komennyo');

    function resetForm(target) {
        $(target).find('.form-group').attr('class', 'form-group');
        $(target).find('label.has-error').remove();
        $(target).find('span.glyphicon').remove();
        $(target).find('span.fa').remove();
        $(target).find('em.has-error').remove();
        $(target)
            .find("input,textarea,select").val('').end()
            .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
    }

    // Validation detail Start // 
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
                url: http + 'list?f=' + remote + '&d=' + uuid,
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
                        if (res.komentar.code) {
                            resetForm('#add-komentar');
                            hideLoading();
                            alertSuccess(res.komentar.message);
                            window.location.href = '';
                        } else {
                            hideLoading();
                            alertWarning(res.komentar.message);
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
    // Validation detail End //

    setInterval(loadkomen(), 1000);

    function loadkomen() {
        var uuid = komen.attr('data-target');
        var uid = $('input[type=hidden]').val();
        $.ajax({
            url: http + 'list?f=' + remote + '&d=' + uuid + '&id=' + uid,
            async: true,
            dataType: 'json',
            type: 'POST',
            beforeSend: function() {
                komen.empty();
            },
            success: function (res) {
                hideLoading();
                let datanyo = "";
                if (res.komen.code) {
                    if (res.komen.data.length > 0) {
                        jmlh.html(res.komen.data.length);
                        datanyo += "<div class=\"row\">";
                        for (let i = 0; i < res.komen.data.length; i++) {
                            let tgl = res.komen.data[i].tgl;
                            let title = res.komen.data[i].name;
                            let cont = res.komen.data[i].comment;
                            datanyo += "<div class=\"col-xs-12\">" +
                                            "<h5>Nama : " + title + "</h5>" +
                                            "<p>Tanggal : " + moment(tgl).format("dddd, DD-MMMM-YYYY HH:mm:ss") + "</p>" +
                                            "<p>Komentar : " + cont + "</p>" +
                                        "</div>";
                        }
                        datanyo+="</div>";
                    }
                } else {
                    datanyo = res.komen.message;
                }
                komen.append(datanyo);
            }
        });
    }
});