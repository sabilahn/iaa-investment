<?php
require_once('header.php');
?>

<body>
    <div id="page-wrap">
        <div>
            <?= $action ?>
        </div>
        <br>
        <div id="print">
            <?php
            require_once('v-profil-pemegang-saham.php'); ?>

        </div>
        <?php
        require_once('script.php'); ?>
    </div>
</body>

</html>