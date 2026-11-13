<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=distribusi_dan_porsi" . "_" . $tahun . ".xls");
?>
<link rel="stylesheet" href="<?= base_url() ?>assets/stisla/build/css/table-style.css">

<?php require_once('v-distribusi-dan-porsi.php') ?>