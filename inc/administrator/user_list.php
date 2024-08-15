<?php 
if(isset($_GET['detail_page']) && $_GET['detail_page'] == "hak_akses_menu"):
	include "inc/administrator/menu_access.php";
elseif(isset($_GET['detail_page']) && $_GET['detail_page'] == "hak_akses_depo"):
    include "inc/administrator/depo_access.php";
else:
?>
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="?page=dashboard">Home</a>
                </li>
                <li class="active">Hak Akses user</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="page-header">
                <h1>
                    Hak Akses User
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
                                        <th>Nama</th>
                                        <th>User Level</th>
                                        <th class="center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sql_user="select * from user order by nama asc";
                                        $user = DB::connection('mysql_hris')->query($sql_user);  
                                        $no=1;
                                        while($duser = $user->fetch_array()):
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $no;?>.</td>
                                        <td><?php echo $duser['nama'];?></td>
                                        <td><?php echo $duser['user_level'];?></td>
                                        <td class="center">
                                            <div class="btn-group">
                                                <button data-toggle="dropdown" class="btn dropdown-toggle">
                                                    Set Hak Akses
                                                    <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-default">
                                                    <li>
                                                        <a href="?mm=hak_akses&detail_page=hak_akses_menu&user_id=<?php echo $duser['id_user'];?>">
                                                            Hak Akses Menu
                                                        </a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a href="?mm=hak_akses&detail_page=hak_akses_depo&user_id=<?php echo $duser['id_user'];?>">
                                                            Hak Akses Depo
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
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
            responsive: true
    });
});
</script>
	
<?php endif; ?>