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
            include('v-distribusi-dan-porsi.php'); ?>

        </div>
        <?php
        include('script.php'); ?>
    </div>
</body>

</html>