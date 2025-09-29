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
    $sql="select r.*,c.Category,s.Supplier 
    from tblremain r,tblcategory c,tblsupplier s 
    where r.Qty<=3 and r.CategoryID=c.AID and r.SupplierID=s.AID ".$a."  
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
            <th>Quatity</th>
            <th>PurchasePrice</th>
            <th>SellPrice</th>
            <th>Category</th>
            <th>Supplier</th>
            <th width="7%" class="text-center">Image</th>       
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["CodeNo"]}</td>
                <td>{$row["ItemName"]}</td>
                <td>{$row["Qty"]}</td>
                <td>".number_format($row["PurchasePrice"])."</td>  
                <td>".number_format($row["SellPrice"])."</td>
                <td>{$row["Category"]}</td>
                <td>{$row["Supplier"]}</td>               
                <td class='text-center'>
                    <a href='#' id='btnview' class='dropdown-item'
                        data-aid='{$row['AID']}'
                        data-toggle='modal' data-target='#viewmodal'><i
                        class='fas fa-camera text-primary'
                        style='font-size:15px;'></i>
                    </a>
                </td>
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select r.AID  
        from tblremain r,tblcategory c,tblsupplier s 
        where r.Qty<=3 and r.CategoryID=c.AID and r.SupplierID=s.AID ".$a."  
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
            <th>SellPrice</th>
            <th>Category</th>
            <th>Supplier</th>
            <th width="7%" class="text-center">Image</th>       
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


if($_POST["action"] == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){    
        $a = " and (r.CodeNo like '%$search%' or r.ItemName like '%$search%') ";
    }     
    $sql="select r.*,c.Category,s.Supplier 
    from tblremain r,tblcategory c,tblsupplier s 
    where r.Qty<=3 and r.CategoryID=c.AID and r.SupplierID=s.AID ".$a."  
    order by AID desc"; 
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "NotiRemainReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="8" align="center"><h3>လက်ကျန်စာရင်းသတိပေးချက်</h3></td>
            </tr>
            <tr><td colspan="8"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">CodeNo</th>  
                <th style="border: 1px solid ;">ItemName</th>  
                <th style="border: 1px solid ;">Qty</th>
                <th style="border: 1px solid ;">PurchasePrice</th>  
                <th style="border: 1px solid ;">SellPrice</th>  
                <th style="border: 1px solid ;">Category</th>  
                <th style="border: 1px solid ;">Supplier</th>
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
                    <td style="border: 1px solid ;">'.number_format($row["PurchasePrice"]).'</td>  
                    <td style="border: 1px solid ;">'.number_format($row["SellPrice"]).'</td>
                    <td style="border: 1px solid ;">'.$row["Category"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Supplier"].'</td>  
                
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
                <td colspan="8" align="center"><h3>လက်ကျန်စာရင်းသတိပေးချက်</h3></td>
            </tr>
            <tr><td colspan="8"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">CodeNo</th>  
                <th style="border: 1px solid ;">ItemName</th>  
                <th style="border: 1px solid ;">Qty</th>
                <th style="border: 1px solid ;">PurchasePrice</th>  
                <th style="border: 1px solid ;">SellPrice</th>  
                <th style="border: 1px solid ;">Category</th>  
                <th style="border: 1px solid ;">Supplier</th>
            </tr>
            <tr>
                <td colspan="8" style="border: 1px solid ;" align="center">No data</td>
            </tr>';
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}


?>