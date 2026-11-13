<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Bagi Hasil SHU</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Laporan</div>
                <div class="breadcrumb-item">Bagi Hasil SHU</div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-body">
                <form target="_blank" action="<?= site_url() ?>laporan/cetak_bagi_hasil_shu" method="POST">
                    <div class="form-group col-md-4 col-lg-4">
                        <label>Tahun</label>
                        <select class="custom-select" id="tahunBagiHasil" name="tahunBagiHasil">
                            <?php
                            $thn_skr = date('Y');
                            for ($x = $thn_skr; $x >= $thn_skr - 5; $x--) {
                            ?>
                                <option value="<?= $x ?>"><?= $x ?></option>
                            <?php
                            } ?>
                        </select>
                        <label>Kecamatan</label>
                        <select class="custom-select select2" id="Kec" name="Kec" style="width: 100%">
                            <option value="Semua">-- Semua -- </option>
                            <?php
                            foreach ($kecamatan as $kec) {
                            ?>
                                <option value="<?= $kec['kec'] ?>"><?= $kec['kec'] ?></option>
                            <?php
                            } ?>
                        </select>
                        <h1></h1>
                        <button class="btn btn-primary" type="submit">Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>