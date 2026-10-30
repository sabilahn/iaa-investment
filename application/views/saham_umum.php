<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Saham Umum</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url() ?>dashboard">Dashboard</a></div>
                <div class="breadcrumb-item">Saham Umum</div>
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
                <h2 class="section-title">Daftar Saldo Umum</h2>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="penarikanList">
                            <thead>
                                <tr class="text-center">
                                    <th>No.</th>
                                    <th>Nomor Rekening</th>
                                    <th>Jumlah Transaksi (Rp)</th>
                                    <th>Jumlah Penarikan (Rp)</th>
                                    <th>Saldo Investasi (Rp)</th>
                                    <th style="width: 10%;">Aksi</th>
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
<!-- Update -->
<div class="modal fade" tabindex="-1" role="dialog" id="detailModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Data Saham</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-primary" id="detail-body">
                    <div id="no_rekening_det">

                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Jml. Transaksi (Rp)</th>
                                <th>Jml. Penarikan (Rp)</th>
                                <th>Saldo Investasi (Rp)</th>
                            </tr>
                        </thead>
                        <tbody id="detail_saldo">

                        </tbody>
                    </table>
                </div>
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
                "url": "<?= site_url("Saham_umum/getData") ?>" + '/' + tahun,
                "dataSrc": ""
            },
            "columns": [{
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, full, meta) {
                        return meta.row + 1
                    }
                },
                {
                    "data": "no_rekening",
                    "className": "text-center",
                },
                {
                    "data": "jml_transaksi",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number('.', ',', 2)
                },
                {
                    "data": "jml_penarikan",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number('.', ',', 2)
                },
                {
                    "data": "saldo",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number('.', ',', 2)
                },
                {
                    "data": "no_rekening",
                    "render": function(data, type, row) {
                        var html = '<div class="text-center"><button onclick="detail(' + "'" + row.no_rekening + "'" + ',' + row.tahun + ')" type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-original-title="Cetak">' +
                            '<i class="fa fa-list"></i>' +
                            '</div>';
                        return html
                    }

                }
            ]
        });
    }

    function get_data() {
        var tahun = $('#tahun').val();

        show_data(tahun);
    }

    function detail(no_rekening, tahun) {
        $.ajax({
            type: 'POST',
            data: 'no_rekening=' + no_rekening + '&tahun=' + tahun,
            url: '<?= base_url() ?>Saham_umum/getDataID',
            dataType: 'json',
            success: function(data) {
                var html = '';
                var temp = 0;
                var no_rekening = '';

                for (var i = 0; i < data.length; i++) {
                    no_rekening = data[i].no_rekening;
                    temp = temp + (data[i].jml_transaksi - data[i].jml_penarikan);
                    html += '<tr>' +
                        '<td class="text-center">' + (i + 1) + '</td>' +
                        '<td class="text-center">' + data[i].tanggal + '</td>' +
                        '<td class="text-right">' + number(parseFloat(data[i].jml_transaksi)) + '</td>' +
                        '<td class="text-right">' + number(parseFloat(data[i].jml_penarikan)) + '</td>' +
                        '<td class="text-right">' + number(parseFloat(temp)) + '</td>' +
                        '</tr>';

                    console.log(temp, data[i].jml_transaksi - data[i].jml_penarikan);
                }

                var no_rekening = '<h4>' + no_rekening + '</h4>';

                $('#no_rekening_det').html(no_rekening);
                $('#detail_saldo').html(html);
                $('#detailModal').modal('show');
            }
        });
    }

    function number(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>