<input type="hidden" id="category" value="<?php echo $_GET['category'] ?>">
<table width="100%" class="table table-striped table-bordered table-hover" id="data_master">
    <thead>
        <tr>
            <th>#</th>
            <th>Depo</th>
            <th>Tanggal</th>
            <th>Category</th>
            <th>Description</th>
            <!-- <th><i class="fa fa-cog"></i></th> -->
        </tr>
    </thead>
</table>
<script>
    // var tahun = $("#tahun").val();
    // var bulan = $('#bulan').val();
    var category = $('#category').val();

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
            "url": "inc/activity/list_task/data.php",
            "type": "POST",
            "data": function (d) {
                // d.tahun = tahun;
                // d.bulan = bulan;
                d.category = category;
            }
        },
        "columns": [
            { "data": "counter", "width": "5%" },
            { "data": "depo" },
            { "tanggal": "tanggal", "width": "15%" },
            { "category": "category" },
            { "description": "description" },
            // { "data": "btn", "width": "11%" , "orderable": false, "searchable": false },
        ],
        "createdRow": function (row, data, dataIndex) {
            // Add the class to the first cell only
            $('td:eq(0)', row).addClass('td_l1');
            // MergeCommonRows($('#data_master'));
        }
    });
</script>