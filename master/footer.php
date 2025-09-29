
<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>BRIGHTWARE IT Solutions - </b> Product Version 1.1.0
    </div>
    <br>
</footer>



<!-- ./wrapper -->

<!-- Summernote -->
<script src="<?php echo roothtml.'lib/plugins/summernote/summernote-bs4.min.js' ?>"></script>

<!-- jQuery -->
<script src="<?php echo roothtml.'lib/plugins/jquery/jquery.min.js' ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo roothtml.'lib/plugins/bootstrap/js/bootstrap.bundle.min.js' ?>"></script>
<!-- Select2 -->
<script src="<?php echo roothtml.'lib/plugins/select2/js/select2.full.min.js' ?>"></script>
<!-- BS-Stepper -->
<script src="<?php echo roothtml.'lib/plugins/bs-stepper/js/bs-stepper.min.js' ?>"></script>
<!-- DataTables -->
<script src="<?php echo roothtml.'lib/plugins/datatables/jquery.dataTables.min.js' ?>"></script>
<script src="<?php echo roothtml.'lib/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js' ?>"></script>
<script src="<?php echo roothtml.'lib/plugins/datatables-responsive/js/dataTables.responsive.min.js' ?>"></script>
<script src="<?php echo roothtml.'lib/plugins/datatables-responsive/js/responsive.bootstrap4.min.js' ?>"></script>
<!-- bs-custom-file-input -->
<script src="<?php echo roothtml.'lib/plugins/bs-custom-file-input/bs-custom-file-input.min.js' ?>"></script>
<!-- overlayScrollbars -->
<script src="<?php echo roothtml.'lib/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js' ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo roothtml.'lib/dist/js/adminlte.min.js' ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo roothtml.'lib/dist/js/demo.js' ?>"></script>
<!-- ChartJS -->
<script src="<?php echo roothtml.'lib/plugins/chart.js/Chart.min.js' ?>"></script>

<!-- Print -->
<script src="<?php echo roothtml.'lib/printThis.js' ?>"></script>
<script src="<?php echo roothtml.'lib/print.min.js' ?>"></script>

<script>
$(document).ready(function() {
    $(document).ajaxStart(function() {
        $(".loader").show();
    });

    $(document).ajaxComplete(function() {
        $(".loader").hide();
    });

    $('[data-toggle="tooltip"]').tooltip();   

    //for input type file  
    bsCustomFileInput.init();

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

    $(document).on("click", "#btntoday", function(e) {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'index_action.php' ?>",
            data: {
                action:'today'
            },          
            success: function (data) {
                $("#frmtoday").html(data);
                $("#modaltoday").modal('show');                
            }
        });
    });

    $(document).on("click", "#btnprintvoucher", function() {
        print_fun("frmtoday");
    });

    $(document).on("click", "#btnlogout", function(e) {
        e.preventDefault();
        swal({
                title: "Answer ?",
                text: "Are you sure Exit!",
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes,Sure!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: "<?php echo roothtml.'index_action.php' ?>",
                    data: {
                        action: 'logout'
                    },
                    success: function(data) {
                        if (data == 1) {
                            location.href =
                                "<?php echo roothtml.'index.php' ?>";
                        }
                    }
                });
            });
    });



    $("#catsearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        
        $("#asearch a").filter(function() {
            
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });


    $('.select2').select2({
      theme: 'bootstrap4'
    });


    $(document).on("click","#cat_click",function(){
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'pos/pos_action.php' ?>",
            data: {
                action:'show_category',
                aid:aid
            },  
            beforeSend: function() {
             $(".loader").show();
            },
            success: function (data) {
                 $(".loader").hide();
                $("#show_data").html(data);
            }
        });
    });


    $(document).on("click","#btngopos",function(){
        var vno=$(this).data('vno');
        $.ajax({
           type: "post",
           url: "<?php echo roothtml.'sell/pause_action.php' ?>",
           data: {
               action:'gopos',
               vno:vno
           },  
           beforeSend: function() {
             $(".loader").show();
            },        
           success: function (data) {
               location.href="<?php echo roothtml.'pos/pos.php' ?>";
               
           }
       });
    });

    function show_order_count(){
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'index_action.php' ?>",
            data: {
                action:'show_ordercount',
            },   
            cache: false,
            global: false,    
            success: function (data) {
                $("#show_order_count").html(data);              
            }
       });
    }

    // show order count
    setInterval(show_order_count,1000);




});
</script>
</div>

</body>

</html>
