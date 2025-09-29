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
    $a = " ";
    if($search != ''){ 
        $a = " and (c.Name like '%$search%' or v.OrderName like '%$search%') ";
    }       
    $sql="select v.*,Date(v.Date) as vdate,c.Name as cname   
    from tblordervoucher v,tblcustomer c 
    where v.Status=1 and v.CustomerID=c.AID ".$a." 
    order by v.AID desc limit $offset,$limit_per_page";    
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">စဉ်</th>
            <th>Date</th>                                        
            <th>Customer</th>
            <th>OrderName</th>
            <th>OrderPhone</th>                                      
            <th class="text-right">Total</th>          
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        $totalamt=0;
        $totaldis=0;
        $totaltax=0;
        $total=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $totalamt+=$row["TotalAmt"];
            $out.="<tr>
                <td>{$no}</td>
                <td>".enDate($row["vdate"])."</td>
                <td>{$row["cname"]}</td>
                <td>{$row["OrderName"]}</td>
                <td>{$row["OrderPhoneNo"]}</td> 
                <td class='text-right'>";
                    $out.=number_format($row["TotalAmt"]);
                    $out.="</td>                
                
            </tr>";
        }
        $out.="</tbody>";
        $out.="<tfoot>
                    <tr>                                                                               
                        <td colspan='5' class='text-right'>စုစုပေါင်း</td>
                        <td class='text-right' >";$out.=number_format($totalamt);
                        $out.="</td>                                    
                    </tr>
                </tfoot>";
        $out.="</table>";

        $sql_total="select v.AID  
        from tblordervoucher v,tblcustomer c 
        where v.Status=1 and v.CustomerID=c.AID 
        order by v.AID desc";
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
            <th>Customer</th>
            <th>OrderName</th>
            <th>OrderPhone</th>                                      
            <th class="text-right">Total</th>          
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="text-center">No data</td>
            </tr>
            </tbody>
        </table>
        ';
        echo $out;
    }

}


if($_POST["action"] == 'excel'){
    $search = $_POST['ser'];
    $a = " ";
    if($search != ''){ 
        $a = " and (c.Name like '%$search%' or v.OrderName like '%$search%') ";
    }       
    $sql="select v.*,Date(v.Date) as vdate,c.Name as cname   
    from tblordervoucher v,tblcustomer c 
    where v.Status=1 and v.CustomerID=c.AID ".$a." 
    order by v.AID desc"; 
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "OrderReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="9" align="center"><h3>Order Report</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">VNO</th> 
                <th style="border: 1px solid ;">Order Name</th>
                <th style="border: 1px solid ;">Order PhoneNo</th> 
                <th style="border: 1px solid ;">Order Address</th>
                <th style="border: 1px solid ;">Customer Name</th>  
                <th style="border: 1px solid ;">Total Qty</th>
                <th style="border: 1px solid ;">Total Amount</th>                  
                <th style="border: 1px solid ;">Date</th>
       
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["VNO"].'</td>  
                    <td style="border: 1px solid ;">'.$row["OrderName"].'</td>  
                    <td style="border: 1px solid ;">'.$row["OrderPhoneNo"].'</td> 
                    <td style="border: 1px solid ;">'.$row["OrderAddress"].'</td> 
                    <td style="border: 1px solid ;">'.$row["cname"].'</td> 
                    <td style="border: 1px solid ;">'.$row["TotalQty"].'</td> 
                    <td style="border: 1px solid ;">'.number_format($row["TotalAmt"]).'</td> 
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td> 
                
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
                <td colspan="9" align="center"><h3>Order Report</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">VNO</th> 
                <th style="border: 1px solid ;">Order Name</th>
                <th style="border: 1px solid ;">Order PhoneNo</th> 
                <th style="border: 1px solid ;">Order Address</th>
                <th style="border: 1px solid ;">Customer Name</th>  
                <th style="border: 1px solid ;">Total Qty</th>
                <th style="border: 1px solid ;">Total Amount</th>                  
                <th style="border: 1px solid ;">Date</th>       
            </tr>
            <tr>
                <td colspan="9" style="border: 1px solid ;" align="center">No data</td>
            </tr>';
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }  
}


?>