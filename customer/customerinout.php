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
                    <h1>Customer In/Out</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-profile-tab" data-toggle="pill"
                                        href="#custom-tabs-one-profile" role="tab"
                                        aria-controls="custom-tabs-one-profile" aria-selected="true">
                                        Customer</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-home-tab" data-toggle="pill"
                                        href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                                        aria-selected="false">Customer Pay</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-home1-tab" data-toggle="pill"
                                        href="#custom-tabs-one-home1" role="tab" aria-controls="custom-tabs-one-home1"
                                        aria-selected="false">Customer Detail</a>
                                </li>



                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-one-profile" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-profile-tab">

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="card card-primary card-outline p-2">

                                                <div class="form-group">
                                                    <label for="usr"> Customer :</label>
                                                    <select class="form-control border-success select2" name="suppliermain">
                                                        <option value="">Select Customer</option>
                                                        <?=load_customer();?>
                                                    </select>
                                                </div>
                                                <button class="form-control btn btn-primary"
                                                    id="btnsearch1">Search</button>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card card-primary card-outline p-2" id="showmain">
                                                <div class="form-group row">
                                                    <label class="col-sm-4" for="usr">Customer name :</label>
                                                    <input type="text" class="col-sm-8 form-control border-primary"
                                                        value="" readonly>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4" for="usr">Total Amount :</label>
                                                    <input type="text"
                                                        class="col-sm-8 form-control text-right border-primary"
                                                        value="0" readonly>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4" for="usr">Total Pay :</label>
                                                    <input type="text"
                                                        class="col-sm-8 form-control text-right border-primary"
                                                        value="0" readonly>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4" for="usr">Total Remain :</label>
                                                    <input type="text"
                                                        class="col-sm-8 form-control text-right border-primary"
                                                        value="0" readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-home" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-home-tab">

                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <button id="btnnew" type="button" class="btn btn-sm btn-primary"><i
                                                            class="fas fa-plus"></i>&nbsp; Add </button>
                                                </td>
                                                <td>
                                                    <form method="POST" action="customerinout_action.php">
                                                        <input type="hidden" name="hid">
                                                        <input type="hidden" name="ser">
                                                        <input type="hidden" name="hfrom1">
                                                        <input type="hidden" name="hto1">
                                                        <input type="hidden" name="hsupplier1">
                                                        <button type="submit" name="action" value="excel"
                                                            class="btn btn-sm btn-primary"><i
                                                                class="fas fa-file-excel"></i>&nbsp;Excel</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="card card-primary card-outline p-2">
                                                <div class="form-group">
                                                    <label for="usr"> From :</label>
                                                    <input type="date" class="form-control border-success"
                                                        value="<?=date('Y-m-d')?>" name="dt1from">
                                                </div>
                                                <div class="form-group">
                                                    <label for="usr"> To :</label>
                                                    <input type="date" class="form-control border-success"
                                                        value="<?=date('Y-m-d')?>" name="dt1to">
                                                </div>
                                                <div class="form-group">
                                                    <label for="usr"> Supplier :</label>
                                                    <select class="form-control border-success select2" id="supplierpay"
                                                        name="supplierpay">
                                                        <option value="">Select Customer</option>
                                                        <?=load_customer()?>
                                                    </select>
                                                </div>
                                                <button class="form-control btn btn-primary"
                                                    id="btnsearch2">Search</button>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="card card-primary card-outline p-2">
                                                <table width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td width="22%">
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3"
                                                                        class="col-sm-5 col-form-label">Show</label>
                                                                    <div class="col-sm-7">
                                                                        <select id="entry" class="custom-select btn-sm">
                                                                            <option value="10" selected="">10</option>
                                                                            <option value="25">25</option>
                                                                            <option value="50">50</option>
                                                                            <option value="100">100</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td width="70%" class="float-right">
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3"
                                                                        class="col-sm-3 col-form-label">Search</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="search" class="form-control"
                                                                            id="searching" placeholder="Search....">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div id="showpay" class="table-responsive-sm">

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-home1" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-home1-tab">

                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>

                                                </td>
                                                <td>
                                                    <form method="POST" action="customerinout_action.php">
                                                        <input type="hidden" name="hid2">
                                                        <input type="hidden" name="ser2">
                                                        <input type="hidden" name="hfrom2">
                                                        <input type="hidden" name="hto2">
                                                        <input type="hidden" name="hsupplier2">
                                                        <button type="submit" name="action" value="excel1"
                                                            class="btn btn-sm btn-primary"><i
                                                                class="fas fa-file-excel"></i>&nbsp;Excel</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="card card-primary card-outline p-2">
                                                <div class="form-group">
                                                    <label for="usr"> From :</label>
                                                    <input type="date" class="form-control border-success"
                                                        value="<?=date('Y-m-d')?>" name="dt2from">
                                                </div>
                                                <div class="form-group">
                                                    <label for="usr"> To :</label>
                                                    <input type="date" class="form-control border-success"
                                                        value="<?=date('Y-m-d')?>" name="dt2to">
                                                </div>
                                                <div class="form-group">
                                                    <label for="usr"> Supplier :</label>
                                                    <select class="form-control border-success select2" id="supplierpay2"
                                                        name="supplierpay2">
                                                        <option value="">Select Customer</option>
                                                        <?=load_customer();?>
                                                    </select>
                                                </div>
                                                <button class="form-control btn btn-primary"
                                                    id="btnsearch3">Search</button>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="card card-primary card-outline p-2">
                                                <table width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td width="22%">
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3"
                                                                        class="col-sm-5 col-form-label">Show</label>
                                                                    <div class="col-sm-7">
                                                                        <select id="entry1"
                                                                            class="custom-select btn-sm">
                                                                            <option value="10" selected="">10</option>
                                                                            <option value="25">25</option>
                                                                            <option value="50">50</option>
                                                                            <option value="100">100</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td width="70%" class="float-right">
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3"
                                                                        class="col-sm-3 col-form-label">Search</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="search" class="form-control"
                                                                            id="searching1" placeholder="Search....">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div id="showdetail" class="table-responsive-sm">

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <!-- /.card -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- new Modal -->
<div class="modal fade" id="btnnewmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Customer Pay</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm" method="POST" enctype="multipart/form-data">
                <!-- Modal body -->
                <input type="hidden" name="action" id="action" value="save">
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr">Customer Name:</label>
                        <select class="form-control border-success select2" name="supplierpay1">
                            <option value="">Select Customer</option>
                            <?=load_customer();?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usr">Amount:</label>
                        <input type="number" value="0" class="form-control border-success" name="amt"
                            placeholder="Amount">
                    </div>
                    <div class="form-group">
                        <label for="usr">Date:</label>
                        <input type="date" class="form-control border-success" name="date" value="<?=date('Y-m-d')?>">
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnsave' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                        အသစ်သွင်းမည်</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Edit Customer Pay</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="showedit" method="POST">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="editpayprepare">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">View Customer Pay</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
           <div class="modal-body" id="showpaydetail">

           </div>
    </div>
