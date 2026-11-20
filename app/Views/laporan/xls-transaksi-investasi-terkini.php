<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=transaksi_investasi_sd_" . date('d-m-Y') . ".xls");
?>
<link rel="stylesheet" href="<?= base_url() ?>assets/stisla/build/css/table-style.css">

<?php require_once('v-transaksi-investasi-terkini.php') ?>