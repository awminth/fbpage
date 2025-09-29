<?php
include('../config.php');

$action = $_POST["action"];


if($action=='show'){
    $sql="select * from tblremain where Qty>1 order by AID desc limit 50";
    $result = mysqli_query($con,$sql);
    $out = "";    
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $url = roothtml.'lib/images/kg1.jpg';
            if($row['Img']!="" || $row['Img']!=NULL){
                $url=roothtml.'upload/purchase/'.$row['Img'];
            }
            
            $out .= '
            <div class="col-md-2 m-2 card float-left">
                <a id="cart" data-aid="'.$row["AID"].'" 
                    data-codeno="'.$row["CodeNo"].'" 
                    data-itemname="'.$row["ItemName"].'" 
                    data-price="'.$row["SellPrice"].'"  >
                    <img class="card-img-top" src="'.$url.'" alt="Card image" style="width:100%;height:150px;">
                    <div class="card-body text-center">
                        <h6 class="card-title text-primary">'.$row["ItemName"].'</h6>
                        <p class="card-text">Ks '.number_format($row["SellPrice"]).'</p>
                    </div>
                </a>
            </div>
            ';
            
        }        
    }else{
        $out.='<div class="text-center">
            <h5 class="text-danger">No data</h5>
        </div>';
    }
    echo $out;
}

if($action=='show_totalamt'){
    $out="";
    $out.="<input type='hidden' name='chkqty' value=".(isset($_SESSION['totalqty'])?$_SESSION['totalqty']:'').">";
    $out.="<input type='hidden' name='chkamt' value=".(isset($_SESSION['totalamt'])?$_SESSION['totalamt']:'').">";
    $out.="<td></td><td width='22%'>Total Qty</td><td >".(isset($_SESSION['totalqty'])?$_SESSION['totalqty']:'')."</td><td width='15%' >Amount</td><td class='text-center'>".number_format((isset($_SESSION['totalamt'])?$_SESSION['totalamt']:0))." MMK</td><td></td>";
    echo $out;
}

if($action=='choose_items'){
    $output="";
    $total = 0;
    $totalqty = 0;
    $userid = $_SESSION['eadmin_userid'];
    $sql = "select * from tblsale_temp where UserID={$userid}";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_array($res)){
            $output.="
            <tr>
                <td>{$row['CodeNo']}</td>                
                <td><a id='btnqtyincrease' class='btn btn-block btn-success btn-xs text-white' 
                    data-aid='{$row['AID']}'
                    data-codeno='{$row['CodeNo']}'
                    data-itemname='{$row['ItemName']}'
                    data-price='{$row['SellPrice']}' 
                    data-qty='{$row['Qty']}' 
                    data-remainaid='{$row['RemainID']}' >{$row['ItemName']}</a></td>
                <td>{$row['Qty']}</td>
                <td class='text-right'>".number_format($row['SellPrice'])."</td>
                <td>";
                $output.=number_format($row['Qty'] * $row['SellPrice']);
                $output.="</td>
                <td><a href='#' id='removecart' data-aid='{$row['AID']}' ><i class='fas fa-trash text-danger'></i></a></td>
            </tr>
            ";
            $total = $total + ($row["Qty"] * $row["SellPrice"]);
            $totalqty = $totalqty + $row["Qty"];            
        }
        $_SESSION['totalamt'] = $total;
        $_SESSION['totalqty'] = $totalqty;
    }
    echo $output;
}

if($action == 'addcart'){ 
    $userid = $_SESSION['eadmin_userid'];
    $aid = $_POST['aid'];
    $codeno = $_POST['codeno'];
    $itemname = $_POST['itemname']; 
    $price = $_POST['price'];

    $chk = "select * from tblsale_temp where RemainID={$aid} and UserID={$userid}";
    $res = mysqli_query($con,$chk);
    if(mysqli_num_rows($res) > 0){
        $row_chk = mysqli_fetch_array($res);
        $qty_old = $row_chk["Qty"] + 1;
        // check qty is enough or not
        $sqlqty = "select Qty from tblremain where AID={$aid}";
        $res_qty = GetInt($sqlqty);
        if($res_qty < $qty_old){
            echo 2;
        }else{
            $sql_upd = 'update tblsale_temp set Qty="'.$qty_old.'" 
            where RemainID="'.$aid.'" and UserID="'.$userid.'"';
            if(mysqli_query($con,$sql_upd)){
                echo 1;
            }else{
                echo 0;
            }
        }
    }else{
        $sql_in = 'insert into tblsale_temp (RemainID,CodeNo,ItemName,Qty,SellPrice,UserID) 
        values ("'.$aid.'","'.$codeno.'","'.$itemname.'",1,"'.$price.'","'.$userid.'")';
        if(mysqli_query($con,$sql_in)){
            echo 1;
        }else{
            echo 0;
        }
    }    
}

