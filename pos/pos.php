<?php
include('../config.php');
include(root.'master/header.php');
?>

<style>
tr:nth-child(even) {
    /* background-color: rgb(235, 202, 254); */
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row pt-2">
                <div class="col-md-4 card pt-2">
                    <?php 
                        if(!isset($_GET['key'])){
                            unset($_SESSION["editcustomerid"]);
                            unset( $_SESSION["editcustomername"]);
                            unset( $_SESSION["editsalevno"]);
                            unset( $_SESSION["editsalechk"]);
                        }
                        $chk = (isset($_SESSION["editsalechk"])?$_SESSION["editsalechk"]:"Cash");
                    ?>
                    <input type="hidden" name="rdcheck" value="<?=$chk?>"/>
                    <div class="input-group p-1 text-center">
                        <?php 
                            for($i=0;$i<count($arr_chk);$i++){ 
                                $ck = "";
                                if($chk == $arr_chk[$i]){
                                    $ck = "checked";
                                }
                        ?>
                        <div class="form-check-inline">
                            <label class="form-check-label" for="radio1">
                                <input type="radio" class="form-check-input" id="rdcheck" 
                                    name="rdchk" value="<?=$arr_chk[$i]?>" <?=$ck?>>Sale <?=$arr_chk[$i]?>
                            </label>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <div class="input-group p-1">
                        <select class="form-control border-success select2" name="category" id="category">
                            <?php if(isset($_SESSION["editsalevno"])) {  ?>
                            <option value="<?php echo $_SESSION["editcustomerid"] ?>">
                                <?php echo $_SESSION["editcustomername"] ?></option>
                            <?php } else{  ?>
                            <!-- <option value="">Choose Customer</option> -->
                            <?php } ?>
                            <?php echo load_customer(); ?>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text"><i id="newcustomer" class="fas fa-plus-circle text-teal"></i>
                            </div>
                        </div>
                    </div>
                    <?php if(isset($_SESSION["editsalevno"])) {  ?>
                    <div class="input-group p-1">
                        <input type="text" value="<?php echo $_SESSION['editsalevno'] ?>"
                            placeholder="search code or item name" class="form-control border-success" />
                    </div>
                    <?php } ?>

                    <div class="input-group p-1">
                        <input type="search" placeholder="search code or item name" autocomplete="off"
                            id="searchcatlist" class="form-control border-success" />
                    </div>
                    <div id="itemlist"></div>
                    <div class="input-group p-1 table-responsive" style="height:20%;">
                        <table class="table table-striped table-sm">
                            <thead class="text-sm">
                                <tr>
                                    <th>Code</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-center">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="show_cart">

                            </tbody>
                        </table>
                    </div>
                    <div class="input-group p-1">
                        <table class="table table-striped text-sm">
                            <tr id="show_amt">

                            </tr>
                        </table>
                        <div class="container">
                            <?php if(!isset($_SESSION["editsalevno"])) {  ?>
                            <button type="button" id="btnclickpause" class="btn btn-primary btn-sm"><i
                                    class="fas fa-pause"></i>&nbsp;ရပ်ဆိုင်းထားမည်</button>
                            <?php } ?>
                            <button type="button" id="btndeletesession" class="btn btn-danger btn-sm"><i
                                    class="fas fa-trash"></i>&nbsp;ပယ်ဖျက်ရန်</button>
                            <?php if(isset($_SESSION["editsalevno"])) {  ?>
                            <button type="button" id="btnpaidedit" class="btn btn-info btn-sm"><i
                                    class="fas fa-edit"></i>&nbsp;ပြင်ဆင်မည်</button>
                            <?php }else{ ?>
                            <button type="button" id="btnpaid" class="btn btn-info btn-sm"><i
                                    class="fas fa-dollar"></i>&nbsp;ငွေရှင်းမည်</button>

                            <?php } ?>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="col-md-8">
                    <div id="show_data" class="row">

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- pause Modal -->
<div class="modal fade" id="modalpause">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-teal">
                <h4 class="modal-title">ရပ်ဆိုင်းထားမည်</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmpause" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="container">
                        <div class="form-group">
                            <label for="usr"> အမည် :</label>
                            <input type="text" class="form-control border-success" name="pname"
                                placeholder="အမည်ရိုက်ထည့်ပါ">
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button id='btnpause' type="submit" class='btn btn-success'><i class="fas fa-save"></i>
                        သိမ်းမည်</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- voucher Modal -->
<div class="modal fade" id="modalvoucher">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-teal">
                <h4 class="modal-title">Voucher</h4>
                <div class="float-right m-2">
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    <a id="btnprintvoucher" class="text-white" style="float:right;"><i class="fas fa-print"
                            style="font-size:20px;"></i></a>
                </div>
            </div>
            <div id="printvoucher" class="container  modal-body">

            </div>
            <br><br>
        </div>
    </div>
</div>

<!-- qty Modal -->
<div class="modal fade" id="qtyincreasemodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-teal">
                <h4 class="modal-title">ပြင်ဆင်ရန်</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="from-group">
                    <label>Item Name</label>
                    <input type="hidden" name="eaid" />
                    <input type="hidden" name="eremainaid" />
                    <input type="text" name="eitemname" readonly class="form-control form-control-sm text-sm" />
                </div>
                <div class="row">
                    <div class="col-6">
                        <label>Price</label>
                        <input type="text" name="eprice" readonly class="form-control form-control-sm text-sm" />
                    </div>
                    <div class="col-6">
                        <label>Qty</label>
                        <input type="text" name="eqty" class="form-control form-control-sm text-sm" />
                    </div>
                </div>
                <br>
                <button type="submit" id="btneditqty" class="btn btn-sm btn-info float-right"><i
                        class="fas fa-edit"></i>&nbsp; Edit</button>
            </div>
        </div>
    </div>
</div>

<!-- customer Modal -->
<div class="modal fade" id="btnnewmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-teal">
                <h4 class="modal-title">New Customer</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> အမည် :</label>
                        <input type="text" class="form-control border-success" id="name" name="name"
                            placeholder="Customer Name">
                    </div>
                    <div class="form-group">
                        <label for="usr"> ဖုန်းနံပါတ် :</label>
                        <input type="text" class="form-control border-success" id="phno" name="phno"
                            placeholder="Phone No">
                    </div>
                    <div class="form-group">
                        <label for="usr"> နေရပ်လိပ်စာ :</label>
                        <input type="text" class="form-control border-success" id="address" name="address"
                            placeholder="Address">
                    </div>
                    <div class="form-group">
                        <label for="usr"> အီးမေးလ် :</label>
                        <input type="email" class="form-control border-success" id="email" name="email"
                            placeholder="Email">
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnsave' class='btn btn-success'><i class="fas fa-save"></i>
                        အသစ်သွင်းမည်</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- cash Modal -->
<div class="modal fade" id="modalpaidcash">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-teal">
                <h4 class="modal-title">ငွေရှင်းမည် (Cash)</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmpaid" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="container">
                        <table class="table text-sm">
                            <input type="hidden" name="ch_customerid" />                            
                            <tr class="bg-white">
                                <td width="25%">Total Qty </td>
                                <td>
                                    <input type="number" readonly class="form-control border-success"
                                        style="font-size:30px;" name="ch_tchkqty">
                                </td>
                            </tr>
                            <tr>
                                <td>Total Amt </td>
                                <td>
                                    <input type="number" readonly class="form-control border-success"
                                        style="font-size:30px;" name="ch_tchkamt">
                                </td>
                            </tr>
                            <tr>
                                <td>Discount </td>
                                <td>
                                    <input type="number" class="form-control border-success" id="ch_disc"
                                        style="font-size:30px;" name="ch_disc" placeholder="Discount">
                                </td>
                            </tr>
                            <tr>
                                <td>Tax </td>
                                <td>
                                    <input type="number" class="form-control border-success" id="ch_tax"
                                        style="font-size:30px;" name="ch_tax" placeholder="Tax">
                                </td>
                            </tr>
                            <tr>
                                <td>Total </td>
                                <td>
                                    <input type="text" readonly class="form-control border-success"
                                        style="font-size:30px;" id="ch_total" name="ch_total" placeholder="Total">
                                </td>
                            </tr>
                            <tr>
                                <td>Cash </td>
                                <td>
                                    <input type="number" class="form-control border-success" id="ch_cash"
                                        style="font-size:30px;" name="ch_cash" placeholder="Cash">
                                </td>
                            </tr>
                            <tr>
                                <td>ပြန်အမ်းငွေ </td>
                                <td>
                                    <input type="text" readonly class="form-control border-success"
                                        style="font-size:30px;" name="ch_refund" placeholder="Refund">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class='modal-footer'>
                    <?php if(!isset($_GET['key'])){ ?>
                    <button id='btnpaidsave' type="submit" class='btn btn-success'><i class="fas fa-save"></i>
                        ရှင်းမည်</button>
                    <?php }else{ ?>
                    <button id='btnpaidedit1' type="submit" class='btn btn-success'><i class="fas fa-save"></i>
                        ပြင်ဆင်မည်</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- credit Modal -->
<div class="modal fade" id="modalpaidcredit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-teal">
                <h4 class="modal-title">ငွေရှင်းမည် (Credit)</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmcredit" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="container">
                        <table class="table text-sm">
                            <input type="hidden" name="cd_customerid" />                            
                            <tr class="bg-white">
                                <td width="25%">Total Qty </td>
                                <td>
                                    <input type="number" readonly class="form-control border-success"
                                        style="font-size:30px;" name="cd_tchkqty">
                                </td>
                            </tr>
                            <tr>
                                <td>Total Amt </td>
                                <td>
                                    <input type="number" readonly class="form-control border-success"
                                        style="font-size:30px;" name="cd_tchkamt">
                                </td>
                            </tr>
                            <tr>
                                <td>Discount </td>
                                <td>
                                    <input type="number" class="form-control border-success" id="cd_disc"
                                        style="font-size:30px;" name="cd_disc" placeholder="Discount">
                                </td>
                            </tr>
                            <tr>
                                <td>Tax </td>
                                <td>
                                    <input type="number" class="form-control border-success" id="cd_tax"
                                        style="font-size:30px;" name="cd_tax" placeholder="Tax">
                                </td>
                            </tr>
                            <tr>
                                <td>Total </td>
                                <td>
                                    <input type="text" readonly class="form-control border-success"
                                        style="font-size:30px;" id="cd_total" name="cd_total" placeholder="Total">
                                </td>
                            </tr>
                            <tr>
                                <td>Pay </td>
                                <td>
                                    <input type="number" class="form-control border-success" id="cd_cash"
                                        style="font-size:30px;" name="cd_cash" placeholder="Cash">
                                </td>
                            </tr>
                            <tr>
                                <td>Credit </td>
                                <td>
                                    <input type="text" readonly class="form-control border-success"
                                        style="font-size:30px;" name="cd_credit" placeholder="Credit">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class='modal-footer'>
                    <?php if(!isset($_GET['key'])){ ?>
                    <button id='btnpaidcredit' type="submit" class='btn btn-success'><i class="fas fa-save"></i>
                        ရှင်းမည်</button>
                    <?php }else{ ?>
                    <button id='btnpaidedit2' type="submit" class='btn btn-success'><i class="fas fa-save"></i>
                        ပြင်ဆင်မည်</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- credit Modal -->
<div class="modal fade" id="modalpaidreturn">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-teal">
                <h4 class="modal-title">Sale Return</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmreturn" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="container">
                        <table class="table text-sm">
                            <input type="hidden" name="rt_customerid" />                            
                            <tr class="bg-white">
                                <td width="25%">Total Qty </td>
                                <td>
                                    <input type="number" readonly class="form-control border-success"
                                        style="font-size:30px;" name="rt_tchkqty">
                                </td>
                            </tr>
                            <tr>
                                <td>Total Amt </td>
                                <td>
                                    <input type="number" readonly class="form-control border-success"
                                        style="font-size:30px;" name="rt_tchkamt">
                                </td>
                            </tr>
                            <tr>
                                <td>Return Amt </td>
                                <td>
                                    <input type="number" class="form-control border-success" id="rt_cash"
                                        style="font-size:30px;" name="rt_cash" placeholder="Amount">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class='modal-footer'>
                    <?php if(!isset($_GET['key'])){ ?>
                    <button id='btnpaidreturn' type="submit" class='btn btn-success'><i class="fas fa-save"></i>
                        ရှင်းမည်</button>
                    <?php }else{ ?>
                    <button id='btnpaidedit3' type="submit" class='btn btn-success'><i class="fas fa-save"></i>
                        ပြင်ဆင်မည်</button>
                    <?php } ?>
                </div>
            </form>
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

    // show items
    function Load_data() {
        var action = "show";
        $.ajax({
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            type: "POST",
            data: {
                action: action
            },
            success: function(data) {
                $("#show_data").html(data);
            }
        });
    }
    Load_data();

    // show total amt
    function load_totalamt() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: {
                action: 'show_totalamt'
            },
            success: function(data) {
                $("#show_amt").html(data);
            }
        });
    }

    // show choose items
    function load_session() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: {
                action: 'choose_items'
            },
            success: function(data) {
                load_totalamt();
                $("#show_cart").html(data);
            }
        });
    }
    load_session();

    //add temp
    $(document).on("click", "#cart", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var codeno = $(this).data("codeno");
        var itemname = $(this).data("itemname");
        var price = $(this).data("price");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: {
                action: 'addcart',
                aid: aid,
                codeno: codeno,
                itemname: itemname,
                price: price,
            },
            success: function(data) {
                if(data == 1){
                    load_session();
                }else if(data == 2){
                    swal("Warning","Item qty is not enough.","warning");
                }else{
                    swal("Error","error","error");
                }
            }
        });
    });

    // delete one colu temp
    $(document).on("click", "#removecart", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: {
                action: 'remove',
                aid: aid
            },
            success: function(data) {
                load_session();
            }
        });
    });

    // edit qty prepare
    $(document).on("click", "#btnqtyincrease", function() {
        var aid = $(this).data("aid");
        var codeno = $(this).data("codeno");
        var itemname = $(this).data("itemname");
        var price = $(this).data("price");
        var qty = $(this).data("qty");
        var remainaid = $(this).data("remainaid");
        $("[name='eaid'").val(aid);
        $("[name='eremainaid'").val(remainaid);
        $("[name='eitemname'").val(itemname);
        $("[name='eprice'").val(price);
        $("[name='eqty'").val(qty);
        $("#qtyincreasemodal").modal("show");
    });

    // edit qty save
    $(document).on("click", "#btneditqty", function() {
        var aid = $("[name='eaid'").val();
        var remainaid = $("[name='eremainaid'").val();
        var qty = $("[name='eqty'").val();
        if(qty < 0 || qty == ""){
            swal("Warning","Please fill qty","warning");
            return false;
        }
        $("#qtyincreasemodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: {
                action: 'save_editqty',
                aid: aid,
                qty: qty,
                remainaid: remainaid
            },
            success: function(data) {
                if(data == 1){
                    load_session();
                }else if(data == 2){
                    swal("Warning","Item qty is not enough.","warning");
                }else{
                    swal("Error","error","error");
                }
            }
        });
    });

    // delete all temp data
    $(document).on("click", "#btndeletesession", function() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: {
                action: 'delete_temp'
            },
            success: function(data) {
                load_session();
            }
        });
    });

    // add customer
    $(document).on("click", "#newcustomer", function() {
        $("#btnnewmodal").modal("show");
    });

    $(document).on("click", "#btnsave", function(e) {
        e.preventDefault();
        var name = $("#name").val();
        var phno = $("#phno").val();
        var address = $("#address").val();
        var email = $("#email").val();
        if (name == "" || phno == "" || address == "") {
            swal("Information", "Please fill all data", "info");
        } else {
            $.ajax({
                type: "post",
                url: "<?php echo roothtml.'pos/pos_action.php' ?>",
                data: $("#frm").serialize() + "&action=save",
                success: function(data) {
                    if (data == 1) {
                        swal("Successful!", "Save Successful.",
                            "success");
                        window.location.reload();
                    } else {
                        swal("Error!", "Error Save.", "error");
                    }
                }
            });
        }
    });

    // check cash / credit /return
    $(document).on("change","#rdcheck",function(e){
        e.preventDefault();
        var rdcheck = $(this).val();
        $("[name='rdcheck']").val(rdcheck);
    });
    
    ////////// paid option cash/ credi /return /////////////
    function show_cash_modal(){
        var totalqty = $("[name='chkqty']").val();
        var totalamt = $("[name='chkamt']").val();
        var customerid = $("[name='category']").val();
        $("[name='ch_disc']").val('0');
        $("[name='ch_tax']").val('0');
        $("[name='ch_cash']").val('0');
        $("[name='ch_refund']").val('0');
        if (customerid == '' || totalqty == '') {
            swal("Warning", "Please choose Customer (or) Items", "warning");
            return false;
        }
        $("[name='ch_customerid']").val(customerid);
        $("[name='ch_tchkqty']").val(totalqty);
        $("[name='ch_tchkamt']").val(totalamt);
        $("[name='ch_disc']").val('0');
        $("[name='ch_tax']").val('0');
        $("[name='ch_total']").val(totalamt);
        $("#modalpaidcash").modal("show");
    }

    function show_credit_modal(){
        var totalqty = $("[name='chkqty']").val();
        var totalamt = $("[name='chkamt']").val();
        var customerid = $("[name='category']").val();
        $("[name='cd_disc']").val('0');
        $("[name='cd_tax']").val('0');
        $("[name='cd_cash']").val('0');
        $("[name='cd_credit']").val('0');
        if (customerid == '' || totalqty == '') {
            swal("Warning", "Please choose Customer (or) Items", "warning");
            return false;
        }
        $("[name='cd_customerid']").val(customerid);
        $("[name='cd_tchkqty']").val(totalqty);
        $("[name='cd_tchkamt']").val(totalamt);
        $("[name='cd_disc']").val('0');
        $("[name='cd_tax']").val('0');
        $("[name='cd_total']").val(totalamt);
        $("[name='cd_credit']").val(totalamt);
        $("#modalpaidcredit").modal("show");
    }

    function show_return_modal(){
        var totalqty = $("[name='chkqty']").val();
        var totalamt = $("[name='chkamt']").val();
        var customerid = $("[name='category']").val();
        if (customerid == '' || totalqty == '') {
            swal("Warning", "Please choose Customer (or) Items", "warning");
            return false;
        }
        $("[name='rt_customerid']").val(customerid);
        $("[name='rt_tchkqty']").val(totalqty);
        $("[name='rt_tchkamt']").val(totalamt);
        $("#modalpaidreturn").modal("show");
    }

    $(document).on("click", "#btnpaid", function() {
        var rdcheck = $("[name='rdcheck']").val();
        if(rdcheck == "Cash"){
            show_cash_modal();
        }else if(rdcheck == "Credit"){
            show_credit_modal();
        }else if(rdcheck == "Return"){
            show_return_modal();
        }
    });

    $(document).on("click", "#btnpaidedit", function() {
        var rdcheck = $("[name='rdcheck']").val();
        if(rdcheck == "Cash"){
            show_cash_modal();
        }else if(rdcheck == "Credit"){
            show_credit_modal();
        }else if(rdcheck == "Return"){
            show_return_modal();
        }
    });

    ////////// start cash sale/////////////
    function calc_cash_fun(){
        var disc = $("[name='ch_disc']").val();
        var tax = $("[name='ch_tax']").val();
        var totalamt = $("[name='ch_tchkamt']").val();
        if (disc == '') {
            disc = 0;
        }
        if (tax == '') {
            tax = 0;
        }
        var pdis = totalamt * (disc / 100);
        var ptax = totalamt * (tax / 100);
        var sub = Number(totalamt) + Number(ptax);
        var total = Number(sub) - Number(pdis);
        $("[name='ch_total']").val(total);
       
        var cash = $("[name='ch_cash']").val();
        var refund = Math.abs(Number(cash) - Number(total));
        $("[name='ch_refund']").val(refund);
    }

    $(document).on("keyup", "#ch_disc", function() {
        calc_cash_fun();
    });

    $(document).on("keyup", "#ch_tax", function() {
        calc_cash_fun();
    });

    $(document).on("keyup", "#ch_cash", function() {
        calc_cash_fun();
    });

    $(document).on("click", "#btnpaidsave", function(e) {
        e.preventDefault();
        var cash = $("[name='ch_cash']").val();
        if(cash <= 0){
            swal("Information","Please fill cash amount","info");
            return false;
        }
        $("#modalpaidcash").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: $("#frmpaid").serialize() + "&action=paidsave_cash",
            success: function(data) {  
                $("#printvoucher").html(data);
                $("#modalvoucher").modal("show");
                load_session();
            }
        });
    });

    $(document).on("click", "#btnpaidedit1", function(e) {
        e.preventDefault();
        var cash = $("[name='ch_cash']").val();
        if(cash <= 0){
            swal("Information","Please fill cash amount","info");
            return false;
        }
        $("#modalpaidcash").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: $("#frmpaid").serialize() + "&action=paidedit_cash",
            success: function(data) {
                if(data == 1){
                    location.href = "<?=roothtml.'sell/cashsell.php'?>";
                }else{
                    swal("Error","Edit error","error");
                }
            }
        });
    });

    $(document).on("click", "#btnprintvoucher", function(e) {
        e.preventDefault();
        print_fun("printvoucher");
    });
    //////// end cash sale ///////////////

    ////////// start credit sale/////////////
    function calc_credit_fun(){
        var disc = $("[name='cd_disc']").val();
        var tax = $("[name='cd_tax']").val();
        var totalamt = $("[name='cd_tchkamt']").val();
        if (disc == '') {
            disc = 0;
        }
        if (tax == '') {
            tax = 0;
        }
        var pdis = totalamt * (disc / 100);
        var ptax = totalamt * (tax / 100);
        var sub = Number(totalamt) + Number(ptax);
        var total = Number(sub) - Number(pdis);
        $("[name='cd_total']").val(total);
       
        var cash = $("[name='cd_cash']").val();
        var credit = Math.abs(Number(cash) - Number(total));
        $("[name='cd_credit']").val(credit);
    }

    $(document).on("keyup", "#cd_disc", function() {
        calc_credit_fun();
    });

    $(document).on("keyup", "#cd_tax", function() {
        calc_credit_fun();
    });

    $(document).on("keyup", "#cd_cash", function() {
        calc_credit_fun();
    });

    $(document).on("click", "#btnpaidcredit", function(e) {
        e.preventDefault();
        $("#modalpaidcredit").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: $("#frmcredit").serialize() + "&action=paidsave_credit",
            success: function(data) {  
                $("#printvoucher").html(data);
                $("#modalvoucher").modal("show");
                load_session();
            }
        });
    });

    $(document).on("click", "#btnpaidedit2", function(e) {
        e.preventDefault();
        $("#modalpaidcredit").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: $("#frmcredit").serialize() + "&action=paidedit_credit",
            success: function(data) {
                if(data == 1){
                    location.href = "<?=roothtml.'sell/creditsell.php'?>";
                }else{
                    swal("Error","Edit error","error");
                }
            }
        });
    });
    //////// end credit sale ///////////////

    ////////// start return sale/////////////
    $(document).on("click", "#btnpaidreturn", function(e) {
        e.preventDefault();
        var cash = $("[name='rt_cash']").val();
        if(cash <= 0 || cash == ""){
            swal("Warning","Please fill return amount.","warning");
            return false;
        }
        $("#modalpaidreturn").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: $("#frmreturn").serialize() + "&action=paidsave_return",
            success: function(data) {  
                $("#printvoucher").html(data);
                $("#modalvoucher").modal("show");
                load_session();
            }
        });
    });

    $(document).on("click", "#btnpaidedit3", function(e) {
        e.preventDefault();
        var cash = $("[name='rt_cash']").val();
        if(cash <= 0 || cash == ""){
            swal("Warning","Please fill return amount.","warning");
            return false;
        }
        $("#modalpaidreturn").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: $("#frmreturn").serialize() + "&action=paidedit_return",
            success: function(data) {
                if(data == 1){
                    location.href = "<?=roothtml.'sell/sellreturn.php'?>";
                }else{
                    swal("Error","Edit error","error");
                }
            }
        });
    });
    ///////// end return sale //////////


    // pasue
    $(document).on("click", "#btnclickpause", function() {
        var totalqty = $("[name='chkqty']").val();
        if (totalqty == '') {
            swal("Warning", "No Items", "warning");
            return false;
        }
        $("#modalpause").modal("show");
    });

    // pause save
    $(document).on("click", "#btnpause", function() {
        var pname = $("[name='pname']").val();
        if(pname == ""){
            swal("Information","Please fill name.","info");
            return false;
        }
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: {
                action: "pause",
                pname: pname
            },
            success: function(data) {
                
            }
        });
    });    

    // type code or itemname in search
    $(document).on("search", "#searchcatlist", function() {
        var cat = $(this).val();
        if (cat.length <= 0) {
            $('#itemlist').fadeOut();
            return false;
        }
    });

    // type code or itemname in search
    $(document).on("keyup", "#searchcatlist", function() {
        var cat = $(this).val();
        if (cat.length <= 0) {
            $('#itemlist').fadeOut();
            return false;
        }
        $.ajax({
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            method: "POST",
            data: {
                action: 'searchcategorylist',
                cat: cat
            },
            success: function(data) {
                $('#itemlist').html(data);
                $('#itemlist').fadeIn();
            }
        });
    });

     $(document).on("keyup", "#searchcatlist", function() {
        var cat = $(this).val();
        if (cat.length == 8) {
           
            $.ajax({
                url: "<?php echo roothtml.'pos/pos_action.php' ?>",
                method: "POST",
                data: {
                    action: 'addcartbycodeno',
                    codeno: cat
                },
                success: function(data) {
                    if(data == 1){
                        load_session();
                        $('#searchcatlist').val('');
                    }else if(data == 2){
                        swal("Warning","Item qty is not enough.","warning");
                    }else{
                        swal("Error","error","error");
                        $('#searchcatlist').val('');
                    }
                }
            });
        }
    });

    // search item click
    $(document).on('click', '#selectcatitem', function() {
        $('#searchcatlist').val($(this).text());
        $('#itemlist').fadeOut();
        var aid = $(this).data("aid");
        var codeno = $(this).data("codeno");
        var itemname = $(this).data("itemname");
        var price = $(this).data("price");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: {
                action: 'addcart',
                aid: aid,
                codeno: codeno,
                itemname: itemname,
                price: price
            },
            success: function(data) {
                if(data == 1){
                    load_session();
                    $('#searchcatlist').val('');
                }else if(data == 2){
                    swal("Warning","Item qty is not enough.","warning");
                }else{
                    swal("Error","error","error");
                }
            }
        });
    });


    

});
</script>