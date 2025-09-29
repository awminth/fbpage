<?php
include('../config.php');
include(root.'master/header.php'); 
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>အော်ဒါစာရင်း</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">

                        <div class="card-header">
                            <form method="POST" action="orderlist_action.php">
                                <input type="hidden" name="hid">
                                <input type="hidden" name="ser">
                                <button type="submit" name="action" value="excel" class="btn btn-sm btn-success"><i
                                        class="fas fa-file-excel"></i>&nbsp;Excel</button>
                            </form>

                        </div>

                        <!-- Card body -->
                        <div class="card-body" id="show_search">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-6 col-form-label">Show</label>
                                            <div class="col-sm-6">
                                                <select id="entry" class="custom-select btn-sm">
                                                    <option value="10" selected>10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td width="60%" class="float-right">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Search</label>
                                            <div class="col-sm-10">
                                                <input type="search" class="form-control" id="searching"
                                                    placeholder="Searching . . . . . ">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <div id="show_table" class="table-responsive-sm">

                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- new Modal -->
<div class="modal fade" id="modalviewsell">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-teal">
                <h4 class="modal-title">Detail View</h4>
                <div class="float-right m-3">
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    <a id="btnprintsell" class="text-white" style="float:right;"><i class="fas fa-print"
                            style="font-size:20px;"></i></a>
                </div>
            </div>

            <div id="printviewsell" class="container">

            </div>
        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="modaleditsell">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="loadeditsell">


        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {
    //print js fun
    function print_fun(place){
        printJS({
            printable: place,
            type: 'html',
            style: 'table, tr,td {font-weight: bold; font-size: 10px;border-bottom: 1px solid LightGray;border-collapse: collapse;}' +
                '.txtc{text-align: center;font-weight: bold;}' +
                '.txtr{text-align: right;font-weight: bold;}' +
                '.txtl{text-align: left;font-weight: bold;}' +
                ' h5{ text-align: center;font-weight: bold;}' +
                '.fs{font-size: 10px;font-weight: bold;}' +
                '.txt,h5 {text-align: center;font-size: 10px;font-weight: bold;}' +
                '.lt{width:50%;float:left;font-weight: bold;}' +
                '.rt{width:50%;float:right;font-weight: bold;}' +
                '.wno{width:5%;font-weight: bold;}',
        });
    }

    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'order/orderlist_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                search: search
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }
    load_pag();

    $(document).on('click', '.page-link', function() {
        var pageid = $(this).data('page_number');
        load_pag(pageid);
    });

    $(document).on("change", "#entry", function() {
        var entryvalue = $(this).val();
        $("[name='hid']").val(entryvalue);
        load_pag();
    });


    $(document).on("keyup", "#searching", function() {
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_pag();
    });


    $(document).on("click", "#btnconfrim", function(e) {
        e.preventDefault();
        var vno = $(this).data("vno");
        swal({
                title: "Confirm?",
                text: "Are you sure Confirm!",
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, Confirm it!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: "<?php echo roothtml.'order/orderlist_action.php'; ?>",
                    data: {
                        action: 'confrim',
                        vno: vno
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Confirm data success.",
                                "success");
                            load_pag();
                            swal.close();
                        } else {
                            swal("Error",
                                "Confirm data failed.",
                                "error");
                        }
                    }
                });
            });


    });


    $(document).on("click", "#deletesell", function(e) {
        e.preventDefault();
        var vno = $(this).data("vno");
        var path = $(this).data("path");
        swal({
                title: "Delete?",
                text: "Are you sure delete!",
                type: "error",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: "<?php echo roothtml.'order/orderlist_action.php'; ?>",
                    data: {
                        action: 'delete',
                        vno: vno,
                        path: path
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            load_pag();
                            swal.close();
                        } else {
                            swal("Error",
                                "Delete data failed.",
                                "error");
                        }
                    }
                });
            });
    });


    $(document).on("click", "#vieworder", function() {
        var vno = $(this).data("vno");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'order/orderlist_action.php' ?>",
            data: {
                action: 'view',
                vno: vno,
            },
            success: function(data) {
                $("#printviewsell").html(data);
                $("#modalviewsell").modal("show");
            }
        });
    });

    $(document).on("click", "#btnprintsell", function() {
        print_fun("printviewsell");
    });


});
</script>