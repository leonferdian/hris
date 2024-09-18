<?php
include ('../../../lib/database.php');
require "../../../lib/fungsi_rupiah.php";
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();

$filter = "";
$filter .= " AND ([email_atasan] = '".$_SESSION['username']."' OR [nik] = '".$_SESSION['nik']."')";

$orderColumn = "a.[id]
                ,a.[depo]
                ,a.[nama_karyawan]
                ,a.[pin]
                ,a.[nik]
                ,a.[keterangan]
                ,a.[date_start]
                ,a.[date_end]";

$query = "SELECT
            a.[id]
            ,a.[depo]
            ,a.[nama_karyawan]
            ,a.[pin]
            ,a.[nik]
            ,a.[keterangan]
            ,a.[date_start]
            ,a.[date_end]
            ,a.[date_create]
            ,a.[date_update]
            ,a.[updated_by]
            ,b.status
        FROM [db_hris].[dbo].[table_request_cuti] a
        LEFT JOIN [db_hris].[dbo].[table_request_cuti_status] b
        on a.status = b.id 
        WHERE 1=1";
$query .= $filter;
$query .= " ORDER BY $orderColumn";
// echo $query;
$result = $sqlsrv_hris->query($query);
$events = [];
while ($row = $sqlsrv_hris->fetch_array($result)) {
    $events[] = $row;
}

$arr_event = [];
foreach($events as $row) {
    $label = "";
    if ($row['status'] == "diajukan") {
        $label = "label-bluesea";
    } else if ($row['status'] == "tidak disetujui") {
        $label = "label-red";
    } else if ($row['status'] == "disetujui") {
        $label = "label-green";
    }

    echo '<div class="hide" id="detail-event-'.$row['id'].'">'.htmlspecialchars(str_replace("\r","",str_replace("\n","",substr(strtolower($row['nama_karyawan']),0,6) . " - " .$row['keterangan']))).'</div>';
    
    $date_end = $row['date_end'];

    // Add 1 day
    $date_end->modify('+1 day');

    // Format the new date as "Y-m-d"
    $new_date_end = $date_end->format('Y-m-d');

    $arr_event[] = "{
        title: $('#detail-event-".$row['id']."').html().replace('&amp;', '&'),
        start: moment().subtract(30, 'days').format('".date_format($row['date_start'], "Y-m-d")."'),
		end: moment().subtract(1, 'days').format('".$new_date_end."'),
        id_event: '".$row['id']."',
        depo: '".$row['depo']."',
        nama_karyawan: '".$row['nama_karyawan']."',
        pin: '".$row['pin']."',
        nik: '".$row['nik']."',
        date_start: '".date_format($row['date_start'], "Y-m-d")."',
        date_end: '".date_format($row['date_end'], "Y-m-d")."',
        date_create: '".date_format($row['date_create'], "Y-m-d")."',
        updated_by: '".$row['updated_by']."',
        status: '".$row['status']."',
        className: '".$label."',
        week: '".date('w', strtotime(date_format($row['date_start'], "Y-m-d")))."'
    }";
}

$event = implode(",", $arr_event);
?>
<style>
    .label-white {
        background-color: #fff; color: #000000;
        border-radius: 0px;
    }

    .label-bluesea {
        background-color: #20bde1; color: #fff;
        border-radius: 0px;
    }

    .label-red {
        background-color: #D15B47; color: #fff;
        border-radius: 0px;
    }

    .label-green {
        background-color: #82AF6F; color: #fff;
        border-radius: 0px;
    }

    .label-yellow {
        background-color: #FEE188; color: #000000;
        border-radius: 0px;
    }

    .fc-ltr .fc-basic-view .fc-day-number {
        text-align: center;
        color: black;
    }

    .fc-event.fc-draggable {
        border-radius: 5px;
        border: none;
    }

    html body.no-skin div#main-container.main-container.ace-save-state div.main-content div.main-content-inner div.page-content div.row div.col-xs-12 div.widget-body div#view_report div.row div.col-sm-12 div#calendar.fc.fc-ltr.fc-unthemed div.fc-view-container div.fc-view.fc-month-view.fc-basic-view table tbody.fc-body tr td.fc-widget-content div.fc-day-grid-container div.fc-day-grid div.fc-row.fc-week.fc-widget-content div.fc-content-skeleton table thead tr td.fc-day-number.fc-today.fc-state-highlight {
        color: transparent;
    }

    html body.no-skin div#main-container.main-container.ace-save-state div.main-content div.main-content-inner div.page-content div.row div.col-xs-12 div.widget-body div#view_report div.row div.col-sm-12 div#calendar.fc.fc-ltr.fc-unthemed div.fc-view-container div.fc-view.fc-month-view.fc-basic-view table tbody.fc-body tr td.fc-widget-content div.fc-day-grid-container div.fc-day-grid div.fc-row.fc-week.fc-widget-content div.fc-bg table tbody tr td.fc-day.fc-widget-content.fc-today.fc-state-highlight {
        text-align: center;
    }

    .fc-widget-header {
        background: #fff;
        color: #000000;
    }

    .fc button {
        border: none;
    }
