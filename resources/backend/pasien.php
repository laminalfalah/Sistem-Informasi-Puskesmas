<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="box box-solid box-default">
            <div class="box-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#dataresep" id="dataresepnyo">Data Resep</a></li>
                    <li><a data-toggle="tab" href="#datarekam" id="datarekamnyo">Data Rekam Medis</a></li>
                    <li><a data-toggle="tab" href="#datarawat" id="datarawatnyo">Data Rawat Jalan</a></li>
                </ul>
                <div class="tab-content">
                    <div id="dataresep" class="tab-pane fade in active">
                        <?php
                            echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-resep']['table']) . "</div></div>";
                        ?>
                    </div>
                    <div id="datarekam" class="tab-pane fade in">
                        <?php
                            echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-rekam_medis']['table']) . "</div></div>";
                        ?>
                    </div>
                    <div id="datarawat" class="tab-pane fade in">
                        <?php
                            echo "<div class=\"row\"><div class=\"col-md-12 col-xs-12\">" . table($static['data-rawat']['table']) . "</div></div>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= BASE_URL . 'assets/js/page/dashboard/pasien.js' ?>"></script>