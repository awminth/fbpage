<?php 
session_start();

date_default_timezone_set("Asia/Rangoon");

define('server_name',$_SERVER['HTTP_HOST']);

if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
    $chk_link = "https";
}else{
    $chk_link = "http";
}

define('root',__DIR__.'/');
//define('rc',dirname(dirname(__FILE__)).'/');

// define('roothtml',$chk_link."://".server_name."/fbpage/");
// $con=new mysqli("65.60.39.46","kyuseinl_admin","kyoungunity*007*","kyuseinl_fbpage");

define('curlink',basename($_SERVER['SCRIPT_NAME']));

define('colortype',isset($_SESSION['color'])?$_SESSION['color']:'dark');

define('roothtml',$chk_link."://".server_name."/fbpage/");
$con=new mysqli("localhost","root","root","fbpage");

mysqli_set_charset($con,"utf8");

$paytype=array('One Pay','KBZ Pay','CB Pay','AYA Pay');
$arr_gender = array('Male','Female');
$arr_status = array('Premium','Free');
$arr_tf = array('True','False');
$arr_usertype = array('Admin','User');
$rate = array("MMK");
$color='primary';
$arr_chk = array("Cash","Credit","Return");

function GetString($sql){
    global $con;
    $str="";   
    $result=mysqli_query($con,$sql) or die("Query Fail");
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
        $str= $row[0];
    }
    return $str;
}

function load_voucher(){
    
}

function GetInt($sql){
    global $con;
    $str=0;   
    $result=mysqli_query($con,$sql) or die("Query Fail");
    if(mysqli_num_rows($result)>0){

        $row = mysqli_fetch_array($result);
       $str= $row[0];
    }
    return $str;
}

function GetBool($sql){
    global $con;
    $str=false;   
    $result=mysqli_query($con,$sql) or die("Query Fail");
    if(mysqli_num_rows($result)>0){

       $str=true;
    }
    return $str;
}

function load_user(){
    global $con;
    $sql="select * from tbluser";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["UserName"]}</option>";
    }
    return $out;
}

function load_category(){
    global $con;
    $sql="select * from tblcategory";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Category"]}</option>";
    }
    return $out;
}

function load_itemname(){
    global $con;
    $sql="select CodeNo,ItemName from tblremain";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["CodeNo"]}'>{$row["ItemName"]}</option>";
    }
    return $out;
}

function load_supplier(){
    global $con;
    $sql="select * from tblsupplier";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Supplier"]}</option>";
    }
    return $out;
}

