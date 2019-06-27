<?php
session_start();
require_once '../path.php';
require_once(ABSPATH . '../config/config.php');
require_once(ABSPATH . '../config/database.php');
require_once(ABSPATH . '../config/header-json.php');
include_once(ABSPATH . '../config/functions.php');
require_once(ABSPATH . '../config/enkripsi.php');

$data = array();

$mods = @$_REQUEST['tipe'] ?? @$_REQUEST['tipe'];

if ($mods == "login") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $a = mysqli_real_escape_string($link, strip_tags($_POST['username']));
        $b = mysqli_real_escape_string($link, strip_tags($_POST['password']));
        $check = execute("SELECT username FROM tb_users WHERE username='$a'");
        if (mysqli_num_rows($check) == 0) {
            $data['auth'] = result(0, 'Username not found !');
        } else {
            $pass = hash('sha512', $b);
            $sqlLogin = execute("SELECT u.id_user,username,password,status,name_rule FROM tb_users AS u
                INNER JOIN tb_rule_user AS ru ON ru.id_user = u.id_user 
                INNER JOIN tb_rules AS r ON r.id_rule = ru.id_rule WHERE username='$a' AND password='$pass' LIMIT 1");
            $r = mysqli_fetch_assoc($sqlLogin);
            if (mysqli_num_rows($sqlLogin) == 1 && $r['status'] == 0) {
                $data['auth'] = result(0, 'Your account isn\'t active !');
            } else if (mysqli_num_rows($sqlLogin) == 1 && $r['status'] == 1) {
                $data['auth'] = result(1, 'Login Success ! Waiting redirected');
                $_SESSION['is_logged'] = true;
                $_SESSION['id'] = $r['id_user'];
                $_SESSION['username'] = $r['username'];
                $_SESSION['level'] = $r['name_rule'];
                $_SESSION['user_agent'] = $userAgent;
                setcookie("sisteminformasipuskesmas", true, time() + (60 * 10), "/simas", "localhost", false, true);
            } else {
                $data['auth'] = result(0, 'Username or Password is wrong !');
            }
        }
    } else {
        $data['auth'] = result(0, 'Username or Password is required !');
    }
} elseif ($mods == "dashboard") {
    if (isset($_SESSION['is_logged'])) {
        $user = mysqli_num_rows(execute("SELECT * FROM tb_users"));
        $alat = mysqli_num_rows(execute("SELECT code_item FROM tb_items AS i
                                             INNER JOIN tb_categories AS c ON c.code_category = i.code_category 
                                             WHERE c.name_category='Alat'"));
        $part = mysqli_num_rows(execute("SELECT name_item, name_category FROM tb_items AS i
                                             INNER JOIN tb_categories AS c ON c.code_category = i.code_category
                                             WHERE c.name_category LIKE '%Sparepart%'"));
        $html = '
                <div class="col-lg-4 col-xs-12">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>' . $user . '</h3>
                            <p>Jumlah Pengguna</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="small-box-footer"></div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-12">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>' . $alat . '</h3>
                            <p>Jumlah Alat</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-wrench"></i>
                        </div>
                        <div class="small-box-footer"></div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-12">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>' . $part . '</h3>
                            <p>Jumlah Sparepart</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-gears"></i>
                        </div>
                        <div class="small-box-footer"></div>
                    </div>
                </div>
            ';
        $data = $html;
    } else {
        $data['dashboard'] = result(0, 'Access Forbidden');
    }
} else {
    $data['api'] = result(0, 'Url Invalid');
}

echo json_encode($data);
