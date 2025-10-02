<?php
include('../config.php');

$action = $_POST['action'];
$userid = $_SESSION["eadmin_userid"];

//Reports
//PreOrder Confirm
if($action == 'show_confirm'){  
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
        $a .=" and CustomerName={$customer} ";
    }   
    $sql="SELECT * FROM tblpreordervoucher WHERE Chk='Confirm' ".$a." 
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
            $out.="<tr>
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
                    <div class='btn-group btn-group-sm'>
                        <a href='#' id='btnviewconfirm' data-toggle='tooltip' data-placement='bottom'
                            title='အသေးစိတ်ကြည့်မည်' 
                            data-vno='{$row['VNO']}' 
                            class='btn btn-success btn-sm'><i class='fas fa-eye'></i></a>
                        <a href='#' id='btndeleteconfirm' data-toggle='tooltip' data-placement='bottom'
                            title='ဖျက်သိမ်းမည်' 
                            data-vno='{$row['VNO']}'
                            class='btn btn-danger btn-sm'><i
                            class='fas fa-trash'></i></a>
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

        $sql_total="SELECT * FROM tblpreordervoucher WHERE Chk='Confirm' ".$a." 
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

if($action == "viewvoucher_confirm"){
    $vno = $_POST["vno"];
    printVoucher($vno);
}

if($action == "delete_confirm"){
    $vno = $_POST["vno"];
    deletepreorder($vno);
}

if($action == 'excel_confirm'){
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
    $sql="SELECT * FROM tblpreordervoucher WHERE Chk='Confirm' ".$a." 
    ORDER BY AID DESC";
    $cashier = GetString("SELECT UserName FROM tbluser WHERE AID='{$row["UserID"]}'");
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "PreOrder Confirm Reports".date('d-m-Y').".xls";
    $out .= '<head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="10" align="center"><h3>PreOrder Confirm Reports</h3></td>
            </tr>
            <tr><td colspan="10"><td></tr>
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
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>                 
                </tr>';
        }          
    }else{
        $out .= '
            <tr>
                <td style="border: 1px solid ;" colspan="10" align="center">No data found</td>   
            </tr>';
        
    }
    $out .= '</table>';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
}

//PreOrder Return
if($action == 'show_return'){  
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
        $a .=" and CustomerName={$customer} ";
    }   
    $sql="SELECT * FROM tblpreordervoucher WHERE Chk='Return' ".$a." 
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
            $out.="<tr>
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
                    <div class='btn-group btn-group-sm'>
                        <a href='#' id='btnviewreturn' data-toggle='tooltip' data-placement='bottom'
                            title='အသေးစိတ်ကြည့်မည်' 
                            data-vno='{$row['VNO']}' 
                            class='btn btn-success btn-sm'><i class='fas fa-eye'></i></a>
                        <a href='#' id='btndeletereturn' data-toggle='tooltip' data-placement='bottom'
                            title='ဖျက်သိမ်းမည်' 
                            data-vno='{$row['VNO']}'
                            class='btn btn-danger btn-sm'><i
                            class='fas fa-trash'></i></a>
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

        $sql_total="SELECT * FROM tblpreordervoucher WHERE Chk='Return' ".$a." 
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

if($action == "viewvoucher_return"){
    $vno = $_POST["vno"];
    printVoucher($vno);
}

if($action == "delete_return"){
    $vno = $_POST["vno"];
    deletepreorder($vno);
}

if($action == 'excel_return'){
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
    $sql="SELECT * FROM tblpreordervoucher WHERE Chk='Return' ".$a." 
    ORDER BY AID DESC";
    $cashier = GetString("SELECT UserName FROM tbluser WHERE AID='{$row["UserID"]}'");
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "PreOrder Return Reports".date('d-m-Y').".xls";
    $out .= '<head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="10" align="center"><h3>PreOrder Return Reports</h3></td>
            </tr>
            <tr><td colspan="10"><td></tr>
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
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>                 
                </tr>';
        }          
    }else{
        $out .= '
            <tr>
                <td style="border: 1px solid ;" colspan="10" align="center">No data found</td>   
            </tr>';
        
    }
    $out .= '</table>';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
}

//PreOrder Cancel
if($action == 'show_cancel'){  
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
        $a .=" and CustomerName={$customer} ";
    }   
    $sql="SELECT * FROM tblpreordervoucher WHERE Chk='Cancel' ".$a." 
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
            $out.="<tr>
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
                    <div class='btn-group btn-group-sm'>
                        <a href='#' id='btnviewcancel' data-toggle='tooltip' data-placement='bottom'
                            title='အသေးစိတ်ကြည့်မည်' 
                            data-vno='{$row['VNO']}' 
                            class='btn btn-success btn-sm'><i class='fas fa-eye'></i></a>
                        <a href='#' id='btndeletecancel' data-toggle='tooltip' data-placement='bottom'
                            title='ဖျက်သိမ်းမည်' 
                            data-vno='{$row['VNO']}'
                            class='btn btn-danger btn-sm'><i
                            class='fas fa-trash'></i></a>
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

        $sql_total="SELECT * FROM tblpreordervoucher WHERE Chk='Cancel' ".$a." 
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

if($action == "viewvoucher_cancel"){
    $vno = $_POST["vno"];
    printVoucher($vno);
}

if($action == "delete_cancel"){
    $vno = $_POST["vno"];
    deletepreorder($vno);
}

if($action == 'excel_cancel'){
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
    $sql="SELECT * FROM tblpreordervoucher WHERE Chk='Cancel' ".$a." 
    ORDER BY AID DESC";
    $cashier = GetString("SELECT UserName FROM tbluser WHERE AID='{$row["UserID"]}'");
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "PreOrder Cancel Reports".date('d-m-Y').".xls";
    $out .= '<head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="10" align="center"><h3>PreOrder Cancel Reports</h3></td>
            </tr>
            <tr><td colspan="10"><td></tr>
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
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>                 
                </tr>';
        }          
    }else{
        $out .= '
            <tr>
                <td style="border: 1px solid ;" colspan="10" align="center">No data found</td>   
            </tr>';
        
    }
    $out .= '</table>';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
}

?>