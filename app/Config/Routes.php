<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public / Auth routes
$routes->get('/', 'Auth::index');
$routes->get('auth', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('auth/ubah_password', 'Auth::ubah_password', ['filter' => 'auth']);
$routes->post('auth/update_password', 'Auth::update_password', ['filter' => 'auth']);

// Public QR verification routes
$routes->get('surat_saham/index/(:segment)', 'Surat_saham::index/$1');
$routes->get('penarikan/index/(:segment)', 'Penarikan::index/$1');
$routes->get('penambahan_saldo', 'Penambahan_saldo::index');

// Public REST API routes (compatible with CI3 RestController endpoints)
$routes->group('c_api', static function ($routes) {
    $routes->get('Users', 'C_api::Users_get');
    $routes->get('users', 'C_api::Users_get');
    $routes->get('Clients', 'C_api::Clients_get');
    $routes->get('clients', 'C_api::Clients_get');
    $routes->get('ClientId/(:segment)', 'C_api::ClientId_get/$1');
    $routes->get('clientid/(:segment)', 'C_api::ClientId_get/$1');
    $routes->get('SaldoHarianId/(:segment)', 'C_api::SaldoHarianId_get/$1');
    $routes->get('saldoharianid/(:segment)', 'C_api::SaldoHarianId_get/$1');
    $routes->get('DividenId/(:segment)', 'C_api::DividenId_get/$1');
    $routes->get('dividenid/(:segment)', 'C_api::DividenId_get/$1');
    $routes->get('TransaksiId/(:segment)', 'C_api::TransaksiId_get/$1');
    $routes->get('transaksiid/(:segment)', 'C_api::TransaksiId_get/$1');
    $routes->get('TransaksiSaham/(:segment)', 'C_api::TransaksiSaham_get/$1');
    $routes->get('transaksisaham/(:segment)', 'C_api::TransaksiSaham_get/$1');
    $routes->get('SaldoInvestasi/(:segment)/(:segment)', 'C_api::SaldoInvestasi_get/$1/$2');
    $routes->get('saldoinvestasi/(:segment)/(:segment)', 'C_api::SaldoInvestasi_get/$1/$2');
});

// Protected routes (require authenticated session)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // Profil Pemegang Saham
    $routes->get('profil_pemegang_saham', 'Profil_pemegang_saham::index');
    $routes->get('profil_pemegang_saham/show', 'Profil_pemegang_saham::show');
    $routes->get('profil_pemegang_saham/get_bank', 'Profil_pemegang_saham::get_bank');
    $routes->get('profil_pemegang_saham/tambah_data_anggota', 'Profil_pemegang_saham::tambah_data_anggota');
    $routes->post('profil_pemegang_saham/tambah_data', 'Profil_pemegang_saham::tambah_data');
    $routes->get('profil_pemegang_saham/ubah_data_anggota/(:segment)', 'Profil_pemegang_saham::ubah_data_anggota/$1');
    $routes->post('profil_pemegang_saham/ubah_data', 'Profil_pemegang_saham::ubah_data');
    $routes->get('profil_pemegang_saham/lihat_data_anggota/(:segment)', 'Profil_pemegang_saham::lihat_data_anggota/$1');
    $routes->post('profil_pemegang_saham/hapus_anggota', 'Profil_pemegang_saham::hapus_anggota');

    // Transaksi Investasi (plus CI3 route alias)
    $routes->match(['GET', 'POST'], 'transaksi_investasi', 'Transaksi_investasi::index');
    $routes->match(['GET', 'POST'], 'transaksi_investasi/tambah_investasi', 'Transaksi_investasi::index');
    $routes->post('transaksi_investasi/tambah_data_transaksi', 'Transaksi_investasi::tambah_data_transaksi');
    $routes->post('transaksi_investasi/ubah_data_transaksi', 'Transaksi_investasi::ubah_data_transaksi');
    $routes->post('transaksi_investasi/hapus_data_transaksi', 'Transaksi_investasi::hapus_data_transaksi');
    $routes->get('transaksi_investasi/migrasi/(:num)', 'Transaksi_investasi::migrasi/$1');
    $routes->get('transaksi_investasi/cetak_surat_saham/(:num)/(:segment)', 'Transaksi_investasi::cetak_surat_saham/$1/$2');
    $routes->get('transaksi_investasi/cetak_surat_keterangan/(:num)', 'Transaksi_investasi::cetak_surat_keterangan/$1');

    // Penarikan Investasi (plus CI3 route alias)
    $routes->get('penarikan_investasi', 'Penarikan_investasi::index');
    $routes->get('transaksi_investasi/penarikan_investasi', 'Penarikan_investasi::index');
    $routes->get('penarikan_investasi/getData/(:num)', 'Penarikan_investasi::getData/$1');
    $routes->post('penarikan_investasi/getDataID', 'Penarikan_investasi::getDataID');
    $routes->post('penarikan_investasi/tambah_data_penarikan', 'Penarikan_investasi::tambah_data_penarikan');
    $routes->post('penarikan_investasi/update', 'Penarikan_investasi::update');
    $routes->post('penarikan_investasi/delete', 'Penarikan_investasi::delete');
    $routes->post('penarikan_investasi/getJumlah', 'Penarikan_investasi::getJumlah');

    // Saham Umum (plus CI3 route alias)
    $routes->get('saham_umum', 'Saham_umum::index');
    $routes->get('transaksi_investasi/saham_umum', 'Saham_umum::index');
    $routes->get('saham_umum/getData/(:num)', 'Saham_umum::getData/$1');
    $routes->post('saham_umum/getDataID', 'Saham_umum::getDataID');

    // Saldo Harian
    $routes->match(['GET', 'POST'], 'saldo_harian', 'Saldo_harian::index');
    $routes->get('saldo_harian/update_saldo/(:num)', 'Saldo_harian::update_saldo/$1');
    $routes->get('saldo_harian/update_saldo/(:num)/(:num)/(:num)', 'Saldo_harian::update_saldo/$1/$2/$3');

    // Sisa Hasil Usaha
    $routes->get('sisa_hasil_usaha', 'Sisa_hasil_usaha::index');
    $routes->post('sisa_hasil_usaha/tambah_data_shu', 'Sisa_hasil_usaha::tambah_data_shu');
    $routes->post('sisa_hasil_usaha/ubah_data_shu', 'Sisa_hasil_usaha::ubah_data_shu');
    $routes->post('sisa_hasil_usaha/hapus_data_shu', 'Sisa_hasil_usaha::hapus_data_shu');

    // Distribusi dan Porsi
    $routes->match(['GET', 'POST'], 'distribusi_dan_porsi', 'Distribusi_dan_porsi::index');

    // Bagi Hasil
    $routes->match(['GET', 'POST'], 'bagi_hasil', 'Bagi_hasil::index');
    $routes->match(['GET', 'POST'], 'bagi_hasil/lihat_data', 'Bagi_hasil::lihat_data');

    // Laporan
    $routes->get('label_surat', 'Laporan::label_surat');
    $routes->get('laporan', 'Laporan::index');
    $routes->get('laporan/cetak_profil_pemegang_saham', 'Laporan::cetak_profil_pemegang_saham');
    $routes->get('laporan/konfersi_profil_pemegang_saham', 'Laporan::konfersi_profil_pemegang_saham');
    $routes->get('laporan/export_profil_pemegang_saham', 'Laporan::export_profil_pemegang_saham');

    $routes->get('laporan/saldo_awal_tahun', 'Laporan::saldo_awal_tahun');
    $routes->post('laporan/cetak_saldo_awal_tahun', 'Laporan::cetak_saldo_awal_tahun');
    $routes->get('laporan/konfersi_saldo_awal_tahun/(:num)', 'Laporan::konfersi_saldo_awal_tahun/$1');
    $routes->get('laporan/export_saldo_awal_tahun/(:num)', 'Laporan::export_saldo_awal_tahun/$1');

    $routes->get('laporan/cetak_transaksi_terkini', 'Laporan::cetak_transaksi_terkini');
    $routes->get('laporan/konfersi_transaksi_terkini/(:segment)/(:segment)/(:segment)', 'Laporan::konfersi_transaksi_terkini/$1/$2/$3');
    $routes->get('laporan/export_transaksi_terkini/(:segment)/(:segment)/(:segment)', 'Laporan::export_transaksi_terkini/$1/$2/$3');

    $routes->get('laporan/transaksi_bulanan', 'Laporan::transaksi_bulanan');
    $routes->post('laporan/cetak_transaksi_invest_bln', 'Laporan::cetak_transaksi_invest_bln');
    $routes->get('laporan/konfersi_transaksi_invest_bln/(:segment)/(:num)/(:num)', 'Laporan::konfersi_transaksi_invest_bln/$1/$2/$3');
    $routes->get('laporan/export_transaksi_invest_bln/(:segment)/(:num)/(:num)', 'Laporan::export_transaksi_invest_bln/$1/$2/$3');

    $routes->get('laporan/penarikan_investasi', 'Laporan::penarikan_investasi');
    $routes->post('laporan/cetak_penarikan_investasi', 'Laporan::cetak_penarikan_investasi');
    $routes->get('laporan/konfersi_penarikan_investasi/(:num)', 'Laporan::konfersi_penarikan_investasi/$1');
    $routes->get('laporan/export_penarikan_investasi/(:num)', 'Laporan::export_penarikan_investasi/$1');

    $routes->get('laporan/saldo_harian', 'Laporan::saldo_harian');
    $routes->post('laporan/cetak_saldo_harian', 'Laporan::cetak_saldo_harian');
    $routes->get('laporan/konfersi_saldo_harian/(:num)', 'Laporan::konfersi_saldo_harian/$1');
    $routes->get('laporan/export_saldo_harian/(:num)', 'Laporan::export_saldo_harian/$1');

    $routes->get('laporan/distribusi_dan_porsi', 'Laporan::distribusi_dan_porsi');
    $routes->post('laporan/cetak_distribusi_dan_porsi', 'Laporan::cetak_distribusi_dan_porsi');
    $routes->get('laporan/konfersi_distribusi_dan_porsi/(:num)', 'Laporan::konfersi_distribusi_dan_porsi/$1');
    $routes->get('laporan/export_distribusi_dan_porsi/(:num)', 'Laporan::export_distribusi_dan_porsi/$1');

    $routes->get('laporan/bagi_hasil_shu', 'Laporan::bagi_hasil_shu');
    $routes->post('laporan/cetak_bagi_hasil_shu', 'Laporan::cetak_bagi_hasil_shu');
    $routes->get('laporan/konfersi_bagi_hasil_shu/(:num)/(:segment)', 'Laporan::konfersi_bagi_hasil_shu/$1/$2');
    $routes->get('laporan/export_bagi_hasil_shu/(:num)/(:segment)', 'Laporan::export_bagi_hasil_shu/$1/$2');

    $routes->get('laporan/label_surat', 'Laporan::label_surat');
    $routes->post('laporan/cetak_label_surat', 'Laporan::cetak_label_surat');

    $routes->get('laporan/rek_bank_anggota', 'Laporan::rek_bank_anggota');
    $routes->get('laporan/konfersi_rek_bank_anggota', 'Laporan::konfersi_rek_bank_anggota');

    // Users
    $routes->get('users', 'Users::index');
    $routes->get('users/list', 'Users::list');
    $routes->post('users/tambah', 'Users::tambah');
    $routes->post('users/hapus', 'Users::hapus');
    $routes->post('users/reset', 'Users::reset');
    $routes->get('users/activity', 'Users::activity');
});
