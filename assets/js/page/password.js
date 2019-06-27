$(function() {
    var remote = $('#formpassword').attr('data-remote');
    var target = $('#formpassword').attr('data-target');
    var sesiid = $('#formpassword').attr('data-session');
    var uuid = $('#formpassword').find('.passlama').attr('data-target');

    $('#formpassword').validate({
        errorClass: 'has-error animated tada',
        validClass: 'has-success',
        rules: {
            passwordlama: {
                required: true,
                rangelength: [8,25],
                remote: {
                    url: http + 'fetch?f='+remote+'&d='+uuid+'&u='+sesiid,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        passwordlama: function() {
                            return $('#formpassword').find('.passlama').val();
                        }
                    }
                }
            },
            passwordbaru: {
                required: true,
                rangelength: [8,25],
            },
            konfirmasipassword: {
                required: true,
                rangelength: [8,25],
                equalTo: '#passwordbaru'
            }
        },
        messages: {
            passwordlama: {
                required: "Password lama harus diisi !",
                rangelength: "Minimal 8 karakter, Maksimal 25 karakter",
                remote: "Password tidak benar !"
            },
            passwordbaru: {
                required: "Password baru harus diisi !",
                rangelength: "Minimal 8 karakter, Maksimal 25 karakter"
            },
            konfirmasipassword: {
                required: "Konfirmasi password harus diisi !",
                rangelength: "Minimal 8 karakter, Maksimal 25 karakter",
                equalTo: "Konfirmasi password harus sama dengan password baru !"
            }
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            error.addClass("help-block");
            element.parents(".col-md-9").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
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
            var pass = new FormData($('#formpassword')[0]);
            $.ajax({
                url: http + 'fetch?f='+remote+'&d='+target+'&u='+sesiid,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                timeout: 3000,
                data: pass,
                beforeSend: function() {
                    showLoading();
                },
                success: function(res) {
                    if (res.length == 0) {
                        hideLoading();
                        alertDanger('Invalid request');
                    } else {
                        if (res.password.code == 1) {
                            ajaxSuccess('#formpassword');
                            hideLoading();
                            alertSuccess(res.password.message);
                        } else {
                            hideLoading();
                            alertWarning(res.password.message);
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
    })
});