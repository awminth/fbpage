<?php
include('../config.php');

$action = $_POST["action"];


if($action == 'show'){  
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
        $a = " and (v.VNO like '%$search%' or c.Name like '%$search%' or u.UserName like '%$search%') ";
    } 
    $from=$_POST['from'];  
    $to=$_POST['to'];     
    $customer=$_POST['customer'];   
    $b="";
    $c="";
    if($from!='' || $to!=''){
        $b=" and Date(v.Date)>='{$from}' and Date(v.Date)<='{$to}' ";
    }  
    if($customer!=''){
        $c=" and v.CustomerID={$customer} ";
    }   
    $sql="select v.*,Date(v.Date) as vdate,c.Name,u.UserName 
    from tblvoucher v,tblcustomer c,tbluser u 
    where v.UserID=u.AID and v.CustomerID=c.AID and v.Chk='Credit' ".$b.$c.$a." 
    order by AID desc limit $offset,$limit_per_page";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered tabel-sm table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">စဉ်</th>
            <th>Date</th>                                        
            <th>VNO</th>
            <th>Customer</th>
            <th class="text-right">SubTotal</th>                                                                             
            <th>Disc</th>
            <th>Tax</th>
            <th class="text-right">Total</th>  
            <th class="text-right">Cash</th> 
            <th class="text-right">Credit</th> 
            <th>Cashier</th>     
            <th width="10%;" class="text-center">Actions</th>       
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        $totalamt=0;
        $totaldis=0;
        $totaltax=0;
        $total=0;
        $totalcash=0;
        $totalcredit=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $totalamt+=$row["TotalAmt"];
            $totaldis+=$row["Dis"];
            $totaltax+=$row["Tax"];
            $total+=$row["Total"];
            $totalcash+=$row["Cash"];
            $totalcredit+=$row["Credit"];
            $out.="<tr>
                <td>{$no}</td>
                <td>".enDate($row["vdate"])."</td>
                <td>{$row["VNO"]}</td>
                <td>{$row["Name"]}</td>
                <td class='text-right' >".number_format($row["TotalAmt"])."</td>                                       
                <td  class='text-right' >".number_format($row["Dis"])."</td>
                <td class='text-right' >".number_format($row["Tax"])."</td>
                <td class='text-right' >".number_format($row["Total"])."</td>
                <td class='text-right' >".number_format($row["Cash"])."</td>
                <td class='text-right' >".number_format($row["Credit"])."</td>
                <td >{$row["UserName"]}</td> 
                <td class='text-center'>
                    <div class='btn-group btn-group-sm'>
                        <a href='#' id='customerpay' data-toggle='tooltip' data-placement='bottom'
                            title='Pay' data-vno='{$row['VNO']}'
                            data-customerid='{$row['CustomerID']}'
                            data-cusname='{$row['Name']}'
                            data-userid='{$row['UserID']}'
                            data-date='{$row['Date']}'
                            data-totalamt='{$row['TotalAmt']}'
                            data-total='{$row['Total']}'
                            data-dis='{$row['Dis']}'
                            data-tax='{$row['Tax']}'
                            data-cash='{$row['Cash']}'
                            data-refund='{$row['Refund']}' 
                            data-credit='{$row['Credit']}' 
                            data-chk='{$row['Chk']}' 
                            class='btn btn-info btn-sm'><i class='fas fa-dollar-sign'></i></a>
                        <a href='#' id='viewsell' data-toggle='tooltip' data-placement='bottom'
                            title='အသေးစိတ်ကြည့်မည်' data-vno='{$row['VNO']}'
                            data-customerid='{$row['CustomerID']}'
                            data-cusname='{$row['Name']}'
                            data-userid='{$row['UserID']}'
                            data-date='{$row['Date']}'
                            data-totalamt='{$row['TotalAmt']}'
                            data-total='{$row['Total']}'
                            data-dis='{$row['Dis']}'
                            data-tax='{$row['Tax']}'
                            data-cash='{$row['Cash']}'
                            data-refund='{$row['Refund']}' 
                            data-credit='{$row['Credit']}' 
                            data-chk='{$row['Chk']}' 
                            class='btn btn-success btn-sm'><i class='fas fa-eye'></i></a>
                        <a style='display:none;' href='#' id='editsell' data-toggle='tooltip' data-placement='bottom'
                            title='ပြင်ဆင်မည်' 
                            data-vno='{$row['VNO']}'
                            data-customerid='{$row['CustomerID']}'
                            data-customername='{$row['Name']}'
                            data-totalamt='{$row['TotalQty']}'
                            data-totalqty='{$row['TotalAmt']}' 
                            data-chk='{$row['Chk']}'  class='btn btn-info btn-sm'><i
                            class='fas fa-edit'></i></a>
                        <a href='#' data-vno='{$row['VNO']}' id='deletesell' data-toggle='tooltip' data-placement='bottom'
                            title='ဖျက်သိမ်းမည်' class='btn btn-danger btn-sm'><i
                            class='fas fa-trash'></i></a>
                    </div>
                </td>
            </tr>";
        }
        $out.="</tbody>";
        $out.="<tfoot>
                    <tr>                                                                               
                        <td></td>
                        <td></td>  
                        <td></td>                                     
                        <td>စုစုပေါင်း</td>
                        <td class='text-right' >".number_format($totalamt)."</td>                                       
                        <td class='text-right'>".number_format($totaldis)."</td>
                        <td class='text-right' >".number_format($totaltax)."</td>
                        <td class='text-right' >".number_format($total)."</td>
                        <td class='text-right' >".number_format($totalcash)."</td>
                        <td class='text-right' >".number_format($totalcredit)."</td>
                        <td></td>   
                        <td></td>                                 
                    </tr>
                </tfoot>";
        $out.="</table>";

        $sql_total="select v.AID  
        from tblvoucher v,tblcustomer c,tbluser u 
        where v.UserID=u.AID and v.CustomerID=c.AID and v.Chk='Credit' ".$b.$c.$a." 
        order by AID desc";
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
            <th>Date</th>                                        
            <th>VNO</th>
            <th>Customer</th>
            <th class="text-right">စုစုပေါင်း</th>                                                                             
            <th>Disc</th>
            <th>Tax</th>
            <th class="text-right">စုစုပေါင်း</th>  
            <th class="text-right">Cash</th>  
            <th class="text-right">Credit</th>  
            <th>Cashier</th>          
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="12" class="text-center">No data</td>
            </tr>
            </tbody>
        </table>
        ';
        echo $out;
    }
}

