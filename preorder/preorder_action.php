<?php
include('../config.php');

$action = $_POST['action'];
$userid = $_SESSION["eadmin_userid"];

// Create Pre Order Page
if($action == "showcreate"){
    $no = 0;
    $totalpriceshow = 0;
    $sql = "SELECT * FROM  tblpreordersale_temp ORDER BY AID DESC";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result)>0){
        $out.='
        <div class="table-responsive-sm">
            <table class="table table-bordered table-striped responsive nowrap">
                <thead>
                    <tr>
                        <th width="7%;">No</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Qty</th>
                        <th>SellPrice/Unit</th>
                        <th>TotalPrice</th>
                        <th width="10%;" class="text-center">Action</th>               
                    </tr>
                </thead>
                <tbody>
        ';
        while($row=mysqli_fetch_array($result)){
            $no += 1;
            $out .= "
                <td>{$no}</td>
                <td>{$row["CodeNo"]}</td>
                <td>{$row["ItemName"]}</td>
                <td>".number_format($row["Qty"])."</td>
                <td>".number_format($row["SellPrice"])."</td>
                <td>".number_format($row["TotalPrice"])."</td>
                <td class='text-center'>
                    <div class='btn-group btn-group-sm'>
                        <a href='#' id='btnedittemp' class='btn btn-success btn-sm'
                            data-aid='{$row['AID']}'
                            data-itemcode='{$row['CodeNo']}'
                            data-itemname='{$row['ItemName']}'
                            data-qty='{$row['Qty']}'
                            data-sellpriceperunit='{$row['SellPrice']}'
                            data-totalprice='{$row['TotalPrice']}'><i
                            class='fas fa-edit'
                            style='font-size:15px;'></i>
                        </a>
                        <a href='#' id='btndeletetemp' class='btn btn-danger btn-sm'
                            data-aid='{$row['AID']}'><i
                            class='fas fa-trash'
                            style='font-size:15px;'></i>
                        </a>
                    </div>
                </td>
            </tr>
            ";
            $totalpriceshow += $row["TotalPrice"];
        }
        $out.="
            <tr>
                <td colspan='5' class='text-center text-primary'>TotalPrice To Pay</td>
                <td colspan='2' id='totalpriceshow' data-total=".$totalpriceshow.">".number_format($totalpriceshow)."</td>
            </tr>
        ";
        $out.="</tbody>";
        $out.="</table></div>";
        echo $out;
    }
}

