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
                    <h1>Cash Report</h1>
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
                            <form method="POST" action="voucherreport_action.php">
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

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {
    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        var from = $("[name='hfrom']").val();
        var to = $("[name='hto']").val();
        var customer = $("[name='hcustomer']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'report/voucherreport_action.php' ?>",
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




});
</script>