if($action == 'view'){
    $vno = $_POST["vno"];
    $customerid = $_POST["customerid"];
    $cusname = $_POST["cusname"];
    $userid = $_POST["userid"];
    $date = $_POST["date"];
    $totalamt = $_POST["totalamt"];
    $total = $_POST["total"];
    $dis = $_POST["dis"];
    $tax = $_POST["tax"];
    $cash = $_POST["cash"];
    // $refund = $_POST["refund"];
    // $credit = $_POST["credit"];
    $chk = $_POST["chk"];
    $txt1 = "";
    $txt2 = 0;
    $str = "";
    if($chk == "Cash"){
        $txt1 = "ပြန်အမ်းငွေ";
        $txt2 = $_POST["refund"];
        $str = "Cash";
    }else if($chk == "Credit"){
        $txt1 = "Credit";
        $txt2 = $_POST["credit"];
        $str = "Pay";
    }else if($chk == "Return"){
        $txt1 = "";
        $txt2 = "";
        $str = "Return Amt";
    }
    $sellname=GetString("select UserName from tbluser where AID={$userid}");   
    
    $out = "";    
    $out="<h5 class='text-center'>{$_SESSION['shopname']}</h5>
    <div align='center'><img src='".roothtml."lib/images/logo2.jpg' style='width: 100px;height:auto;'></img></div>
    <p class='text-center txt'>{$_SESSION['shopaddress']}<br>
    {$_SESSION['shopphno']}<br>
    {$_SESSION['shopemail']}</p>
    <hr>
    <p class='txtl fs'>
        Date :  ".enDate($date)."<br>
        VoucherNo : {$vno}<br>
        Customer Name: {$cusname}<br>
        Sell Name : {$sellname}<br>
    </p>
    <table class='table table-bordered text-sm' frame=hsides rules=rows width='100%'>
        <tr>
            <th class='txtl'>ItemName</th>
            <th class='text-center txtc'>Qty</th>
            <th class='text-right txtr'>Price</th>
            <th class='text-right txtr'>Total</th>
        </tr>
    ";
    $sql = "select * from tblsale where VNO='{$vno}'";
    $result = mysqli_query($con,$sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="                    
            <tr>
                <td>{$row['ItemName']}</td>
                <td class='text-center txtc'>{$row['Qty']}</td>
                <td class='text-right txtr'>".number_format($row['SellPrice'])."</td>";
                $printtotal=$row['Qty']*$row['SellPrice'];
                $out.="<td class='text-right txtr'>".number_format($printtotal)."</td>
            </tr>                    
            ";
        }
    $out.="
        <tr class='text-right txtr'>
            <td colspan='3'>
                Total<br>
                Discount:<br>
                Tax:<br>
                SubTotal:<br>
                {$str}:<br>
                {$txt1}
            </td>
            <td>
                ".number_format($totalamt)."<br>
                {$dis}<br>
                {$tax}<br>
                ".number_format($total)."<br>
                ".number_format($cash)."<br>
                ".$txt2."
            </td>
        </tr>                             
        <tr class='text-center txtc'>
            <td colspan='4'>---Thank You---</td>   
        </tr>
        </table>
        ";            
        echo $out;
    }
}

