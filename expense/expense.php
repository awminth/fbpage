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
                    <h1>Expense</h1>
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
                                    <td><button id="btnnew" type="button" class="btn btn-sm btn-<?=$color?>"><i
                                                class="fas fa-plus"></i>&nbsp; New
                                        </button></td>
                                    <td>
                                        <form method="POST" action="expense_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <input type="hidden" name="dtfrom">
                                            <input type="hidden" name="dtto">
                                            <input type="hidden" name="uu">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;Excel</button>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="card card-primary card-outline p-2">
                                        <div class="form-group">
                                            <label for="usr"> From :</label>
                                            <input type="date" class="form-control border-success"
                                                value="<?=date('Y-m-d')?>" name="from">
                                        </div>
                                        <div class="form-group">
                                            <label for="usr"> To :</label>
                                            <input type="date" class="form-control border-success"
                                                value="<?=date('Y-m-d')?>" name="to">
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">အသုံးပြုသူအမည်</label>
                                            <select class="form-control border-success select2" id="userid"
                                                name="userid">
                                                <option value="">Select User</option>
                                                <?=load_user()?>
                                            </select>
                                        </div>
                                        <button class="form-control btn btn-primary" id="btnsearch2">Search</button>
                                    </div>
                                </div>

                                <div class="col-sm-9">
                                    <div class="card card-primary card-outline p-2">
                                        <table width="100%">
                                            <tr>
                                                <td width="25%">
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
                                                <td width="70%" class="float-right">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3"
                                                            class="col-sm-3 col-form-label">Search</label>
                                                        <div class="col-sm-9">
                                                            <input type="search" class="form-control" id="searching"
                                                                placeholder="Searching . . . . .">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <div id="show_table" class="table-responsive-sm">

                                        </div>
                                    </div>
                                </div>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">New Customer</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm" method="POST" enctype="multipart/form-data">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <input type="hidden" name="action" id="action" value="save">
                    <div class="form-group">
                        <label for="usr"> အသုံးပြုသူအမည် :</label>
                        <input type="text" class="form-control border-success" readonly
                            value="<?php echo $_SESSION['eadmin_username'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="usr"> အကြောင်းအရာ :</label>
                        <input type="text" class="form-control border-success" id="reason" name="reason"
                            placeholder="Reason">
                    </div>
                    <div class="form-group">
                        <label for="usr"> ကုန်ကျငွေ :</label>
                        <input type="number" class="form-control border-success" id="amount" name="amount"
                            placeholder="00.00">
                    </div>
                    <div class="form-group">
                        <label for="usr"> ရက်စွဲ :</label>
                        <input type="date" class="form-control border-success" id="date" name="date"
                            value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="usr">File :</label>
                        <div class="border border-success p-1">
                            <input type="file" accept=".pdf ,.xls,.xlsx,.docx" name="file" id="file">
                        </div>
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


<!-- The fileedit Modal -->
<div class="modal fade" id="filemodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Change File</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmfile" method="POST" enctype="multipart/form-data">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal animate__animated animate__slideInDown" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Edit Category</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm1" method="POST">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {
    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        var dtfrom = $("[name='dtfrom']").val();
        var dtto = $("[name='dtto']").val();
        var userid = $("[name='uu']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'expense/expense_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                dtfrom: dtfrom,
                dtto: dtto,
                userid: userid
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

    $(document).on("change", "#userid", function() {
        var serdata = $(this).val();
        $("[name='uu']").val(serdata);
        load_pag();
    });

    $(document).on("click", "#btnsearch2", function() {
        var from = $("[name='from']").val();
        var to = $("[name='to']").val();
        $("[name='dtfrom']").val(from);
        $("[name='dtto']").val(to);
        load_pag();
    });


    $(document).on("click", "#btnnew", function() {
        $("#btnnewmodal").modal("show");
    });


    $("#frm").on("submit", function(e) {
        e.preventDefault();
        var reason = $("#reason").val();
        var amount = $("#amount").val();
        var date = $("#date").val();

        var formData = new FormData(this);
        if (reason == "" || amount == "" || date == "") {
            swal("Information", "Please fill all data", "info");
        } else {

            $("#btnnewmodal").modal("hide");
            $.ajax({
                type: "post",
                url: "<?php echo roothtml.'expense/expense_action.php' ?>",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data == "success") {
                        swal("Successful!",
                            "Save data Successful.",
                            "success");
                        load_pag();
                        swal.close();
                    }
                    if (data == "fail") {
                        swal("Error!", "Error Save.", "error");
                    }
                    if (data == "wrongtype") {
                        swal("Warning!",
                            "Your file must be .xls, xlsx, .docx, .pdf !",
                            "warning");
                    }
                }
            });
        }
    });


    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'expense/expense_action.php' ?>",
            data: {
                action: 'editprepare',
                aid: aid
            },
            success: function(data) {
                $("#frm1").html(data);
            }
        });
    });


    $(document).on("click", "#btnupdate", function(e) {
        e.preventDefault();
        $("#editmodal").modal('hide');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'expense/expense_action.php' ?>",
            data: $("#frm1").serialize() + "&action=update",
            success: function(data) {
                if (data == 1) {
                    swal("Successful", "Edit data success.",
                        "success");
                    load_pag();
                    swal.close();
                } else {
                    swal("Error", "Edit data failed.", "error");
                }
            }
        });
    });


    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
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
                    url: "<?php echo roothtml.'expense/expense_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid,
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


    $(document).on("click", "#btneditfile", function() {
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'expense/expense_action.php'; ?>",
            data: {
                action: 'editfile',
                aid: aid
            },
            success: function(data) {
                $("#frmfile").html(data);
            }
        });
    });

    $("#frmfile").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#filemodal").modal('hide');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'expense/expense_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == "success") {
                    swal("Successful",
                        "Change file success.",
                        "success");
                    load_pag();
                    swal.close();
                }
                if (data == "fail") {
                    swal("Error",
                        "Change file failed.",
                        "error");
                }
                if (data == "nofile") {
                    swal("Warning",
                        "Please choose file.",
                        "warning");
                }
                if (data == "wrongtype") {
                    swal("Information",
                        "Your file must be .pdf, .xls, .xlsx, .docx !",
                        "info");
                }
            }
        });
    });

});
</script>