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
                    <h1>PreOrder Cancel စာရင်းများ</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="form-group">
                                <label>From</label>
                                <input type="date" value="<?=date('Y-m-d')?>" name="from" class="form-control " />

                            </div>
                            <div class="form-group">
                                <label>To</label>
                                <input type="date" value="<?=date('Y-m-d')?>" name="to" class="form-control " />

                            </div>
                            <div class="form-group">
                                <label>Customer</label>
                                <select class="form-control select2" name="customer" id="customer">
                                    <option value="">Select Customer</option>
                                    <?=load_customername();?>
                                </select>
                            </div>                            
                            <div class="form-group">
                                <button type="submit" id="btnsearch" class="form-control btn btn-sm btn-<?=$color?>"><i
                                        class="fas fa-search"></i>&nbsp;Search</button>
                            </div>
                            <form method="POST" action="preorderreport_action.php">
                                <input type="hidden" name="hid">
                                <input type="hidden" name="ser">
                                <input type="hidden" name="hfrom">
                                <input type="hidden" name="hto">
                                <input type="hidden" name="hcustomer">
                                <button type="submit" name="action" value="excel_cancel"
                                    class="form-control btn btn-sm btn-<?=$color?>"><i
                                        class="fas fa-file-excel"></i>&nbsp;Excel</button>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card card-primary card-outline">

                        <div class="card-body">
                            <table width="100%">
                                <tr>
                                    <td width="25%">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Show</label>
                                            <div class="col-sm-7">
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
                                            <label for="inputEmail3" class="col-sm-3 col-form-label">Search</label>
                                            <div class="col-sm-9">
                                                <input type="search" class="form-control" id="searching"
                                                    placeholder="Searching by VNO . . . . . ">
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
var ajax_url = "<?php echo roothtml.'preorder/preorderreport_action.php'; ?>";
$(document).ready(function() {
    
    //print js fun
    function print_fun(place) {
        printJS({
            printable: place,
            type: 'html',
            style: 'table, tr,td {font-weight: bold; font-size: 10px;' +
                '.txtc{text-align: center;font-weight: bold;}' +
                '.txtr{text-align: right;font-weight: bold;}' +
                '.txtl{text-align: left;font-weight: bold;}' +
                ' h5{ text-align: center;font-weight: bold;}' +
                ' hs{font-size: 18px;font-weight: bold;}' +
                ' hl{font-size: 24px;font-weight: bold;}' +
                '.fs{font-size: 10px;font-weight: bold;}' +
                '.fl{font-size: 18px;font-weight: bold;}' +
                '.txt,h5,h3,h6 {text-align: center;font-size: 10px;font-weight: bold;}' +
                '.lt{width:50%;float:left;font-weight: bold;}' +
                '.rt{width:50%;float:right;font-weight: bold;}' +
                '.wno{width:5%;font-weight: bold;}',
        });
    }

    function load_page(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        var from = $("[name='hfrom']").val();
        var to = $("[name='hto']").val();
        var customer = $("[name='hcustomer']").val();
        $.ajax({
            type: "post",
            url: ajax_url,
            data: {
                action: 'show_cancel',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                from: from,
                to: to,
                customer: customer
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }
    load_page();

    $(document).on('click', '.page-link', function() {
        var pageid = $(this).data('page_number');
        load_page(pageid);
    });

    $(document).on("change", "#entry", function() {
        var entryvalue = $(this).val();
        $("[name='hid']").val(entryvalue);
        load_page();
    });


    $(document).on("keyup", "#searching", function() {
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_page();
    });

    $(document).on("click", "#btnsearch", function() {
        var from = $("[name='from']").val();
        var to = $("[name='to']").val();
        var customer = $("[name='customer']").val();
        $("[name='hfrom']").val(from);
        $("[name='hto']").val(to);
        $("[name='hcustomer']").val(customer);
        load_page();
    });

    $(document).on("click", "#btnprint", function(e) {
        e.preventDefault();
        print_fun("printdata");
    });

    $(document).on("click", "#btnviewcancel", function() {
        var vno = $(this).data("vno");
        $.ajax({
            type: "post",
            url: ajax_url,
            data: {
                action: 'viewvoucher_cancel',
                vno: vno
            },
            success: function(data) {
                $("#frmvoucher").html(data);
                $("#vouchermodal").modal("show");
            }
        });
    });

    $(document).on("click", "#btndeletecancel", function(e) {
        e.preventDefault();
        var vno = $(this).data("vno");
        swal({
                title: "Preorder Delete?",
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
                        action: 'delete_cancel',
                        vno: vno
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            load_page();
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
});
</script>