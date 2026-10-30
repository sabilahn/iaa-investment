<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profil Pemegang Saham</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Profil Pemegang Saham</div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h2 class="section-title">Daftar Pemegang Saham</h2>
                <h4></h4>
                <div class="card-header-action">
                    <a href="<?= site_url() ?>profil_pemegang_saham/tambah_data_anggota" class="btn btn-primary"><i class="fas fa-plus"> Tambah Anggota</i></a>
                </div>
            </div>
            <div class="collapse show" id="showDaftar">
                <div class="card">
                    <div class="card-body">
                        <?php if ($this->session->flashdata('success')) : ?>
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body text-center">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    <b><?= $this->session->flashdata('success') ?></b>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body text-center">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    <b><?= $this->session->flashdata('error') ?></b>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr class="text-center">
                                        <th>No.</th>
                                        <th>Nomor Rekening</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <!-- <th>TTL</th> -->
                                        <!-- <th>Alamat</th> -->
                                        <th>Tanggal Daftar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="show_data">
                                    <?php
                                    $no = 0;
                                    foreach ($profiles as $profile) {
                                        $no += 1;
                                        $no_rekening = $profile['no_rekening'];
                                        $nik = $profile['nik'];
                                        $nama = ucwords($profile['nama']);
                                        $tgl_daftar = $profile['tgl_daftar'];
                                    ?>
                                        <tr>
                                            <td class="text-center">
                                                <h><?= $no ?></h>
                                            </td>
                                            <td class="text-center"><?= $no_rekening; ?></td>
                                            <td class="text-center"><?= $nik; ?></td>
                                            <td><?= $nama; ?></td>
                                            <td class="text-center"><?= $tgl_daftar; ?></td>
                                            <td class="text-center">
                                                <?= $profile['action'] ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    </section>
</div>

<!-- Hapus Modal -->
<?php
foreach ($profiles as $profile) :
    $no_rekening = $profile['no_rekening'];
?>
    <div class="modal fade" tabindex="-1" role="dialog" id="hapusModal<?= $no_rekening ?>">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="profil_pemegang_saham/hapus_anggota">
                    <div class="modal-body">
                        <div class="card card-danger"><br>
                            <input type="hidden" name="rekening" id="rekening" value="<?= $no_rekening ?>">
                            Yakin untuk menghapus data dengan kode <?= $no_rekening ?>?
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>