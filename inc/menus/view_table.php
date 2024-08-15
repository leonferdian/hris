<?php require('../../lib/database.php'); ?>
<table width="100%" class="table table-striped table-bordered table-hover display" id="grid_table">
    <thead>
        <tr>
            <th style="vertical-align:middle; text-align:center;">#</th>
            <th style="vertical-align:middle; text-align:center;">Menu</th>
            <th style="vertical-align:middle; text-align:center;">Main Menu</th>
            <th style="vertical-align:middle; text-align:center;">Submenu</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql_menu = "SELECT w.id_webpages, w.webpage_display, w.webpage_acces, m.idmain_menu, m.mainmenu_display, m.mainmenu_acces, s.id_submenu, s.submenu_display, s.submenu_access FROM tbl_webpages w
                    LEFT JOIN tbl_mainmenu m ON w.`id_webpages` = m.`id_webpage`
                    LEFT JOIN tbl_submenu s ON m.`idmain_menu` = s.`id_mainmenu`
                    order by w.webpage_display, m.mainmenu_display, s.submenu_display";
        $stmt_menu = DB::connection('mysql_hris')->query($sql_menu);
        $no = 1;
        while ($row_menu = $stmt_menu->fetch_array()) {
            $id_menu1 = $row_menu['id_webpages'];
            $status1 = $row_menu["webpage_acces"];
            if ($status1 == 1) {
                $label1 = 'label-success';
                $active1 = 'active';
            } else {
                $label1 = 'label-inverse';
                $active1 = 'inactive';
            }

            $id_menu2 = $row_menu['idmain_menu'];
            $status2 = $row_menu["mainmenu_acces"];
            if ($status2 == 1) {
                $label2 = 'label-success';
                $active2 = 'active';
            } else {
                $label2 = 'label-inverse';
                $active2 = 'inactive';
            }

            $id_menu3 = $row_menu['id_submenu'];
            $status3 = $row_menu["submenu_access"];
            if ($status3 == 1) {
                $label3 = 'label-success';
                $active3 = 'active';
            } else {
                $label3 = 'label-inverse';
                $active3 = 'inactive';
            }
            ?>
            <tr>
                <td style="vertical-align:middle; text-align:center; "><?php echo $no; ?></td>
                <td style="vertical-align:middle; text-align:center; ">
                    <?php echo $row_menu["webpage_display"]; ?>
                    <span class="label label-sm <?php echo $label1; ?>"><?php echo $active1; ?></span>
                    <div class="hidden-sm hidden-xs btn-group">
                        <button class="btn btn-xs btn-info" onclick="edit('<?php echo $id_menu1; ?>', 'id_webpages')">
                            <i class="ace-icon fa fa-pencil bigger-120"></i>
                        </button>
                        <button class="btn btn-xs btn-danger" onclick="del('<?php echo $id_menu1; ?>', 'id_webpages')">
                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                        </button>
                    </div>
                </td>
                <td style="vertical-align:middle; text-align:center; ">
                    <?php if ($row_menu["mainmenu_display"] != NULL): ?>
                        <?php echo $row_menu["mainmenu_display"]; ?>
                        <?php if ($status2 != NULL): ?><span
                                class="label label-sm <?php echo $label2; ?>"><?php echo $active2; ?></span> <?php endif; ?>
                        <div class="hidden-sm hidden-xs btn-group">
                            <button class="btn btn-xs btn-info" onclick="edit('<?php echo $id_menu2; ?>', 'idmain_menu')">
                                <i class="ace-icon fa fa-pencil bigger-120"></i>
                            </button>
                            <button class="btn btn-xs btn-danger" onclick="del('<?php echo $id_menu2; ?>', 'idmain_menu')">
                                <i class="ace-icon fa fa-trash-o bigger-120"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                </td>
                <td style="vertical-align:middle; text-align:center; ">
                    <?php if ($row_menu["submenu_display"] != NULL): ?>
                        <?php echo $row_menu["submenu_display"]; ?>
                        <?php if ($status3 != NULL): ?><span
                                class="label label-sm <?php echo $label3; ?>"><?php echo $active3; ?></span><?php endif; ?>
                        <div class="hidden-sm hidden-xs btn-group">
                            <button class="btn btn-xs btn-info" onclick="edit('<?php echo $id_menu3; ?>', 'id_submenu')">
                                <i class="ace-icon fa fa-pencil bigger-120"></i>
                            </button>
                            <button class="btn btn-xs btn-danger" onclick="del('<?php echo $id_menu3; ?>', 'id_submenu')">
                                <i class="ace-icon fa fa-trash-o bigger-120"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
            <?php
            $no++;
        }
        ?>
    </tbody>
    <tfoot>
    </tfoot>
</table>
<script>
    $("#grid_table").rowspanizer({
        vertical_align: 'middle',
        columns: [1, 2, 3]
    });

    $(document).ready(function () {
        MergeCommonRows($('#grid_table'));
        fix_thead();
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

    function fix_thead() {
        var $table = $('#grid_table');
        $table.floatThead({
            responsiveContainer: function ($table) {
                return $table.closest('.table-responsive');
            },
            position: 'absolute'

        });
    }
</script>