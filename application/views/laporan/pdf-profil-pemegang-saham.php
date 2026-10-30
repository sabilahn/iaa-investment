<?php
require_once('header_pdf.php');
?>

<body>
    <!-- <h1 class="text-center" style="margin-top:20%;">PROFIL PEMEGANG SAHAM <br> CV. DHARMA IAA</h1> -->
    <table width=100%; style="padding-bottom: 20px;">
        <tr>
            <td style="text-align: center;" class="no-background">
                <h2>PROFIL PEMEGANG SAHAM <br> CV. DHARMA IAA</h2>
            </td>
        </tr>
    </table>
    <table>
        <thead>
            <tr class="text-center">
                <th rowspan="2">No.</th>
                <th rowspan="2">No. Anggota</th>
                <th rowspan="2">Nama</th>
                <th rowspan="2">NIK</th>
                <th colspan="6">Alamat</th>
                <th rowspan="2">Tanggal Daftar</th>
            </tr>
            <tr>
                <th>RT</th>
                <th>RW</th>
                <th>Dusun</th>
                <th>Desa</th>
                <th>Kec.</th>
                <th>Kab.</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            foreach ($profiles as $profile) {
                $no += 1;
                $rt = $profile['rt'];
                $rw = $profile['rw'];
                $dsn = $profile['dsn'];
                $ds = $profile['ds'];
                $kec = $profile['kec'];
                $kab = $profile['kab'];
            ?>
                <tr>
                    <td class="tengah"><?= $no ?></td>
                    <td class="tengah"><?= $profile['no_rekening'] ?></td>
                    <td><?= ucwords($profile['nama']) ?></td>
                    <td class="tengah">
                        <font color='#ffffff'>'</font><?= $profile['nik'] ?>
                    </td>
                    <td><?= $rt ?></td>
                    <td><?= $rw ?></td>
                    <td><?= $dsn ?></td>
                    <td><?= $ds ?></td>
                    <td><?= $kec ?></td>
                    <td><?= $kab ?></td>
                    <td class="tengah"><?= $profile['tgl_daftar'] ?></td>
                </tr>
            <?php }
            ?>
        </tbody>
    </table>
</body>

</html>