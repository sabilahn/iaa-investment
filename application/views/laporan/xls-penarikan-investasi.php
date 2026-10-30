<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=penarikan_investasi" . "_" . $tahun . ".xls");
?>
<link rel="stylesheet" href="<?= base_url() ?>assets/stisla/build/css/table-style.css">

<?php require_once('v-penarikan-investasi.php') ?>