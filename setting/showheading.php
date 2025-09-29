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
                    <h1>Show Heading</h1>
                </div>
                <div class="col-sm-6 text-right">
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
                                    $heading1 = "";
                                    $heading2 = "";
                                    $heading3 = "";
                                    $subheading1 = "";
                                    $subheading2 = "";
                                    $subheading3 = "";
                                    $img1 = "";
                                    $img2 = "";
                                    $img3 = "";                                    
                                    $aid = 0;
                                    $sql = "select * from tblheading order by AID desc limit 1";
                                    $res = mysqli_query($con,$sql);
                                    if(mysqli_num_rows($res) > 0){
                                        $row = mysqli_fetch_array($res);
                                        $aid = $row["AID"];
                                        $heading1 = $row["Heading1"];
                                        $heading2 = $row["Heading2"];
                                        $heading3 = $row["Heading3"];
                                        $subheading1 = $row["SubHeading1"];
                                        $subheading2 = $row["SubHeading2"];
                                        $subheading3 = $row["SubHeading3"];
                                        $img1 = $row["Img1"];
                                        $img2 = $row["Img2"];
                                        $img3 = $row["Img3"];
                                    }
                                ?>
                                <div class="row p-1">
                                    <div class="col-sm-6">
                                        <input type="hidden" name="txt1" value="<?=$img1?>">
                                        <input type="hidden" name="txt2" value="<?=$img2?>">
                                        <input type="hidden" name="txt3" value="<?=$img3?>">
                                        <input type="hidden" name="action" id="action" value="savedetail">
                                            <input type="hidden" name="aid" value="<?=$aid?>">
                                        <div class='form-group'>                                            
                                            <label for='usr'> Heading One:</label></label>
                                            <input type="text" name="heading1" value="<?=$heading1?>" class="form-control" required/>
                                        </div>
                                        <div class='form-group'>                                            
                                            <label for='usr'> SubHeading One:</label></label>
                                            <input type="text" name="subheading1" value="<?=$subheading1?>" class="form-control" required/>
                                        </div>
                                        <div class='form-group'>                                            
                                            <label for='usr'> Image One :</label></label>
                                            <input type="file" accept=".png, .jpeg, .jpg" id="img1" name="img1" /><br>
                                            <img src='<?=roothtml.'upload/heading/'.$img1?>' id="showimg1"
                                                style='width:100%;height:220px;' />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class='form-group'>                                            
                                            <label for='usr'> Heading Two:</label></label>
                                            <input type="text" name="heading2" value="<?=$heading2?>" class="form-control" required/>
                                        </div>
                                        <div class='form-group'>                                            
                                            <label for='usr'> SubHeading Two:</label></label>
                                            <input type="text" name="subheading2" value="<?=$subheading2?>" class="form-control" required/>
                                        </div>
                                        <div class='form-group'>
                                            <label for='usr'> Image Two :</label></label><input type="file"
                                                accept=".png, .jpeg, .jpg" id="img2" name="img2" /><br>
                                            <img src='<?=roothtml.'upload/heading/'.$img2?>' id="showimg2"
                                                style='width:100%;height:220px;' />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class='form-group'>                                            
                                            <label for='usr'> Heading Three:</label></label>
                                            <input type="text" name="heading3" value="<?=$heading3?>" class="form-control" required/>
                                        </div>
                                        <div class='form-group'>                                            
                                            <label for='usr'> SubHeading Three:</label></label>
                                            <input type="text" name="subheading3" value="<?=$subheading3?>" class="form-control" required/>
                                        </div>
                                        <div class='form-group'>
                                            <label for='usr'> Image Three :</label></label><input type="file"
                                                accept=".png, .jpeg, .jpg" id="img3" name="img3" /><br>
                                            <img src='<?=roothtml.'upload/heading/'.$img3?>' id="showimg3"
                                                style='width:100%;height:220px;' />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-success form-control">Save</button>
                                    </div>
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

    //new purchase save
    $("#frm").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'setting/showheading_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == "success") {
                    swal("Successful!", "Save Successful.",
                        "success");
                    window.location.reload();
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