<input type="hidden" id="tahun" value="<?php echo $_GET['tahun'] ?>">
<input type="hidden" id="bulan" value="<?php echo $_GET['bulan'] ?>">
<table width="100%" class="table table-striped table-bordered table-hover" id="data_master">
    <thead>
        <tr>
            <th>#</th>
            <th>Depo</th>
            <th>Nama Karyawan</th>
            <th>Pin</th>
            <th>Nik</th>
            <th>Keterangan Izin</th>
            <th>Alasan Reject</th>
            <th>Nama Atasan</th>
            <th>Tanggal Awal</th>
            <th>Tanggal Akhir</th>
            <th>Tanggal Diajukan</th>
            <th>Updated By</th>
            <th>Status</th>
            <th>Verifikasi</th>
            <th><i class="fa fa-cog"></i></th>
        </tr>
    </thead>
</table>
<script>
    var tahun = $("#tahun").val();
    var bulan = $('#bulan').val();

    var table = $('#data_master').DataTable({
        "paging": true,
        "bSort": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "bDestroy": true,
        // "bProcessing": true,
        "bScrollCollapse": true,
        "bRetrieve": true,
        "responsive": true,
        "deferRender": true,
        "scrollY": 550,
        "scrollX": true,
        "pageLength": 50,
        responsive: true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "inc/cuti/verifikasi_cuti/data.php",
            "type": "POST",
            "data": function (d) {
                d.tahun = tahun;
                d.bulan = bulan;
            }
        },
        "columns": [
            { "data": "counter", "width": "5%" },
            { "data": "depo" },
            { "data": "nama_karyawan", "width": "15%" },
            { "data": "pin" },
            { "data": "nik" },
            { "data": "keterangan" },
            { "data": "alasan_reject" },
            { "data": "nama_atasan" },
            { "data": "date_start" },
            { "data": "date_end" },
            { "data": "date_create" },
            { "data": "updated_by" },
            { "data": "status" },
            { "data": "verified" },
            { "data": "btn", "width": "11%" , "orderable": false, "searchable": false },
        ],
        "createdRow": function (row, data, dataIndex) {
            // Add the class to the first cell only
            $('td:eq(0)', row).addClass('td_l1');
            // MergeCommonRows($('#data_master'));
        }
    });
</script>