function load_customer(){
    global $con;
    $sql="select * from tblcustomer";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_customername(){
    global $con;
    $sql="select * from tblcustomer";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["Name"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function kill_all_session(){
    unset($_SESSION["eadmin_userid"]);
    unset($_SESSION["eadmin_username"]);                  
    unset($_SESSION["eadmin_usertype"]);
    unset($_SESSION["eadmin_userpassword"]);
    unset($_SESSION["shopname"]);
    unset($_SESSION["shopaddress"]);                  
    unset($_SESSION["shopphno"]);
    unset($_SESSION["shopemail"]); 
    unset($_SESSION['totalamt']);
    unset($_SESSION['totalqty']);
    unset($_SESSION['editsalevno']);
}

function save_log($des){
    global $con;
    $dt=date("Y-m-d H:i:s");
    $userid=$_SESSION['eadmin_userid'];
    $sql="insert into tbllog (Description,UserID,Date) values ('{$des}'
    ,$userid,'{$dt}')";
    mysqli_query($con,$sql);   
}

function NumtoText($number){
    $array = [
        '1' => 'First',
        '2' => 'Second',
        '3' => 'Third',
        '4' => 'Four',
        '5' => 'Five',
        '6' => 'Six',
        '7' => 'Seven',
        '8' => 'Eight',
        '9' => 'Nine',
        '10' => 'Ten',
    ];
    return strtr($number, $array);
}

function toMyanmar($number){
    $array = [
        '0' => '၀',
        '1' => '၁',
        '2' => '၂',
        '3' => '၃',
        '4' => '၄',
        '5' => '၅',
        '6' => '၆',
        '7' => '၇',
        '8' => '၈',
        '9' => '၉',
    ];
    return strtr($number, $array);
}

function toEnglish($number){
    $array = [
        '၀' => '0',
        '၁' => '1',
        '၂' => '2',
        '၃' => '3',
        '၄' => '4',
        '၅' => '5',
        '၆' => '6',
        '၇' => '7',
        '၈' => '8',
        '၉' => '9',
    ];
    return strtr($number, $array);
}

function mmDate($date){
    $date = date_create($date);
    $date = date_format($date,"d-m-Y");
    return toMyanmar($date);
}

function enDate($date){
    $date = date_create($date);
    $date = date_format($date,"d-m-Y");
    return $date;
}

function enDate1($date){
    $date = date_create($date);
    $date = date_format($date,"d M Y");
    return $date;
}

function enDateTime($date){
    $date = date_create($date);
    $date = date_format($date,"M, d, Y h:i A");
    return $date;
}

function enDateTime1($date){
    $date = date_create($date);
    $date = date_format($date,"F, d, Y");
    return $date;
}

function getTotalYear($date) {
    return date_diff(date_create($date), date_create('now'))->y;
}

function pretty_filesize($file) {
    //$size=filesize($file);
    $size = $file;
    if($size<1024){$size=$size." Bytes";}
    elseif(($size<1048576)&&($size>1023)){$size=round($size/1024, 1)." KB";}
    elseif(($size<1073741824)&&($size>1048575)){$size=round($size/1048576, 1)." MB";}
    else{$size=round($size/1073741824, 1)." GB";}
    return $size;
}

function control_downloadlink($file_path){
    $path = $file_path;
    header('Content-Disposition: attachment; filename="'.basename($path).'"');   
    readfile($path); 
    exit();
}

function save_supplier_detail($purchaseid,$supplier,$amt,$dt){
    global $con;
    $sql="select * from tblsupplierdetail where PurchaseID={$purchaseid}";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    if(mysqli_num_rows($result) > 0){
        $sqldel="delete from tblsupplierdetail where PurchaseID={$purchaseid}";
        mysqli_query($con,$sqldel);
    }
    $sqlsave="insert into tblsupplierdetail (PurchaseID,SupplierID,Amt,Date) values 
    ('{$purchaseid}','{$supplier}','{$amt}','{$dt}')";
    mysqli_query($con,$sqlsave);
}

function delete_supplier_detail($purchaseid){
    global $con;
    $sqldel="delete from tblsupplierdetail where PurchaseID={$purchaseid}";
    mysqli_query($con,$sqldel);
   
    
}

function purchase_return($aid,$qty){
    global $con;
    $sql="update tblremain set Qty='{$qty}' where AID={$aid}";
    mysqli_query($con,$sql);   
    
}

function insertData_Fun($table, $data) {
    global $con;
    $con->begin_transaction();
    try{
        // array ရဲ့ key name ကိုသာ ယူ
        $columns = implode(", ", array_keys($data));
        // array length ရှိသလောက် ? ကို သတ်မှတ်ပေး
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        // array ရဲ့ value ကို ယူ
        $values = array_values($data);

        // types သတ်မှတ် (i = int, d = double, s = string)
        $types = "";
        foreach ($values as $v) {
            if (is_int($v)) {
                $types .= "i";
            } elseif (is_float($v)) {
                $types .= "d";
            } else {
                $types .= "s";
            }
        }

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $con->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $con->error);
        }

        $stmt->bind_param($types, ...$values);

        $success = $stmt->execute();

        if (!$success) throw new Exception($stmt->error);
        // check row count is success
        $affected = $stmt->affected_rows;

        $con->commit();
        // return $con->insert_id; 
        return $affected > 0 ? true : false;
    } catch (Exception $e) {
        $con->rollback();
        return false;
    }    
}

function updateData_Fun($table, $data, $where) {
    global $con;
    $con->begin_transaction();
    try {
        // Step 1: Check if the AID exists first
        $whereKeys = array_keys($where);
        $whereValues = array_values($where);
        $whereClauseCheck = implode(" AND ", array_map(fn($col) => "$col=?", $whereKeys));

        $sqlCheck = "SELECT COUNT(*) FROM $table WHERE $whereClauseCheck";
        $stmtCheck = $con->prepare($sqlCheck);
        if (!$stmtCheck) {
            throw new Exception("Prepare failed: " . $con->error);
        }

        $typesCheck = "";
        foreach ($whereValues as $v) {
            if (is_int($v)) $typesCheck .= "i";
            elseif (is_float($v)) $typesCheck .= "d";
            else $typesCheck .= "s";
        }
        $stmtCheck->bind_param($typesCheck, ...$whereValues);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        // If the AID does not exist, rollback and return a specific value
        if ($count == 0) {
            $con->rollback();
            return 0; // Return 0 for "No matching AID found"
        }

        // Step 2: Proceed with the UPDATE if the AID was found
        $setClause = implode(", ", array_map(fn($col) => "$col=?", array_keys($data)));
        $whereClause = implode(" AND ", array_map(fn($col) => "$col=?", array_keys($where)));

        $types = "";
        $values = array_merge(array_values($data), array_values($where));
        foreach ($values as $v) {
            if (is_int($v)) $types .= "i";
            elseif (is_float($v)) $types .= "d";
            else $types .= "s";
        }

        $sql = "UPDATE $table SET $setClause WHERE $whereClause";
        $stmt = $con->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }

        $stmt->bind_param($types, ...$values);
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception($stmt->error);
        }

        $con->commit();

        return 1; // 1 for success, 0 for no changes
        
    } catch (Exception $e) {
        $con->rollback();
        return $e->getMessage();
    }
}

