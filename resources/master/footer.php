        <?php if (FIRST_PART == "home") { ?>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="container">
                    <div class="pull-right hidden-xs">
                        <b>Version</b> 1.0.0
                    </div>
                    <strong>Copyright &copy; <label id="year-copy"></label> <a href="#" target="_blank">Puskesmas</a>.</strong> All rights reserved.
                </div>
                <!-- /.container -->
            </footer>
        <?php } else if (FIRST_PART == "dashboard") { include_once '../app/modal.php'; ?>
            <script>
                $(function() {
                    var str = $('span.hidden-xs').html();
                    var res = str.substr(0, 15);
                    $('span.hidden-xs').html(res);
                    var name_account = $('#account').find('p').html();
                    var nm = name_account.substr(0,15);
                    $('#account').find('p').html(nm);
                });
            </script>
            <!-- Main Footer -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 1.0.0
                </div>
                <strong>Copyright &copy; <label id="year-copy"></label> <a href="#" target="_blank">Puskesmas</a>.</strong> All rights reserved.
            </footer>
        <?php } ?>
    <?= FIRST_PART == "home" || FIRST_PART == "dashboard" ? '</div>' : '' ?>
    <div id="loading" aria-hidden="true" role="dialog"></div>

    <script src="<?= BASE_URL ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="<?= BASE_URL ?>assets/plugins/fastclick/lib/fastclick.js"></script>
    <?= FIRST_PART == "home" || FIRST_PART == "dashboard" ? '<script src="'.BASE_URL.'assets/js/adminlte.js"></script>' : '' ?>
    <?= FIRST_PART == "login" ? '<script src="'.BASE_URL.'assets/js/page/login.js"></script>' : '' ?>
    <?php if (FIRST_PART == "home") { ?> 
        <script>
            $(function() {
                $('[data-toggle="tooltip"]').tooltip();
                $('#cari[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
                    $('.tooltip').addClass('animated wobble');
                    trigger: 'manual';
                }).tooltip('show');
                var str = $('span.hidden-xs').html();
                var res = str.substr(0, 15);
                $('span.hidden-xs').html(res);
            });
        </script>
    <?php } ?>
    <?php if (FIRST_PART == "dashboard") { ?>
        <script src="<?= BASE_URL ?>/assets/js/init_tinymce.js"></script>
    <?php } ?>
  </body>
</html>