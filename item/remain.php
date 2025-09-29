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
                    <h1>လက်ကျန်စာရင်း</h1>
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
                            <form method="POST" action="remain_action.php">
                                <input type="hidden" name="hid">
                                <input type="hidden" name="ser">
                                <button type="submit" name="action" value="excel" class="btn btn-sm btn-<?=$color?>"><i
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
<div class="modal fade" id="btnnewmodal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Barcode Print</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="row">
                <div class="card col-md-4 ml-4 m-3">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="" class="control-label ">Code No</label>
                            <input type="text" id="codeno" readonly class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Item Name</label>
                            <input type="text" id="itemname" readonly class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Price</label>
                            <input type="text" id="price" readonly class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Qty</label>
                            <input type="text" id="cnt" value="5" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Barcode Type</label>
                            <select class="browser-default custom-select custom-select-sm" id="type">
                                <option value="C128">Code 128</option>
                                <option value="C128A">Code 128 A</option>
                                <option value="C128B">Code 128 B</option>
                                <option value="C39">Code 39</option>
                                <option value="C39E">Code 39 E</option>
                                <option value="C93">Code 93</option>
                                <option value="EAN8">EAN 8</option>
                                <option value="EAN13">EAN 13</option>
                            </select>
                        </div>
                        <button type="button" class="col-md-4 btn-block float-right btn btn-<?=$color?> btn-sm"
                            id="generate">Generate</button>
                    </div>
                </div>
                <div class=" card col-md-7 ml-2 m-3" id='bcode-card'>
                    <div class="card-body">
                        <div id="display">
                            <center>Barcode</center>
                        </div>

                    </div>
                    <div class="card-footer" style="display:none">
                        <button type="button" class="col-md-4 btn-block btn btn-<?=$color?> btn-sm float-right"
                            id="print">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- The Modal -->
<div class="modal fade" id="viewmodal">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">View Image</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm2" method="POST" enctype="multipart/form-data">
                <!-- Modal body -->
                <div class='modal-body'>
                    <div class='form-group'>
                        <label for='usr'> Image :</label><br>
                        <img src='' id="showimg" style='width:100%;height:220px;' />
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


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
            url: "<?php echo roothtml.'item/remain_action.php' ?>",
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


    $(document).on("click", "#btnview", function() {
        var img = $(this).data("img");
        var path = "<?php echo roothtml.'upload/purchase/' ?>" + img;
        $('#showimg').attr('src', path);
        $("#viewmodal").modal('show');
    });


    $(document).on("click", "#btnbarcode", function() {
        var aid = $(this).data("aid");
        var codeno = $(this).data("codeno");
        var itemname = $(this).data("itemname");
        var price = $(this).data("sellprice");
        $("#codeno").val(codeno);
        $("#itemname").val(itemname);
        $("#price").val(price);
        $("#aid").val(aid);
        $("#display").html('');
        $("#btnnewmodal").modal("show").SlideUp();
        $("#cnt").focus();
    });


    $(document).on('click', '#generate', function(e) {
        e.preventDefault();
        $("#display").html('');
        if ($('#code').val() != '') {
            $.ajax({
                url: "<?php echo roothtml.'item/barcode_action.php' ?>",
                method: "POST",
                data: {
                    codeno: $('#codeno').val(),
                    type: $('#type').val(),
                    itemname: $('#itemname').val(),
                    price: $('#price').val(),
                    cnt: $('#cnt').val()
                },
                error: err => {
                    console.log(err)
                },
                success: function(data) {
                    $('#display').html(data)
                    $('#bcode-card .card-footer').show('slideUp')
                }
            });
        }
    });

    // $(document).on("click", "#print", function() {
    //     print_fun("display");
    // });

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


});
</script>