if($action == 'addcartbycodeno'){ 
    $userid = $_SESSION['eadmin_userid'];
    $codeno = $_POST['codeno'];

    $chk = "select * from tblsale_temp where CodeNo='{$codeno}' and UserID={$userid}";
    $res = mysqli_query($con,$chk);
    if(mysqli_num_rows($res) > 0){
        $row_chk = mysqli_fetch_array($res);
        $qty_old = $row_chk["Qty"] + 1;
        // check qty is enough or not
        $sqlqty = "select Qty from tblremain where CodeNo='{$codeno}'";
        $res_qty = GetInt($sqlqty);
        if($res_qty < $qty_old){
            echo 2;
        }else{
            $sql_upd = 'update tblsale_temp set Qty="'.$qty_old.'" 
            where CodeNo="'.$codeno.'" and UserID="'.$userid.'"';
            if(mysqli_query($con,$sql_upd)){
                echo 1;
            }else{
                echo 0;
            }
        }
    }else{
        $sql_in = "insert into tblsale_temp (RemainID,CodeNo,ItemName,Qty,SellPrice,UserID) select AID,CodeNo,ItemName,1,SellPrice,'{$userid}' from tblremain where CodeNo='{$codeno}'";
        if(mysqli_query($con,$sql_in)){
            echo 1;
        }else{
            echo 0;
        }
    }    
}

if($action == 'remove'){
    $aid = $_POST["aid"];
    $sql = "delete from tblsale_temp where AID={$aid}";
    if(mysqli_query($con,$sql)){
        echo 1;
    }else{
        echo 0;
    }
}

if($action == 'save_editqty'){
    $aid = $_POST['aid'];
    $qty = $_POST['qty'];
    $remainaid = $_POST['remainaid'];
    // check qty is enough or not
    $sqlqty = "select Qty from tblremain where AID={$remainaid}";
    $res_qty = GetInt($sqlqty);
    if($res_qty < $qty){
        echo 2;
    }else{
        $sql_upd = 'update tblsale_temp set Qty="'.$qty.'" 
        where AID="'.$aid.'"';
        if(mysqli_query($con,$sql_upd)){
            echo 1;
        }else{
            echo 0;
        }
    }
}

if($action=='delete_temp'){
    $userid = $_SESSION["eadmin_userid"];
    $sql = "delete from tblsale_temp where UserID={$userid}";
    if(mysqli_query($con,$sql)){
        unset($_SESSION['totalamt']);
        unset($_SESSION['totalqty']);   
        echo 1;
    }else{
        echo 0;
    }  
}

if($action == 'show_category'){
    $aid = $_POST["aid"];
    $sql="select * from tblremain where CategoryID=$aid";
    $result = mysqli_query($con,$sql);
    $out = ""; 
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $url = roothtml.'lib/images/kg1.jpg';
            if($row['Img']!="" || $row['Img']!=NULL){
                $url=roothtml.'upload/purchase/'.$row['Img'];
            }
            $out .= '
            <div class="col-md-2 m-2 card float-left">
                <a id="cart" data-aid="'.$row["AID"].'" 
                    data-codeno="'.$row["CodeNo"].'" 
                    data-itemname="'.$row["ItemName"].'" 
                    data-price="'.$row["SellPrice"].'"  >
                    <img class="card-img-top" src="'.$url.'" alt="Card image" style="width:100%">
                    <div class="card-body text-center">
                        <h6 class="card-title text-primary">'.$row["ItemName"].'</h6>
                        <p class="card-text">Ks '.number_format($row["SellPrice"]).'</p>
                    </div>
                </a>
            </div>
            ';
        }        
    }else{
        $out.='<div class="text-center">
            <h5 class="text-danger">No data</h5>
        </div>';
    }
    echo $out;
}

