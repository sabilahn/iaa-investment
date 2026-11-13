<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Transaksi Investasi Bulanan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Laporan</div>
                <div class="breadcrumb-item">Transaksi Investasi Bulanan</div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-body">
                <form target="_blank" action="<?= site_url() ?>laporan/cetak_transaksi_invest_bln" method="POST">
                    <div class="form-group mb-0 col-md-4 col-lg-4">
                        <label>Tahun</label>
                        <select class="custom-select" id="tahunTransaksi" name="tahunTransaksi">
                            <?php
                            $thn_skr = date('Y');
                            for ($x = $thn_skr; $x >= $thn_skr - 5; $x--) {
                            ?>
                                <option value="<?= $x ?>"><?= $x ?></option>
                            <?php
                            } ?>
                        </select>
                    </div>
                    <div class="form-group mb-0 col-md-4 col-lg-4">
                        <label>Bulan</label>
                        <select class="custom-select" name="bulanTransaksi" id="bulanTransaksi">
                            <?php
                            $bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                            $jml_bln = count($bulan);
                            for ($i = 0; $i < $jml_bln; $i += 1) { ?>
                                <option value="<?= $i + 1 ?>"><?= $bulan[$i] ?></option>
                            <?php }
                            ?>
                        </select>
                        <h1></h1>
                        <button class="btn btn-primary" type="submit">Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>