</style>
<!-- PAGE CONTENT BEGINS -->
<div class="row">
    <div class="col-sm-12">
        <div class="space"></div>

        <div id="calendar"></div>
    </div>

    <!-- <div class="col-sm-3">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h4>Draggable events</h4>
            </div>

            <div class="widget-body">
                <div class="widget-main no-padding">
                    <div id="external-events">
                        <div class="external-event label-grey" data-class="label-grey">
                            <i class="ace-icon fa fa-arrows"></i>
                            My Event 1
                        </div>

                        <div class="external-event label-success" data-class="label-success">
                            <i class="ace-icon fa fa-arrows"></i>
                            My Event 2
                        </div>

                        <div class="external-event label-danger" data-class="label-danger">
                            <i class="ace-icon fa fa-arrows"></i>
                            My Event 3
                        </div>

                        <div class="external-event label-purple" data-class="label-purple">
                            <i class="ace-icon fa fa-arrows"></i>
                            My Event 4
                        </div>

                        <div class="external-event label-yellow" data-class="label-yellow">
                            <i class="ace-icon fa fa-arrows"></i>
                            My Event 5
                        </div>

                        <div class="external-event label-pink" data-class="label-pink">
                            <i class="ace-icon fa fa-arrows"></i>
                            My Event 6
                        </div>

                        <div class="external-event label-info" data-class="label-info">
                            <i class="ace-icon fa fa-arrows"></i>
                            My Event 7
                        </div>

                        <label>
                            <input type="checkbox" class="ace ace-checkbox" id="drop-remove" />
                            <span class="lbl"> Remove after drop</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>

<!-- PAGE CONTENT ENDS -->

