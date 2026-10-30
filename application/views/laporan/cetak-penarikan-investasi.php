<?php
include('header.php');
?>

<body>
    <div id="page-wrap">
        <div>
            <?= $action ?>
        </div>
        <br>
        <div id="print">
            <?php
            require_once('v-penarikan-investasi.php'); ?>

        </div>
        <?php
        include('script.php'); ?>
    </div>
</body>

</html>