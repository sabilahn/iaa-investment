<?php
$thisPage = $this->uri->segment(1);
$thisPage1 = $this->uri->segment(2);
?>
<!-- sidebar -->
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <br>
        <div class="sidebar-brand">
            <a href="<?= base_url() ?>dashboard"><img src="<?= base_url() ?>assets/stisla/images/iaa.png" alt="logo" width="120">
                <h4></h4>
                <h6 style="color: #fff">INVESTMENT</h6>
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= base_url() ?>dashboard"><img src="<?= base_url() ?>assets/stisla/images/iaa.png" alt="logo" width="50">
            </a>
        </div>
        <br><br>
        <ul class="sidebar-menu">
            <li class="<?= ($thisPage == 'dashboard') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>dashboard"><i class="fas fa-home"></i><span>Dashboard</a></li>
            <li class="<?= ($thisPage == 'profil_pemegang_saham') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>profil_pemegang_saham"><i class="fas fa-user"></i> <span>Profil Pemegang Saham</span></a></li>
            <!-- <li class="<?= ($thisPage == 'transaksi_investasi') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>transaksi_investasi"><i class="fas fa-exchange-alt"></i> <span>Transaksi Investasi</span></a></li>
            <li class="<?= ($thisPage == 'penarikan_investasi') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>penarikan_investasi"><i class="fas fa-exchange-alt"></i> <span>Penarikan Investasi</span></a></li> -->
            <li class="<?= ($thisPage == 'transaksi_investasi') ? 'dropdown active' : 'dropdown'; ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-exchange-alt"></i>
                    <span>Transaksi Investasi</span></a>
                <ul class="dropdown-menu">
                    <li class="<?= ($thisPage1 == 'tambah_investasi') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>transaksi_investasi/tambah_investasi">Tambah Investasi</a></li>
                    <li class="<?= ($thisPage1 == 'penarikan_investasi') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>transaksi_investasi/penarikan_investasi">Penarikan Investasi</a></li>
                    <li class="<?= ($thisPage1 == 'saham_umum') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>transaksi_investasi/saham_umum">Saham Umum</a></li>
                </ul>
            </li>
            <li class="<?= ($thisPage == 'saldo_harian') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>saldo_harian"><i class="fas fa-wallet"></i> <span>Saldo Harian</span></a></li>
            <li class="<?= ($thisPage == 'sisa_hasil_usaha') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>sisa_hasil_usaha"><i class="fas fa-chart-line"></i> <span>Sisa Hasil Usaha (SHU)</span></a></li>
            <li class="<?= ($thisPage == 'distribusi_dan_porsi') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>distribusi_dan_porsi"><i class="fas fa-coins"></i> <span>Distribusi dan Porsi SHU</span></a></li>
            <li class="<?= ($thisPage == 'bagi_hasil') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>bagi_hasil"><i class="fas fa-balance-scale"></i> <span>Bagi Hasil SHU</span></a></li>
            <li class="<?= ($thisPage == 'laporan') ? 'dropdown active' : 'dropdown'; ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file"></i>
                    <span>Laporan</span></a>
                <ul class="dropdown-menu">
                    <li class="<?= ($thisPage1 == 'profil_pemegang_saham') ? 'active' : ''; ?>"><a target="_blank" class="nav-link" href="<?= site_url() ?>laporan/cetak_profil_pemegang_saham">Profil Pemegang Saham</a></li>
                    <li class="<?= ($thisPage1 == 'saldo_awal_tahun') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>laporan/saldo_awal_tahun">Saldo Awal Tahun</a></li>
                    <li class="<?= ($thisPage1 == 'transaksi_terkini') ? 'active' : ''; ?>"><a target="_blank" class="nav-link" href="<?= site_url() ?>laporan/cetak_transaksi_terkini">Transaksi Investasi Terkini</a></li>
                    <li class="<?= ($thisPage1 == 'transaksi_bulanan') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>laporan/transaksi_bulanan">Transaksi Investasi Bulanan</a></li>
                    <li class="<?= ($thisPage1 == 'penarikan_investasi') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>laporan/penarikan_investasi">Penarikan Investasi</a></li>
                    <li class="<?= ($thisPage1 == 'saldo_harian') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>laporan/saldo_harian">Saldo Harian</a></li>
                    <li class="<?= ($thisPage1 == 'distribusi_dan_porsi') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>laporan/distribusi_dan_porsi">Distribusi dan Porsi</a></li>
                    <li class="<?= ($thisPage1 == 'bagi_hasil_shu') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>laporan/bagi_hasil_shu">Bagi Hasil SHU</a></li>
                    <li class="<?= ($thisPage1 == 'rek_bank_anggota') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>laporan/rek_bank_anggota" target="_blank">Rekening Bank Anggota</a></li>
                </ul>
            </li>
            <li class="<?= ($thisPage == 'label_surat') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>label_surat"><i class="fas fa-address-card"></i><span>Label Surat</span></a></li>
            <?php
            if ($this->session->userdata('role') == 'super_user') { ?>
                <li class="<?= ($thisPage == 'users') ? 'dropdown active' : 'dropdown'; ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i>
                        <span>Setting</span></a>
                    <ul class="dropdown-menu">
                        <li class="<?= ($thisPage1 == 'list') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>users/list">Daftar User</a></li>
                        <li class="<?= ($thisPage1 == 'activity') ? 'active' : ''; ?>"><a class="nav-link" href="<?= site_url() ?>users/activity">Aktivitas User</a></li>
                    </ul>
                </li>
        </ul>
    <?php
            }
    ?>
    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
        <a href="<?= site_url('auth/logout') ?>" class="btn btn-light btn-lg btn-block btn-icon-split">
            <i class="fas fa-sign-out-alt"></i> <b>Logout</b>
        </a>
    </div>
    </aside>
</div>
<!-- /sidebar -->