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
        $a = " and c.Name like '%$search%' ";
    }        
    $sql="select m.*,c.Name from tblmessage m,tblcustomer c 
    where c.AID=m.ClientID ".$a." 
    order by m.AID desc limit $offset,$limit_per_page";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">စဉ်</th>
            <th width="20%">ရက်စွဲ</th>
            <th>Message</th>
            <th>Reply</th>
            <th>Customer Name</th>
            <th width="10%;" class="text-center">Action</th>          
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
                <td>{$row["Message"]}</td>
                <td>{$row["Reply"]}</td>
                <td>{$row["Name"]}</td> 
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                            <a href='#' id='btnedit' class='dropdown-item'
                                data-aid='{$row['AID']}'
                                data-message='{$row['Message']}'
                                data-toggle='modal'
                                data-target='#editmodal'><i class='fas fa-edit text-primary'
                                    style='font-size:13px;'></i>
                            Reply</a>
                            <div class='dropdown-divider'></div>      
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}'><i
                                    class='fas fa-trash text-danger' style='font-size:13px;'></i>
                            Delete</a>               
                        </div>
                    </div>
                </td>  
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select m.AID from tblmessage m,tblcustomer c 
        where c.AID=m.ClientID ".$a." 
        order by m.AID desc";
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
                if($next_id >= $total_links){
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
            <th width="20%">ရက်စွဲ</th>
            <th>Message</th>
            <th>Reply</th>
            <th>Customer Name</th>
            <th width="10%;">Action</th>          
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


if($action == 'update'){
    $aid = $_POST["aid"];
    $message = $_POST["message"];
    
    $sql = "update tblmessage set Reply='{$message}',Status=1 where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]."သည်  Reply လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}


if($action == 'delete'){

    $aid = $_POST["aid"];
    $sql = "delete from tblmessage where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]." သည် message ဖျက်သွားသည်။");
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
        $a = " and c.Name like '%$search%' ";
    }        
    $sql="select m.*,c.Name from tblmessage m,tblcustomer c 
    where c.AID=m.ClientID ".$a." 
    order by m.AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "MessageReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="5" align="center"><h3>Message</h3></td>
            </tr>
            <tr><td colspan="5"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">CustomerName</th>  
                <th style="border: 1px solid ;">Message</th>  
                <th style="border: 1px solid ;">Date</th>
                <th style="border: 1px solid ;">Reply</th>
       
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Message"].'</td>  
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>  
                    <td style="border: 1px solid ;">'.$row["Reply"].'</td>  
                
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
                <td colspan="5" align="center"><h3>Message</h3></td>
            </tr>
            <tr><td colspan="5"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">CustomerName</th>  
                <th style="border: 1px solid ;">Message</th>  
                <th style="border: 1px solid ;">Date</th>
                <th style="border: 1px solid ;">Reply</th>
       
            </tr>
            <tr>
                <td colspan="5" style="border: 1px solid ;" align="center">No data</td>
            </tr>';
    $out .= '</table>'; 
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
    }   
    
}




?>