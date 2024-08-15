<?php
include('../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
?>
<script>
    $(document).ready(function(){
        var table = $('#data_master').DataTable({
            // order: [[ 0, "asc" ]],
            "paging": true,
            "bSort": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
            "bDestroy": true,
            "bProcessing": true,
            "bScrollCollapse" : true,
            "bRetrieve" : true,
            "responsive": true ,
            "deferRender": true,
            "scrollY": 550,
            "scrollX": true,
            "pageLength": 50,
            responsive: true,
            // fixedHeader: {
            //     header: true,
            //     footer: true
            // },
            // "footerCallback": function ( row, data, start, end, display ) {
            //     var api = this.api(), data;
                
            //     // Remove the formatting to get integer data for summation
            //     var intVal = function ( i ) {
            //         return typeof i === 'string' ?
            //             i.replace(/[\$,.]/g, '')*1 :
            //             typeof i === 'number' ?
            //                 i : 0;
            //     };
            // }
        });	
    });
</script>
<table width="100%" class="table table-striped table-bordered table-hover" id="data_master">
    <thead>
        <tr>
            <th style="vertical-align:middle; text-align:center; width:100px;">No</th>
            <th style="vertical-align:middle; text-align:center;">Nomor Pengajuan</th>
            <th style="vertical-align:middle; text-align:center;">email kadiv</th>
            <th style="vertical-align:middle; text-align:center;">kode divisi</th>
            <th style="vertical-align:middle; text-align:center;">jabatan kandidat</th>
            <th style="vertical-align:middle; text-align:center;">tgl pengajuan</th>
            <th style="vertical-align:middle; text-align:center;">tgl deadline</th>
            <th style="vertical-align:middle; text-align:center;">status</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">Act</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT a.*,b.status,c.nama_divisi,d.nama_jabatan FROM [db_hris].[dbo].[table_pengajuan_karyawan] a
                LEFT JOIN [db_hris].[dbo].[table_approval_pengajuan_karyawan] b
                ON a.id = b.id_pengajuan
                LEFT JOIN [db_hris].[dbo].[table_master_divisi] c
                ON a.kode_divisi = c.kode
                LEFT JOIN [db_hris].[dbo].[table_master_jabatan] d
                ON a.kode_jabatan_kandidat = d.kode";
        $no = 1;
        $stmt = $sqlsrv_hris->query($sql);
        if ($stmt):
        while ($row = $sqlsrv_hris->fetch_array($stmt)):
        ?>
            <tr class="odd gradeX">
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $no; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <a href="?sm=pengajuan_karyawan&act=detail&id=<?php echo $row['id']; ?>">
                        <?php echo $row['no_pengajuan']; ?>
                    </a>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['email_kadiv']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['nama_divisi']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['nama_jabatan']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo date_format($row['tgl_pengajuan'],"Y-m-d"); ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo date_format($row['tgl_deadline'],"Y-m-d"); ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['status']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php if($row['status'] == "draft"): ?>
                    <button class="btn btn-xs btn-warning" onclick="update_status('<?php echo $row['id']; ?>','proses')">
                        proses
                    </button>
                    <?php elseif($row['status'] == "proses"): ?>
                    <button class="btn btn-xs btn-success" onclick="update_status('<?php echo $row['id']; ?>','finish')">
                        finish
                    </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php
                $no++;
            endwhile;
            else:
                echo $sql;
            endif;
            ?>
    </tbody>
</table>
<?php #echo $sql; ?>