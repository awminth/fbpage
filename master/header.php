<?php 
if(isset($_SESSION['eadmin_userid'])){
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font: Source Sans Pro -->

    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/fontawesome-free/css/all.min.css' ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css' ?>">
    <link rel="stylesheet"
        href="<?php echo roothtml.'lib/plugins/datatables-responsive/css/responsive.bootstrap4.min.css' ?>">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/overlayScrollbars/css/OverlayScrollbars.min.css' ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/dist/css/adminlte.min.css' ?>">
    <link rel="stylesheet" href="<?php echo roothtml.'lib/dist/font-awesome.min.css' ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/select2/css/select2.min.css' ?>">
    <link rel="stylesheet"
        href="<?php echo roothtml.'lib/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ?>">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="<?php echo roothtml.'lib/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css' ?>">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet"
        href="<?php echo roothtml.'lib/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css' ?>">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/bs-stepper/css/bs-stepper.min.css' ?>">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/dropzone/min/dropzone.min.css' ?>">

    <link rel="stylesheet" href="<?php echo roothtml.'lib/animate.min.css' ?>">
    <title>ECOMMERCE Admin Dashboard</title>
    <!-- summernote -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/summernote/summernote-bs4.min.css' ?>">

    <!-- jQuery UI -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/jquery-ui/jquery-ui.css'?>" />
    <script src="<?php echo roothtml.'lib/plugins/jquery-ui/jquery-ui.min.js'?>"></script>

    <!-- Sweet Alarm -->
    <link href="<?php echo roothtml.'lib/sweet/sweetalert.css' ?>" rel="stylesheet" />
    <script src="<?php echo roothtml.'lib/sweet/sweetalert.min.js' ?>"></script>
    <script src="<?php echo roothtml.'lib/sweet/sweetalert.js' ?>"></script>

    <link rel="shortcut icon" href="<?php echo roothtml.'lib/images/icon.png' ?>" />
    <link href="<?php echo roothtml.'lib/print.min.css' ?>" rel="stylesheet" />

    <style>
    #logo {
        background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo roothtml.'images/icon.png' ?>');
        /* Used if the image is unavailable */
        height: 550px;
        /* You must set a specified height */
        background-position: center;
        /* Center the image */
        background-repeat: no-repeat;
        /* Do not repeat the image */
        background-size: cover;
        /* Resize the background image to cover the entire container */

    }

    .loader {
        position: fixed;
        z-index: 999;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        background-color: Black;
        filter: alpha(opacity=60);
        opacity: 0.7;
        -moz-opacity: 0.8;
    }

    .center-load {
        z-index: 1000;
        margin: 300px auto;
        padding: 10px;
        width: 130px;
        background-color: black;
        border-radius: 10px;
        filter: 1;
        -moz-opacity: 1;
    }

    .center-load img {
        height: 128px;
        width: 128px;
    }

    .bgactive {
        background-color: RGB(73, 78, 83);
    }
    </style>

</head>

