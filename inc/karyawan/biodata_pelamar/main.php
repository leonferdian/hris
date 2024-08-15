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
            <li class="active">Biodata Pelamar</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right">
                <span class="green middle bolder hide" data-toggle="modal" data-target="#modal_add_master" onclick="add_dialog()">
                    <a href="#">
                        Tambah Pelamar
                    </a>
                </span>
                <a href="#" onclick="get_excel();"> 
                    <span class="vertical-date pull-right fa fa-file-excel-o text-success"> 
                        Export To Excel
                    </span>
                </a>
            </div>
            <h1>
                Biodata Pelamar
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget-body">
                    <span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait... </span>
                    <br>
                    <div id="list_vol">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="data_master">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Timestamp</th>
                                    <th>Score</th>
                                    <th>Nama Lengkap</th>
                                    <th>Nomor HP</th>
                                    <th>Jabatan yang dilamar</th>
                                    <th>Act</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="export_excel" class="hide">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_add_master" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title font-weight-bold">Form Pengajuan</h2>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="save()">
                    <i class="ace-icon fa fa-check"></i>
                    Save
                </button>
                <button class="btn btn-sm" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_edit_master" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title font-weight-bold">Form Pengajuan</h2>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="edit()">
                    <i class="ace-icon fa fa-check"></i>
                    Save
                </button>
                <button class="btn btn-sm" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Cancel
                </button>
                <div id="result"></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        view_report();
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
                table.find(".td_l1:nth-child(" + i + ")").each(function (index, e) {
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

    function view_report() {
        // $('#progress').show();
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
            "bScrollCollapse" : true,
            "bRetrieve" : true,
            "responsive": true ,
            "deferRender": true,
            "scrollY": 550,
            "scrollX": true,
            "pageLength": 50,
            responsive: true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "inc/karyawan/biodata_pelamar/view_report.php",
                "type": "POST",
                "data": function (d) {
                    d.tahun = $('#tahun').val();
                    d.bulan = $('#bulan').val();
                    d.detail = $('#detail').val();
                }
            },
            "columns": [
                { "data": "counter", "width": "5%"  },
                { "data": "date_create" },
                { "data": "total_jawaban_benar" },
                { "data": "nama" },
                { "data": "no_hp" },
                { "data": "kode_jabatan" },
                { "data": "btn" , "orderable": false, "searchable": false },
            ],
            "createdRow": function(row, data, dataIndex) {
                // Add the class to the first cell only
                $('td:eq(0)', row).addClass('td_l1');
                MergeCommonRows($('#data_master'));
            }
        });
    }

    function add_dialog() {
        $('#modal_add_master').modal('hide');
        $.ajax({
            type: "POST",
            url: "inc/karyawan/biodata_pelamar/add_edit_delete.php",
            traditional: true,
            data:{
                act: 'add_dialog'
            },
            success: function(data) {
                $("#modal_add_master .modal-body").html(data);
            },
            error: function(msg) {
                alert(msg);
            }
        });
    }

    function save() {
        var nama = $("#modal_add_master #nama").val();
        var email = $("#modal_add_master #email").val();
        var no_hp = $("#modal_add_master #no_hp").val();
        var kode_jabatan = $("#modal_add_master #kode_jabatan").val();
        
        if (nama == "") {
            alert("Semua data Harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/karyawan/biodata_pelamar/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'add_data',
                    nama : nama,
                    email: email,
                    no_hp: no_hp,
                    kode_jabatan: kode_jabatan
                },
                success: function(data) {
                    alert(data);
                    if (data.trim() == "Data Berhasil Ditambahkan") {
                        $('#modal_add_master').modal('hide');
                        view_report();
                    }
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }
    }

    function edit_dialog(id) {
        $('#modal_edit_master').modal('show');
        $.ajax({
            type:"POST",
            url: "inc/karyawan/biodata_pelamar/add_edit_delete.php",
            traditional: true,
            data:{
                act: 'edit_dialog',
                id : id
            },
            success:function(data){											
                $("#modal_edit_master .modal-body").html(data);
            },
            error:function(msg){
                alert(msg);
            }
        });
    }

    function edit() {
        var id = $("#modal_edit_master #id").val();
        var nama = $("#modal_add_master #nama").val();
        var email = $("#modal_add_master #email").val();
        var no_hp = $("#modal_add_master #no_hp").val();
        var kode_jabatan = $("#modal_add_master #kode_jabatan").val();

        if (nama == "") {
            alert("Semua data harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/karyawan/biodata_pelamar/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'edit_data',
                    id: id,
                    nama : nama,
                    email: email,
                    no_hp: no_hp,
                    kode_jabatan: kode_jabatan
                },
                success: function(data) {
                    alert(data);
                    if (data.trim() == "Data Berhasil Diubah") {
                        $('#modal_edit_master').modal('hide');
                        view_report();
                    }
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }
    }

    function del(id){
        if (window.confirm("Apakah anda yakin ingin menghapus data ini?")) {
            $.ajax({
        		type:"POST",
        		url: "inc/karyawan/biodata_pelamar/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'del',
                    id : id
                },
        		success:function(data){
                    alert(data);
                    if (data.trim() == "Data Berhasil dihapus") {
                        view_report();
                    }
        		},
        		error:function(msg){
        			alert(msg);
        		}
            });	
        }
    }

    function get_excel() {
        $('#progress').show();
        $.ajax({
            type:"POST",
            url: "inc/karyawan/biodata_pelamar/export_excel.php",
            traditional: true,
            data:{
            },
            success:function(data){											
                $("#export_excel").html(data);
                $('#progress').hide();
                const d = new Date();
                export_excel('data_excel', 'Data Pelamar-'+d.getDate()+'-'+d.getTime());
            },
            error:function(msg){
                alert(msg);
            }
        });
    }

    function export_excel(id, name) {
        var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
            tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
            tab_text = tab_text + '<x:Name>Test Sheet</x:Name>';
            tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
            tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
            tab_text = tab_text + "<table border='1px'>";
        var exportTable = $('#' + id).clone();
            exportTable.find('input').each(function (index, elem) { $(elem).remove(); });
            tab_text = tab_text + exportTable.html();
            tab_text = tab_text + '</table></body></html>';
        var data_type = 'data:application/vnd.ms-excel';
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        var fileName = name + '.xls';
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            if (window.navigator.msSaveBlob) {
        var blob = new Blob([tab_text], {
            type: "application/csv;charset=utf-8;"
        });
        navigator.msSaveBlob(blob, fileName);
            }
        } else {
            var blob2 =  new Blob([tab_text], {
                type: "application/csv;charset=utf-8;"
            });
            var filename = fileName;
        var elem = window.document.createElement('a');
            elem.href = window.URL.createObjectURL(blob2);
            elem.download = filename;
            document.body.appendChild(elem);
            elem.click();
            document.body.removeChild(elem);
        }
    }
</script>