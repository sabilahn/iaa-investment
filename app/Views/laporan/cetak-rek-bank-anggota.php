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
            <p></p>
        <h2> REKENING BANK ANGGOTA <br> CV. DHARMA IAA</h2>
        <p></p>
        <table border="1">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>NO-TA</th>
                    <th>Nama Anggota</th>
                    <th>Nama Bank</th>
                    <th>Nomor Rekening</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;
                foreach ($data_rek as $data) {
                    $no += 1;
                ?>
                    <tr>
                        <td class="tengah"><?= $no ?></td>
                        <td class="tengah"><?= $data['no_rekening'] ?></td>
                        <td><?= ucwords($data['nama']) ?></td>
                        <td class="tengah"><?= $data['nama_bank'] ?></td>
                        <td class="tengah"><?= $data['rek_bank'] ?></td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
        <table>
            <thead>
                <tr class="putih">
                    <td class="no-border"></td>
                </tr>
                <tr class="putih">
                    <td class="no-border kanan">Sumenep, <?= date('d-m-Y') ?>
                </tr>
                </td>
            </thead>
            <tbody>
                <tr class="putih">
                    <td class="no-border kanan">
                        Bendahara,</td>
                </tr>
                <tr class="putih">
                    <td class="no-border"></td>
                </tr>
                <tr class="putih">
                    <td class="no-border"></td>
                </tr>
                <tr class="putih">
                    <td class="no-border kanan"><b>
                            Abd. Basith</b>
                </tr>
                </td>
            </tbody>
        </table>
    
        </div>
        <?php
        include('script.php'); ?>
    </div>
</body>

</html>