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
                    <h1>Print Setting</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline p-1">
                        <p>အောက်ပါအချက်အလက်များကို ပြန်လည်ပြင်ဆင်နိုင်ပါသည်။</p>
                        <div class="row m-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>ဆိုင်အမည်</label>
                                    <input class="form-control" value="<?php echo $_SESSION['shopname'] ?>"
                                        name="shopname" />
                                </div>
                                <div class="form-group">
                                    <label>အီးမေးလ်လိပ်စာ</label>
                                    <input class="form-control" value="<?php echo $_SESSION['shopemail'] ?>"
                                        name="shopemail" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>ဆက်သွယ်ရမည့်ဖုန်းနံပါတ်</label>
                                    <input class="form-control" value="<?php echo $_SESSION['shopphno'] ?>"
                                        name="shopphno" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>လိပ်စာ</label>
                                    <input class="form-control" value="<?php echo $_SESSION['shopaddress'] ?>"
                                        name="shopaddress" />
                                </div>
                            </div>

                            <button class="btn btn-sm btn-<?=$color?> text-right" id="btnprintsetting"><i
                                    class="fas fa-edit"></i> &nbsp; ပြင်ဆင်မည်</button>
                            <br>
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

    $(document).on("click", "#btnprintsetting", function() {
        var shopname = $("[name='shopname']").val();
        var shopaddress = $("[name='shopaddress']").val();
        var shopphno = $("[name='shopphno']").val();
        var shopemail = $("[name='shopemail']").val();

        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'setting/printsetting_action.php' ?>",
            data: {
                action: 'edit',
                shopname: shopname,
                shopaddress: shopaddress,
                shopphno: shopphno,
                shopemail: shopemail
            },
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Edit Successful.",
                        "success");
                    window.location.reload();
                } else {
                    swal("Error!", "Error Save.", "error");
                }

            }
        });


    });



});
</script>