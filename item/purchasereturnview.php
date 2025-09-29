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
                <div class="col-sm-12">
                    <h1>Purchase Return</h1>
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
                            <table>
                                <tr>
                                    <td>
                                        <a href="<?=roothtml.'item/purchasereturn.php'?>" id="btnsearch"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-plus"></i>
                                            Add</a>
                                    </td>
                                    <td>
                                        <form method="POST" action="purchasereturnview_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <input type="hidden" name="hfrom">
                                            <input type="hidden" name="hto">
                                            <input type="hidden" name="hsupplier">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-<?=$color?> "><i
                                                    class="fas fa-file-excel"></i>&nbsp;Excel</button>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card card-primary card-outline p-2">
                                        <div class="form-group">
                                            <label for="usr">From:</label>
                                            <input type="hidden" name="aid" />
                                            <input type="date" class="form-control border-success" name="from" id="name"
                                                value="<?=date('Y-m-d')?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">To :</label>
                                            <input type="date" class="form-control border-success" name="to"
                                                value="<?=date('Y-m-d')?>" id="name" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">Supplier:</label>
                                            <select class="form-control border-success select2" name="fsupplier">
                                                <option value="">Select Supplier</option>
                                                <?=load_supplier();?>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" id="btnsearch"
                                                class="btn btn-<?=$color?> form-control"><i class="fas fa-save"></i>
                                                Search</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="card card-primary card-outline">

                                        <!-- Card body -->
                                        <div class="card-body" id="show_search">
                                            <table width="100%">
                                                <tr>
                                                    <td width="20%">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3"
                                                                class="col-sm-6 col-form-label">Show</label>
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
                                                            <label for="inputEmail3"
                                                                class="col-sm-4 col-form-label">Search</label>
                                                            <div class="col-sm-8">
                                                                <input type="search" class="form-control" id="searching"
                                                                    placeholder="Searching . . . . . ">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <div id="show_table" class="table-responsive">

                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->



<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {
    //print js fun
    function print_fun(place) {
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
        var from = $("[name='hfrom']").val();
        var to = $("[name='hto']").val();
        var supplier = $("[name='hsupplier']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'item/purchasereturnview_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                from: from,
                to: to,
                supplier: supplier
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




    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var qty = $(this).data("qty");
        var remainid = $(this).data("remainid");
         var supplierid = $(this).data("supplierid");
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
                    url: "<?php echo roothtml.'item/purchasereturnview_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid,
                        qty: qty,
                        remainid: remainid,
                        supplierid:supplierid
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

    $(document).on("click", "#btnsearch", function() {
        var from = $("[name='from']").val();
        var to = $("[name='to']").val();
        var supplier = $("[name='fsupplier']").val();

        $("[name='hfrom']").val(from);
        $("[name='hto']").val(to);
        $("[name='hsupplier']").val(supplier);
        load_pag();
    });



});
</script>