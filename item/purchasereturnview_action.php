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
        $a = " and (r.CodeNo like '%$search%' or r.ItemName like '%$search%') ";
    }
    
    $from=$_POST['from'];
    $to=$_POST['to'];
    $supplier=$_POST['supplier'];
    $b="";
    if($from!='' || $to!=''){
        $b=" and pr.Date>='{$from}' and pr.Date<='{$to}' ";
    }
    $c="";
    if($supplier!=''){
        $c=" and pr.SupplierID={$supplier}";
    }
    $sql = "select pr.*,s.Supplier,r.ItemName,r.CodeNo from tblpurchasereturn pr ,tblsupplier s,tblremain r
     where s.AID=pr.SupplierID and r.AID=pr.RemainID ".$b.$c.$a." 
    order by AID desc limit $offset,$limit_per_page";    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table  class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">စဉ်</th>
                    <th>CodeNo</th>
                    <th>ItemName</th>
                    <th>Supplier</th>
                    <th>Quatity</th>
                    <th>PurchasePrice</th> 
                    <th>Total</th> 
                    <th>Action</th>                
                </tr>
            </thead>
            <tbody>
        ';
        $no=0;
        $totalqty=0;
        $totalamt=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;           
            $totalqty=$totalqty+$row["Qty"];
            $totalamt=$totalamt+$row["Amt"];
            $out.="<tr >
                <td>{$no}</td>
                <td>{$row["CodeNo"]}</td>
                <td>{$row["ItemName"]}</td>
                <td>{$row["Supplier"]}</td>
                <td>{$row["Qty"]}</td>
                <td class='text-right'>".number_format($row["Price"])."</td> 
                <td class='text-right'>".number_format($row["Amt"])."</td> 
                <td>
                    <a href='#' id='btndelete' data-aid='{$row['AID']}' 
                    data-qty='{$row["Qty"]}' 
                    data-remainid='{$row["RemainID"]}' 
                    data-supplierid='{$row["SupplierID"]}' class='btn btn-sm btn-danger'>Delete</a>
                </td>
                
            </tr>";
           
        }
        $out.="
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td>".number_format($totalqty)."</td>
            <td class='text-right'></td> 
            <td class='text-right'>".number_format($totalamt)."</td> 
            <td>
            
            </td>
        ";
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select pr.AID from tblpurchasereturn pr ,tblsupplier s,tblremain r
        where s.AID=pr.SupplierID and r.AID=pr.RemainID ".$b.$c.$a." 
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
    }else{
        $out.='
        <table  class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">စဉ်</th>
                    <th>CodeNo</th>
                    <th>ItemName</th>
                    <th>Quatity</th>
                    <th>PurchasePrice</th>                   
                </tr>
            </thead>
            <tbody>
            <tr>
                    <td colspan="5" class="text-center">No data</td>
                </tr>
            </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action == 'delete'){
    $aid = $_POST["aid"];
    $qty = $_POST["qty"]; 
    $remainid = $_POST["remainid"];   
    $supplierid = $_POST["supplierid"];   

    $sqldel="delete from tblpurchasereturn where AID={$aid}";
    if(mysqli_query($con,$sqldel)){

        $sql="update tblremain set Qty=Qty+{$qty} where AID={$remainid}";
        mysqli_query($con,$sql);
        
        $sql_detail = "delete from tblsupplierdetail where ReturnID='{$aid}'";
        mysqli_query($con,$sql_detail);

        save_log($_SESSION["eadmin_username"]."သည် Purchase Return အား Delete လုပ်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }   
          
   
}


if($action == 'excel'){
    $search = $_POST['ser'];

    $a = "";
    if($search != ''){ 
        $a = " and (r.CodeNo like '%$search%' or r.ItemName like '%$search%') ";
    }
    
    $from=$_POST['hfrom'];
    $to=$_POST['hto'];
    $supplier=$_POST['hsupplier'];
    $b="";
    if($from!='' || $to!=''){
        $b=" and pr.Date>='{$from}' and pr.Date<='{$to}' ";
    }
    $c="";
    if($supplier!=''){
        $c=" and pr.SupplierID={$supplier}";
    }

           
    $sql = "select pr.*,s.Supplier,r.ItemName,r.CodeNo from tblpurchasereturn pr ,tblsupplier s,tblremain r
     where s.AID=pr.SupplierID and r.AID=pr.RemainID ".$b.$c.$a." 
    order by AID desc "; 

    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "PurchaseReturnReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="6" align="center"><h3>Purchase Return</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Code No</th>  
                <th style="border: 1px solid ;">Item Name</th>  
                <th style="border: 1px solid ;">Qty</th>
                <th style="border: 1px solid ;">PurchasePrice</th>  
                <th style="border: 1px solid ;">Total</th>  
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["CodeNo"].'</td>  
                    <td style="border: 1px solid ;">'.$row["ItemName"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Qty"].'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Price"]).'</td>  
                    <td style="border: 1px solid ;">'.number_format($row["Amt"]).'</td>
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
                <td colspan="6" align="center"><h3>Purchase Return</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Code No</th>  
                <th style="border: 1px solid ;">Item Name</th>  
                <th style="border: 1px solid ;">Qty</th>
                <th style="border: 1px solid ;">PurchasePrice</th>  
                <th style="border: 1px solid ;">Total</th>  
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid ;" align="center">No data</td>
            </tr>';
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}

?>