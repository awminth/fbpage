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
                    <h1>Sale Return</h1>
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
                                <select class="form-control select2" name="customer">
                                    <option value="">Select Customer</option>
                                    <?=load_customer();?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="btnsearch" class="form-control btn btn-sm btn-<?=$color?>"><i
                                        class="fas fa-search"></i>&nbsp;Search</button>
                            </div>
                            <form method="POST" action="sellreturn_action.php">
                                <input type="hidden" name="hid">
                                <input type="hidden" name="ser">
                                <input type="hidden" name="hfrom">
                                <input type="hidden" name="hto">
                                <input type="hidden" name="hcustomer">
                                <button type="submit" name="action" value="excel"
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
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- new Modal -->
<div class="modal fade" id="modalviewsell">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">အရောင်းစာရင်း</h4>
                <div class="float-right m-2">
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    <a id="btnprintsell" class="text-white" style="float:right;"><i class="fas fa-print"
                            style="font-size:20px;"></i></a>
                </div>
            </div>
            <div id="printviewsell" class="container">

            </div>
            <br><br>
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
        var from = $("[name='hfrom']").val();
        var to = $("[name='hto']").val();
        var customer = $("[name='hcustomer']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'sell/sellreturn_action.php' ?>",
            data: {
                action: 'show',
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

    $(document).on("click", "#btnsearch", function() {
        var from = $("[name='from']").val();
        var to = $("[name='to']").val();
        var customer = $("[name='customer']").val();
        $("[name='hfrom']").val(from);
        $("[name='hto']").val(to);
        $("[name='hcustomer']").val(customer);
        load_pag();
    });

    $(document).on("click", "#viewsell", function() {
        var vno = $(this).data("vno");
        var customerid = $(this).data("customerid");
        var cusname = $(this).data("cusname");
        var userid = $(this).data("userid");
        var date = $(this).data("date");
        var totalamt = $(this).data("totalamt");
        var total = $(this).data("total");
        var dis = $(this).data("dis");
        var tax = $(this).data("tax");
        var cash = $(this).data("cash");
        var refund = $(this).data("refund");
        var credit = $(this).data("credit");
        var chk = $(this).data("chk");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'sell/sellreturn_action.php' ?>",
            data: {
                action: 'view',
                vno: vno,
                customerid: customerid,
                userid: userid,
                date: date,
                totalamt: totalamt,
                total: total,
                dis: dis,
                tax: tax,
                cash: cash,
                refund: refund,
                cusname: cusname,
                credit: credit,
                chk: chk
            },
            success: function(data) {
                $("#printviewsell").html(data);
                $("#modalviewsell").modal("show");
            }
        });
    });

    $(document).on("click", "#btnprintsell", function(e) {
        e.preventDefault();
        print_fun("printviewsell");
    });

    $(document).on("click", "#editsell", function() {
        var vno = $(this).data("vno");
        var customerid = $(this).data("customerid");
        var customername = $(this).data("customername");
        var totalamt = $(this).data("totalamt");
        var totalqty = $(this).data("totalqty");
        var chk = $(this).data("chk");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'sell/sellreturn_action.php' ?>",
            data: {
                action: 'edit',
                vno: vno,
                customerid: customerid,
                customername: customername,
                totalqty: totalqty,
                totalamt: totalamt,
                chk: chk
            },
            success: function(data) {
                if(data == 1){
                    location.href = "<?=roothtml.'pos/pos.php?key=kill'?>";
                }else{
                    swal("Error","Error","error");
                }
            }
        });

    });

    $(document).on("click", "#deletesell", function(e) {
        e.preventDefault();
        var vno = $(this).data("vno");
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
                    url: "<?php echo roothtml.'sell/sellreturn_action.php'; ?>",
                    data: {
                        action: 'delete',
                        vno: vno
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




});
</script>