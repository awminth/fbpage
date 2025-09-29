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
                    <h1>ထိပ်တန်းကုန်ပစ္စည်းဇယားပြပုံ</h1>
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
                    <p>အရောင်းထိပ်တန်းကုန်ပစ္စည်းပြဇယား<p>                       

                        <div class="row p-1">
                            <div class="col-sm-6">                               

                                <!-- BAR CHART -->
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">ယခုလ (<?php echo date("F - Y") ?>)</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="barChart"
                                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>

                            </div>

                            <div class="col-sm-6">

                                <!-- BAR CHART -->
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">ပြီးခဲ့သည့်လ (<?php echo date("F - Y", strtotime(' -1 month')) ?>)</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="barChart2"
                                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
                        </div>


                    </div>

                    <div class="card card-primary card-outline p-2">
                    <p>အော်ဒါထိပ်တန်းကုန်ပစ္စည်းပြဇယား<p>
                        

                        <div class="row p-1">
                            <div class="col-sm-6">                               

                                <!-- BAR CHART -->
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">ယခုလ (<?php echo date("F - Y") ?>)</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="barChart3"
                                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>

                            </div>

                            <div class="col-sm-6">

                                <!-- BAR CHART -->
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">ပြီးခဲ့သည့်လ (<?php echo date("F - Y", strtotime(' -1 month')) ?>)</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="barChart4"
                                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

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

<?php 

$sql="select ItemName,Sum(Qty) as sqty from tblsale 
where month(Date)=month(Now()) and year(Date)=year(Now()) 
group by ItemName Order by Sum(Qty) desc Limit 10";
$result = mysqli_query($con,$sql);
$arr=Array();
$arr1=array();
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_array($result)){

     $arr[]= $row['ItemName'];
     $arr1[]= $row['sqty'];
    }
}

$sql="select ItemName,Sum(Qty) as sqty 
from tblordersale where Status=1 and month(Date)=month(Now()) 
and year(Date)=year(Now()) 
group by ItemName Order by Sum(Qty) desc Limit 10";
$result = mysqli_query($con,$sql);
$arr33=Array();
$arr44=array();
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_array($result)){

     $arr33[]= $row['ItemName'];
     $arr44[]= $row['sqty'];
    }
}

$sql="select ItemName,Sum(Qty) as sqty from tblsale 
where month(Date)=month(Now())-1 and year(Date)=year(Now()) 
group by ItemName Order by Sum(Qty) desc Limit 10";
$result = mysqli_query($con,$sql);
$arr11=Array();
$arr22=array();
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_array($result)){

     $arr11[]= $row['ItemName'];
     $arr22[]= $row['sqty'];
    }
}

$sql="select ItemName,Sum(Qty) as sqty 
from tblordersale where Status=1 and month(Date)=month(Now())-1 
and year(Date)=year(Now()) 
group by ItemName Order by Sum(Qty) desc Limit 10";
$result = mysqli_query($con,$sql);
$arr55=Array();
$arr66=array();
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_array($result)){

     $arr55[]= $row['ItemName'];
     $arr66[]= $row['sqty'];
    }
}



