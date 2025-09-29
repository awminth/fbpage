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
                    <h1>Back Up</h1>
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
                        <div class="card-header">
                            <a href="<?php echo roothtml.'setting/backup_action.php' ?>"
                                class="btn btn-sm btn-success"><i class="fas fa-database"></i>&nbsp;
                                BackUp Data</a>
                            <button id="btnnew" type="button" class="btn btn-sm btn-success"><i
                                    class="fas fa-file-excel"></i>&nbsp;
                                Excel</button>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="example" class="table table-bordered table-striped responsive nowrap"
                                style="width:100%">

                                <thead>
                                    <tr>
                                        <th width="7%;">စဉ်</th>
                                        <th width="15%">User Name</th>
                                        <th>BackUp Name</th>
                                        <th>BackUp Date</th>
                                        <th style="display:none;">Download</th>
                                        <th width="7%;">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 

                                          $sql="select b.*,u.UserName from tblbackup b,tbluser u where b.UserID=u.AID  order by b.AID desc";
                                          $result=mysqli_query($con,$sql) or die("SQL Query");
                                                $output="";
                                          if(mysqli_num_rows($result) > 0){
                                                $no=1;
                                                while($row = mysqli_fetch_array($result)){

                                                ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $row["UserName"] ?></td>
                                        <td><?php echo $row["DBName"] ?></td>
                                        <td><?php echo $row["Date"] ?></td>
                                        <td style="display:none;" class="text-center"><a
                                                href="<?php echo roothtml.'backup/'.$row['DBName'] ?>"><i
                                                    class="fas fa-file-download text-success"
                                                    style="font-size:20px;"></i></a>
                                        </td>
                                        <td class="text-center"><a href="#" id="btndelete"
                                                data-aid="<?php echo $row['AID'] ?>"
                                                data-path="<?php echo $row['DBName'] ?>"><i
                                                    class="fas fa-close text-danger" style="font-size:25px;"></i></a>
                                        </td>

                                    </tr>
                                    <?php }} ?>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content-wrapper -->

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    $("#example").DataTable({
        "responsive": true,
        "autoWidth": false,
        "searching": false,
        "paging": false
    });

    $(document).on("click", "#btndelete", function() {
        var aid = $(this).data("aid");
        var path = $(this).data("path");
        var action = "backup";
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
                    url: "<?php echo roothtml.'setting/usercontrol_action.php'; ?>",
                    data: {
                        action: action,
                        path: path,
                        aid: aid
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Deleted!",
                                "Delete success.",
                                "success");
                            window.location.reload();
                        } else {
                            swal("Deleted!",
                                "Delete BackUp File.",
                                "error");
                        }
                    }
                });

            });

    });


});
</script>