if($action == "savetemp"){
    $eaid = $_POST["eaid"];
    $itemcode = $_POST["itemcode"];
    $itemname = $_POST["itemname"];
    $qty = $_POST["qty"];
    $sellpriceperunit = $_POST["sellpriceperunit"];
    $totalprice = $_POST["totalprice"];
    if($eaid != 0){
        //Edit Condition
        $data_edit = [
            "CodeNo" => $itemcode,
            "ItemName" => $itemname,
            "Qty" => $qty,
            "SellPrice" => $sellpriceperunit,
            "TotalPrice" => $totalprice
        ];
        $where = [
            "AID" => $eaid
        ];
        $result_edit = updateData_Fun("tblpreordersale_temp",$data_edit,$where);
        if($result_edit){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    else{
        //Save Condition
        $data = [
            "CodeNo" => $itemcode,
            "ItemName" => $itemname,
            "Qty" => $qty,
            "SellPrice" => $sellpriceperunit,
            "TotalPrice" => $totalprice,
            "UserID" => $userid
        ];
        $result = insertData_Fun("tblpreordersale_temp",$data);
        if($result){
            echo 1;
        }
        else{
            echo 0;
        }
    }
}

if($action == "deletetemp"){
    $aid = $_POST["aid"];
    $where = [
        "AID" => $aid
    ];
    $result = deleteData_Fun("tblpreordersale_temp",$where);
    if($result){
        echo 1;
    }
    else{
        echo 0;
    }
}

if($action == "save"){
    $totalamt = $_POST["pretotalprice"];
    $total = $_POST["finaltotalprice"];
    $disc = $_POST["disc"];
    $payamt = $_POST["payamt"];
    $change = $_POST["change"];
    $customername = $_POST["customername"];
    $address = $_POST["address"];
    $phoneno = $_POST["phoneno"];
    $dt = $_POST["dt"];
    $vno = date("Ymd-His");
    $totalqty = GetInt("SELECT SUM(Qty) FROM tblpreordersale_temp WHERE AID IS NOT NULL");
    //Insert Sale First
    $sql = "INSERT INTO tblpreordersale (CodeNo,ItemName,Qty,SellPrice,TotalPrice,CustomerName,Address,PhoneNo,
    Date,VNO) SELECT CodeNo,ItemName,Qty,SellPrice,TotalPrice,'".$customername."','".$address."','".$phoneno."',
    '".$dt."','".$vno."' FROM tblpreordersale_temp WHERE UserID='{$userid}'";
    if(mysqli_query($con,$sql)){
        $sql_voucher = "INSERT INTO tblpreordervoucher (VNO,CustomerName,TotalQty,TotalAmt,Dis,Total,UserID,Cash,Refund,Date) 
        VALUES ('{$vno}','{$customername}','{$totalqty}','{$totalamt}','{$disc}','{$total}','{$userid}','{$payamt}','{$change}','{$dt}')";
        if(mysqli_query($con,$sql_voucher)){
            $sqldel_temp = "DELETE FROM tblpreordersale_temp WHERE UserID='{$userid}'";
            if(mysqli_query($con,$sqldel_temp)){
                save_log($_SESSION['eadmin_username']."သည် Preorderအသစ်တင်သွားသည်");
                printVoucher($vno);
            }
            else{
                echo 2;
            }
        }
        else{
            echo 3;
        }
    }
    else{
        echo 4;
    }
}

//View Pre Order Page
if($action == 'showvieworder'){  
    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=10; 
    } else{
        $limit_per_page=$_POST['entryvalue']; 
    }
    
    $page="";
    if(isset($_POST["page_no"])){
        $page=$_POST["page_no"];
    }
    else{
        $page=1;
    }

    $offset = ($page-1) * $limit_per_page;                                               
   
    $search = $_POST['search'];
    $a = "";
    if($search != ''){  
        $a .= " and (VNO like '%$search%') ";
    } 
    $from=$_POST['from'];  
    $to=$_POST['to'];     
    $customer=$_POST['customer'];
    if($from!='' || $to!=''){
        $a .=" and Date(Date)>='{$from}' and Date(Date)<='{$to}' ";
    }  
    if($customer!=''){
        $a .=" and CustomerName='{$customer}' ";
    }   
    $sql="SELECT * FROM tblpreordervoucher WHERE AID IS NOT NULL ".$a." 
    ORDER BY AID DESC limit $offset,$limit_per_page";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered tabel-sm table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">စဉ်</th>                                       
            <th>VNO</th>
            <th>CustomerName</th>
            <th class="text-right">SubTotal</th>                                                                             
            <th>Disc</th>
            <th class="text-right">Total</th>  
            <th class="text-right">Cash</th> 
            <th class="text-right">Refund</th> 
            <th>Cashier</th>   
            <th>Date</th>    
            <th width="10%;" class="text-center">Actions</th>       
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        $total_totalamt = 0;
        $total_disc = 0;
        $total_total = 0;
        $total_cash = 0;
        $total_refund = 0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $total_totalamt += $row["TotalAmt"];
            $total_disc += $row["Dis"];
            $total_total += $row["Total"];
            $total_cash += $row["Cash"];
            $total_refund += $row["Refund"];
            $cashier = GetString("SELECT UserName FROM tbluser WHERE AID='{$row["UserID"]}'");
            $bgchk = "";
            if($row["Chk"] == "Confirm") {
                $bgchk = "success";
            }
            else if($row["Chk"] == "Return"){
                $bgchk = "warning";
            }
            else{
                $bgchk = "danger";
            }
            $out.="<tr class='bg-".$bgchk."'>
                <td>{$no}</td>
                <td>{$row["VNO"]}</td>
                <td>{$row["CustomerName"]}</td>
                <td class='text-right' >".number_format($row["TotalAmt"])."</td>                                       
                <td  class='text-right' >".number_format($row["Dis"])."</td>
                <td class='text-right' >".number_format($row["Total"])."</td>
                <td class='text-right' >".number_format($row["Cash"])."</td>
                <td class='text-right' >".number_format($row["Refund"])."</td>
                <td >{$cashier}</td> 
                <td >".enDate($row["Date"])."</td> 
                <td class='text-center'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <span class='text-primary' style='cursor:pointer;'>o o o</span>
                    </a>
                    <div class='dropdown-menu'>
					";
					$out.="
                        <a href='#' id='btnvieworder' class='dropdown-item'
                            title='အသေးစိတ်ကြည့်မည်' 
                            data-vno='{$row['VNO']}'>
                            <i class='fas fa-eye text-primary'></i>
                            View
                        </a>";
                    if($row['Chk'] != 'Confirm'){
                    $out .= "
                        <div class='dropdown-divider'></div>
                        <a href='#' id='btnconfirm' class='dropdown-item'
                            title='Confirm' 
                            data-aid='{$row['AID']}'>
                            <i class='fas fa-check text-success'></i>
                            Confirm
                        </a>";
                    }
                    if($row['Chk'] != 'Return'){
                    $out .= "
                        <div class='dropdown-divider'></div>
                        <a href='#' id='btnreturn' class='dropdown-item'
                            title='Return' 
                            data-aid='{$row['AID']}'>
                            <i class='fas fa-check text-warning'></i>
                            Return
                        </a>";
                    }
                    if($row['Chk'] != 'Cancel'){
                    $out .= "
                        <div class='dropdown-divider'></div>
                        <a href='#' id='btncancel' class='dropdown-item'
                            title='Cancel' 
                            data-aid='{$row['AID']}'>
                            <i class='fas fa-trash text-danger'></i>
                            Cancel
                        </a>";
                    }
                    $out .= "
                    </div>
                </td>
            </tr>";
        }
        $out.="</tbody>";
        $out.="<tfoot>
                    <tr>                                      
                        <td colspan='3' class='text-center'>စုစုပေါင်း</td>
                        <td class='text-right' >".number_format($total_totalamt)."</td>                                       
                        <td class='text-right'>".number_format($total_disc)."</td>
                        <td class='text-right' >".number_format($total_total)."</td>
                        <td class='text-right' >".number_format($total_cash)."</td>
                        <td class='text-right' >".number_format($total_refund)."</td>
                        <td></td>   
                        <td></td> 
                        <td></td>                                 
                    </tr>
                </tfoot>";
        $out.="</table>";

        $sql_total="SELECT * FROM tblpreordervoucher WHERE AID IS NOT NULL ".$a." 
        ORDER BY AID DESC";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>Total Records -  ';
        $out.=$total_record;
        $out.='</p></div>';

        $out.='<div class="float-right">
                <ul class="pagination">
            ';      
        
        $previous_link = '';
        $next_link = '';
        $page_link = '';

        if($total_links > 4){
            if($page < 5){
                for($count = 1; $count <= 5; $count++)
                {
                    $page_array[] = $count;
                }
                $page_array[] = '...';
                $page_array[] = $total_links;
            }else{
                $end_limit = $total_links - 5;
                if($page > $end_limit){
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for($count = $end_limit; $count <= $total_links; $count++)
                    {
                        $page_array[] = $count;
                    }
                }else{
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for($count = $page - 1; $count <= $page + 1; $count++)
                    {
                        $page_array[] = $count;
                    }
                    $page_array[] = '...';
                    $page_array[] = $total_links;
                }
            }            

        }else{
            for($count = 1; $count <= $total_links; $count++)
            {
                $page_array[] = $count;
            }
        }

        for($count = 0; $count < count($page_array); $count++){
            if($page == $page_array[$count]){
                $page_link .= '<li class="page-item active">
                                    <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                                </li>';

                $previous_id = $page_array[$count] - 1;
                if($previous_id > 0){
                    $previous_link = '<li class="page-item">
                                            <a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a>
                                    </li>';
                }
                else{
                    $previous_link = '<li class="page-item disabled">
                                            <a class="page-link" href="#">Previous</a>
                                    </li>';
                }

                $next_id = $page_array[$count] + 1;
                if($next_id > $total_links){
                    $next_link = '<li class="page-item disabled">
                                        <a class="page-link" href="#">Next</a>
                                </li>';
                }else{
                    $next_link = '<li class="page-item">
                                    <a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a>
                                </li>';
                }
            }else{
                if($page_array[$count] == '...')
                {
                    $page_link .= '<li class="page-item disabled">
                                        <a class="page-link" href="#">...</a>
                                    </li> ';
                }else{
                    $page_link .= '<li class="page-item">
                                        <a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
                                    </li> ';
                }
            }
        }

        $out .= $previous_link . $page_link . $next_link;
        $out .= '</ul></div>';
        echo $out; 
    }
    else{
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">စဉ်</th>                                       
            <th>VNO</th>
            <th>CustomerName</th>
            <th class="text-right">SubTotal</th>                                                                             
            <th>Disc</th>
            <th class="text-right">Total</th>  
            <th class="text-right">Cash</th> 
            <th class="text-right">Refund</th> 
            <th>Cashier</th>   
            <th>Date</th>    
            <th width="10%;" class="text-center">Actions</th>          
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="11" class="text-center">No data</td>
            </tr>
            </tbody>
        </table>
        ';
        echo $out;
    }
}