?>


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



    

    //-------------
    //- BAR CHART 1-
    //-------------
    var areaChartData = {
        labels: [
            '<?php echo (isset($arr[0])?$arr[0]:'null') ?>',
            '<?php echo (isset($arr[1])?$arr[1]:'null') ?>',
            '<?php echo (isset($arr[2])?$arr[2]:'null') ?>',
            '<?php echo (isset($arr[3])?$arr[3]:'null') ?>',
            '<?php echo (isset($arr[4])?$arr[4]:'null') ?>',
            '<?php echo (isset($arr[5])?$arr[5]:'null') ?>',
            '<?php echo (isset($arr[6])?$arr[6]:'null') ?>',
            '<?php echo (isset($arr[7])?$arr[7]:'null') ?>',
            '<?php echo (isset($arr[8])?$arr[8]:'null') ?>',
            '<?php echo (isset($arr[9])?$arr[9]:'null') ?>',
        ],
        datasets: [{
                label: 'Qty',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: [
                    <?php echo (isset($arr1[0])?$arr1[0]:0) ?>,
                    <?php echo (isset($arr1[1])?$arr1[1]:0) ?>,
                    <?php echo (isset($arr1[2])?$arr1[2]:0) ?>,
                    <?php echo (isset($arr1[3])?$arr1[3]:0) ?>,
                    <?php echo (isset($arr1[4])?$arr1[4]:0) ?>,
                    <?php echo (isset($arr1[5])?$arr1[5]:0) ?>,
                    <?php echo (isset($arr1[6])?$arr1[6]:0) ?>,
                    <?php echo (isset($arr1[7])?$arr1[7]:0) ?>,
                    <?php echo (isset($arr1[8])?$arr1[8]:0) ?>,
                    <?php echo (isset($arr1[9])?$arr1[9]:0) ?>
                ]
            },

        ]
    }

    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    //var temp1 = areaChartData.datasets[1]
    //barChartData.datasets[0] = temp1
    //barChartData.datasets[1] = temp0

    var barChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
    }

    var barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
    })


    //-------------
    //- BAR CHART 2-
    //-------------
    var areaChartData2 = {
        labels: [
            '<?php echo (isset($arr11[0])?$arr11[0]:'null') ?>',
            '<?php echo (isset($arr11[1])?$arr11[1]:'null') ?>',
            '<?php echo (isset($arr11[2])?$arr11[2]:'null') ?>',
            '<?php echo (isset($arr11[3])?$arr11[3]:'null') ?>',
            '<?php echo (isset($arr11[4])?$arr11[4]:'null') ?>',
            '<?php echo (isset($arr11[5])?$arr11[5]:'null') ?>',
            '<?php echo (isset($arr11[6])?$arr11[6]:'null') ?>',
            '<?php echo (isset($arr11[7])?$arr11[7]:'null') ?>',
            '<?php echo (isset($arr11[8])?$arr11[8]:'null') ?>',
            '<?php echo (isset($arr11[9])?$arr11[9]:'null') ?>',
        ],
        datasets: [{
                label: 'Qty',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: [
                    <?php echo (isset($arr22[0])?$arr22[0]:0) ?>,
                    <?php echo (isset($arr22[1])?$arr22[1]:0) ?>,
                    <?php echo (isset($arr22[2])?$arr22[2]:0) ?>,
                    <?php echo (isset($arr22[3])?$arr22[3]:0) ?>,
                    <?php echo (isset($arr22[4])?$arr22[4]:0) ?>,
                    <?php echo (isset($arr22[5])?$arr22[5]:0) ?>,
                    <?php echo (isset($arr22[6])?$arr22[6]:0) ?>,
                    <?php echo (isset($arr22[7])?$arr22[7]:0) ?>,
                    <?php echo (isset($arr22[8])?$arr22[8]:0) ?>,
                    <?php echo (isset($arr22[9])?$arr22[9]:0) ?>
                ]
            },

        ]
    }

    var barChartCanvas2 = $('#barChart2').get(0).getContext('2d')
    var barChartData2 = $.extend(true, {}, areaChartData2)
    var temp2 = areaChartData2.datasets[0]
    //var temp1 = areaChartData.datasets[1]
    //barChartData.datasets[0] = temp1
    //barChartData.datasets[1] = temp0

    var barChartOptions2 = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
    }

    var barChart2 = new Chart(barChartCanvas2, {
        type: 'bar',
        data: barChartData2,
        options: barChartOptions2
    })

    //-------------
    //- BAR CHART 3-
    //-------------
    var areaChartData3 = {
        labels: [
            '<?php echo (isset($arr33[0])?$arr33[0]:'null') ?>',
            '<?php echo (isset($arr33[1])?$arr33[1]:'null') ?>',
            '<?php echo (isset($arr33[2])?$arr33[2]:'null') ?>',
            '<?php echo (isset($arr33[3])?$arr33[3]:'null') ?>',
            '<?php echo (isset($arr33[4])?$arr33[4]:'null') ?>',
            '<?php echo (isset($arr33[5])?$arr33[5]:'null') ?>',
            '<?php echo (isset($arr33[6])?$arr33[6]:'null') ?>',
            '<?php echo (isset($arr33[7])?$arr33[7]:'null') ?>',
            '<?php echo (isset($arr33[8])?$arr33[8]:'null') ?>',
            '<?php echo (isset($arr33[9])?$arr33[9]:'null') ?>',
        ],
        datasets: [{
                label: 'Qty',
                backgroundColor: 'rgba(40,167,69)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: [
                    <?php echo (isset($arr44[0])?$arr44[0]:0) ?>,
                    <?php echo (isset($arr44[1])?$arr44[1]:0) ?>,
                    <?php echo (isset($arr44[2])?$arr44[2]:0) ?>,
                    <?php echo (isset($arr44[3])?$arr44[3]:0) ?>,
                    <?php echo (isset($arr44[4])?$arr44[4]:0) ?>,
                    <?php echo (isset($arr44[5])?$arr44[5]:0) ?>,
                    <?php echo (isset($arr44[6])?$arr44[6]:0) ?>,
                    <?php echo (isset($arr44[7])?$arr44[7]:0) ?>,
                    <?php echo (isset($arr44[8])?$arr44[8]:0) ?>,
                    <?php echo (isset($arr44[9])?$arr44[9]:0) ?>
                ]
            },

        ]
    }

    var barChartCanvas3 = $('#barChart3').get(0).getContext('2d')
    var barChartData3 = $.extend(true, {}, areaChartData3)
    var temp3 = areaChartData3.datasets[0]
    //var temp1 = areaChartData.datasets[1]
    //barChartData.datasets[0] = temp1
    //barChartData.datasets[1] = temp0

    var barChartOptions3 = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
    }

    var barChart3 = new Chart(barChartCanvas3, {
        type: 'bar',
        data: barChartData3,
        options: barChartOptions3
    })

    //-------------
    //- BAR CHART 2-
    //-------------
    var areaChartData4 = {
        labels: [
            '<?php echo (isset($arr55[0])?$arr55[0]:'null') ?>',
            '<?php echo (isset($arr55[1])?$arr55[1]:'null') ?>',
            '<?php echo (isset($arr55[2])?$arr55[2]:'null') ?>',
            '<?php echo (isset($arr55[3])?$arr55[3]:'null') ?>',
            '<?php echo (isset($arr55[4])?$arr55[4]:'null') ?>',
            '<?php echo (isset($arr55[5])?$arr55[5]:'null') ?>',
            '<?php echo (isset($arr55[6])?$arr55[6]:'null') ?>',
            '<?php echo (isset($arr55[7])?$arr55[7]:'null') ?>',
            '<?php echo (isset($arr55[8])?$arr55[8]:'null') ?>',
            '<?php echo (isset($arr55[9])?$arr55[9]:'null') ?>',
        ],
        datasets: [{
                label: 'Qty',
                backgroundColor: 'rgba(40,167,69)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: [
                    <?php echo (isset($arr66[0])?$arr66[0]:0) ?>,
                    <?php echo (isset($arr66[1])?$arr66[1]:0) ?>,
                    <?php echo (isset($arr66[2])?$arr66[2]:0) ?>,
                    <?php echo (isset($arr66[3])?$arr66[3]:0) ?>,
                    <?php echo (isset($arr66[4])?$arr66[4]:0) ?>,
                    <?php echo (isset($arr66[5])?$arr66[5]:0) ?>,
                    <?php echo (isset($arr66[6])?$arr66[6]:0) ?>,
                    <?php echo (isset($arr66[7])?$arr66[7]:0) ?>,
                    <?php echo (isset($arr66[8])?$arr66[8]:0) ?>,
                    <?php echo (isset($arr66[9])?$arr66[9]:0) ?>
                ]
            },

        ]
    }

    var barChartCanvas4 = $('#barChart4').get(0).getContext('2d')
    var barChartData4 = $.extend(true, {}, areaChartData4)
    var temp4 = areaChartData4.datasets[0]
    //var temp1 = areaChartData.datasets[1]
    //barChartData.datasets[0] = temp1
    //barChartData.datasets[1] = temp0

    var barChartOptions4 = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
    }

    var barChart4 = new Chart(barChartCanvas4, {
        type: 'bar',
        data: barChartData4,
        options: barChartOptions4
    })



});
</script>