<body class="hold-transition sidebar-mini <?php echo (curlink == 'pos.php')?'sidebar-collapse' : 'layout-fixed' ?> ">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand fixed-top navbar-<?=$color?> navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars text-white"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a data-toggle="tooltip" data-placement="bottom" title="Home"
                        href="<?php echo roothtml.'home/home.php' ?>" class="nav-link text-white">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a data-toggle="tooltip" data-placement="bottom" title="POS"
                        href="<?php echo roothtml.'pos/pos.php' ?>" target="_blank" class="nav-link text-white">POS</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a data-toggle="tooltip" data-placement="bottom" title="ဘားကုဒ်ထုတ်ရန်"
                        href="<?php echo roothtml.'item/barcode.php' ?>" class="nav-link text-white"><i
                            class="fas fa-barcode"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a data-toggle="tooltip" data-placement="bottom" title="Print Setting"
                        href="<?php echo roothtml.'setting/printsetting.php' ?>" class="nav-link text-white"><i
                            class="fas fa-cogs"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a data-toggle="tooltip" data-placement="bottom" id="btntoday" title="ယနေ့အရောင်း" href="#"
                        class="nav-link text-red"><i class="far fa-money-bill-alt text-white"></i>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->

                <?php 
                        $sqlremain="select AID as caid from tblremain where Qty<=3";
                        $result=mysqli_query($con,$sqlremain);
                        if(mysqli_num_rows($result)>0){
                              $row=mysqli_num_rows($result);
                        ?>
                <li class="nav-item d-none d-sm-inline-block">
                    <a data-toggle="tooltip" data-placement="bottom" title="လက်ကျန် ၃ ခုနှင့်အောက်စာရင်း"
                        href="<?php echo roothtml.'item/notiremain.php' ?>" class="nav-link text-red"><i
                            class="fas fa-bullhorn text-white"></i>
                        <span class="badge badge-warning navbar-badge"><?php echo $row ?></span>
                    </a>
                </li>

                <?php } ?>

                <?php 
                        $sqlmessage="select AID from tblmessage where Status=0";
                        $result1=mysqli_query($con,$sqlmessage);
                        if(mysqli_num_rows($result1)>0){
                              $rowmes=mysqli_num_rows($result1);


                        ?>


                <li class="nav-item d-none d-sm-inline-block">
                    <a data-toggle="tooltip" data-placement="bottom" title="Message"
                        href="<?php echo roothtml.'message/message.php' ?>" class="nav-link text-red"><i
                            class="fas fa-comments text-white"></i>
                        <span class="badge badge-warning navbar-badge"><?php echo $rowmes ?></span>
                    </a>
                </li>

                <?php } ?>

                <div id="show_order_count"></div>

                <?php 
                        $sqlpause="select PVNO from tblpausevoucher group by PVNO";
                        $result=mysqli_query($con,$sqlpause);
                        if(mysqli_num_rows($result)>0){
                              $row=mysqli_num_rows($result);


                        ?>

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-bell text-white"></i>
                        <span class="badge badge-warning navbar-badge"><?php echo $row ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">ရပ်ဆိုင်းထားသော စာရင်များ</span>
                        <div class="dropdown-divider"></div>
                        <?php 

                                    $sqlpause="select Sum(Qty) as sqty,PName,Date,PVNO from tblpausevoucher group by PVNO order by AID desc limit 10";
                                    $result=mysqli_query($con,$sqlpause);
                                    if(mysqli_num_rows($result)>0){
                                        while($row=mysqli_fetch_array($result)){

                                    ?>

                        <a href="#" id="btngopos" data-vno="<?php echo $row['PVNO'] ?>" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> <?php echo $row['PName'] ?>(<?php echo $row['sqty'] ?>)
                            <span class="float-right text-muted text-sm"><?php echo $row['Date'] ?></span>
                        </a>
                        <div class="dropdown-divider"></div>

                        <?php }} ?>
                        <a href="<?php echo roothtml.'sell/pause.php' ?>" class="dropdown-item dropdown-footer">See
                            All</a>
                    </div>
                </li>

                <?php } ?>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img class="img-circle img-sm" src="<?php echo roothtml.'lib/images/img.jpg' ?>"
                            alt="Profile">&nbsp;&nbsp;

                        <span
                            class="text-white"><?php echo isset($_SESSION['eadmin_username'])?$_SESSION['eadmin_username'] : ''; ?>
                            ( <?php echo $_SESSION['eadmin_usertype'] ?> )</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="bg-<?=$color?> text-center p-1 m-1">
                            <img class="img-circle" src="<?php echo roothtml.'lib/images/img.jpg' ?>" alt="Profile">
                            <p class="text-white">
                                <?php echo isset($_SESSION['eadmin_username'])?$_SESSION['eadmin_username'] : ''; ?>
                                ( <?php echo $_SESSION['eadmin_usertype'] ?> )</p>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="float-left m-2">
                            <a href="<?php echo roothtml.'profile/profile.php' ?>" class="btn btn-<?=$color?> ">
                                <i class="fas fa-user text-white"></i>&nbsp;Profile</a>
                        </div>
                        <div class="float-right m-2">
                            <a href="#" id="btnlogout" class="btn btn-<?=$color?> ">
                                <i class="fas fa-sign-out-alt text-white"></i>&nbsp;Logout</a>
                        </div>
                    </div>
                </li>



                <?php if(curlink == 'pos.php'){ ?>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-folder text-white"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">အမျိုးအစားဖြင့်ရှာရန်</span>
                        <div class="input-group input-group-sm p-1">
                            <input class="form-control" id="catsearch" type="search" placeholder="Search"
                                aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div id="asearch">
                            <?php
                            $sql = "select * from tblcategory";
                            $result=mysqli_query($con,$sql);
                            if(mysqli_num_rows($result)>0){
                                while($row = mysqli_fetch_array($result)){
                        ?>

                            <a href="#" id="cat_click" data-aid="<?php echo $row['AID'] ?>" class="dropdown-item">
                                <i class="fas fa-folder mr-2" style="font-size:25px;"></i><?php echo $row["Category"] ?>

                            </a>
                            <?php
                                }
                            ?>
                        </div>
                        <?php
                            }

                        ?>

                    </div>
                </li>
                <?php } ?>

            </ul>
        </nav>
        <!-- /.navbar -->


        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-success elevation-4">
            <!-- Brand Logo -->
            <a href="<?php echo roothtml.'home/home.php' ?>" class="brand-link bg-<?=$color?>">
                <img src="<?php echo roothtml.'lib/images/icon.png' ?>" alt="AdminLTE Logo"
                    class="brand-image elevation-3" style="opacity: .8;width: 50px;">
                <span class="brand-text font-weight-light">BRIGHTWARE POS</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="<?php echo roothtml.'home/home.php' ?>"
                                class="nav-link <?php echo (curlink == 'home.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <p class="nav-link text-white">Pre Order</p>
                            <hr style="background-color:white; margin:0px; padding:0px;">
                        </li>
                        <li
                            class="nav-item <?php echo (curlink == 'pause.php' || curlink=='selllist.php' || 
                            curlink=='cashsell.php' || curlink=='creditsell.php' || curlink=='sellreturn.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>
                                    Pre Order
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'preorder/createpreorder.php' ?>"
                                        class="nav-link <?php echo (curlink == 'createpreorder.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Create Pre Order</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'preorder/preorderconfirm.php' ?>"
                                        class="nav-link <?php echo (curlink == 'preorderconfirm.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Pre Order Confirm</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'sell/creditsell.php' ?>"
                                        class="nav-link <?php echo (curlink == 'creditsell.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Pre Order Cancel</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'sell/sellreturn.php' ?>"
                                        class="nav-link <?php echo (curlink == 'sellreturn.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Pre Order Return</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item">
                            <p class="nav-link text-white">In Stock</p>
                            <hr style="background-color:white; margin:0px; padding:0px;">
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo roothtml.'pos/pos.php' ?>" target="_blank"
                                class="nav-link <?php echo (curlink == 'pos.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                    POS
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item <?php echo (curlink == 'purchase.php' || curlink=='remain.php' || 
                            curlink=='supplierinout.php' || curlink=='purchasereturnview.php' || curlink=='purchasereturn.php' || curlink=='remaindetail.php' || curlink=='barcode.php' || curlink=='notiremain.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    အဝယ်
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'item/purchase.php' ?>"
                                        class="nav-link <?php echo (curlink == 'purchase.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>အဝယ်စာရင်း</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'item/remain.php' ?>"
                                        class="nav-link <?php echo (curlink == 'remain.php' || curlink=='remaindetail.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>လက်ကျန်စာရင်း</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'item/supplierinout.php' ?>"
                                        class="nav-link <?php echo (curlink == 'supplierinout.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Supplier In/Out</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'item/purchasereturnview.php' ?>"
                                        class="nav-link <?php echo (curlink == 'purchasereturn.php' || curlink == 'purchasereturnview.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Purchase Return</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'item/notiremain.php' ?>"
                                        class="nav-link <?php echo (curlink == 'notiremain.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>သတိပေးချက်</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'item/barcode.php' ?>"
                                        class="nav-link <?php echo (curlink == 'barcode.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>ဘားကုဒ်ထုတ်ရန်</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item <?php echo (curlink == 'pause.php' || curlink=='selllist.php' || 
                            curlink=='cashsell.php' || curlink=='creditsell.php' || curlink=='sellreturn.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>
                                    အရောင်း
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item" style="display:none;">
                                    <a href="<?php echo roothtml.'sell/selllist.php' ?>"
                                        class="nav-link <?php echo (curlink == 'selllist.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>အရောင်းစာရင်း</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'sell/cashsell.php' ?>"
                                        class="nav-link <?php echo (curlink == 'cashsell.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Cash အရောင်းစာရင်း</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'sell/creditsell.php' ?>"
                                        class="nav-link <?php echo (curlink == 'creditsell.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Credit အရောင်းစာရင်း</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'sell/sellreturn.php' ?>"
                                        class="nav-link <?php echo (curlink == 'sellreturn.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Sale Return</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'sell/pause.php' ?>"
                                        class="nav-link <?php echo (curlink == 'pause.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>ရပ်ဆိုင်းထားသောစာရင်း</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li style="display:none;"
                            class="nav-item <?php echo (curlink == 'orderlist.php' || curlink=='orderconfirm.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-shipping-fast"></i>
                                <p>
                                    အော်ဒါ
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'order/orderlist.php' ?>"
                                        class="nav-link <?php echo (curlink == 'orderlist.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>အော်ဒါစာရင်း</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'order/orderconfirm.php' ?>"
                                        class="nav-link <?php echo (curlink == 'orderconfirm.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Order History</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item <?php echo (curlink=='salereturnreport.php' || 
                            curlink=='creditreport.php' || curlink=='orderreport.php' || curlink=='sellgraph1.php' || curlink=='voucherreport.php' ||curlink == 'todayreport.php' || curlink=='monthreport.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-bar-chart-o"></i>
                                <p>
                                    အစီရင်ခံစာများ
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'report/monthreport.php' ?>"
                                        class="nav-link <?php echo (curlink == 'monthreport.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>လစဉ်အရောင်း</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'report/voucherreport.php' ?>"
                                        class="nav-link <?php echo (curlink == 'voucherreport.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Cash Report</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'report/creditreport.php' ?>"
                                        class="nav-link <?php echo (curlink == 'creditreport.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Credit Report</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'report/salereturnreport.php' ?>"
                                        class="nav-link <?php echo (curlink == 'salereturnreport.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Sale Return Report</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'report/orderreport.php' ?>"
                                        class="nav-link <?php echo (curlink == 'orderreport.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>အော်ဒါအစီရင်ခံစာ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'report/sellgraph1.php' ?>"
                                        class="nav-link <?php echo (curlink == 'sellgraph1.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>ထိပ်တန်းကုန်ပစ္စည်းဇယား</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item <?php echo (curlink=='printsetting.php' || curlink=='backup.php' || curlink == 'usercontrol.php' || curlink == 'category.php' ||
                        curlink == 'supplier.php' || curlink == 'log.php' || curlink == 'backup.php' || 
                        curlink == 'showheading.php')?'menu-open' : '' ?>">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Setting
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'setting/usercontrol.php' ?>"
                                        class="nav-link <?php echo (curlink == 'usercontrol.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>User Control</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'setting/category.php' ?>"
                                        class="nav-link <?php echo (curlink == 'category.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>အမျိုးအစား</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'setting/supplier.php' ?>"
                                        class="nav-link <?php echo (curlink == 'supplier.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Company</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'setting/log.php' ?>"
                                        class="nav-link <?php echo (curlink == 'log.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Log History</p>
                                    </a>
                                </li>
                                <li class="nav-item" style="display:none;">
                                    <a href="<?php echo roothtml.'setting/backup.php' ?>"
                                        class="nav-link <?php echo (curlink == 'backup.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Back Up</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'setting/printsetting.php' ?>"
                                        class="nav-link <?php echo (curlink == 'printsetting.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Print Setting</p>
                                    </a>
                                </li>
                                <li class="nav-item" style="display:none;">
                                    <a href="<?php echo roothtml.'setting/showheading.php' ?>"
                                        class="nav-link <?php echo (curlink == 'showheading.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Show Heading</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <p class="nav-link text-white">
                                Expense
                            </p>
                            <hr style="background-color:white; margin:0px; padding:0px;">
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo roothtml.'expense/expense.php' ?>"
                                class="nav-link <?php echo (curlink == 'expense.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-dollar"></i>
                                <p>
                                    Expense
                                </p>
                            </a>
                        </li>

                        <li
                            class="nav-item <?php echo (curlink == 'customer.php' || curlink=='customerinout.php')?'menu-open' : '' ?>">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Customer
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'customer/customer.php' ?>"
                                        class="nav-link <?php echo (curlink == 'customer.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Customer</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'customer/customerinout.php' ?>"
                                        class="nav-link <?php echo (curlink == 'customerinout.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Customer In/Out</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item" style="display:none;">
                            <a href="<?php echo roothtml.'message/message.php' ?>"
                                class="nav-link <?php echo (curlink == 'message.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>
                                    Message
                                </p>
                            </a>
                        </li>



                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <br><br>

        <div class="loader" style="display:none;">
            <div class="center-load">
                <img src="<?php echo roothtml.'lib/images/ajax-loader1.gif'; ?>" />
            </div>
        </div>


        <div class="modal fade" id="modaltoday">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-<?=$color?>">
                        <h4 class="modal-title">ယနေ့အရောင်းကိုကြည့်ရန်</h4>
                        <div class="float-right m-2">
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            <a id="btnprintvoucher" class="text-white" style="float:right;"><i class="fas fa-print"
                                    style="font-size:20px;"></i></a>

                        </div>
                    </div>

                    <div id="frmtoday" class="container  modal-body">

                    </div>
                </div>
            </div>
        </div>


        <?php }else{  

header("location:". roothtml."errorpage.php");   
      
}
?>