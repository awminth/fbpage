<?php

include('../config.php');

include(root.'master/header.php') 
?>
<title>ECOMMERCE | နေ့စဉ်အရောင်း</title>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>နေ့စဉ်အရောင်း</h1>
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
                    <h4>Quick Link</h4>                       
                            
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-teal">
                <h4 class="modal-title">New Category</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="row print_div">
                <!-- accepted payments column -->
                <div class="col-6">
                    <p class="lead">Payment Methods:</p>
                    <img src="<?php echo roothtml.'lib/images/img.jpg' ?>" alt="Visa">
                    <img class="no-print" src="<?php echo roothtml.'lib/images/user.png' ?>" alt="Mastercard">
                    
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango
                        imeem
                        plugg
                        dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                    </p>
                </div>
                <!-- /.col -->
                <div class="col-6">
                    <p class="lead">Amount Due 2/22/2014</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Subtotal:</th>
                                <td>$250.30</td>
                            </tr>
                            <tr>
                                <th>Tax (9.3%)</th>
                                <td>$10.34</td>
                            </tr>
                            <tr>
                                <th>Shipping:</th>
                                <td>$5.80</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td>$265.24</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
                <div class='modal-footer no-print'>
                <a href="#" class='btn btn-success'><i class="fas fa-save"></i>
                    Print</a>
            </div>
            </div>
            <!-- /.row -->

            <div class='modal-footer no-print'>
                <a href="#" id='btnprint' class='btn btn-success'><i class="fas fa-save"></i>
                    Print</a>
            </div>

        </div>
    </div>
</div>



<?php include(root.'master/footer.php') ?>

<script>
$(document).ready(function() {

    $("#example").DataTable({
        "responsive": true,
        "autoWidth": false,
        "searching": false
    });

    $(document).on("click", "#showprint", function() {
        $("#btnnewmodal").modal("show");
    });

    $(document).on("click", "#btnprint", function() {
        $(".print_div").printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            printContainer: true,
            loadCSS: "../css/style.css",
            pageTitle: "My Modal",
            removeInline: false,
            printDelay: 333,
            header: null,
            formValues: true
        });
    });




});
</script>