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
                                        <a href="<?=roothtml.'item/purchasereturnview.php'?>" id="btnsearch"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>
                                            Back</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card card-primary card-outline">
                                        <input type="hidden" name="hid">
                                        <input type="hidden" name="ser">
                                        <!-- Card body -->
                                        <div class="card-body" id="show_search">
                                            <table width="100%">
                                                <tr>
                                                    <td width="30%">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3"
                                                                class="col-sm-5 col-form-label">Show</label>
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
                                                    <td width="70%" class="float-right">
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
                                            <div id="show_table" class="table-responsive table-responsive-sm">

                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-primary card-outline p-2">
                                        <div class="form-group">
                                            <label for="usr">Item Name:</label>
                                            <input type="hidden" name="aid" />
                                            <input type="text" class="form-control border-success" name="itemname"
                                                id="name" placeholder="Item Name" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">Supplier :</label>
                                            <input type="hidden" name="supplierid" />
                                            <input type="text" class="form-control border-success" name="suppliername"
                                                id="name" placeholder="" readonly>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="usr">Qty:</label>
                                                    <input type="hidden" name="oldqty" />
                                                    <input type="number" class="form-control border-success" name="qty"
                                                        id="qty" placeholder="Qty">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="usr">Remain Qty:</label>
                                                    <input type="number" readonly value="0"
                                                        class="form-control border-success" name="remainqty"
                                                        placeholder="Remain Qty">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label for="usr">Purchase Price:</label>
                                            <input type="number" value="0" class="form-control border-success"
                                                name="price" id="qty" placeholder="Purchase Price" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">Total :</label>
                                            <input type="number" value="0" class="form-control border-success"
                                                name="total" id="qty" placeholder="Total" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">Date :</label>
                                            <input type="date" value="<?=date('Y-m-d')?>"
                                                class="form-control border-success" name="dtdate">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" id="btnsave" class="btn btn-<?=$color?>"><i
                                                    class="fas fa-save"></i>
                                                အသစ်သွင်းမည်</button>
                                        </div>
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
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'item/purchasereturn_action.php' ?>",
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


    $('#print').click(function() {
        $("#display").printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            printContainer: true,
            loadCSS: "../css/style.css",
            pageTitle: "Print",
            removeInline: false,
            printDelay: 333,
            header: null,
            formValues: true
        });
    });

    $(document).on('click', '#btndetail', function(e) {
        var aid = $(this).data('aid');
        location.href = "<?php echo roothtml.'item/remaindetail.php?aid=' ?>" + aid;
    });

    function calculate_fun() {
        var qty = $("[name='qty']").val();
        var oldqty = $("[name='oldqty']").val();
        var price = $("[name='price']").val();
        if (Number(qty) <= Number(oldqty)) {
            var total = Number(qty) * Number(price);
            $("[name='total']").val(total);
            var remainqty = Number(oldqty) - Number(qty);
            $("[name='remainqty']").val(remainqty);
        } else {
            swal('info', 'အရေအတွက်ကျော်လွန်နေပါသည်', 'info');
            $("[name='qty']").val(0);
            $("[name='total']").val(0);
            $("[name='remainqty']").val(oldqty);
        }

    }


    $(document).on("click", "#btnclick", function() {
        var aid = $(this).data('aid');
        var name = $(this).data('name');
        var price = $(this).data('price');
        var supplierid = $(this).data('supplierid');
        var suppliername = $(this).data('suppliername');
        var qty = $(this).data('qty');
        $("[name='aid']").val(aid);
        $("[name='itemname']").val(name);
        $("[name='price']").val(price);
        $("[name='supplierid']").val(supplierid);
        $("[name='suppliername']").val(suppliername);
        $("[name='remainqty']").val(qty);
        $("[name='oldqty']").val(qty);
        $("[name='qty']").val(0);
        $("[name='total']").val(0);


    });

    $(document).on("keyup", "#qty", function() {
        calculate_fun();
    });

    function clear_fun() {
        $("[name='aid']").val('');
        $("[name='itemname']").val('');
        $("[name='price']").val(0);
        $("[name='supplierid']").val('');
        $("[name='suppliername']").val('');
        $("[name='remainqty']").val(0);
        $("[name='oldqty']").val(0);
        $("[name='qty']").val(0);
        $("[name='total']").val(0);
    }

    $(document).on("click", "#btnsave", function() {
        var aid = $("[name='aid']").val();
        var name = $("[name='itemname']").val();
        var qty = $("[name='qty']").val();
        var price = $("[name='price']").val();
        var amt = $("[name='total']").val();
        var supplierid = $("[name='supplierid']").val();
        var date = $("[name='dtdate']").val();
        var remainqty = $("[name='remainqty']").val();
        if (name == '') {
            swal('info', 'Item တခုကိုရွေးချယ်ပေးပါ', 'info');
            return;
        }
        if (qty == '' || qty <= 0) {
            swal('info', 'Qty ထည့်ပေးပါ။', 'info');
            return;
        }
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'item/purchasereturn_action.php' ?>",
            data: {
                action: 'save',
                aid: aid,
                qty: qty,
                remainqty: remainqty,
                price: price,
                amt: amt,
                supplierid: supplierid,
                date: date
            },
            success: function(data) {
                if (data == '1') {
                    load_pag();
                    clear_fun();
                } else {
                    load_pag();
                }
            }
        });

    });


});
</script>