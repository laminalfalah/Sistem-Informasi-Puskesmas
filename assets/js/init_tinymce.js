tinymce.init({
    selector: '.cover, .cover_edit',
    theme: 'modern',
    width: '100%',
    height: 100,
    toolbar: false,
    menubar: false,
    statusbar: false,
    plugins: [
        'responsivefilemanager autoresize',
    ],
    autoresize_on_init: false,
    content_css: http + 'assets/plugins/tinymce/skins/lightgray/content.min.css',
    external_filemanager_path: http + "/filemanager/",
    filemanager_title: app,
    external_plugins: { "filemanager": http + "/filemanager/plugin.min.js" },
    toolbar1: 'responsivefilemanager',
    image_advtab: true,
});
tinymce.init({
    selector: '.description, .description_edit',
    theme: 'modern',
    width: '100%',
    height: 200,
    toolbar: false,
    menubar: false,
    statusbar: false,
    plugins: [
        'autoresize advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'save table contextmenu directionality emoticons template paste textcolor responsivefilemanager code'
    ],
    autoresize_on_init: false,
    content_css: http + 'assets/plugins/tinymce/skins/lightgray/content.min.css',
    external_filemanager_path: http + "/filemanager/",
    filemanager_title: app,
    external_plugins: { "filemanager": http + "/filemanager/plugin.min.js" },
    toolbar1: 'insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent |',
    toolbar2: "responsivefilemanager | link unlink anchor | image media | forecolor backcolor emoticons | print preview code |",
    image_advtab: true,
});
function ajaxLoad() {
    var cvr = tinyMCE.get('cover');
    var cvr_edit = tinyMCE.get('cover_edit');
    var desc = tinyMCE.get('description');
    var desc_edit = tinyMCE.get('description_edit');

    // Do you ajax call here, window.setTimeout fakes ajax call
    cvr.setProgressState(1); // Show progress
    window.setTimeout(function () {
        cvr.setProgressState(0); // Hide progress
        cvr.setContent('HTML content that got passed from server.');
    }, 3000);
    cvr_edit.setProgressState(1); // Show progress
    window.setTimeout(function () {
        cvr_edit.setProgressState(0); // Hide progress
        cvr_edit.setContent('HTML content that got passed from server.');
    }, 3000);
    desc.setProgressState(1); // Show progress
    window.setTimeout(function () {
        desc.setProgressState(0); // Hide progress
        desc.setContent('HTML content that got passed from server.');
    }, 3000);
    desc_edit.setProgressState(1); // Show progress
    window.setTimeout(function () {
        desc_edit.setProgressState(0); // Hide progress
        desc_edit.setContent('HTML content that got passed from server.');
    }, 3000);
}
function ajaxSave() {
    var cvr = tinyMCE.get('cover');
    var cvr_edit = tinyMCE.get('cover_edit');
    var desc = tinyMCE.get('description');
    var desc_edit = tinyMCE.get('description_edit');

    // Do you ajax call here, window.setTimeout fakes ajax call
    cvr.setProgressState(1); // Show progress
    window.setTimeout(function () {
        cvr.setProgressState(0); // Hide progress
        alert(cvr.getContent());
    }, 3000);
    cvr_edit.setProgressState(1); // Show progress
    window.setTimeout(function () {
        cvr_edit.setProgressState(0); // Hide progress
        alert(cvr_edit.getContent());
    }, 3000);
    desc.setProgressState(1); // Show progress
    window.setTimeout(function () {
        desc.setProgressState(0); // Hide progress
        alert(desc.getContent());
    }, 3000);
    desc_edit.setProgressState(1); // Show progress
    window.setTimeout(function () {
        desc_edit.setProgressState(0); // Hide progress
        alert(desc_edit.getContent());
    }, 3000);
}