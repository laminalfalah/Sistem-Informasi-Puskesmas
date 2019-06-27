$(function () {
    var remote = $('#list_berita').attr('data-remote');
    var target = $('#list_berita').attr('data-target');
    var items = $('#items');
    var boxload = $('#box-load');
    
    $('html').bind('keypress', function (e) {
        if (e.keyCode == 13) {
            return false;
        }
    });
    
    setInterval(loadData(), 1000);

    function loadData() {
        items.empty();
        $('#msg-empty').css('display','none');
        $.ajax({
            url: http + 'list?f=' + remote + '&d=' + target,
            async: true,
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                hideLoading();
                let datanyo = "";
                if (res.berita.code == 1) {
                    boxload.css('display','block');
                    let max = res.berita.data.length;
                    datanyo += "<div class=\"row itemnyo\">"
                    for (let i = 0; i < res.berita.data.length; i++) {
                        let slug = res.berita.data[i].slug;
                        let title = res.berita.data[i].title;
                        let cont = res.berita.data[i].content;
                        let img = http + res.berita.data[i].cover;
                        datanyo += "<div class=\"col-md-12 col-xs-12\">" + 
                                        "<div class=\"box box-solid box-default\">" + 
                                            "<div class=\"box-header\">" + 
                                                "<h4 class=\"box-title\">" + title + "</h4>" + 
                                            "</div>" + 
                                            "<div class=\"box-body\">" + 
                                                "<div class=\"row\">" + 
                                                    "<div class=\"col-md-3 col-xs-12\">" + 
                                                        "<img src=\"" + img + "\" class=\"img-responsive\" alt=\"\" width=\"100%\" />" +
                                                    "</div>" +
                                                    "<div class=\"col-md-9 col-xs-12\">" + 
                                                        "<p style=\"text-align: justify;\">" + cont + " <a href=\""+ http + "home/detail/" + slug + "/" +"\">Selengkapnya...</a></p>" +
                                                    "</div>" +
                                                "</div>" + 
                                            "</div>" +
                                        "</div>" + 
                                    "</div>";
                    }
                    datanyo+="</div>";
                } else {
                    $('#cari').attr('disabled',true);
                    $('#msg-empty').css('display','block');
                }
                if (res.berita.total <= 6) {
                    $('#box-load').css('display', 'none');
                }
                items.append(datanyo);
            }
        });
    }

    //jQuery.noConflict();

    function delay(callback, ms) {
        var timer = 0;
        return function () {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }

    $('#cari').keyup(delay(function (e) {
        e.preventDefault();
        var target = $('#form-cari').attr('data-target');
        var remote = $('#form-cari').attr('data-remote');
        var query = $(this).val();
        if (query != "") {
            $.ajax({
                url: http + 'list?f=' + remote + '&d=' + target + '&s=0&q=' + query,
                async: true,
                dataType: 'json',
                type: 'POST',
                beforeSend: function () {
                    items.empty();
                    boxload.css('display', 'none');
                    $('#msg-empty').css('display','none');
                },
                success: function (res) {
                    let datanyo = "";
                    if (res.berita.code == 1) {
                        let max = res.berita.data.length;
                        datanyo += "<div class=\"row itemnyo\">"
                        for (let i = 0; i < res.berita.data.length; i++) {
                            let slug = res.berita.data[i].slug;
                            let title = res.berita.data[i].title;
                            let cont = res.berita.data[i].content;
                            let img = http + res.berita.data[i].cover;
                            datanyo += "<div class=\"col-md-12 col-xs-12\">" + 
                                            "<div class=\"box box-solid box-default\">" + 
                                                "<div class=\"box-header\">" + 
                                                    "<h4 class=\"box-title\">" + title + "</h4>" + 
                                                "</div>" + 
                                                "<div class=\"box-body\">" + 
                                                    "<div class=\"row\">" + 
                                                        "<div class=\"col-md-3 col-xs-12\">" + 
                                                            "<img src=\"" + img + "\" class=\"img-responsive\" alt=\"\" width=\"100%\" />" +
                                                        "</div>" +
                                                        "<div class=\"col-md-9 col-xs-12\">" + 
                                                            "<p style=\"text-align: justify;\">" + cont + " <a href=\""+ http + "home/detail/" + slug + "/" +"\">Selengkapnya...</a></p>" +
                                                        "</div>" +
                                                    "</div>" + 
                                                "</div>" +
                                            "</div>" + 
                                        "</div>";
                        }
                        datanyo+="</div>";
                    } else {
                        $('#msg-empty').css('display','block');
                        $('#msg-text').html("Tidak ditemukan <em class=\"text-red\">\"" + query + "\"</em>");
                    }
                    if (res.berita.filter <= 6) {
                        $('#box-load').css('display', 'none');
                    }
                    items.append(datanyo);
                    hideLoading();
                }
            });
        } else {
            loadData();
        }
    }, 500));

    $(document).on('click', '#loadmore', function (e) {
        e.preventDefault();
        var count = 0;
        var item = $('.itemnyo').children().length;
        var remote = $('#list_berita').attr('data-remote');
        var target = $('#box-load').attr('data-target');
        var query = $('#cari').val();
        for (let i = 0; i < item; i++) {
            count++
        }
        if (query == "") {
            $.ajax({
                url: http + 'list?f=' + remote + '&d=' + target + '&s=' + count + '&q=' + query,
                async: true,
                dataType: 'json',
                type: 'POST',
                beforeSend: function() {
                    showLoading();
                },
                success: function (res) {
                    let datanyo = "";
                    if (res.berita.code == 1) {
                        boxload.css('display','block');
                        let max = res.berita.data.length;
                        datanyo += "<div class=\"row itemnyo\">"
                        for (let i = 0; i < res.berita.data.length; i++) {
                            let slug = res.berita.data[i].slug;
                            let title = res.berita.data[i].title;
                            let cont = res.berita.data[i].content;
                            let img = http + res.berita.data[i].cover;
                            datanyo += "<div class=\"col-md-12 col-xs-12\">" + 
                                            "<div class=\"box box-solid box-default\">" + 
                                                "<div class=\"box-header\">" + 
                                                    "<h4 class=\"box-title\">" + title + "</h4>" + 
                                                "</div>" + 
                                                "<div class=\"box-body\">" + 
                                                    "<div class=\"row\">" + 
                                                        "<div class=\"col-md-3 col-xs-12\">" + 
                                                            "<img src=\"" + img + "\" class=\"img-responsive\" alt=\"\" width=\"100%\" />" +
                                                        "</div>" +
                                                        "<div class=\"col-md-9 col-xs-12\">" + 
                                                            "<p style=\"text-align: justify;\">" + cont + " <a href=\""+ http + "home/detail/" + slug + "/" +"\">Selengkapnya...</a></p>" +
                                                        "</div>" +
                                                    "</div>" + 
                                                "</div>" +
                                            "</div>" + 
                                        "</div>";
                        }
                        datanyo+="</div>";
                        items.append(datanyo);
                        count = 0;
                    } else {
                        $('#msg-empty').css('display','block');
                    }
                    if (res.berita.total <= res.berita.filter) {
                        $('#box-load').css('display', 'none');
                    }
                    hideLoading();
                }
            });
        } else {
            var item = $('.itemnyo').children().length;
            var target = $('#form-cari').attr('data-target');
            var remote = $('#form-cari').attr('data-remote');
            $.ajax({
                url: http + 'list?f=' + remote + '&d=' + target + '&s=' + item + '&q=' + query,
                async: true,
                dataType: 'json',
                type: 'POST',
                beforeSend: function() {
                    showLoading();
                    $('#box-load').css('display', 'none');
                },
                success: function (res) {
                    setTimeout(function () {
                        let datanyo = "";
                        if (res.berita.code == 1) {
                            boxload.css('display','block');
                            let max = res.berita.data.length;
                            datanyo += "<div class=\"row itemnyo\">"
                            for (let i = 0; i < res.berita.data.length; i++) {
                                let slug = res.berita.data[i].slug;
                                let title = res.berita.data[i].title;
                                let cont = res.berita.data[i].content;
                                let img = http + res.berita.data[i].cover;
                                datanyo += "<div class=\"col-md-12 col-xs-12\">" + 
                                                "<div class=\"box box-solid box-default\">" + 
                                                    "<div class=\"box-header\">" + 
                                                        "<h4 class=\"box-title\">" + title + "</h4>" + 
                                                    "</div>" + 
                                                    "<div class=\"box-body\">" + 
                                                        "<div class=\"row\">" + 
                                                            "<div class=\"col-md-3 col-xs-12\">" + 
                                                                "<img src=\"" + img + "\" class=\"img-responsive\" alt=\"\" width=\"100%\" />" +
                                                            "</div>" +
                                                            "<div class=\"col-md-9 col-xs-12\">" + 
                                                                "<p style=\"text-align: justify;\">" + cont + " <a href=\""+ http + "home/detail/" + slug + "/" +"\">Selengkapnya...</a></p>" +
                                                            "</div>" +
                                                        "</div>" + 
                                                    "</div>" +
                                                "</div>" + 
                                            "</div>";
                            }
                            datanyo+="</div>";
                        } else {
                            $('#msg-empty').css('display','block');
                            $('#msg-text').html("Tidak ditemukan <em class=\"text-red\">\"" + query + "\"</em>");
                        }
                        items.append(datanyo);
                        hideLoading();
                    }, 1000);
                    if (res.berita.filter <= 6) {
                        $('#box-load').css('display', 'none');
                    }
                }
            });
        }
    });
});