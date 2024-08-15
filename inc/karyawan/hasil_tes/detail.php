<?php $sqlsrv_hris = DB::connection('sqlsrv_hris'); ?>
<style>
    /* Center align data in the DataTable */
    .dataTables_wrapper .dataTable td, 
    .dataTables_wrapper .dataTable th {
        text-align: center;
        vertical-align: middle;
    }

    table.dataTable {
        width: 100% !important;
    }
</style>
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="?page=dashboard">Home</a>
                </li>
                <li class="active">Rekaman Hasil Tes</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="page-header">
                <h1>
                    Rekaman Hasil Tes - <?php echo $_GET['nama'] ??  $_GET['nama']; ?>
                </h1>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-body">
                        <div class="table-responsive">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID Peserta</th>
                                        <th>Kode Soal</th>
                                        <th>Pertanyaan</th>
                                        <th>Jawaban</th>
                                        <th>Hasil</th>
                                        <th>Score</th>
                                        <th>Waktu Selesai</th>
                                        <!-- <th class="center">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sql ="SELECT a.*, b.[pertanyaan] 
                                                FROM [db_hris].[dbo].[table_tes_record_hasil] a
                                                LEFT JOIN [db_hris].[dbo].[table_tes_soal_detail] b
                                                ON a.id_soal = b.id
                                                WHERE a.[id_peserta] = '".$_GET['id_peserta']."'
                                                ORDER BY a.id_soal";
                                        $stmt = $sqlsrv_hris->query($sql);
                                        $no=1;
                                        while($row = $sqlsrv_hris->fetch_array($stmt)):
                                            $jawaban = "";
                                            switch ($row['jawaban_peserta']) {
                                                case 'a1':
                                                    $jawaban = 'A';
                                                    break;
                                                case 'a2':
                                                    $jawaban = 'B';
                                                    break;
                                                case 'a3':
                                                    $jawaban = 'C';
                                                    break;
                                                case 'a4':
                                                    $jawaban = 'D';
                                                    break;
                                                case 'a5':
                                                    $jawaban = 'E';
                                                    break;
                                                default:
                                                    $jawaban = 'Unknown';
                                                    break;
                                            }
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $no;?>.</td>
                                        <td><?php echo $row['id_peserta'];?></td>
                                        <td><?php echo $row['kode_soal'];?></td>
                                        <td><?php echo $row['pertanyaan'];?></td>
                                        <td><?php echo $jawaban;?></td>
                                        <td><?php echo $row['hasil'];?></td>
                                        <td><?php echo $row['score'];?></td>
                                        <td><?php echo date_format($row['time_stamp'],"Y-m-d H:i:s");?></td>
                                        <!-- <td class="center">
                                            <div class="btn-group">
                                                <a class="btn btn-xs btn-white btn-info" href="?mm=data_karyawan&detail_page=form_karyawan&act=edit_karyawan&id=<?php echo $row['id'];?>">
                                                    Edit
                                                </a>
                                                <a class="btn btn-xs btn-white btn-warning" href="?mm=data_karyawan&detail_page=form_karyawan&act=detail_karyawan&id=<?php echo $row['id'];?>">
                                                    Detail
                                                </a>
                                            </div>
                                        </td> -->
                                    </tr>
                                   <?php
                                        $no++;
                                        endwhile;
                                   ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function() {
        //alert('ss');
        $('#dataTables-example').DataTable({
            responsive: true,
            "paging": false,
            "bSort": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
            "bDestroy": true,
            "bProcessing": true,
            "bScrollCollapse": true,
            "bRetrieve": true,
            "responsive": true,
            "deferRender": true,
            "scrollY": 550,
            "scrollX": true,
            "pageLength": 50
        });
        MergeCommonRows($('#dataTables-example'));
    });

    function MergeCommonRows(table) {
        //alert(table)
        var firstColumnBrakes = [];
        // iterate through the columns instead of passing each column as function parameter:
        for (var i = 1; i <= table.find('th').length; i++) {
            var previous = null,
                cellToExtend = null,
                rowspan = 1;
            //table.find("td:nth-child(" + i + ")").each(function(index, e){
            table.find(".td_l1:nth-child(" + i + ")").each(function(index, e) {
                var jthis = $(this),
                    content = jthis.text();
                //alert(content);
                // check if current row "break" exist in the array. If not, then extend rowspan:
                if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1 && typeof content === "string") {
                    // hide the row instead of remove(), so the DOM index won't "move" inside loop.
                    jthis.addClass('hidden');
                    cellToExtend.attr("rowspan", (rowspan = rowspan + 1));
                } else {
                    // store row breaks only for the first column:
                    if (i === 1) firstColumnBrakes.push(index);
                    rowspan = 1;
                    previous = content;
                    cellToExtend = jthis;
                }
            });
        }
        // now remove hidden td's (or leave them hidden if you wish):
        $('td.hidden').remove();
    }
</script>