</div>



<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        var from = $("[name='hfrom1']").val();
        var to = $("[name='hto1']").val();
        var supplier = $("[name='hsupplier1']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'customer/customerinout_action.php' ?>",
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
                $("#showpay").html(data);
            }
        });
    }
    load_pag();

    $(document).on('click', '.pagin1', function() {
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

    $(document).on("change", "#supplierpay", function() {
        var serdata = $(this).val();
        $("[name='hsupplier1']").val(serdata);

    });

    $(document).on("click", "#btnsearch2", function() {
        var from = $("[name='dt1from']").val();
        var to = $("[name='dt1to']").val();
        $("[name='hfrom1']").val(from);
        $("[name='hto1']").val(to);
        load_pag();
    });


    $(document).on("click", "#btnnew", function() {
        $("#btnnewmodal").modal("show").fadeToggle();
    });

    $(document).on('click', '#btnsearch1', function() {
        var supplier = $("[name='suppliermain']").val();
        var name = $("[name='suppliermain'] option:selected").text();
        if (supplier == '') {
            swal("Information", "Please choose customer", "info");
            return false;
        }
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'customer/customerinout_action.php' ?>",
            data: {
                action: 'showmain',
                supplier: supplier,
                name: name
            },
            success: function(data) {
                $("#showmain").html(data);
            }
        });
    });

    $(document).on('click', '#btnsave', function(e) {
        e.preventDefault();
        var supplier = $("[name='supplierpay1']").val();
        var amt = $("[name='amt']").val();
        var date = $("[name='date']").val();
        if (supplier == '') {
            swal('info', 'Please Fill Customer', 'info');
            return false;
        }
        if (amt == '' || amt <= 0) {
            swal('info', 'Please Fill Amount', 'info');
            return false;
        }
        $("#btnnewmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'customer/customerinout_action.php' ?>",
            data: {
                action: 'save',
                supplier: supplier,
                amt: amt,
                date: date
            },
            success: function(data) {
                if (data == 1) {
                    swal('success', 'Save Success', 'success');
                    
                    load_pag();
                    swal.close();
                } else {
                    swal('error', 'error save', 'error');
                }
            }
        });

    });

    $(document).on('click', '#btneditprepare', function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'customer/customerinout_action.php' ?>",
            data: {
                action: 'editprepare',
                aid: aid
            },
            success: function(data) {
                $("#showedit").html(data);
            }
        });

    });

    $(document).on('click', '#btnedit', function(e) {
        e.preventDefault();
        var supplier = $("[name='esupplierpay1']").val();
        var amt = $("[name='eamt']").val();
        var date = $("[name='edate']").val();
        var aid = $("[name='aid']").val();
        if (supplier == '') {
            swal('info', 'Please Fill Supplier', 'info');
            return false;
        }
        if (amt == '' || amt <= 0) {
            swal('info', 'Please Fill Amount', 'info');
            return false;
        }
        $("#editmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'customer/customerinout_action.php' ?>",
            data: {
                action: 'edit',
                supplier: supplier,
                amt: amt,
                date: date,
                aid: aid
            },
            success: function(data) {
                if (data == 1) {
                    swal('success', 'Edit Success', 'success');
                    
                    load_pag();
                    swal.close();
                } else {
                    swal('error', 'error edit', 'error');
                }
            }
        });

    });

    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");

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
                    url: "<?php echo roothtml.'customer/customerinout_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid
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

    function load_pag1(page) {
        var entryvalue = $("[name='hid2']").val();
        var search = $("[name='ser2']").val();
        var from = $("[name='hfrom2']").val();
        var to = $("[name='hto2']").val();
        var supplier = $("[name='hsupplier2']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'customer/customerinout_action.php' ?>",
            data: {
                action: 'show1',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                from: from,
                to: to,
                supplier: supplier
            },
            success: function(data) {
                $("#showdetail").html(data);
            }
        });
    }
    load_pag1();

    $(document).on('click', '.pagin2', function() {
        var pageid = $(this).data('page_number');
        load_pag1(pageid);
    });

    $(document).on("change", "#entry1", function() {
        var entryvalue = $(this).val();
        $("[name='hid2']").val(entryvalue);
        load_pag1();
    });


    $(document).on("keyup", "#searching1", function() {
        var serdata = $(this).val();
        $("[name='ser2']").val(serdata);
        load_pag();
    });

    $(document).on("change", "#supplierpay2", function() {
        var serdata = $(this).val();
        $("[name='hsupplier2']").val(serdata);

    });

    $(document).on("click", "#btnsearch3", function() {
        var from = $("[name='dt2from']").val();
        var to = $("[name='dt2to']").val();
        $("[name='hfrom2']").val(from);
        $("[name='hto2']").val(to);
        load_pag1();
    });





});
</script>