if($action == 'save'){    
    $name = $_POST["name"];
    $phno = $_POST["phno"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $sql = "insert into tblcustomer (Name,PhoneNo,Address,Email) 
    values ('{$name}','{$phno}','{$address}','{$email}')";    
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]." သည် user အားအသစ်သွင်းသွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

if($action == 'searchcategorylist'){  
    $cat = $_POST["cat"];
    $output = '';  
    $query = "SELECT * FROM tblremain WHERE (ItemName LIKE '%$cat%' or CodeNo LIKE '%$cat%') limit 5";  
    $result = mysqli_query($con, $query);  
    $output = '<ul class="list-unstyled" style="cursor:pointer; background-color:#eee;">';  
    if(mysqli_num_rows($result) > 0){  
        while($row = mysqli_fetch_array($result)){  
            $output .= "<li id='selectcatitem' style='padding:8px;' 
                    data-aid='{$row["AID"]}' 
                    data-codeno='{$row['CodeNo']}' 
                    data-itemname='{$row['ItemName']}' 
                    data-price='{$row['SellPrice']}'>{$row["ItemName"]}</li>";  
        }  
    }     
    $output .= '</ul>';  
    echo $output;  
}  

// sale cash
if($action == 'paidsave_cash'){
    $totalqty = $_POST['ch_tchkqty'];
    $totalamt = $_POST['ch_tchkamt'];
    $disc = $_POST['ch_disc'];
    $tax = $_POST['ch_tax'];
    $cash = $_POST['ch_cash'];
    $refund = $_POST['ch_refund'];
    $total = $_POST['ch_total'];
    $customerid = $_POST['ch_customerid'];
    $userid = $_SESSION['eadmin_userid'];
    $vno = date("Ymd-His");
    $dt = date("Y-m-d H:i:s");
    $out = "";

    //save tblvoucher    
    $sql_voucher='insert into tblvoucher (VNO,CustomerID,TotalQty,TotalAmt,Dis,Tax,Total,
    UserID,Cash,Refund,Credit,Date,Chk) values ("'.$vno.'","'.$customerid.'","'.$totalqty.'",
    "'.$totalamt.'","'.$disc.'","'.$tax.'","'.$total.'","'.$userid.'","'.$cash.'",
    "'.$refund.'",0,"'.$dt.'","Cash")';
    
    if(mysqli_query($con,$sql_voucher)){
        // save tblsale
        $sql_sale = 'insert into tblsale (RemainID,CodeNo,ItemName,Qty,SellPrice,Date,VNO,CustomerID)  
        select RemainID,CodeNo,ItemName,Qty,SellPrice,"'.$dt.'","'.$vno.'","'.$customerid.'" 
        from tblsale_temp where UserID='.$userid;
        mysqli_query($con,$sql_sale);

        // decrease qty from remain
        $sql_temp = "select * from tblsale_temp where UserID={$userid}";
        $res_temp = mysqli_query($con,$sql_temp);
        if(mysqli_num_rows($res_temp) > 0){
            while($row_temp = mysqli_fetch_array($res_temp)){
                $sql_oldqty = "select Qty from tblremain where AID={$row_temp['RemainID']}";
                $res_oldqty = mysqli_query($con,$sql_oldqty);
                if(mysqli_num_rows($res_oldqty) > 0){
                    $row_qty = mysqli_fetch_array($res_oldqty);
                    $new_qty = $row_qty['Qty'] - $row_temp['Qty'];

                    $sqlremain = "update tblremain set Qty={$new_qty} where AID={$row_temp['RemainID']}";
                    mysqli_query($con,$sqlremain);
                }
            }
        }

        $cusname = GetString("select Name from tblcustomer where AID={$customerid}");
        $todaydate = date("Y-m-d");
        $out="<h5 class='text-center'>{$_SESSION['shopname']}</h5>
        <div align='center'><img src='".roothtml."lib/images/logo2.jpg' style='width: 200px;height:auto;'></img></div>
        <p class='text-center txt'>{$_SESSION['shopaddress']}<br>
        {$_SESSION['shopphno']}<br>
        {$_SESSION['shopemail']}</p>
        <hr>
        <p class='txtl fs'>
            Date : {$todaydate}<br>
            VoucherNo : {$vno}<br>
            Customer Name: {$cusname}<br>
            Sell Name : {$_SESSION['eadmin_username']}<br>
        </p>
        <table class='table table-bordered text-sm' frame=hsides rules=rows width='100%'>
            <tr>
                <th class='txtl'>ItemName</th>
                <th class='text-center txtc'>Qty</th>
                <th class='text-right txtr'>Price</th>
                <th class='text-right txtr'>Total</th>
            </tr>
        ";
        $sql_v = "select * from tblsale_temp where UserID={$userid}";
        $res_v = mysqli_query($con,$sql_v);
        if(mysqli_num_rows($res_v) > 0){
            while($row_v = mysqli_fetch_array($res_v)){
                $out.="                    
                <tr>
                    <td>{$row_v['ItemName']}</td>
                    <td class='text-center txtc'>{$row_v['Qty']}</td>
                    <td class='text-right txtr'>".number_format($row_v['SellPrice'])."</td>";
                    $printtotal=$row_v['Qty']*$row_v['SellPrice'];
                    $out.="<td class='text-right txtr'>".number_format($printtotal)."</td>
                </tr>                    
                ";
            }
        }
        $out.="
            <tr class='text-right txtr'>
                <td colspan='3'>
                    Total<br>
                    Disc(%)<br>
                    Tax(%)<br>
                    SubTotal<br>
                    Cash<br>
                    Refund
                </td>
                <td>
                    ".number_format($totalamt)."<br>
                    ".number_format($disc)."<br>
                    ".number_format($tax)."<br>
                    ".number_format($total)."<br>
                    ".number_format($cash)."<br>
                    ".number_format($refund)."<br>
                </td>
            </tr>
            <tr class='text-center txt'>
                <td colspan='4'>
                    ********** Thank You **********
                </td>   
            </tr>
        </table>
        <br>
        ";        
        unset($_SESSION['totalamt']);
        unset($_SESSION['totalqty']);

        $sql_del = "delete from tblsale_temp where UserID={$userid}";
        mysqli_query($con,$sql_del);

        if(isset($_SESSION['pause'])){
            $pvno=$_SESSION['pause'];
            $sqlpause="delete from tblpausevoucher where PVNO='{$pvno}'";
            mysqli_query($con,$sqlpause);
            unset($_SESSION['pause']);
        }
        save_log($_SESSION["eadmin_username"]." သည် vno: ".$vno." ဖြင့် cash အရောင်းသွင်းသွားသည်။");
        echo $out;
    }
}

