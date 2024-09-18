<input type="hidden" id="tanggal" value="<?php echo $_GET['tanggal'] ?>">
<div class="table-responsive">
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
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>
                <th>Tanggal Diajukan</th>
                <th>Status</th>
                <!-- <th><i class="fa fa-cog"></i></th> -->
            </tr>
        </thead>
    </table>
</div>
<script>
    var tanggal = $("#tanggal").val();
    // var bulan = $('#bulan').val();

    var table = $('#data_master').DataTable({
        "paging": true,
        "bSort": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
        "bDestroy": true,
        // "bProcessing": true,
        "bScrollCollapse": true,
        "bRetrieve": true,
        "responsive": true,
        "deferRender": true,
        // "scrollY": 550,
        // "scrollX": true,
        "pageLength": 50,
        responsive: true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "inc/cuti/approval_atasan/data.php",
            "type": "POST",
            "data": function (d) {
                d.tanggal = tanggal;
            }
        },
        "columns": [
            { "data": "counter", "width": "5%" },
            { "data": "depo", "width": "10%" },
            { "data": "nama_karyawan", "width": "15%" },
            { "data": "pin", "width": "10%" },
            { "data": "nik", "width": "10%" },
            { "data": "keterangan", "width": "20%" },
            { "data": "alasan_reject", "width": "15%" },
            { "data": "date_start", "width": "10%" },
            { "data": "date_end", "width": "10%" },
            { "data": "date_create", "width": "10%" },
            { "data": "status", "width": "10%" },
            // { "data": "btn", "width": "11%" , "orderable": false, "searchable": false },
        ],
        "createdRow": function (row, data, dataIndex) {
            // Add the class to the first cell only
            $('td:eq(0)', row).addClass('td_l1');
            // MergeCommonRows($('#data_master'));
        }
    });
</script>