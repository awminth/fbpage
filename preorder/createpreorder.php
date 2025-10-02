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
                    <h1>Pre Order Page</h1>
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
                                        Create Preorder</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-home-tab" data-toggle="pill"
                                        href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                                        aria-selected="false">View Pre Order</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <!-- For Create Pre Order -->
                                <div class="tab-pane fade active show" id="custom-tabs-one-profile" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-profile-tab">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="card card-primary card-outline p-2" id="showmain">
                                                <form method="POST" id="frmsavetemp">
                                                    <input type="hidden" name="action" value="savetemp" />
                                                    <input type="hidden" name="eaid" value="0" />
                                                    <div class="form-group row">
                                                        <label class="col-sm-4" for="usr">Item Code :</label>
                                                        <input type="text"
                                                            class="col-sm-8 form-control border-primary text-right"
                                                            name="itemcode">
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-4" for="usr">Item name :</label>
                                                        <input type="text"
                                                            class="col-sm-8 form-control border-primary text-right"
                                                            name="itemname">
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-4" for="usr">Qty :</label>
                                                        <input type="number"
                                                            class="col-sm-8 form-control text-right border-primary"
                                                            name="qty" id="qty">
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-4" for="usr">SellPrice/Unit :</label>
                                                        <input type="number"
                                                            class="col-sm-8 form-control text-right border-primary"
                                                            name="sellpriceperunit" id="sellpriceperunit">
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-4" for="usr">Total Price :</label>
                                                        <input type="number"
                                                            class="col-sm-8 form-control text-right border-primary"
                                                            name="totalprice" readonly>
                                                    </div>
                                                    <div class="form-group text-right">
                                                        <button type='submit'
                                                            class='btn btn-success form-control col-sm-6'><i
                                                                class="fas fa-save"></i>
                                                            Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="card card-primary card-outline p-2" id="showmain">
                                                <div id="showtablecreate"></div>
                                                <form id="frmsave" method="POST">
                                                    <input type="hidden" name="action" value="save" />
                                                    <input type="hidden" name="pretotalprice"/>
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control"
                                                                for="userinput1">Discount(%)</label>
                                                            <div class="col-md-9 mx-auto">
                                                                <input type="number" class="form-control border-primary"
                                                                    placeholder="Discount" name="disc" id="disc">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control"
                                                                for="userinput1">TotalPrice</label>
                                                            <div class="col-md-9 mx-auto">
                                                                <input type="number" class="form-control border-primary"
                                                                    placeholder="TotalPrice" name="finaltotalprice" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control"
                                                                for="userinput1">Pay Amount</label>
                                                            <div class="col-md-9 mx-auto">
                                                                <input type="number" class="form-control border-primary"
                                                                    placeholder="Pay Amount" name="payamt" id="payamt">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control"
                                                                for="userinput1">Change</label>
                                                            <div class="col-md-9 mx-auto">
                                                                <input type="number" class="form-control border-primary"
                                                                    placeholder="Change" name="change" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control"
                                                                for="userinput1">CustomerName</label>
                                                            <div class="col-md-9 mx-auto">
                                                                <input type="text" class="form-control border-primary"
                                                                    placeholder="CustomerName" name="customername">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control"
                                                                for="userinput1">Address</label>
                                                            <div class="col-md-9 mx-auto">
                                                                <input type="text" class="form-control border-primary"
                                                                    placeholder="Address" name="address">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control"
                                                                for="userinput1">Phone No</label>
                                                            <div class="col-md-9 mx-auto">
                                                                <input type="text" class="form-control border-primary"
                                                                    placeholder="Phone No" name="phoneno">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control"
                                                                for="timesheetinput3">Date</label>
                                                            <div class="col-md-9 mx-auto">
                                                                <div class="position-relative has-icon-left">
                                                                    <input type="date" id="timesheetinput3"
                                                                        class="form-control border-primary"
                                                                        name="dt" value="<?= date("Y-m-d")?>">
                                                                    <div class="form-control-position">
                                                                        <i class="ft-message-square"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success form-control col-sm-6"><i
                                                                class="la la-save"></i>Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <!-- For View Pre Order Page -->
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
                                                    <form method="POST" action="preorder_action.php">
                                                        <input type="hidden" name="hid">
                                                        <input type="hidden" name="ser">
                                                        <input type="hidden" name="hfrom">
                                                        <input type="hidden" name="hto">
                                                        <input type="hidden" name="hcustomer">
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
                                                        value="<?=date('Y-m-d')?>" name="dtfrom">
                                                </div>
                                                <div class="form-group">
                                                    <label for="usr"> To :</label>
                                                    <input type="date" class="form-control border-success"
                                                        value="<?=date('Y-m-d')?>" name="dtto">
                                                </div>
                                                <div class="form-group">
                                                    <label for="usr"> Customer :</label>
                                                    <select class="form-control border-success select2" id="customername"
                                                        name="customername">
                                                        <option value="">Select Customer</option>
                                                        <?=load_customername()?>
                                                    </select>
                                                </div>
                                                <button class="form-control btn btn-primary"
                                                    id="btnsearch">Search</button>
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
                                                                            id="searching" placeholder="Search by VNO...">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div id="showvieworder" class="table-responsive-sm">

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

