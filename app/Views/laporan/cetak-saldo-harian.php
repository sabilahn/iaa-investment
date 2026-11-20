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
            require_once('v-saldo-harian.php'); ?>

        </div>
        <?php
        require_once('script.php'); ?>
    </div>
</body>

</html>