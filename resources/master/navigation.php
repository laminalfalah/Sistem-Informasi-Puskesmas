        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">    
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    <div class="pull-left image">
                    <img src="<?= BASE_URL . 'assets/img/puskes.png'; ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info" id="account">
                        <p><?= $name; ?></p>
                        <a id="profil" name="profil" href="<?= BASE_URL.'dashboard/'.strtolower(str_replace(' ','-',$menu[4])).'/@'.strtolower($_SESSION['username']).'/' ?>">
                            <i class="fa fa-user text-yellow"></i>
                        </a>
                        <a id="password" name="password" href="<?= BASE_URL.'dashboard/'.strtolower(str_replace(' ','-',$menu[5])).'/@'.strtolower($_SESSION['username']).'/' ?>">
                            <i class="fa fa-lock text-blue"></i>
                        </a>
                        <a id="logout" name="logout" href="<?= BASE_URL.'logout/'; ?>">
                            <i class="fa fa-sign-out text-red"></i>
                        </a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree" id="navigation">
                    <li class="header">MAIN NAVIGATION</li>
                    <!-- Optionally, you can add icons to the links -->
                    <li class="<?= FIRST_PART == 'dashboard' && SECOND_PART == '' && THIRD_PART == '' ? 'active' : '' ?>">
                        <a href="<?= BASE_URL . 'dashboard/' ?>" class="menu">
                            <i class="fa fa-dashboard text-olive"></i> 
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <!-- Nav Manajemen Pengguna -->
                    <?php if (hasPermit('menu_user')) { ?>
                        <li class="treeview <?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[0])) ? 'active' : '' ?>">
                            <a href="javascript:void()">
                                <i class="fa fa-database text-purple"></i> 
                                <span><?= $menu[0] ?></span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if (hasPermit('submenu_dokter')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[0])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[0])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[0])). '/' .strtolower(str_replace(' ', '-', $submenu[0])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-red"></i>
                                            <span><?= $submenu[0] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (hasPermit('submenu_pasien')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[0])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[1])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[0])). '/' .strtolower(str_replace(' ', '-', $submenu[1])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-yellow"></i>
                                            <span><?= $submenu[1] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (hasPermit('submenu_staff')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[0])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[2])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[0])). '/' .strtolower(str_replace(' ', '-', $submenu[2])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-green"></i>
                                            <span><?= $submenu[2] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <!-- Nav Manajemen Obat -->
                    <?php if (hasPermit('menu_obat')) { ?>
                        <li class="treeview <?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[1])) ? 'active' : '' ?>">
                            <a href="javascript:void()">
                                <i class="fa fa-shopping-basket text-maroon"></i> 
                                <span><?= $menu[1] ?></span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if (hasPermit('submenu_obat')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[1])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[3])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[1])). '/' .strtolower(str_replace(' ', '-', $submenu[3])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-red"></i>
                                            <span><?= $submenu[3] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (hasPermit('submenu_satuan')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[1])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[4])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[1])). '/' .strtolower(str_replace(' ', '-', $submenu[4])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-yellow"></i>
                                            <span><?= $submenu[4] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <!-- Nav Manajemen Puskesmas -->
                    <?php if (hasPermit('menu_puskesmas')) { ?>
                        <li class="treeview <?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2])) ? 'active' : '' ?>">
                            <a href="javascript:void()">
                                <i class="fa fa-medkit text-red"></i> 
                                <span><?= $menu[2] ?></span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if (hasPermit('submenu_berita')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[5])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[2])). '/' .strtolower(str_replace(' ', '-', $submenu[5])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-red"></i>
                                            <span><?= $submenu[5] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (hasPermit('submenu_poli')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[6])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[2])). '/' .strtolower(str_replace(' ', '-', $submenu[6])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-yellow"></i>
                                            <span><?= $submenu[6] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (hasPermit('submenu_darah')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[7])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[2])). '/' .strtolower(str_replace(' ', '-', $submenu[7])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-green"></i>
                                            <span><?= $submenu[7] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (hasPermit('submenu_spesialis')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[8])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[2])). '/' .strtolower(str_replace(' ', '-', $submenu[8])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-blue"></i>
                                            <span><?= $submenu[8] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (hasPermit('submenu_jadwal')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[9])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[2])). '/' .strtolower(str_replace(' ', '-', $submenu[9])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-orange"></i>
                                            <span><?= $submenu[9] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (hasPermit('submenu_resep')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[10])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[2])). '/' .strtolower(str_replace(' ', '-', $submenu[10])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-aqua"></i>
                                            <span><?= $submenu[10] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (hasPermit('submenu_rekam_medis')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[11])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[2])). '/' .strtolower(str_replace(' ', '-', $submenu[11])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-purple"></i>
                                            <span><?= $submenu[11] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (hasPermit('submenu_rawat_jalan')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[2])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[12])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[2])). '/' .strtolower(str_replace(' ', '-', $submenu[12])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-olive"></i>
                                            <span><?= $submenu[12] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <!-- Nav Manajemen Website -->
                    <?php if (hasPermit('menu_konfigurasi')) { ?>
                        <li class="treeview <?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[3])) ? 'active' : '' ?>">
                            <a href="javascript:void()">
                                <i class="fa fa-gears text-yellow"></i> 
                                <span><?= $menu[3] ?></span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if (hasPermit('submenu_beranda')) { ?>
                                    <li class="<?= @$_REQUEST['menu'] == strtolower(str_replace(' ', '-', $menu[3])) && @$_REQUEST['submenu'] == strtolower(str_replace(' ', '-', $submenu[15])) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL . 'dashboard/' .strtolower(str_replace(' ', '-', $menu[3])). '/' .strtolower(str_replace(' ', '-', $submenu[15])). '/' ?>" class="menu">
                                            <i class="fa fa-circle-o text-green"></i>
                                            <span><?= $submenu[15] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>