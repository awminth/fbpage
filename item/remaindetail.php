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
                    <h1>လက်ကျန်စာရင်း (Detail)</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?=roothtml.'item/remain.php'?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-left"></i>&nbsp;Back</a>
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
                        <!-- Card body -->
                        <div class="card-body">
                            <form id="frm" method="POST" enctype="multipart/form-data">
                                <?php 
                                    $aid=$_GET['aid'];
                                    $sql="select * from tblremaindetail where RemainID=$aid"; 
                                    $txt1="";
                                    $txt2="";
                                    $txt3="";
                                    $txt4="";
                                    $desc="";
                                    $spec="";
                                    $result=mysqli_query($con,$sql) or die("SQL a Query");
                                    if(mysqli_num_rows($result) > 0){
                                          $row = mysqli_fetch_array($result); 
                                          $txt1=$row["Img1"];
                                          $txt2=$row["Img2"];
                                          $txt3=$row["Img3"];
                                          $txt4=$row["Img4"];
                                          $desc=$row["Description"];
                                          $spec=$row["Specification"];
                                          
                                    }
                                    
                                    ?>
                                <div class="row p-1">
                                    <div class="col-sm-6">
                                        <input type="hidden" name="txt1" value="<?php echo $txt1; ?>">
                                        <input type="hidden" name="txt2" value="<?php echo $txt2; ?>">
                                        <input type="hidden" name="txt3" value="<?php echo $txt3; ?>">
                                        <input type="hidden" name="txt4" value="<?php echo $txt4; ?>">
                                        <div class='form-group'>
                                            <input type="hidden" name="action" id="action" value="savedetail">
                                            <input type="hidden" name="remainid" value="<?php echo $aid; ?>">
                                            <label for='usr'> Image 1 :</label></label><input type="file"
                                                accept=".png, .jpeg, .jpg" id="img1" name="img1" /><br>
                                            <img src='<?php echo roothtml.'upload/purchase/'.$txt1 ?>' id="showimg1"
                                                style='width:100%;height:250px;' />
                                        </div>
                                        <div class='form-group'>
                                            <label for='usr'> Image 3 :</label></label><input type="file"
                                                accept=".png, .jpeg, .jpg" id="img3" name="img3" /><br>
                                            <img src='<?php echo roothtml.'upload/purchase/'.$txt3 ?>' id="showimg3"
                                                style='width:100%;height:250px;' />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class='form-group'>
                                            <label for='usr'> Image 2 :</label></label><input type="file"
                                                accept=".png, .jpeg, .jpg" id="img2" name="img2" /><br>
                                            <img src='<?php echo roothtml.'upload/purchase/'.$txt2 ?>' id="showimg2"
                                                style='width:100%;height:250px;' />
                                        </div>
                                        <div class='form-group'>
                                            <label for='usr'> Image 4 :</label><input type="file" name="img4"
                                                accept=".png, .jpeg, .jpg" id="img4" /><br>
                                            <img src='<?php echo roothtml.'upload/purchase/'.$txt4 ?>' id="showimg4"
                                                style='width:100%;height:250px;' />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for='usr'> Description :</label><br>
                                            <textarea class="form-control" col="10" row="30" name="desc"
                                                placeholder="ဖေါ်ပြချက်များရိုက်ထည့်ရန်"><?php echo $desc; ?>

                                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for='usr'> Sepecification :</label><br>
                                            <textarea class="form-control" col="10" row="30" name="spec"
                                                placeholder="enter specification"><?php echo $spec; ?>

                                                            </textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success text-right">Save</button>
                                </div>
                            </form>
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



<!-- The Modal -->
<div class="modal fade" id="viewmodal">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-teal">
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

    function readURL1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#showimg1').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#img1").change(function() {
        readURL1(this);
    });

    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#showimg2').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#img2").change(function() {
        readURL2(this);
    });

    function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#showimg3').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#img3").change(function() {
        readURL3(this);
    });

    function readURL4(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#showimg4').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#img4").change(function() {
        readURL4(this);
    });



    //new purchase save
    $("#frm").on("submit", function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'item/remain_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == "success") {
                    swal("Successful!", "Save Successful.",
                        "success");
                }
                if (data == "fail") {
                    swal("Error!", "Error Save.", "error");
                }
                if (data == "wrongtype") {
                    swal("Information!",
                        "Your upload is wrong type.",
                        "info");
                }
            }
        });

    });



});
</script>