<!-- voucher Modal -->
<div class="modal fade text-left" id="vouchermodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel25"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <label class="modal-title text-text-bold-600" id="myModalLabel25">အရောင်းဘောက်ချာ</label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnpayclose">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="frmvoucher" method="POST">
                <input type="hidden" name="action" value="viewvoucher" />
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary"><i class="la la-print"></i>Print</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
var ajax_url = "<?php echo roothtml.'preorder/preorder_action.php'; ?>";

$(document).ready(function() {

    function load_pagecreate(page) {
        $.ajax({
            type: "post",
            url: ajax_url,
            data: {
                action: 'showcreate'
            },
            success: function(data) {
                $("#showtablecreate").html(data);
            }
        });
    }
    load_pagecreate();

    function calculate() {
        var qty = $("[name='qty']").val();
        var sellpriceperunit = $("[name='sellpriceperunit']").val();
        var totalprice = qty * sellpriceperunit;
        $("[name='totalprice']").val(totalprice);
    }

    function calculate_one() {
        var totalpriceshow = Number($('#showtablecreate #totalpriceshow').text().replace(/,/g,''));
        var predisc = $("[name='disc']").val();
        var disc = predisc * totalpriceshow / 100;
        var finaltotalprice = totalpriceshow - disc;
        var totalpay = $("[name='payamt']").val();
        var change = totalpay - finaltotalprice;
        $("[name='finaltotalprice']").val(finaltotalprice);
        $("[name='change']").val(change);
        $("[name='pretotalprice']").val(totalpriceshow);
        var $changeInput = $("[name='change']");
        $changeInput.val(change);
        if (change < 0) {
            $changeInput.addClass('text-danger');
        } else {
            $changeInput.removeClass('text-danger');
        }
    }

    $(document).on("keyup", "#sellpriceperunit, #qty", function() {
        calculate();
    });

    $(document).on("keyup", "#disc,#payamt", function() {
        calculate_one();
    });

    $("#frmsavetemp").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    $("[name='eaid']").val(0);
                    $("[name='itemcode']").val('');
                    $("[name='itemname']").val('');
                    $("[name='qty']").val('');
                    $("[name='sellpriceperunit']").val('');
                    $("[name='totalprice']").val('');
                    load_pagecreate();
                } else {
                    swal("Error", "Save Data Error.", "Error");
                }
            }
        });
    });

    $(document).on('click', '#btnedittemp', function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        var itemcode = $(this).data('itemcode');
        var itemname = $(this).data('itemname');
        var qty = $(this).data('qty');
        var sellpriceperunit = $(this).data('sellpriceperunit');
        var totalprice = $(this).data('totalprice');
        $("[name='eaid']").val(aid);
        $("[name='itemcode']").val(itemcode);
        $("[name='itemname']").val(itemname);
        $("[name='qty']").val(qty);
        $("[name='sellpriceperunit']").val(sellpriceperunit);
        $("[name='totalprice']").val(totalprice);
    });

    $(document).on("click", "#btndeletetemp", function(e) {
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
                    url: ajax_url,
                    data: {
                        action: 'deletetemp',
                        aid: aid
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            load_pagecreate();
                            swal.close();
                        } 
                        else {
                            swal("Error",
                                "Delete data failed.",
                                "error");
                        }
                    }
                });
            });
    });

    $("#frmsave").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data != 0) {
                    $("#frmvoucher").html(data);
                    $("#vouchermodal").modal("show");
                    $("[name='disc']").val(0);
                    $("[name='pretotalprice']").val('');
                    $("[name='finaltotalprice']").val('');
                    $("[name='payamt']").val('');
                    $("[name='change']").val('');
                    $("[name='customername']").val('');
                    $("[name='address']").val('');
                    $("[name='phoneno']").val('');
                    load_pagecreate();
                } else {
                    swal("Error", "Save Data Error.", "error");
                }
            }
        });
    });



    ////////////////////////////////////////////////////////////////////
    // View Pre Order Page
    ////////////////////////////////////////////////////////////////////
    function load_pageview(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        var from = $("[name='hfrom']").val();
        var to = $("[name='hto']").val();
        var customer = $("[name='hcustomer']").val();
        $.ajax({
            type: "post",
            url: ajax_url,
            data: {
                action: 'showvieworder',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                from: from,
                to: to,
                customer: customer
            },
            success: function(data) {
                $("#showvieworder").html(data);
            }
        });
    }
    load_pageview();

    $(document).on('click', '.page-link', function() {
        var pageid = $(this).data('page_number');
        load_pageview(pageid);
    });

    $(document).on("change", "#entry", function() {
        var entryvalue = $(this).val();
        $("[name='hid']").val(entryvalue);
        load_pageview();
    });

    $(document).on("keyup", "#searching", function() {
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_pageview();
    });

    $(document).on("click", "#btnsearch", function() {
        var from = $("[name='dtfrom']").val();
        var to = $("[name='dtto']").val();
        $("[name='hfrom']").val(from);
        $("[name='hto']").val(to);
        load_pageview();
    });

    $(document).on("change", "#customername", function() {
        var serdata = $(this).val();
        $("[name='hcustomer']").val(serdata);
        load_pageview();
    });

    $(document).on("click", "#btnvieworder", function() {
        var vno = $(this).data("vno");
        $.ajax({
            type: "post",
            url: ajax_url,
            data: {
                action: 'viewvoucher',
                vno: vno
            },
            success: function(data) {
                $("#frmvoucher").html(data);
                $("#vouchermodal").modal("show");
            }
        });
    });

    $(document).on("click", "#btnconfirm", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        swal({
                title: "Confirm?",
                text: "Are you sure Confirm PreOrder!",
                type: "success",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, Confirm it!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: ajax_url,
                    data: {
                        action: 'confirm',
                        aid: aid
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Confirm data success.",
                                "success");
                            load_pageview();
                            swal.close();
                        } 
                        else {
                            swal("Error",
                                "Confirm data failed.",
                                "error");
                        }
                    }
                });
            });
    });

    $(document).on("click", "#btnreturn", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        swal({
                title: "Preorder Return?",
                text: "Are you sure Return PreOrder!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                confirmButtonText: "Yes, Return it!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: ajax_url,
                    data: {
                        action: 'return',
                        aid: aid
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Return data success.",
                                "success");
                            load_pageview();
                            swal.close();
                        } 
                        else {
                            swal("Error",
                                "Return data failed.",
                                "error");
                        }
                    }
                });
            });
    });

    $(document).on("click", "#btncancel", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        swal({
                title: "Preorder Cancel?",
                text: "Are you sure Cancel PreOrder!",
                type: "error",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, Cancel it!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: ajax_url,
                    data: {
                        action: 'cancel',
                        aid: aid
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Cancel data success.",
                                "success");
                            load_pageview();
                            swal.close();
                        } 
                        else {
                            swal("Error",
                                "Cancel data failed.",
                                "error");
                        }
                    }
                });
            });
    });

});
</script>