// sale cash edit
if($action == 'paidedit_cash'){
    $totalqty = $_POST['ch_tchkqty'];
    $totalamt = $_POST['ch_tchkamt'];
    $disc = $_POST['ch_disc'];
    $tax = $_POST['ch_tax'];
    $cash = $_POST['ch_cash'];
    $refund = $_POST['ch_refund'];
    $total = $_POST['ch_total'];
    $customerid = $_POST['ch_customerid'];
    $userid = $_SESSION['eadmin_userid'];
    $vno = $_SESSION['editsalevno'];    

    $sqlsale = "select * from tblsale where VNO='{$vno}'";
    $result = mysqli_query($con,$sqlsale);
    if(mysqli_num_rows($result)>0){
        while( $rowqty = mysqli_fetch_array($result)){      
       
            $sqlremain = "select Qty from tblremain where CodeNo='{$rowqty['CodeNo']}'";
            $result1 = GetInt($sqlremain);
            $newqty = $rowqty['Qty'] + $result1;

            $sqlremain1="update tblremain set Qty={$newqty} where CodeNo='{$rowqty['CodeNo']}'";
            mysqli_query($con,$sqlremain1);
        }

        $sqldel1 ="delete from tblsale where VNO='{$vno}'";       
        mysqli_query($con,$sqldel1);

        $sqldel2= "delete from tblvoucher where VNO='{$vno}'";
        mysqli_query($con,$sqldel2);
    }

    $dt = date("Y-m-d H:i:s");
    //save tblvoucher    
    $sql_voucher='insert into tblvoucher (VNO,CustomerID,TotalQty,TotalAmt,Dis,Tax,Total,
    UserID,Cash,Refund,Credit,Date,Chk) values ("'.$vno.'","'.$customerid.'","'.$totalqty.'",
    "'.$totalamt.'","'.$disc.'","'.$tax.'","'.$total.'","'.$userid.'","'.$cash.'",
    "'.$refund.'",0,"'.$dt.'","Cash")';
    if(mysqli_query($con,$sql_voucher)){
        // save tblsale
        $sql_sale = 'insert into tblsale (RemainID,CodeNo,ItemName,Qty,SellPrice,Date,VNO,CustomerID)  
        select RemainID,CodeNo,ItemName,Qty,SellPrice,"'.$dt.'","'.$vno.'","'.$customerid.'" 
        from tblsale_temp where UserID='.$userid;
        mysqli_query($con,$sql_sale);

        // decrease qty from remain
        $sql_temp = "select * from tblsale_temp where UserID={$userid}";
        $res_temp = mysqli_query($con,$sql_temp);
        if(mysqli_num_rows($res_temp) > 0){
            while($row_temp = mysqli_fetch_array($res_temp)){
                $sql_oldqty = "select Qty from tblremain where AID={$row_temp['RemainID']}";
                $res_oldqty = mysqli_query($con,$sql_oldqty);
                if(mysqli_num_rows($res_oldqty) > 0){
                    $row_qty = mysqli_fetch_array($res_oldqty);
                    $new_qty = $row_qty['Qty'] - $row_temp['Qty'];

                    $sqlremain = "update tblremain set Qty={$new_qty} where AID={$row_temp['RemainID']}";
                    mysqli_query($con,$sqlremain);
                }
            }
        }

        $del_temp = "delete from tblsale_temp where UserID={$userid}";
        mysqli_query($con,$del_temp);

        unset($_SESSION['totalamt']);
        unset($_SESSION['totalqty']);
        unset($_SESSION['editsalevno']);
        echo 1;
    }else{
        echo 0;
    }
}

