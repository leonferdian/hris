<input type="hidden" id="tanggal" value="<?php echo $_GET['tanggal'] ?>">
<input type="hidden" id="depo" value="<?php echo $_GET['slc_depo'] ?>">
<table width="100%" class="table table-striped table-bordered table-hover" id="data_master">
    <thead>
        <tr>
            <th>#</th>
            <th>email</th>
            <th>Nik</th>
            <th>Email Atasan</th>
            <th><i class="fa fa-cog"></i></th>
        </tr>
    </thead>
</table>
<script>
    // var tanggal = $("#tanggal").val();
    // var depo = $('#depo').val();

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
            "url": "inc/cuti/email_atasan/data.php",
            "type": "POST",
            // "data": function (d) {
            //     d.tanggal = tanggal;
            //     d.depo = depo;
            // }
        },
        "columns": [
            { "data": "counter", "width": "5%" },
            { "data": "email_user" },
            { "data": "nik" },
            { "data": "email_atasan" },
            { "data": "btn", "orderable": false, "searchable": false },
        ],
        "createdRow": function (row, data, dataIndex) {
            // Add the class to the first cell only
            $('td:eq(0)', row).addClass('td_l1');
            // MergeCommonRows($('#data_master'));
        }
    });
</script>