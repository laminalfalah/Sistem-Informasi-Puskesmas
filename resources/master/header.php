<?php 
  session_set_cookie_params(0,'/simas'); 
  session_start();
  include_once '../path.php';
  include_once (ABSPATH . '../config/config.php');
  include_once (ABSPATH . '../config/database.php');
  include_once (ABSPATH . '../config/functions.php');
  include_once (ABSPATH . '../config/enkripsi.php');
  include_once (ABSPATH . '../config/static.php');
  
  $url = BASE_URL;
  if (FIRST_PART == "login") {
    if (isset($_SESSION['is_logged']) && isset($_COOKIE['sisteminformasipuskesmas'])) {
      echo "<script>window.location.href= '".$url."dashboard/';</script>";
      exit();
    }
  } elseif (FIRST_PART == "dashboard") {
    if (!isset($_SESSION['is_logged']) && !isset($_COOKIE['sisteminformasipuskesmas'])) {
      echo "<script>window.location.href= '".$url."login/';</script>";
      exit();
    }
  }

  if (isset($_SESSION['is_logged'])) {
    $id = $_SESSION['id'];
    $level = $_SESSION['level'];
    $check = mysqli_query($link,"SELECT * FROM tb_rules");
    $name = "Nama";
    $i = 1;
    while ($r = mysqli_fetch_assoc($check)) {
      if ($level == $r['name_rule'] && $i != 6 && $i != 7) {
        $sql = "SELECT s.name, s.id_staff FROM tb_staff AS s
                INNER JOIN tb_users_staff AS us ON us.id_staff = s.id_staff
                WHERE us.id_user='$id'";
        $query = mysqli_query($link,$sql);
        $a = mysqli_fetch_assoc($query);
        $name = $a['name'];
        $dashboard = "staff";
      } elseif ($level == $r['name_rule'] && $i == 6 && $i != 7) {
        $sql = "SELECT d.name, d.id_dokter FROM tb_dokter AS d
                INNER JOIN tb_users_dokter AS ud ON ud.id_dokter = d.id_dokter
                WHERE ud.id_user='$id'";
        $query = mysqli_query($link,$sql);
        $a = mysqli_fetch_assoc($query);
        $name = $a['name'];
        $idDokternyo = $a['id_dokter'];
        $dashboard = "dokter";
      } elseif ($level == $r['name_rule'] && $i != 6 && $i == 7) {
        $sql = "SELECT p.name, p.id_pasien FROM tb_pasien AS p
                INNER JOIN tb_users_pasien AS up ON up.id_pasien = p.id_pasien
                WHERE up.id_user='$id'";
        $query = mysqli_query($link,$sql);
        $a = mysqli_fetch_assoc($query);
        $name = $a['name'];
        $dashboard = "pasien";
      }
      $i++;
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= APPNAME ?> | <?= ucwords(FIRST_PART) ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/font-awesome/css/font-awesome.css">
    <!--link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/Ionicons/css/ionicons.css"-->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/AdminLTE.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/animatecss/animate.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/sweetalert2/dist/sweetalert2.css"/>
    <?php if (FIRST_PART == "dashboard") { ?>
      <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css">
      <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/DataTables/dataTables.css">
      <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.css">
      <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/DataTables/Scroller-2.0.0/css/scroller.bootstrap.css">
      <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/DataTables/Responsive-2.2.2/css/responsive.bootstrap.css">
      <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/select2/dist/css/select2.css">
      <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css">
      <link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/fancybox/dist/jquery.fancybox.css"/>
    <?php } ?>
    <?= FIRST_PART == "home" || FIRST_PART == "dashboard" ? '<link rel="stylesheet" href="'.BASE_URL.'assets/css/skins/_all-skins.css">' : '' ?>
    <link rel="icon" href="<?= BASE_URL ?>favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="<?= BASE_URL ?>favicon.ico" type="image/x-icon"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <meta name="url" content="<?= BASE_URL ?>">
    <meta name="app" content="<?= APPNAME ?>">
    <script src="<?= BASE_URL ?>assets/plugins/jquery/dist/jquery.js"></script>
    <script src="<?= BASE_URL ?>assets/plugins/bootstrap/dist/js/bootstrap.js"></script>
    <script src="<?= BASE_URL ?>assets/plugins/sweetalert2/dist/sweetalert2.js"></script>
    <script src="<?= BASE_URL ?>assets/plugins/moment/min/moment-with-locales.js"></script>
    <?php if (FIRST_PART == "dashboard") { 
        if (SECOND_PART == "" && THIRD_PART == "") { ?>
            <!--script src="<?= BASE_URL ?>assets/js/page/dashboard.js"></script-->
        <?php } ?>
        <script src="<?= BASE_URL ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="<?= BASE_URL ?>assets/plugins/DataTables/dataTables.js"></script>
        <script src="<?= BASE_URL ?>assets/plugins/DataTables/DataTables-1.10.18/js/dataTables.bootstrap.js"></script>
        <script src="<?= BASE_URL ?>assets/plugins/DataTables/Scroller-2.0.0/js/scroller.bootstrap.js"></script>
        <script src="<?= BASE_URL ?>assets/plugins/DataTables/Responsive-2.2.2/js/dataTables.responsive.js"></script>
        <script src="<?= BASE_URL ?>assets/plugins/tinymce/jquery.tinymce.min.js"></script>
        <script src="<?= BASE_URL ?>assets/plugins/tinymce/tinymce.min.js"></script>
        <script src="<?= BASE_URL ?>assets/plugins/select2/dist/js/select2.js"></script>
        <script src="<?= BASE_URL ?>assets/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
        <script src="<?= BASE_URL ?>assets/plugins/fancybox/dist/jquery.fancybox.js"></script>
    <?php } ?>
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
    <script src="<?= BASE_URL ?>assets/plugins/autoNumeric/autoNumeric.js"></script>
    <script src="<?= BASE_URL ?>assets/plugins/jquery-validation/dist/jquery.validate.js"></script>
  </head>
  
  <body class="hold-transition <?= (FIRST_PART == "home") ? 'skin-green layout-top-nav' : ((FIRST_PART == "dashboard") ? 'skin-green sidebar-mini' : 'login-page') ?>">
    <noscript>
      Agar fungsi di situs ini lebih baik, anda perlu mengaktifkan JavaScript. Berikut ini adalah <a href="https://www.enable-javascript.com/id/" target="_blank" class="notjs">petunjuk cara mengaktifkan JavaScript</a> di peramban web Anda.
    </noscript>
    <div>
      <!--[if lte IE 6]> <div id='badBrowser'>Browser ini tidak mendukung. Silakan gunakan browser seperti <a href="https://www.mozilla.org/id/firefox/fx/" rel="nofollow">Firefox</a>, <a href="https://www.google.com/intl/id/chrome/browser/" rel="nofollow">Chrome</a> atau <a https="http://www.apple.com/safari/" rel="nofollow">Safari</a></div> <![endif]-->
    </div>
    <?= FIRST_PART == "home" || FIRST_PART == "dashboard" ? '<div class="wrapper">' : '' ?>

      <?php if (FIRST_PART == "home") { ?>
        <header class="main-header">
          <nav class="navbar navbar-static-top">
            <div class="container">
              <div class="navbar-header">
                <a href="<?= BASE_URL . 'home/' ?>" class="navbar-brand"><b>PUS</b>KESMAS</a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                  <i class="fa fa-bars"></i>
                </button>
              </div>
              <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                  <li class="<?= FIRST_PART == 'home' && SECOND_PART == '' && THIRD_PART == '' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL . 'home/' ?>">
                      Home <span class="sr-only">(current)</span>
                    </a>
                  </li>
                  <li class="dropdown <?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[0])) ? 'active' : '' ?>">
                    <a href="javascript:void()" class="dropdown-toggle" data-toggle="dropdown">
                      <?= $home[0] ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                      <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[0])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $subhome[0])) ? 'active' : '' ?>">
                        <a href="<?= BASE_URL . 'home/' .strtolower(str_replace(' ', '-', $home[0])). '/' .strtolower(str_replace(' ', '-', $subhome[0])). '/' ?>"><?= $subhome[0] ?></a>
                      </li>
                      <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[0])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $subhome[1])) ? 'active' : '' ?>">
                        <a href="<?= BASE_URL . 'home/' .strtolower(str_replace(' ', '-', $home[0])). '/' .strtolower(str_replace(' ', '-', $subhome[1])). '/' ?>"><?= $subhome[1] ?></a>
                      </li>
                      <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[0])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $subhome[2])) ? 'active' : '' ?>">
                        <a href="<?= BASE_URL . 'home/' .strtolower(str_replace(' ', '-', $home[0])). '/' .strtolower(str_replace(' ', '-', $subhome[2])). '/' ?>"><?= $subhome[2] ?></a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown <?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[1])) ? 'active' : '' ?>">
                    <a href="javascript:void()" class="dropdown-toggle" data-toggle="dropdown">
                      <?= $home[1] ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                      <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[1])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $subhome[3])) ? 'active' : '' ?>">
                        <a href="<?= BASE_URL . 'home/' .strtolower(str_replace(' ', '-', $home[1])). '/' .strtolower(str_replace(' ', '-', $subhome[3])). '/' ?>"><?= $subhome[3] ?></a>
                      </li>
                      <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[1])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $subhome[4])) ? 'active' : '' ?>">
                        <a href="<?= BASE_URL . 'home/' .strtolower(str_replace(' ', '-', $home[1])). '/' .strtolower(str_replace(' ', '-', $subhome[4])). '/' ?>"><?= $subhome[4] ?></a>
                      </li>
                    </ul>
                  </li>
                  <?php 
                    $check = mysqli_query($link,"SELECT * FROM tb_poli");
                    if (mysqli_num_rows($check) > 0) {
                      ?>
                        <li class="dropdown <?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[2])) ? 'active' : '' ?>">
                          <a href="javascript:void()" class="dropdown-toggle" data-toggle="dropdown">
                          <?= $home[2] ?> <span class="caret"></span>
                          </a>
                          <ul class="dropdown-menu" role="menu">
                            <?php
                              while ($r = mysqli_fetch_assoc($check)) {
                                ?>
                                <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[2])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $r['name_poli'])) ? 'active' : '' ?>">
                                  <a href="<?= BASE_URL . 'home/' .strtolower(str_replace(' ', '-', $home[2])). '/' .strtolower(str_replace(' ', '-', $r['name_poli'])). '/' ?>"><?= ucwords($r['name_poli']) ?></a>
                                </li>
                                <?php
                              }
                            ?>
                          </ul>
                        </li>
                      <?php
                    }
                  ?>
                  <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $home[3])) ? 'active' : '' ?>">
                    <a href="<?= BASE_URL . 'home/' .strtolower(str_replace(' ', '-', $home[3])). '/'?>"><?= $home[3] ?></a>
                  </li>
                </ul>
              </div>
              <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                  <?php if (isset($_SESSION['is_logged'])) { ?>
                    <li class="dropdown user user-menu">
                      <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= BASE_URL . 'assets/img/puskes.png'; ?>" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?= $name; ?></span>
                      </a>
                      <ul class="dropdown-menu">
                        <li class="user-body">
                          <a href="<?= BASE_URL . 'dashboard/' ?>">
                            <i class="fa fa-dashboard text-yellow"></i>
                            <span>Dashboard</span>
                          </a>
                        </li>
                        <li class="user-body">
                          <a href="<?= BASE_URL . 'logout/' ?>">
                            <i class="fa fa-sign-out text-red"></i>
                            <span>Logout</span>
                          </a>
                        </li>
                      </ul>
                    </li>
                  <?php } else { ?>
                    <li>
                      <a href="<?= BASE_URL . 'login/' ?>">
                        <i class="fa fa-sign-in"></i>
                        <span class="hidden-xs">Login</span>
                      </a>
                    </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
          </nav>
        </header>
      <?php } elseif (FIRST_PART == "dashboard") { ?>
        <header class="main-header">
          <a href="<?= BASE_URL . 'dashboard/' ?>" class="logo">
            <span class="logo-mini"><b>P</b>KM</span>
            <span class="logo-lg"><b>PUS</b>KESMAS</span>
          </a>
          <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
              <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
              <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                  <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="<?= BASE_URL . 'assets/img/puskes.png'; ?>" class="user-image" alt="User Image">
                    <span class="hidden-xs"><?= $name; ?></span>
                  </a>
                  <ul class="dropdown-menu">
                    <!-- Menu Body -->
                    <li class="user-body">
                      <a href="<?= BASE_URL . 'home/' ?>" target="_blank">
                        <i class="fa fa-home text-green"></i>
                        <span>Beranda</span>
                      </a>
                    </li>
                    <li class="user-body">
                      <a href="<?= BASE_URL . 'dashboard/'.strtolower(str_replace(' ','-',$menu[4])).'/@'.strtolower($_SESSION['username']).'/' ?>">
                        <i class="fa fa-user text-yellow"></i>
                        <span>Profil</span>
                      </a>
                    </li>
                    <li class="user-body">
                      <a href="<?= BASE_URL . 'dashboard/'.strtolower(str_replace(' ','-',$menu[5])).'/@'.strtolower($_SESSION['username']).'/' ?>">
                        <i class="fa fa-lock text-blue"></i>
                        <span>Password</span>
                      </a>
                    </li>
                    <li class="user-body">
                      <a href="<?= BASE_URL . 'logout/' ?>">
                        <i class="fa fa-sign-out text-red"></i>
                        <span>Logout</span>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
        </header>
      <?php } ?>
      
      <?php if (FIRST_PART == "dashboard") include_once '../master/navigation.php'; ?>