// sale credit
if($action == 'paidsave_credit'){
    $totalqty = $_POST['cd_tchkqty'];
    $totalamt = $_POST['cd_tchkamt'];
    $disc = $_POST['cd_disc'];
    $tax = $_POST['cd_tax'];
    $cash = $_POST['cd_cash'];
    $credit = $_POST['cd_credit'];
    $total = $_POST['cd_total'];
    $customerid = $_POST['cd_customerid'];
    $userid = $_SESSION['eadmin_userid'];
    $vno = date("Ymd-His");
    $dt = date("Y-m-d H:i:s");
    $dt1 = date("Y-m-d");
    $out = "";

    //save tblvoucher    
    $sql_voucher='insert into tblvoucher (VNO,CustomerID,TotalQty,TotalAmt,Dis,Tax,Total,
    UserID,Cash,Refund,Credit,Date,Chk) values ("'.$vno.'","'.$customerid.'","'.$totalqty.'",
    "'.$totalamt.'","'.$disc.'","'.$tax.'","'.$total.'","'.$userid.'","'.$cash.'",
    0,"'.$credit.'","'.$dt.'","Credit")';
    
    if(mysqli_query($con,$sql_voucher)){
        // save tblsale
        $sql_sale = 'insert into tblsale (RemainID,CodeNo,ItemName,Qty,SellPrice,Date,VNO,CustomerID)  
        select RemainID,CodeNo,ItemName,Qty,SellPrice,"'.$dt.'","'.$vno.'","'.$customerid.'" 
        from tblsale_temp where UserID='.$userid;
        mysqli_query($con,$sql_sale);

        // decrease qty from remain
        $sql_temp = "select * from tblsale_temp where UserID={$userid}";
        $res_temp = mysqli_query($con,$sql_temp);
        if(mysqli_num_rows($res_temp) > 0){
            while($row_temp = mysqli_fetch_array($res_temp)){
                $sql_oldqty = "select Qty from tblremain where AID={$row_temp['RemainID']}";
                $res_oldqty = mysqli_query($con,$sql_oldqty);
                if(mysqli_num_rows($res_oldqty) > 0){
                    $row_qty = mysqli_fetch_array($res_oldqty);
                    $new_qty = $row_qty['Qty'] - $row_temp['Qty'];

                    $sqlremain = "update tblremain set Qty={$new_qty} where AID={$row_temp['RemainID']}";
                    mysqli_query($con,$sqlremain);
                }
            }
        }

        // insert tblcredit detail
        $sql_detail = "insert into tblcreditdetail (VNO,CustomerID,Amt,Date,UserID) 
        values ('{$vno}','{$customerid}','{$credit}','{$dt1}','{$userid}')";
        mysqli_query($con,$sql_detail);

        $cusname = GetString("select Name from tblcustomer where AID={$customerid}");
        $todaydate = date("Y-m-d");
        $out="<h5 class='text-center'>{$_SESSION['shopname']}</h5>
        <div align='center'><img src='".roothtml."lib/images/logo2.jpg' style='width: 200px;height:auto;'></img></div>
        <p class='text-center txt'>{$_SESSION['shopaddress']}<br>
        {$_SESSION['shopphno']}<br>
        {$_SESSION['shopemail']}</p>
        <hr>
        <p class='txtl fs'>
            Date : {$todaydate}<br>
            VoucherNo : {$vno}<br>
            Customer Name: {$cusname}<br>
            Sell Name : {$_SESSION['eadmin_username']}<br>
        </p>
        <table class='table table-bordered text-sm' frame=hsides rules=rows width='100%'>
            <tr>
                <th class='txtl'>ItemName</th>
                <th class='text-center txtc'>Qty</th>
                <th class='text-right txtr'>Price</th>
                <th class='text-right txtr'>Total</th>
            </tr>
        ";
        $sql_v = "select * from tblsale_temp where UserID={$userid}";
        $res_v = mysqli_query($con,$sql_v);
        if(mysqli_num_rows($res_v) > 0){
            while($row_v = mysqli_fetch_array($res_v)){
                $out.="                    
                <tr>
                    <td>{$row_v['ItemName']}</td>
                    <td class='text-center txtc'>{$row_v['Qty']}</td>
                    <td class='text-right txtr'>".number_format($row_v['SellPrice'])."</td>";
                    $printtotal=$row_v['Qty']*$row_v['SellPrice'];
                    $out.="<td class='text-right txtr'>".number_format($printtotal)."</td>
                </tr>                    
                ";
            }
        }
        $out.="
            <tr class='text-right txtr'>
                <td colspan='3'>
                    Total<br>
                    Disc(%)<br>
                    Tax(%)<br>
                    SubTotal<br>
                    Pay<br>
                    Credit
                </td>
                <td>
                    ".number_format($totalamt)."<br>
                    ".number_format($disc)."<br>
                    ".number_format($tax)."<br>
                    ".number_format($total)."<br>
                    ".number_format($cash)."<br>
                    ".number_format($credit)."<br>
                </td>
            </tr>
            <tr class='text-center txt'>
                <td colspan='4'>
                    ********** Thank You **********
                </td>   
            </tr>
        </table>
        <br>
        ";        
        unset($_SESSION['totalamt']);
        unset($_SESSION['totalqty']);

        $sql_del = "delete from tblsale_temp where UserID={$userid}";
        mysqli_query($con,$sql_del);

        if(isset($_SESSION['pause'])){
            $pvno=$_SESSION['pause'];
            $sqlpause="delete from tblpausevoucher where PVNO='{$pvno}'";
            mysqli_query($con,$sqlpause);
            unset($_SESSION['pause']);
        }
        save_log($_SESSION["eadmin_username"]." သည် vno: ".$vno." ဖြင့် credit အရောင်းသွင်းသွားသည်။");
        echo $out;
    }
}

