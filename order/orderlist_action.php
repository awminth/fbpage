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
        $a = " and (v.OrderName like '%$search%' or v.VNO like '%$search%') ";
    }   
    $sql="select v.*  
    from tblordervoucher v 
    where v.Status=0 ".$a." 
    order by AID desc limit $offset,$limit_per_page";
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">No</th>
            <th>Date</th>
            <th>VNO</th>
            <th>Order Name</th>
            <th>Order PhoneNo</th>
            <th>Order Address</th>
            <th>Qty</th>                                        
            <th>Total</th>
            <th width="10%;">Actions</th>         
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>
                <td>{$no}</td>
                <td>".enDate($row["Date"])."</td>
                <td>{$row["VNO"]}</td>
                <td>{$row["OrderName"]}</td>
                <td>{$row["OrderPhoneNo"]}</td>
                <td>{$row["OrderAddress"]}</td>
                <td>{$row["TotalQty"]}</td>  
                <td>".number_format($row["Total"])."</td>                              
                <td class='text-right py-0 align-middle'>
                    <div class='btn-group btn-group-sm'>
                        <a href='#' id='vieworder' data-toggle='tooltip' data-placement='bottom'
                            title='အသေးစိတ်ကြည့်မည်' data-vno='{$row['VNO']}'
                            class='btn btn-success btn-sm'><i class='fas fa-eye'></i></a>
                        <a href='#' id='btnconfrim' data-toggle='tooltip' data-placement='bottom'
                            title='အတည်ပြုမည်' data-vno='{$row['VNO']}'
                            class='btn btn-info btn-sm'><i
                            class='fas fa-edit'></i></a>
                        <a href='#' data-vno='{$row['VNO']}'  id='deletesell' 
                            data-toggle='tooltip' data-placement='bottom'
                            title='ဖျက်သိမ်းမည်' class='btn btn-danger btn-sm'><i
                            class='fas fa-trash'></i></a>
                    </div>
                </td>
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select v.AID   
        from tblordervoucher v 
        where v.Status=0 ".$a." 
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
            <th width="7%;">No</th>
            <th>Date</th>
            <th>VNO</th>
            <th>Order Name</th>
            <th>Order PhoneNo</th>
            <th>Order Address</th>
            <th>Qty</th>                                        
            <th>Total</th>
            <th width="10%;">Actions</th>         
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="9" class="text-center">No data</td>
            </tr>
            </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action == 'view'){
    $vno = $_POST["vno"];
    $out = "";
    $sql_v = "select * from tblordervoucher where VNO='{$vno}'";
    $res_v = mysqli_query($con,$sql_v);
    if(mysqli_num_rows($res_v) > 0){
        $row_v = mysqli_fetch_array($res_v);
        $out="<h5 class='text-center'>{$_SESSION['shopname']}</h5>
        <p class='text-center txt'>{$_SESSION['shopaddress']}<br>
        {$_SESSION['shopphno']}<br>
        {$_SESSION['shopemail']}</p>
        <hr>
        <p class='txtl fs'>
            VNO : {$vno}<br>
            Date : ".enDate($row_v["Date"])."<br>
            Order Name: {$row_v["OrderName"]} <br> 
            Phone No : {$row_v["OrderPhoneNo"]} <br>
            Order Address : {$row_v["OrderAddress"]}
        </p>
        <table class='table table-bordered text-sm' frame=hsides rules=rows width='100%'>
            <tr>
                <th class='txtl'>ItemName</th>
                <th class='text-center txtc'>Qty</th>
                <th class='text-right txtr'>Price</th>
                <th class='text-right txtr'>Total</th>
            </tr>
        ";
        $sql = "select * from tblordersale where VNO='{$vno}'";
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
                    </tr>";
            }
        $out.="
            <tr class='text-right txtr'>
                <td colspan='3'>Total</td>
                <td>".number_format($row_v["Total"])."</td>
            </tr>            
            <tr class='text-center txtc'>
                <td colspan='4'>---Thank You---</td>   
            </tr>
        </table>
        <br>"; 
        }
    }   
    echo $out;
}

if($action=='confrim'){
    $vno=$_POST['vno'];
    $sqlvoucher="update tblordervoucher set Status=1 where VNO='{$vno}'";
    if(mysqli_query($con,$sqlvoucher)){
        $sqlsale="select * from tblordersale where VNO='{$vno}'";
        $result=mysqli_query($con,$sqlsale);
        if(mysqli_num_rows($result)>0){
            while( $rowqty = mysqli_fetch_array($result)){      
        
                $sqlremain="select Qty from tblremain where CodeNo='{$rowqty['CodeNo']}'";
                $result1=mysqli_query($con,$sqlremain);
                $rowqty1 = mysqli_fetch_array($result1);
                $newqty= $rowqty1['Qty'] - $rowqty['Qty'];

                $sqlremain1="update tblremain set Qty={$newqty} where CodeNo='{$rowqty['CodeNo']}'";
                mysqli_query($con,$sqlremain1);

                $sql_u = "update tblordersale set Status=1 where VNO='{$vno}'";
                mysqli_query($con,$sql_u);
            }
        }
        save_log($_SESSION["eadmin_username"]." သည် vno: ".$vno." ၏ အော်ဒါစာရင်း အား confirm လုပ်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

if($action == 'delete'){
    $vno = $_POST["vno"];
    $sqlvoucher="update tblordervoucher set Status=2 where VNO='{$vno}'"; 
    if(mysqli_query($con,$sqlvoucher)){
        save_log($_SESSION["eadmin_username"]." သည် vno: ".$vno." ၏ အော်ဒါစာရင်း အားဖျက်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}  

if($action == 'excel'){
    $search = $_POST['search'];
    $a = "";
    if($search != ''){      
        $a = " and (v.OrderName like '%$search%' or v.VNO like '%$search%') ";
    }   
    $sql="select v.*  
    from tblordervoucher v 
    where v.Status=0 ".$a." 
    order by AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "OrderReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="8" align="center"><h3>အော်ဒါစာရင်း</h3></td>
            </tr>
            <tr><td colspan="8"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Date</th>  
                <th style="border: 1px solid ;">VNO</th>  
                <th style="border: 1px solid ;">OrderName</th>
                <th style="border: 1px solid ;">OrderPhoneNo</th>
                <th style="border: 1px solid ;">OrderAddress</th>
                <th style="border: 1px solid ;">Qty</th>  
                <th style="border: 1px solid ;">Total</th> 
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>  
                    <td style="border: 1px solid ;">'.$row["VNO"].'</td>  
                    <td style="border: 1px solid ;">'.$row["OrderName"].'</td> 
                    <td style="border: 1px solid ;">'.$row["OrderPhoneNo"].'</td> 
                    <td style="border: 1px solid ;">'.$row["OrderAddress"].'</td> 
                    <td style="border: 1px solid ;">'.$row["TotalQty"].'</td>  
                    <td style="border: 1px solid ;">'.number_format($row["Total"]).'</td>  
                
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
                <td colspan="8" align="center"><h3>အော်ဒါစာရင်း</h3></td>
            </tr>
            <tr><td colspan="8"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Date</th>  
                <th style="border: 1px solid ;">VNO</th>  
                <th style="border: 1px solid ;">OrderName</th>
                <th style="border: 1px solid ;">OrderPhoneNo</th>
                <th style="border: 1px solid ;">OrderAddress</th>
                <th style="border: 1px solid ;">Qty</th>  
                <th style="border: 1px solid ;">Total</th> 
            </tr>
            <tr>
                <td colspan="8">No data</td>
            </tr>
        </table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
      
}


?>