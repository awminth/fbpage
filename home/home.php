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
                    <h1>DashBoard</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <?php 
        $dt = date("Y-m-d");
        $sql="select sum(Total) as stotal from tblvoucher where Date(Date)=Date('$dt')";
        $result = mysqli_query($con,$sql);
        $row = mysqli_fetch_array($result);

        $sql="select sum(TotalAmt) as stotal from tblordervoucher where Status=1 and Date(Date)=Date('$dt')";
        $result = mysqli_query($con,$sql);
        $roww = mysqli_fetch_array($result);


        $sql="select Sum(s.Qty*r.PurchasePrice) as rtotal from tblsale s,tblremain r where r.AID=s.RemainID and Date(s.Date)=Date('$dt')";
        $result = mysqli_query($con,$sql);       
        $row1 = mysqli_fetch_array($result);

        $sql="select Sum(Total) as rtotal from tblordervoucher   
        where Date(Date)=Date('$dt')";
        $result = mysqli_query($con,$sql);       
        $row11 = mysqli_fetch_array($result);

        $sql="select Sum(Amount) as etotal from tblexpense where Date(Date)=Date('$dt')";
        $result = mysqli_query($con,$sql);
        $row2 = mysqli_fetch_array($result);

        $profit=($row['stotal']+$roww['stotal'])-($row1['rtotal']+$row11['rtotal']+$row2['etotal']); 


        $arr=Array('အရောင်းစုစုပေါင်း','အဝယ်စုစုပေါင်း','အခြားအသုံးစရိတ်','အမြတ်စုစုပေါင်း');
        $arr1=array($row['stotal']+$roww['stotal'],$row1['rtotal']+$row11['rtotal'],$row2['etotal'],$profit);
       
?>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline p-2">
                        <h4>Quick Link</h4>
                        <h3 class="card-title">
                            <a href="<?=roothtml.'pos/pos.php' ?>" class="btn btn-app">
                                <i class="fas fa-table"></i> POS
                            </a>
                            <a href="<?=roothtml.'item/purchase.php' ?>" class="btn btn-app">
                                <i class="fas fa-list"></i> အဝယ်
                            </a>
                            <a href="<?=roothtml.'sell/selllist.php' ?>" class="btn btn-app">
                                <i class="fas fa-shopping-cart"></i> အရောင်း
                            </a>
                            <a href="<?=roothtml.'order/orderlist.php' ?>" class="btn btn-app" style="display:none;">
                                <i class="fas fa-shipping-fast"></i> အော်ဒါ
                            </a>
                            <a href="<?=roothtml.'report/monthreport.php' ?>" class="btn btn-app">
                                <i class="fas fa-bar-chart-o"></i> အစီရင်ခံစာ
                            </a>
                            <a href="<?=roothtml.'setting/usercontrol.php' ?>" class="btn btn-app">
                                <i class="fas fa-cog"></i> Setting
                            </a>
                            <a href="<?=roothtml.'setting/printsetting.php' ?>" class="btn btn-app">
                                <i class="fas fa-print"></i> Print Setting
                            </a>
                            <a href="<?=roothtml.'expense/expense.php' ?>" class="btn btn-app">
                                <i class="fas fa-dollar"></i> Expense
                            </a>
                            <a href="<?=roothtml.'customer/customer.php' ?>" class="btn btn-app">
                                <i class="fas fa-users"></i> Customer
                            </a>
                            <a href="<?=roothtml.'message/message.php' ?>" class="btn btn-app"  style="display:none;">
                                <i class="fas fa-comments"></i> Message
                            </a>
                            <a href="<?=roothtml.'item/notiremain.php' ?>" class="btn btn-app">
                                <i class="fas fa-bullhorn"></i> Noti Alert
                            </a>
                        </h3>

                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary card-outline p-2">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">ယနေ့အရောင်း</h3>
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
                                <canvas id="donutChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary card-outline p-2">
                        <h5>ယနေ့အရောင်း</h5><br><br>
                        <table class="table table-bordered" style="width:100%">
                            <tbody>
                                <tr>
                                    <td><?=$arr[0] ?> </td>
                                    <td class="text-right"><?=number_format($arr1[0]) ?> MMK</td>
                                </tr>
                                <tr>
                                    <td><?=$arr[1] ?> </td>
                                    <td class="text-right"><?=number_format($arr1[1]) ?> MMK</td>
                                </tr>
                                <tr>
                                    <td><?=$arr[2] ?> </td>
                                    <td class="text-right"><?=number_format($arr1[2]) ?> MMK</td>
                                </tr>
                                <tr>
                                    <td><?=$arr[3] ?> </td>
                                    <td class="text-right"><?=number_format($arr1[3]) ?> MMK</td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>
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

    $(document).on("click", "#showprint", function() {
        $("#btnnewmodal").modal("show");
    });

    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData = {
        labels: [
            '<?=(isset($arr[0])?$arr[0]:'null')?>',
            '<?=(isset($arr[1])?$arr[1]:'null')?>',
            '<?=(isset($arr[2])?$arr[2]:'null')?>',
            '<?=(isset($arr[3])?$arr[3]:'null')?>',
        ],
        datasets: [{
            data: [
                <?=(isset($arr1[0])?$arr1[0]:0)?>,

                <?=(isset($arr1[1])?$arr1[1]:0)?>,

                <?=(isset($arr1[2])?$arr1[2]:0)?>,

                <?=(isset($arr1[3])?$arr1[3]:0)?>
            ],
            backgroundColor: ['#00a65a', '#f39c12', '#f56954', '#00c0ef'],
        }]
    }
    var donutOptions = {
        maintainAspectRatio: false,
        responsive: true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChart = new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
    });





});
</script>