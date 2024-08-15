<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Dashboard</a>
            </li>
            <li class=""><a href="#">Administrator</a></li>
            <li class="active">Menus
            </li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right">
            </div>
            <h1>
            Manajemen Menu
            </h1>				
        </div>
        <div class="row">
            <div class="col-sm-2">
                    <button type="button" class="btn btn-xs btn-success" onclick="add()">
                        <i class="fa fa-plus-square"></i> Add Menu
                    </button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget-body">
                    <div class="table-responsive" id="get_table">
                        <span id="progress1" style="display:none"><img src="images/loading.gif" width="20" /> Load data...</span>
                          			
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
	<div id="modal_bootstrap" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">Form Entry</h4>
                    <div class="pull-right">	
                        <span id="progress_file_pendukung" style="display:none;">Processing...<img src="images/loading.gif" width="20" /> </span>
                    </div>
				</div>
				<div class="modal-body">

                </div>
                <div class="modal-footer">
					
				</div>
			</div>
		</div>
	</div>

<script>
$(document).ready(function(){
    load_data();
});
</script>

<script>
	function add(){
        $('#modal_bootstrap').modal('show');
        $.ajax({
            type:"GET",
            url:"inc/menus/form_master_add.php",
            success:function(data){											
                $("#modal_bootstrap .modal-body").html(data);
            },
            error:function(msg){
                alert(msg);
            }
        });	
        
    }

    function edit(id, jenis_id){
        $('#modal_bootstrap').modal('show');
        var act = "edit";
        $.ajax({
            type:"GET",
            url:"inc/menus/form_master_add.php?act="+act+"&id="+id+"&jenis_id="+jenis_id,
            success:function(data){											
                $("#modal_bootstrap .modal-body").html(data);
            },
            error:function(msg){
                alert(msg);
            }
        });
    }
    
    function del(id, jenis_id){
        var r = confirm("Anda yakin menghapus menu ini?");
        if (r == true) {
            var act = 'del';
            $.ajax({
                type:"GET",
                async: false,
                url:"inc/menus/proc_master.php",
                data:"id="+encodeURIComponent(id)+"&act="+act+"&jenis_id="+jenis_id,
                success:function(responseText){
                    $.notify("Data Deleted", "warn");
                    load_data();
                },
                error:function(data){
                alert(data);
                }
            });
        }
    }

    function load_data() {
        var queue = $('#progress1');
        queue.show();

        $.ajax({
            type:"GET",
            url:"inc/menus/view_table.php",
            success:function(data){											
                $("#get_table").html(data);
                queue.hide();
            },
            error:function(msg){
                alert(msg);
            }
        });
    }
</script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>