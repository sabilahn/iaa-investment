<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Penarikan Investasi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url() ?>dashboard">Dashboard</a></div>
                <div class="breadcrumb-item">Penarikan Investasi</div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-body">
                <div class="form-group col-md-4 col-lg-4">
                    <label>Tahun</label>
                    <div class="input-group">
                        <select class="custom-select" id="tahun" name="tahun">
                            <option value="">--Pilih Tahun--</option>
                            <?php
                            $thn_skr = date('Y');
                            for ($x = $thn_skr; $x >= $thn_skr - 5; $x--) {
                            ?>
                                <option value="<?= $x ?>"><?= $x ?></option>
                            <?php
                            } ?>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-primary" onclick="get_data()">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h2 class="section-title">Daftar penarikan</h2>
                <h4></h4>
                <div class="card-header-action">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#tambahModal"><i class="fas fa-plus"> Tambah Penarikan</i></button>
                </div>
            </div>
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

                    <div id="error-message"></div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="penarikanList">
                            <thead>
                                <tr class="text-center">
                                    <th>No.</th>
                                    <th>Nomor Rekening</th>
                                    <th>Tanggal Penarikan</th>
                                    <th>Jumlah Penarikan (Rp)</th>
                                    <th style="width: 20%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>
</div>
<!-- Tambah -->
<div class="modal fade" role="dialog" id="tambahModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Penarikan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-primary">
                    <br>
                    <div class="form-group mb-0">
                        <label>No. Rekening</label><br>
                        <select name="rekening" id="rekening" class="form-control select2" style="width: 100%">
                            <?php
                            foreach ($data_rekening->result_array() as $list) {
                                $no_rekening = $list['no_rekening'];
                            ?>
                                <option value="<?= $no_rekening ?>"><?= $no_rekening ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Tanggal Penarikan</label>
                        <input type="text" name="tgl_penarikan" id="tgl_penarikan" class="form-control datepicker" required="">
                    </div>
                    <div class="form-group mb-0">
                        <label>Jumlah Penarikan</label>
                        <input type="text" name="jml_penarikan" id="jml_penarikan" class="form-control" disabled>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" onclick="add()">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Update -->
<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Data Penarikan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-primary">
                    <br>
                    <div class="form-group mb-0">
                        <label>Tanggal penarikan</label>
                        <input type="text" name="tgl_penarikan_edt" id="tgl_penarikan_edt" class="form-control datepicker" required="">
                    </div>
                    <div class="modal-footer bg-whitesmoke br" id="editBtn">
                        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button> -->
                        <!-- <button class="btn btn-primary">Perbarui</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hapus -->
<div class="modal fade" tabindex="-1" role="dialog" id="hapusModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-danger">
                    <br>
                    Yakin untuk menghapus data ?
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br" id="hapusBtn">

            </div>
        </div>
    </div>
</div>

<script>
    var tahun = '';
    $(document).ready(function() {
        document.getElementById('tahun').value = new Date().getFullYear();

        tahun = $('#tahun').val();

        show_data(tahun);
    });

    function show_data(tahun) {
        $('#penarikanList').DataTable({
            destroy: true,
            "ajax": {
                "url": "<?= site_url("Penarikan_investasi/getData") ?>" + '/' + tahun,
                "dataSrc": ""
            },
            "columns": [{
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, full, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "no_rekening",
                    "className": "text-center",
                },
                {
                    "data": "tgl_penarikan",
                    "className": "text-center",
                },
                {
                    "data": "jml_penarikan",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number('.', ',', 2),
                },
                {
                    "data": "id_penarikan",
                    "render": function(data, type, row) {
                        var html = '<div class="text-center"><button onclick="print(' + row.id_penarikan + ')" type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-original-title="Cetak">' +
                            '<i class="fa fa-print"></i>' +
                            '</button>&nbsp;' +
                            '<button onclick="edit(' + row.id_penarikan + ',' + "'" + row.no_rekening + "'" + ')" type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-original-title="Edit">' +
                            '<i class="fas fa-pencil-alt"></i>' +
                            '</button>&nbsp;' +
                            '<button onclick="del(' + row.id_penarikan + ',' + "'" + row.no_rekening + "'" + ')" type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-danger" data-original-title="Hapus">' +
                            '<i class="fa fa-trash"></i>' +
                            '</button>' +
                            '</div>';
                        return html
                    }

                }
            ]
        });
    }

    $('select').on('change', function() {
        getJumlah(this.value);
    });

    function get_data() {
        var tahun = $('#tahun').val();

        show_data(tahun);
    }

    function getJumlah(rekening) {
        $.ajax({
            type: 'POST',
            data: 'rekening=' + rekening,
            url: '<?= base_url() ?>penarikan_investasi/getJumlah',
            dataType: 'json',
            success: function(data) {
                if (data.length == 0) {
                    document.getElementById('jml_penarikan').value = "";
                } else {
                    for (var i = 0; i < data.length; i++) {
                        document.getElementById('jml_penarikan').value = data[i].saldo;
                    }
                }
            }
        });
    }

    function add() {
        var no_rekening = $('#rekening').val();
        var tgl_penarikan = $('#tgl_penarikan').val();
        var jml_penarikan = $('#jml_penarikan').val();

        $.ajax({
            type: 'POST',
            data: 'no_rekening=' + no_rekening + '&tgl_penarikan=' + tgl_penarikan + '&jml_penarikan=' + jml_penarikan,
            url: '<?= base_url() ?>penarikan_investasi/tambah_data_penarikan',
            dataType: 'json',
            success: function(data) {
                show_data(tahun);

                var html = '<div class="alert alert-success alert-dismissible show fade">' +
                    '<div class="alert-body text-center">' +
                    '<button class="close" data-dismiss="alert">' +
                    '<span>&times;</span></button>' +
                    '<b>Sukses! Data berhasil disimpan</b></div></div>;'

                $('#tambahModal').modal('hide');
                $('#error-message').html(html);

            }
        });
    }

    function edit(id_penarikan, no_rekening) {
        $.ajax({
            type: 'POST',
            data: 'id_penarikan=' + id_penarikan,
            url: '<?= base_url() ?>Penarikan_investasi/getDataID',
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                for (var i = 0; i < data.length; i++) {
                    document.getElementById('tgl_penarikan_edt').value = data[i].tgl_penarikan;
                }
                var rowBtn = '<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>' +
                    '<button onclick="update(' + id_penarikan + ',' + "'" + no_rekening + "'" + ')" class="btn btn-primary">Perbarui</button>';
                $('#editBtn').html(rowBtn);
                $('#editModal').modal('show');
            }
        });
    }

    function update(id_penarikan, no_rekening) {
        var tgl_penarikan = $("#tgl_penarikan_edt").val();
        $.ajax({
            type: 'POST',
            data: 'id_penarikan=' + id_penarikan + '&no_rekening=' + no_rekening + '&tgl_penarikan=' + tgl_penarikan,
            url: '<?= base_url() ?>Penarikan_investasi/update',
            dataType: 'json',
            success: function(data) {
                $('#editModal').modal('hide');
                show_data(tahun);

                var html = '<div class="alert alert-success alert-dismissible show fade">' +
                    '<div class="alert-body text-center">' +
                    '<button class="close" data-dismiss="alert">' +
                    '<span>&times;</span></button>' +
                    '<b>Sukses! Data berhasil disimpan</b></div></div>;'

                $('#error-message').html(html);
            }
        });
    }

    function del(id_penarikan, no_rekening) {
        $.ajax({
            type: 'POST',
            data: 'id_penarikan=' + id_penarikan,
            url: '<?= base_url() ?>Penarikan_investasi/getDataID',
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                var tgl_penarikan = '';
                for (var i = 0; i < data.length; i++) {
                    tgl_penarikan = data[i].tgl_penarikan;
                }
                var html = '<button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>' +
                    '<button onclick="delete_id(' + id_penarikan + ',' + "'" + no_rekening + "'" + ',' + "'" + tgl_penarikan + "'" + ')" class="btn btn-danger">Hapus</button>';
                $("#hapusBtn").html(html);
                $('#hapusModal').modal('show');
            }
        });
    }

    function delete_id(id_penarikan, no_rekening, tgl_penarikan) {
        $.ajax({
            type: 'POST',
            data: 'id_penarikan=' + id_penarikan + '&no_rekening=' + no_rekening + '&tgl_penarikan=' + tgl_penarikan,
            url: '<?= base_url() ?>Penarikan_investasi/delete',
            dataType: 'json',
            success: function(data) {
                $('#hapusModal').modal('hide');
                show_data(tahun);

                var html = '<div class="alert alert-success alert-dismissible show fade">' +
                    '<div class="alert-body text-center">' +
                    '<button class="close" data-dismiss="alert">' +
                    '<span>&times;</span></button>' +
                    '<b>Sukses! Data berhasil dihapus</b></div></div>;'

                $('#error-message').html(html);
            }
        });
    }

    function print(id_penarikan) {
        window.open("cetak_surat_keterangan/" + id_penarikan);
    }
</script>