if($action == 'edit'){
    $vno = $_POST["vno"];
    $customerid = $_POST["customerid"];
    $customername = $_POST["customername"];
    $totalamt = $_POST["totalamt"];
    $totalqty = $_POST["totalqty"];
    $userid = $_SESSION["eadmin_userid"];
    $chk = $_POST["chk"];

    $sql_temp = 'insert into tblsale_temp (RemainID,CodeNo,ItemName,Qty,SellPrice,UserID) 
    select RemainID,CodeNo,ItemName,Qty,SellPrice,"'.$userid.'"  
    from tblsale where VNO="'.$vno.'"';
    if(mysqli_query($con,$sql_temp)){
        $_SESSION["editcustomerid"] = $customerid;
        $_SESSION["editcustomername"] = $customername;
        $_SESSION["editsalevno"] = $vno; 
        $_SESSION["editsalechk"] = $chk; 
        echo 1;
    }else{
        echo 0;
    }
}

if($action == 'delete'){
    $vno = $_POST["vno"];
    $sqlsale="select * from tblsale where VNO='{$vno}'";
    $result=mysqli_query($con,$sqlsale);
    if(mysqli_num_rows($result)>0){
        while( $rowqty = mysqli_fetch_array($result)){      
       
            $sqlremain="select Qty from tblremain where CodeNo='{$rowqty['CodeNo']}'";
            $result1=mysqli_query($con,$sqlremain);
            $rowqty1 = mysqli_fetch_array($result1);
            $newqty=$rowqty['Qty']+$rowqty1['Qty'];

            $sqlremain1="update tblremain set Qty={$newqty} where CodeNo='{$rowqty['CodeNo']}'";
            mysqli_query($con,$sqlremain1);
        }

        $sqldel1 ="delete from tblsale where VNO='{$vno}'";       
        mysqli_query($con,$sqldel1);
        $sqldel2= "delete from tblvoucher where VNO='{$vno}'";
        mysqli_query($con,$sqldel2);
        $sqldel3 = "delete from tblcreditdetail where VNO='{$vno}'";
        mysqli_query($con,$sqldel3);

        save_log($_SESSION["eadmin_username"]." သည် vno: ".$vno." ၏ credit အရောင်းစာရင်းအားဖျက်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

if($action == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){  
        $a = " and (v.VNO like '%$search%' or c.Name like '%$search%' or u.UserName like '%$search%') ";
    } 
    $from=$_POST['hfrom'];  
    $to=$_POST['hto'];     
    $customer=$_POST['hcustomer'];   
    $b="";
    $c="";
    if($from!='' || $to!=''){
        $b=" and Date(v.Date)>='{$from}' and Date(v.Date)<='{$to}' ";
    }  
    if($customer!=''){
        $c=" and v.CustomerID={$customer} ";
    }   
    $sql="select v.*,Date(v.Date) as vdate,c.Name,u.UserName 
    from tblvoucher v,tblcustomer c,tbluser u 
    where v.UserID=u.AID and v.CustomerID=c.AID and v.Chk='Credit' ".$b.$c.$a." 
    order by AID desc ";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "SaleReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="11" align="center"><h3>Cash Report</h3></td>
            </tr>
            <tr><td colspan="11"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">VNO</th>  
                <th style="border: 1px solid ;">Customer</th>  
                <th style="border: 1px solid ;">TotalQty</th>
                <th style="border: 1px solid ;">Discount</th>  
                <th style="border: 1px solid ;">Tax</th>  
                <th style="border: 1px solid ;">TotalAmt</th>
                <th style="border: 1px solid ;">Cash</th>
                <th style="border: 1px solid ;">Credit</th>
                <th style="border: 1px solid ;">Cashier</th>  
                <th style="border: 1px solid ;">Date</th>  
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["VNO"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>  
                    <td style="border: 1px solid ;">'.$row["TotalQty"].'</td>
                    <td style="border: 1px solid ;">'.$row["Dis"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Tax"].'</td>  
                    <td style="border: 1px solid ;">'.number_format($row["Total"]).'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Cash"]).'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Credit"]).'</td>
                    <td style="border: 1px solid ;">'.$row["UserName"].'</td>  
                    <td style="border: 1px solid ;">'.enDate($row["vdate"]).'</td>     
                </tr>';
        }
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }else{
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="11" align="center"><h3>Cash Report</h3></td>
            </tr>
            <tr><td colspan="11"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">VNO</th>  
                <th style="border: 1px solid ;">Customer</th>  
                <th style="border: 1px solid ;">TotalQty</th>
                <th style="border: 1px solid ;">Discount</th>  
                <th style="border: 1px solid ;">Tax</th>  
                <th style="border: 1px solid ;">TotalAmt</th>
                <th style="border: 1px solid ;">Cash</th>
                <th style="border: 1px solid ;">Refund</th>
                <th style="border: 1px solid ;">Credit</th>  
                <th style="border: 1px solid ;">Date</th>  
            </tr>
            <tr>
                <td colspan="11" style="border: 1px solid ;" align="center">No data</td>
            </tr>';
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}

