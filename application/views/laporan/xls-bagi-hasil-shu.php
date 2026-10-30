<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=bagi_hasil_shu" . "_" . $kecamatan . "_" . $tahun . ".xls");
?>
<link rel="stylesheet" href="<?= base_url() ?>assets/stisla/build/css/table-style.css">

<table border="1">
    <thead>
        <tr class="text-center">
            <th>No.</th>
            <th>Nomor Rekening</th>
            <th>Nama Anggota</th>
            <th>Nama Bank</th>
            <th>Rekening Bank</th>
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
                <td class="tengah"><?= $bagi_hasil['nama_bank']; ?></td>
                <td class="tengah"><?= "'".$bagi_hasil['rek_bank']; ?></td>
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