if($action == "viewvoucher"){
    $vno = $_POST["vno"];
    printVoucher($vno);
}

if($action == "confirm"){
    $aid = $_POST["aid"];
    $chk = "Confirm";
    $data = [
        "Chk" => $chk
    ];
    $where = [
        "AID" => $aid
    ];
    $result = updateData_Fun("tblpreordervoucher",$data,$where);
    if($result){
        save_log($_SESSION['eadmin_username']."သည် Preorderအား Confirmလုပ်သွားသည်");
        echo 1;
    }
    else{
        echo 0;
    }
}

if($action == "return"){
    $aid = $_POST["aid"];
    $chk = "Return";
    $data = [
        "Chk" => $chk
    ];
    $where = [
        "AID" => $aid
    ];
    $result = updateData_Fun("tblpreordervoucher",$data,$where);
    if($result){
        save_log($_SESSION['eadmin_username']."သည် Preorderအား Returnလုပ်သွားသည်");
        echo 1;
    }
    else{
        echo 0;
    }
}

if($action == "cancel"){
    $aid = $_POST["aid"];
    $chk = "Cancel";
    $data = [
        "Chk" => $chk
    ];
    $where = [
        "AID" => $aid
    ];
    $result = updateData_Fun("tblpreordervoucher",$data,$where);
    if($result){
        save_log($_SESSION['eadmin_username']."သည် Preorderအား Cancelလုပ်သွားသည်");
        echo 1;
    }
    else{
        echo 0;
    }
}