// sale credit edit
if($action == 'paidedit_credit'){
    $totalqty = $_POST['cd_tchkqty'];
    $totalamt = $_POST['cd_tchkamt'];
    $disc = $_POST['cd_disc'];
    $tax = $_POST['cd_tax'];
    $cash = $_POST['cd_cash'];
    $credit = $_POST['cd_credit'];
    $total = $_POST['cd_total'];
    $customerid = $_POST['cd_customerid'];
    $userid = $_SESSION['eadmin_userid'];
    $vno = $_SESSION['editsalevno'];    
    $dt1 = date("Y-m-d");

    $sqlsale = "select * from tblsale where VNO='{$vno}'";
    $result = mysqli_query($con,$sqlsale);
    if(mysqli_num_rows($result)>0){
        while( $rowqty = mysqli_fetch_array($result)){      
       
            $sqlremain = "select Qty from tblremain where CodeNo='{$rowqty['CodeNo']}'";
            $result1 = GetInt($sqlremain);
            $newqty = $rowqty['Qty'] + $result1;

            $sqlremain1="update tblremain set Qty={$newqty} where CodeNo='{$rowqty['CodeNo']}'";
            mysqli_query($con,$sqlremain1);
        }

        $sqldel1 ="delete from tblsale where VNO='{$vno}'";       
        mysqli_query($con,$sqldel1);

        $sqldel2= "delete from tblvoucher where VNO='{$vno}'";
        mysqli_query($con,$sqldel2);

        $sqldel3 = "delete from tblcreditdetail where VNO='{$vno}'";
        mysqli_query($con,$sqldel3);
    }

    $dt = date("Y-m-d H:i:s");
    //save tblvoucher    
    $sql_voucher='insert into tblvoucher (VNO,CustomerID,TotalQty,TotalAmt,Dis,Tax,Total,
    UserID,Cash,Refund,Credit,Date,Chk) values ("'.$vno.'","'.$customerid.'","'.$totalqty.'",
    "'.$totalamt.'","'.$disc.'","'.$tax.'","'.$total.'","'.$userid.'","'.$cash.'",
    0,"'.$credit.'","'.$dt.'","Credit")';
    if(mysqli_query($con,$sql_voucher)){
        // save tblsale
        $sql_sale = 'insert into tblsale (RemainID,CodeNo,ItemName,Qty,SellPrice,Date,VNO,CustomerID)  
        select RemainID,CodeNo,ItemName,Qty,SellPrice,"'.$dt.'","'.$vno.'","'.$customerid.'" 
        from tblsale_temp where UserID='.$userid;
        mysqli_query($con,$sql_sale);

        // decrease qty from remain
        $sql_temp = "select * from tblsale_temp where UserID={$userid}";
        $res_temp = mysqli_query($con,$sql_temp);
        if(mysqli_num_rows($res_temp) > 0){
            while($row_temp = mysqli_fetch_array($res_temp)){
                $sql_oldqty = "select Qty from tblremain where AID={$row_temp['RemainID']}";
                $res_oldqty = mysqli_query($con,$sql_oldqty);
                if(mysqli_num_rows($res_oldqty) > 0){
                    $row_qty = mysqli_fetch_array($res_oldqty);
                    $new_qty = $row_qty['Qty'] - $row_temp['Qty'];

                    $sqlremain = "update tblremain set Qty={$new_qty} where AID={$row_temp['RemainID']}";
                    mysqli_query($con,$sqlremain);
                }
            }
        }

        // insert tblcredit detail
        $sql_detail = "insert into tblcreditdetail (VNO,CustomerID,Amt,Date,UserID) 
        values ('{$vno}','{$customerid}','{$credit}','{$dt1}','{$userid}')";
        mysqli_query($con,$sql_detail);

        $del_temp = "delete from tblsale_temp where UserID={$userid}";
        mysqli_query($con,$del_temp);

        unset($_SESSION['totalamt']);
        unset($_SESSION['totalqty']);
        unset($_SESSION['editsalevno']);
        echo 1;
    }else{
        echo 0;
    }
}

