<?php
require_once('header_pdf.php');
?>

<body>
    <table width=100%; style="padding-bottom: 20px;">
        <tr>
            <td style="text-align: center;" class="no-background">
                <?php if ($kecamatan == "Semua") { ?>
                    <h2>DISTRIBUSI NISBAH BAGI HASIL <br> CV. DHARMA IAA TAHUN BUKU <?= $tahun ?></h2>
                <?php } else { ?>
                    <h2>DISTRIBUSI NISBAH BAGI HASIL KECAMATAN <?= strtoupper($kecamatan) ?> <br> CV. DHARMA IAA TAHUN BUKU <?= $tahun ?></h2>
                <?php }
                ?>
            </td>
        </tr>
    </table>
    <table border="1">
        <thead>
            <tr class="text-center">
                <th>No.</th>
                <th>Nomor Rekening</th>
                <th>Nama Anggota</th>
                <?php
                if ($kecamatan == "Semua") { ?>
                    <th>Kecamatan</th>
                <?php } ?>
                <th>Saham Persero (Rp)</th>
                <th>Sisa Hasil Usaha (Rp)</th>
                <th>Total Saldo<br>Rata-rata Harian (Rp)</th>
                <th>Saham Rata-rata<br>Harian Persero (Rp)</th>
                <th>Nisbah Bagi Hasil (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            $total_saham_persero = 0;
            $total_nisbah = 0;
            foreach ($data_bagi_hasil as $bagi_hasil) {
                $no += 1;
                $total_saham_persero += $bagi_hasil['saham_persero'];
                $total_nisbah += $bagi_hasil['nisbah_bagi_hasil'];
            ?>
                <tr>
                    <td class="tengah"><?= $no ?></td>
                    <td class="tengah"><?= $bagi_hasil['no_rekening'] ?></td>
                    <td><?= ucwords($bagi_hasil['nama']) ?></td>
                    <?php
                    if ($kecamatan == "Semua") { ?>
                        <td><?= ucwords($bagi_hasil['kec']) ?></td>
                    <?php } ?>
                    <td class="kanan"><?= number_format($bagi_hasil['saham_persero'], 2, ',', '.'); ?></td>
                    <td class="kanan"><?= number_format($bagi_hasil['shu'], 2, ',', '.'); ?></td>
                    <td class="kanan"><?= number_format($bagi_hasil['total_rata_harian'], 2, ',', '.'); ?></td>
                    <td class="kanan"><?= number_format($bagi_hasil['rata_harian_persero'], 2, ',', '.'); ?></td>
                    <td class="kanan"><?= number_format($bagi_hasil['nisbah_bagi_hasil'], 2, ',', '.'); ?></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <?php
                if ($kecamatan == "Semua") { ?>
                    <td colspan="4" class="kanan"><b>Jumlah</b></td>
                <?php } else { ?>
                    <td colspan="3" class="kanan"><b>Jumlah</b></td>
                <?php }
                ?>
                <td class="kanan"><b><?= number_format($total_saham_persero, 2, ',', '.'); ?></b></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="kanan"><b><?= number_format($total_nisbah, 2, ',', '.'); ?></b></td>
            </tr>
        </tbody>
    </table>
    <table class="no-background">
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
</body>


</html>