if($action == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){  
        $a .= " and (VNO like '%$search%') ";
    } 
    $from=$_POST['from'];  
    $to=$_POST['to'];     
    $customer=$_POST['customer'];
    if($from!='' || $to!=''){
        $a .=" and Date(Date)>='{$from}' and Date(Date)<='{$to}' ";
    }  
    if($customer!=''){
        $a .=" and CustomerName={$customer} ";
    }   
    $sql="SELECT * FROM tblpreordervoucher WHERE AID IS NOT NULL ".$a." 
    ORDER BY AID DESC";
    $cashier = GetString("SELECT UserName FROM tbluser WHERE AID='{$row["UserID"]}'");
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "PreOrder Reports".date('d-m-Y').".xls";
    $out .= '<head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="11" align="center"><h3>PreOrder Reports</h3></td>
            </tr>
            <tr><td colspan="11"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>
                <th style="border: 1px solid ;">VNO</th>
                <th style="border: 1px solid ;">CustomerName</th>
                <th style="border: 1px solid ;">SubTotal</th>                                                                             
                <th style="border: 1px solid ;">Disc</th>
                <th style="border: 1px solid ;">Total</th>  
                <th style="border: 1px solid ;">Cash</th> 
                <th style="border: 1px solid ;">Refund</th> 
                <th style="border: 1px solid ;">Cashier</th>  
                <th style="border: 1px solid ;">Status</th>    
                <th style="border: 1px solid ;">Date</th> 
            </tr>';
    if(mysqli_num_rows($result) > 0){
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["VNO"].'</td>  
                    <td style="border: 1px solid ;">'.$row["CustomerName"].'</td>
                    <td style="border: 1px solid ;">'.number_format($row["TotalAmt"]).'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Dis"]).'</td>                 
                    <td style="border: 1px solid ;">'.number_format($row["Total"]).'</td>  
                     <td style="border: 1px solid ;">'.number_format($row["Cash"]).'</td>  
                     <td style="border: 1px solid ;">'.number_format($row["Refund"]).'</td>  
                    <td style="border: 1px solid ;">'.$cashier.'</td>   
                    <td style="border: 1px solid ;">'.$row["Chk"].'</td>             
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>                 
                </tr>';
        }          
    }else{
        $out .= '
            <tr>
                <td style="border: 1px solid ;" colspan="11" align="center">No data found</td>   
            </tr>';
        
    }
    $out .= '</table>';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
}

?>