// sale return 
if($action == 'paidsave_return'){
    $totalqty = $_POST['rt_tchkqty'];
    $totalamt = $_POST['rt_tchkamt'];
    $disc = 0;
    $tax = 0;
    $cash = $_POST['rt_cash'];
    $credit = 0;
    $total = $_POST['rt_tchkamt'];
    $customerid = $_POST['rt_customerid'];
    $userid = $_SESSION['eadmin_userid'];
    $vno = date("Ymd-His");
    $dt = date("Y-m-d H:i:s");
    $out = "";

    //save tblvoucher    
    $sql_voucher='insert into tblvoucher (VNO,CustomerID,TotalQty,TotalAmt,Dis,Tax,Total,
    UserID,Cash,Refund,Credit,Date,Chk) values ("'.$vno.'","'.$customerid.'","'.$totalqty.'",
    "'.$totalamt.'","'.$disc.'","'.$tax.'","'.$total.'","'.$userid.'","'.$cash.'",
    0,0,"'.$dt.'","Return")';
    
    if(mysqli_query($con,$sql_voucher)){
        // save tblsale
        $sql_sale = 'insert into tblsale (RemainID,CodeNo,ItemName,Qty,SellPrice,Date,VNO,CustomerID)  
        select RemainID,CodeNo,ItemName,Qty,SellPrice,"'.$dt.'","'.$vno.'","'.$customerid.'" 
        from tblsale_temp where UserID='.$userid;
        mysqli_query($con,$sql_sale);

        // decrease qty from remain
        $sql_temp = "select * from tblsale_temp where UserID={$userid}";
        $res_temp = mysqli_query($con,$sql_temp);
        if(mysqli_num_rows($res_temp) > 0){
            while($row_temp = mysqli_fetch_array($res_temp)){
                $sql_oldqty = "select Qty from tblremain where AID={$row_temp['RemainID']}";
                $res_oldqty = mysqli_query($con,$sql_oldqty);
                if(mysqli_num_rows($res_oldqty) > 0){
                    $row_qty = mysqli_fetch_array($res_oldqty);
                    $new_qty = $row_qty['Qty'] + $row_temp['Qty'];

                    $sqlremain = "update tblremain set Qty={$new_qty} where AID={$row_temp['RemainID']}";
                    mysqli_query($con,$sqlremain);
                }
            }
        }

        $cusname = GetString("select Name from tblcustomer where AID={$customerid}");
        $todaydate = date("Y-m-d");
        $out="<h5 class='text-center'>{$_SESSION['shopname']}</h5>
        <div align='center'><img src='".roothtml."lib/images/logo2.jpg' style='width: 200px;height:auto;'></img></div>
        <p class='text-center txt'>{$_SESSION['shopaddress']}<br>
        {$_SESSION['shopphno']}<br>
        {$_SESSION['shopemail']}</p>
        <hr>
        <p class='txtl fs'>
            Date : {$todaydate}<br>
            VoucherNo : {$vno}<br>
            Customer Name: {$cusname}<br>
            Sell Name : {$_SESSION['eadmin_username']}<br>
        </p>
        <table class='table table-bordered text-sm' frame=hsides rules=rows width='100%'>
            <tr>
                <th class='txtl'>ItemName</th>
                <th class='text-center txtc'>Qty</th>
                <th class='text-right txtr'>Price</th>
                <th class='text-right txtr'>Total</th>
            </tr>
        ";
        $sql_v = "select * from tblsale_temp where UserID={$userid}";
        $res_v = mysqli_query($con,$sql_v);
        if(mysqli_num_rows($res_v) > 0){
            while($row_v = mysqli_fetch_array($res_v)){
                $out.="                    
                <tr>
                    <td>{$row_v['ItemName']}</td>
                    <td class='text-center txtc'>{$row_v['Qty']}</td>
                    <td class='text-right txtr'>".number_format($row_v['SellPrice'])."</td>";
                    $printtotal=$row_v['Qty']*$row_v['SellPrice'];
                    $out.="<td class='text-right txtr'>".number_format($printtotal)."</td>
                </tr>                    
                ";
            }
        }
        $out.="
            <tr class='text-right txtr'>
                <td colspan='3'>
                    Total<br>
                    Disc(%)<br>
                    Tax(%)<br>
                    SubTotal<br>
                    Return Amt<br>
                </td>
                <td>
                    ".number_format($totalamt)."<br>
                    ".number_format($disc)."<br>
                    ".number_format($tax)."<br>
                    ".number_format($total)."<br>
                    ".number_format($cash)."<br>
                </td>
            </tr>
            <tr class='text-center txt'>
                <td colspan='4'>
                    ********** Thank You **********
                </td>   
            </tr>
        </table>
        <br>
        ";        
        unset($_SESSION['totalamt']);
        unset($_SESSION['totalqty']);

        $sql_del = "delete from tblsale_temp where UserID={$userid}";
        mysqli_query($con,$sql_del);

        if(isset($_SESSION['pause'])){
            $pvno=$_SESSION['pause'];
            $sqlpause="delete from tblpausevoucher where PVNO='{$pvno}'";
            mysqli_query($con,$sqlpause);
            unset($_SESSION['pause']);
        }
        save_log($_SESSION["eadmin_username"]." သည် vno: ".$vno." ဖြင့် sale return အရောင်းသွင်းသွားသည်။");
        echo $out;
    }
}