<!-- inline scripts related to this page -->
<script type="text/javascript">
    jQuery(function($) {

        /* initialize the external events
        	-----------------------------------------------------------------*/

        $('#external-events div.external-event').each(function() {

            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);

            // make the event draggable using jQuery UI
            // $(this).draggable({
            //     zIndex: 999,
            //     revert: true, // will cause the event to go back to its
            //     revertDuration: 0 //  original position after the drag
            // });

        });




        /* initialize the calendar
        -----------------------------------------------------------------*/

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();


        var calendar = $('#calendar').fullCalendar({
            //isRTL: true,
            //firstDay: 1,// >> change first day of week 

            buttonHtml: {
                prev: '<i class="ace-icon fa fa-chevron-left"></i>',
                next: '<i class="ace-icon fa fa-chevron-right"></i>'
            },

            header: {
                left: 'prev,next today',
                center: 'title',
                // right: 'month,agendaWeek,agendaDay'
                right: ''
            },

            dayRender: function(date, cell) {
                var today = new Date();
                if (date.isSame(today, 'day')) {
                    var dayNumber = date.format('D');
                    cell.html('<span class="badge badge-purple">'+dayNumber+'</span>');
                    cell.css({
                        // Add your custom CSS properties here
                        backgroundColor: 'white', // For example, change the background color to yellow
                    });
                }
            },
            events: [
                // {
                //     title: 'All Day Event',
                //     start: new Date(y, m, 1),
                //     className: 'label-important'
                // },
                // {
                //     title: 'Long Event',
                //     start: moment().subtract(5, 'days').format('YYYY-MM-DD'),
                //     end: moment().subtract(1, 'days').format('YYYY-MM-DD'),
                //     className: 'label-success'
                // },
                // {
                //     title: 'Some Event',
                //     start: new Date(y, m, d - 3, 16, 0),
                //     allDay: false,
                //     className: 'label-info'
                // }
                <?php echo $event; ?>
            ],

            /**eventResize: function(event, delta, revertFunc) {

            	alert(event.title + " end is now " + event.end.format());

            	if (!confirm("is this okay?")) {
            		revertFunc();
            	}

            },*/
            defaultView: 'month',
            weekNumbers: true,
            editable: true,
            droppable: false, // this allows things to be dropped onto the calendar !!!
            drop: function(date) { // this function is called when something is dropped

                // retrieve the dropped element's stored Event Object
                var originalEventObject = $(this).data('eventObject');
                var $extraEventClass = $(this).attr('data-class');


                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);

                // assign it the date that was reported
                copiedEventObject.start = date;
                copiedEventObject.allDay = false;
                if ($extraEventClass) copiedEventObject['className'] = [$extraEventClass];

                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }

            },
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {

                // bootbox.prompt("New Event Title:", function(title) {
                //     if (title !== null) {
                //         calendar.fullCalendar('renderEvent', {
                //                 title: title,
                //                 start: start,
                //                 end: end,
                //                 allDay: allDay,
                //                 className: 'label-info'
                //             },
                //             true // make the event "stick"
                //         );
                //     }
                // });

                calendar.fullCalendar('unselect');

                var date = new Date(start);

                var year = date.getFullYear();
                var month = date.getMonth()+1;
                var day = date.getDate();
                
                var tanggal = year+'/'+month+'/'+day;
                // list_event(tanggal);
                list_event(tanggal);
            },
            eventClick: function(calEvent, jsEvent, view) {

                //display a modal
                // var modal =
                //     '<div class="modal fade" id="modal-event">\
                //     <div class="modal-dialog">\
                //     <div class="modal-content">\
                //         <div class="modal-body">\
                //         <button type="button" class="close" data-dismiss="modal" style="margin-top:-10px;">&times;</button>\
                //         <form class="no-margin">\
                //             <label>Change event name &nbsp;</label>\
                //             <input type="hidden" id="tanggal" value="'+calEvent.start+'">\
                //             <input type="hidden" id="nmr_aktifitas" value="'+calEvent.nmr_aktifitas+'">\
                //             <input type="hidden" id="jabatan" value="'+calEvent.jabatan+'">\
                //             <input type="hidden" id="act_aktifitas" value="edit_aktifitas">\
                //             <input type="hidden" id="id_aktifitas" value="' + calEvent.id_aktifitas + '">\
                //             <input type="hidden" id="jam_awal" value="' + calEvent.jam_awal + '">\
                //             <input type="hidden" id="jam_akhir" value="' + calEvent.jam_akhir + '">\
                //             <input class="middle" autocomplete="off" type="text" id="detail_aktifitas" value="' + calEvent.title + '" />\
                //             <button type="submit" class="btn btn-sm btn-success" onclick="edit_event()"><i class="ace-icon fa fa-check"></i> Save</button>\
                //         </form>\
                //         </div>\
                //         <div class="modal-footer">\
                //         <button type="button" class="btn btn-sm btn-danger" data-action="delete" onclick="del_complain('+calEvent.nmr_aktifitas+')"><i class="ace-icon fa fa-trash-o"></i> Delete All Event</button>\
                //             <button type="button" class="btn btn-sm btn-danger" data-action="delete" onclick="del_event('+calEvent.id_detail+')"><i class="ace-icon fa fa-trash-o"></i> Delete Event</button>\
                //             <button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>\
                //         </div>\
                //     </div>\
                //     </div>\
                //     </div>';


                // var modal = $(modal).appendTo('body');
                // modal.find('form').on('submit', function(ev) {
                //     ev.preventDefault();

                //     calEvent.title = $(this).find("input[type=text]").val();
                //     calendar.fullCalendar('updateEvent', calEvent);
                //     modal.modal("hide");
                // });
                // modal.find('button[data-action=delete]').on('click', function() {
                //     calendar.fullCalendar('removeEvents', function(ev) {
                //         return (ev._id == calEvent._id);
                //     })
                //     modal.modal("hide");
                // });

                // modal.modal('show').on('hidden', function() {
                //     modal.remove();
                // });


                //console.log(calEvent.id);
                //console.log(jsEvent);
                //console.log(view);

                // change the border color just for fun
                //$(this).css('border-color', 'red');

                edit_event(calEvent.id_event);
            }

        });


    })
</script>