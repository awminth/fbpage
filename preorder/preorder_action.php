<?php
include('../config.php');

$action = $_POST['action'];
$userid = $_SESSION["eadmin_userid"];

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
    '".$dt."','".$vno."'";
    if(mysqli_query($con,$sql)){
        $sql_voucher = "INSERT INTO tblpreordervoucher (VNO,CustomerName,TotalQty,TotalAmt,Dis,Total,UserID,Refund,Date) 
        VALUES ('{$vno}','{$customername}','{$totalqty}','{$totalamt}','{$disc}','{$total}','{$userid}','{$change}','{$dt}')";
        if(mysqli_query($con,$sql_voucher)){
            $sqldel_temp = "DELETE FROM tblpreordersale_temp WHERE UserID='{$userid}'";
            if(mysqli_query($con,$sqldel_temp)){
                save_log($_SESSION['eadmin_username']."သည် Preorderအသစ်တင်သွားသည်");
                echo 1;
            }
            else{
                echo 0;
            }
        }
        else{
            echo 0;
        }
    }
    else{
        echo 0;
    }
}

?>