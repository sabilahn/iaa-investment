<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=profil_pemegang_saham.xls");
?>
<link rel="stylesheet" href="<?= base_url() ?>assets/stisla/build/css/table-style.css">
<?php
require_once('v-profil-pemegang-saham.php');
?>