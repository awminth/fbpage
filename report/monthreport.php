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
                    <h1>လစဉ်အရောင်း</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline p-2">
                        <p>အစီရင်ခံစာပြဇယား</p>
                        <!-- Info boxes -->
                        <div class="row" id="frmcard">

                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card card-primary card-outline p-2">
                        <div class="row m-2">
                            <div class="col-sm-4 card p-2">
                                <div class="form-group">
                                    <label>မှ</label>
                                    <input type="date" name="dtto" value='<?php echo date('Y-m-d') ?>'
                                        class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>ထိ</label>
                                    <input type="date" name="dtfrom" value='<?php echo date('Y-m-d') ?>'
                                        class="form-control" />
                                </div>
                                <button type="submit" id="btnsearch" class="btn  btn-info"><i
                                        class="fas fa-search"></i>&nbsp;ရှာဖွေရန်</button>
                                <br>
                            </div>
                            <div class="col-sm-6">
                                <table class="table table-bordered" style="width:100%">
                                    <tbody id="frmshow">

                                    </tbody>
                                </table>
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
    function showcard() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'report/monthreport_action.php' ?>",
            data: {
                action: 'showcard'
            },
            success: function(data) {
                $("#frmcard").html(data);
            }
        });
    }

    function showmonthly() {
        var dtto = $("[name='dtto']").val();
        var dtfrom = $("[name='dtfrom']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'report/monthreport_action.php'?>",
            data: {
                dtto: dtto,
                dtfrom: dtfrom,
                action: 'show'
            },
            success: function(data) {
                showcard();
                $("#frmshow").html(data);
            }
        });
    }

    showmonthly();
    $(document).on("click", "#btnsearch", function() {
        showmonthly();
    });




});
</script>