if($_POST["action"] == 'customerpayview'){
    $customerid=$_POST['customerid'];
    $cusname = $_POST["cusname"];
    $credit = $_POST["credit"];
    $vno= $_POST["vno"];
    $userid = $_POST["userid"];
  
    $out="
    <div class='modal-header bg-".$color."'>
        <h4 class='modal-title'>Customer Pay</h4>
        <button type='button' class='close text-white' data-dismiss='modal'>&times;</button>
    </div>
    <form id='frm' method='POST' enctype='multipart/form-data'>
        <!-- Modal body -->
        <input type='hidden' name='action' id='action' value='save'>
        <input type='hidden' name='vno' id='vno' value='".$vno."'>
        <input type='hidden' name='userid' id='userid' value='".$userid."'>
        <input type='hidden' name='customerid' id='customerid' value='".$customerid."'>
        <div class='modal-body' data-spy='scroll' data-offset='50'>
            <div class='form-group'>
                <label for='usr'>Customer Name:</label>
                <input type='text' class='form-control border-success' name='cusname' value='".$cusname."'
                    placeholder='Customer Name'>
            </div>
            <div class='form-group'>
                <label for='usr'>Credit:</label>
                <input type='number' value='".$credit."' class='form-control border-success' name='credit'
                    placeholder='Credit'>
            </div>
            <div class='form-group'>
                <label for='usr'>Pay:</label>
                <input type='number' value='0' class='form-control border-success' name='pay'
                    placeholder='Pay'>
            </div>
            <div class='form-group'>
                <label for='usr'>Date:</label>
                <input type='date' class='form-control border-success' name='date' value='".date('Y-m-d')."'
                    placeholder='Date'>
            </div>
        </div>
        <div class='modal-footer'>
            <button type='submit' id='btnsave' class='btn btn-".$color."'><i class='fas fa-save'></i>
                အသစ်သွင်းမည်</button>
        </div>
    </form>";
    echo $out;
}

if($_POST["action"] == 'savecustomerpay'){
    $pay=$_POST['pay'];
    $customerid = $_POST["customerid"];
    $date = $_POST["date"];
    $vno= $_POST["vno"];
    $userid = $_POST["userid"];
  
    $sql="insert into tblcreditpay (CustomerID,Amt,Date,UserID,
    VNO) values 
    ('{$customerid}','{$pay}','{$date}','{$userid}','{$vno}')";
    if(mysqli_query($con,$sql)){
        $sqlupdate="update tblvoucher set Credit=Credit-{$pay},Cash=Cash+{$pay} where VNO='{$vno}'";
        mysqli_query($con,$sqlupdate);
        save_log($_SESSION["eadmin_username"]."သည် Customer Credit Pay အား Save လုပ်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }     
}



?>