// sale return edit
if($action == 'paidedit_return'){
    $totalqty = $_POST['rt_tchkqty'];
    $totalamt = $_POST['rt_tchkamt'];
    $disc = 0;
    $tax = 0;
    $cash = $_POST['rt_cash'];
    $refund = 0;
    $total = $_POST['rt_tchkamt'];
    $customerid = $_POST['rt_customerid'];
    $userid = $_SESSION['eadmin_userid'];
    $vno = $_SESSION['editsalevno'];    

    $sqlsale = "select * from tblsale where VNO='{$vno}'";
    $result = mysqli_query($con,$sqlsale);
    if(mysqli_num_rows($result)>0){
        while( $rowqty = mysqli_fetch_array($result)){      
       
            $sqlremain = "select Qty from tblremain where CodeNo='{$rowqty['CodeNo']}'";
            $result1 = GetInt($sqlremain);
            $newqty = $rowqty['Qty'] + $result1;

            $sqlremain1="update tblremain set Qty={$newqty} where CodeNo='{$rowqty['CodeNo']}'";
            mysqli_query($con,$sqlremain1);
        }

        $sqldel1 ="delete from tblsale where VNO='{$vno}'";       
        mysqli_query($con,$sqldel1);

        $sqldel2= "delete from tblvoucher where VNO='{$vno}'";
        mysqli_query($con,$sqldel2);
    }

    $dt = date("Y-m-d H:i:s");
    //save tblvoucher    
    $sql_voucher='insert into tblvoucher (VNO,CustomerID,TotalQty,TotalAmt,Dis,Tax,Total,
    UserID,Cash,Refund,Credit,Date,Chk) values ("'.$vno.'","'.$customerid.'","'.$totalqty.'",
    "'.$totalamt.'","'.$disc.'","'.$tax.'","'.$total.'","'.$userid.'","'.$cash.'",
    0,0,"'.$dt.'","Return")';
    if(mysqli_query($con,$sql_voucher)){
        // save tblsale
        $sql_sale = 'insert into tblsale (RemainID,CodeNo,ItemName,Qty,SellPrice,Date,VNO,CustomerID)  
        select RemainID,CodeNo,ItemName,Qty,SellPrice,"'.$dt.'","'.$vno.'","'.$customerid.'" 
        from tblsale_temp where UserID='.$userid;
        mysqli_query($con,$sql_sale);

        // decrease qty from remain
        $sql_temp = "select * from tblsale_temp where UserID={$userid}";
        $res_temp = mysqli_query($con,$sql_temp);
        if(mysqli_num_rows($res_temp) > 0){
            while($row_temp = mysqli_fetch_array($res_temp)){
                $sql_oldqty = "select Qty from tblremain where AID={$row_temp['RemainID']}";
                $res_oldqty = mysqli_query($con,$sql_oldqty);
                if(mysqli_num_rows($res_oldqty) > 0){
                    $row_qty = mysqli_fetch_array($res_oldqty);
                    $new_qty = $row_qty['Qty'] - $row_temp['Qty'];

                    $sqlremain = "update tblremain set Qty={$new_qty} where AID={$row_temp['RemainID']}";
                    mysqli_query($con,$sqlremain);
                }
            }
        }

        $del_temp = "delete from tblsale_temp where UserID={$userid}";
        mysqli_query($con,$del_temp);

        unset($_SESSION['totalamt']);
        unset($_SESSION['totalqty']);
        unset($_SESSION['editsalevno']);
        echo 1;
    }else{
        echo 0;
    }
}

// pause save
if($action=='pause'){
    $pname=$_POST['pname'];
    $pvno=date("Ymd-His");    
    $dt = date("Y-m-d H:i:s");
    $userid = $_SESSION["eadmin_userid"];
    $total = $_SESSION['totalamt'];
    $sql = 'insert into tblpausevoucher (ItemName,Qty,SellPrice,Date,
    PVNO,PName,RemainID,CodeNo,Total)  
    select ItemName,Qty,SellPrice,"'.$dt.'","'.$pvno.'","'.$pname.'",RemainID,CodeNo,"'.$total.'" 
    from tblsale_temp where UserID='.$userid;
    if(mysqli_query($con,$sql)){
        $sql_del = "delete from tblsale_temp where UserID={$userid}";
        mysqli_query($con,$sql_del);

        save_log($_SESSION["eadmin_username"]." သည် pvno: ".$pvno." ဖြင့် အရောင်းရပ်ဆိုင်းသွားသည်။");
        unset($_SESSION['totalamt']);
        unset($_SESSION['totalqty']);
        echo 1;
    }else{
        echo 0;
    }
}







?>