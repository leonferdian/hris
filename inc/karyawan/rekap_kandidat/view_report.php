<input type="hidden" id="tgl1" value="<?php echo $_GET['tgl1'] ?>">
<input type="hidden" id="tgl2" value="<?php echo $_GET['tgl2'] ?>">
<input type="hidden" id="slc_jabatan" value="<?php echo $_GET['slc_jabatan'] ?>">
<table width="100%" class="table table-striped table-bordered table-hover" id="data_master">
    <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Tgl Screening</th>
            <th rowspan="2">Tgl Interview</th>
            <th rowspan="2">Nama</th>
            <th rowspan="2">Jabatan</th>
            <th rowspan="2">Posisi</th>
            <th colspan="2">Telp</th>
            <th colspan="2">Form</th>
            <th colspan="2">Interview I</th>
            <th colspan="2">Interview II</th>
            <th colspan="2">Interview III</th>
            <th colspan="2">Pertimbangkan</th>
            <th colspan="2">Final</th>
            <th rowspan="2">Keterangan</th>
            <th rowspan="2"><i class="fa fa-cog"></i></th>
        </tr>
        <tr>
            <th>HP</th>
            <th>WA</th>
            <th>Y</th>
            <th>N</th>
            <th>Y</th>
            <th>N</th>
            <th>Y</th>
            <th>N</th>
            <th>Y</th>
            <th>N</th>
            <th>Y</th>
            <th>N</th>
            <th>Y</th>
            <th>N</th>
        </tr>
    </thead>
</table>
<script>
    var tgl1 = $("#tgl1").val();
    var tgl2 = $("#tgl2").val();
    var jabatan = $('#slc_jabatan').val();

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
        "bScrollCollapse": true,
        "bRetrieve": true,
        "deferRender": true,
        "scrollY": 550,
        "scrollX": true,
        "pageLength": 50,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "inc/karyawan/rekap_kandidat/data.php",
            "type": "POST",
            "data": function (d) {
                d.tgl1 = tgl1;
                d.tgl2 = tgl2;
                d.jabatan = jabatan;
            },
            "dataSrc": function (json) {
                console.log(json);  // Log the entire response for debugging
                return json.data;  // Return the data array
            }
        },
        "columns": [
            { "data": "counter" },
            { "data": "tgl_screening" },
            { "data": "tgl_interview" },
            { "data": "nama", "width": "20%" },
            { "data": "kode_jabatan" },
            { "data": "posisi" },
            { "data": "no_hp" },
            { "data": "no_wa" },
            { "data": "formY" },
            { "data": "formN" },
            { "data": "interview1Y" },
            { "data": "interview1N" },
            { "data": "interview2Y" },
            { "data": "interview2N" },
            { "data": "interview3Y" },
            { "data": "interview3N" },
            { "data": "pertimbangkanY" },
            { "data": "pertimbangkanN" },
            { "data": "finalY" },
            { "data": "finalN" },
            { "data": "keterangan" },
            { "data": "btn", "orderable": false, "searchable": false },
        ],
        "createdRow": function (row, data, dataIndex) {
            // Add the class to the first cell only
            $('td:eq(0)', row).addClass('td_l1');
        }
    });

</script>