function deleteData_Fun($table, $where) {
    global $con;
    $con->begin_transaction();
    try{
        /*
        $where = ["AID" => 2, "Status" => "yes", "UserID" => 5]
        */

        if (empty($where)) {
            die("WHERE condition is required to prevent full table delete!");
        }

        // WHERE clause
        $whereClause = implode(" AND ", array_map(fn($col) => "$col=?", array_keys($where)));

        // Detect types
        $types = "";
        $values = array_values($where);
        foreach ($values as $v) {
            if (is_int($v)) $types .= "i";
            elseif (is_float($v)) $types .= "d";
            else $types .= "s";
        }

        $sql = "DELETE FROM $table WHERE $whereClause";

        $stmt = $con->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $con->error);
        }

        $stmt->bind_param($types, ...$values);
        $success = $stmt->execute();

        if (!$success) throw new Exception($stmt->error);
        // check row count is success
        $affected = $stmt->affected_rows;

        $con->commit();

        return $affected > 0 ? true : false;

    } catch (Exception $e) {
        $con->rollback();
        return false;
    }    
}

function printVoucher($vno){
    global $con;
    $sql = "SELECT s.*,v.* FROM tblpreordervoucher v,tblpreordersale s WHERE v.VNO = s.VNO AND v.VNO='{$vno}'";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        $voucher_row = mysqli_fetch_array($result);
        $out = "";
        $out .= "
            <h5 class='text-center'>{$_SESSION['shopname']}</h5>
            <div align='center'><img src='".roothtml."lib/images/logo2.jpg' style='width: 200px;height:auto;'></img></div>
            <p class='text-center txt'>{$_SESSION['shopaddress']}<br>
            {$_SESSION['shopphno']}<br>
            {$_SESSION['shopemail']}</p>
            <hr>
            <p class='txtl fs'>
                Date : ".$voucher_row["Date"]."<br>
                VoucherNo : ".$voucher_row["VNO"]."<br>
                Customer Name: ".$voucher_row["CustomerName"]."<br>
                Sell Name : {$_SESSION['eadmin_username']}<br>
            </p>
            <table class='table table-bordered text-sm' frame=hsides rules=rows width='100%'>
                <tr>
                    <th class='txtl'>Item Code</th>
                    <th class='txtl'>ItemName</th>
                    <th class='text-center txtc'>Qty</th>
                    <th class='text-right txtr'>Price</th>
                    <th class='text-right txtr'>Total</th>
                </tr>";
            // Reset the result pointer to the beginning
            mysqli_data_seek($result, 0);
            while($row = mysqli_fetch_array($result)){
                $out .= "
                <tr>
                    <td>{$row['CodeNo']}</td>
                    <td>{$row['ItemName']}</td>
                    <td class='text-center txtc'>{$row['Qty']}</td>
                    <td class='text-right txtr'>".number_format($row['SellPrice'])."</td>
                    <td class='text-right txtr'>".number_format($row['TotalPrice'])."</td>
                </tr> ";
            }                    
            $out .= "<tr class='text-right txtr'>
                    <td colspan='4'>
                        Total<br>
                        Disc(%)<br>
                        SubTotal<br>
                        Cash<br>
                        Refund
                    </td>
                    <td>
                        ".number_format($voucher_row["TotalAmt"])."<br>
                        ".number_format($voucher_row["Dis"])."<br>
                        ".number_format($voucher_row["Total"])."<br>
                        ".number_format($voucher_row["Cash"])."<br>
                        ".number_format($voucher_row["Refund"])."<br>
                    </td>
                </tr>
                <tr class='text-center txt'>
                    <td colspan='5'>
                        ********** Thank You **********
                    </td>   
                </tr>
            </table>
            <br><br><br>
            <div style='text-align: right; margin-bottom: 20px; margin-right: 20px;'>
                <button class='btn btn-primary' id='btnprint'>Print</button>
            </div>
            ";      
            /////////////
            echo $out;
    }
}

function deletepreorder($vno){
    global $con;
    $whereone = [
        "VNO" => $vno
    ];
    $resone = deleteData_Fun("tblpreordervoucher",$whereone);
    if($resone){
        $wheretwo = [
            "VNO" => $vno
        ];
        $restwo = deleteData_Fun("tblpreordersale",$wheretwo);
        if($restwo){
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

?>