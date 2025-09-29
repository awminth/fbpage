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
        $a = " and (u.UserName like '%$search%' or e.Reason like '%$search%') ";
    }    
    $dtfrom = $_POST['dtfrom'];
    $dtto = $_POST['dtto'];
    $b = "";
    if($dtfrom!='' || $dtto!=''){
        $b = " and e.Date>='{$dtfrom}' and e.Date<='{$dtto}' ";
    }
    $userid = $_POST['userid'];
    $c = "";
    if($userid != ""){
        $c = " and e.UserID={$userid} ";
    }
    $sql="select e.*,u.UserName from tblexpense e,tbluser u 
    where e.UserID=u.AID  ".$a.$b.$c." 
    order by AID desc limit $offset,$limit_per_page";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">စဉ်</th>
            <th>အမည်</th>
            <th>အကြောင်းအရာ</th>
            <th>ကုန်ကျငွေ</th>
            <th>ရက်စွဲ</th>
            <th>File</th>
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
                <td>{$row["UserName"]}</td>
                <td>{$row["Reason"]}</td>
                <td>".number_format($row["Amount"])."</td>
                <td>".enDate($row["Date"])."</td>
                <td>{$row["ViewFile"]}</td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                            <a href='#' id='btneditfile' class='dropdown-item'
                                data-aid='{$row['AID']}' data-toggle='modal'
                                data-target='#filemodal'><i class='fas fa-edit text-primary'
                                style='font-size:13px;'></i>
                            Change File</a>
                            <div class='dropdown-divider'></div>
                            <a class='dropdown-item'";
                                $furl=roothtml.'upload/expense/'.$row['File'];
                            $out.="href='{$furl}'><i
                                    class='fas fa-download text-success'
                                    style='font-size:13px;'></i>
                                Download</a> 
                            <div class='dropdown-divider'></div> 
                            <a href='#' id='btnedit' class='dropdown-item'
                                data-aid='{$row['AID']}' data-toggle='modal'
                                data-target='#editmodal'><i class='fas fa-edit text-primary'
                                    style='font-size:13px;'></i>
                                Edit</a> 
                            <div class='dropdown-divider'></div> 
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}'
                                data-path='{$row['File']}'><i
                                    class='fas fa-trash text-danger'
                                    style='font-size:13px;'></i>
                                Delete</a>                      
                        </div>
                    </div>
                </td> 
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select e.AID from tblexpense e,tbluser u 
        where e.UserID=u.AID  ".$a.$b.$c." 
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
            <th>အမည်</th>
            <th>အကြောင်းအရာ</th>
            <th>ကုန်ကျငွေ</th>
            <th>ရက်စွဲ</th>
            <th>File</th>
            <th width="10%;" class="text-center">Action</th>            
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        '; 
        echo $out;
    }

}


if($action == 'save'){    
    $reason = $_POST["reason"];
    $amount = $_POST["amount"];
    $date = $_POST["date"];

    $userid=$_SESSION["eadmin_userid"];

    if($_FILES['file']['name'] != ''){
        $filename = $_FILES['file']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $valid_extension = array("xls","xlsx","docx","pdf");
        if(in_array($extension,$valid_extension)){
            $new_filename = rand() .".". $extension;
            $new_path = root."upload/expense/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                $sql = "insert into tblexpense (UserID,Reason,Amount,Date,File,ViewFile) 
                values ('{$userid}','{$reason}','{$amount}','{$date}','{$new_filename}','{$filename}')";
                if(mysqli_query($con,$sql)){
                    save_log($_SESSION["eadmin_username"]." သည် expense အားအသစ်သွင်းသွားသည်။");
                    echo "success";
                }
                else{
                    echo "fail";
                }
            }
        }
        else{
            echo "wrongtype";
        }
    }
    else{
        $sql = "insert into tblexpense (UserID,Reason,Amount,Date) 
        values ('{$userid}','{$reason}','{$amount}','{$date}')";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["eadmin_username"]." သည် expense အားအသစ်သွင်းသွားသည်။");
            echo "success";
        }
        else{
             echo "fail";
        }
    }    
    
}


if($action == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select e.*,u.UserName from tblexpense e,tbluser u where e.UserID=u.AID and e.AID=$aid";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/>                                              
                    <div class='form-group'>
                        <label for='usr'> အသုံးပြုသူအမည် :</label>
                        <input type='text' class='form-control readonly border-success' id='name' name='name' value='{$row['UserName']}'>
                    </div>
                    <div class='form-group'>
                        <label for='usr'> အကြောင်းအရာ :</label>
                        <input type='text' class='form-control border-success' id='reason' name='reason' value='{$row['Reason']}'>
                    </div> 
                    <div class='form-group'>
                        <label for='usr'> ကုန်ကျငွေ :</label>
                        <input type='number' class='form-control border-success' id='amount' name='amount' value='{$row['Amount']}'>
                    </div> 
                    <div class='form-group'>
                        <label for='usr'> ရက်စွဲ :</label>
                        <input type='date' class='form-control border-success' id='date' name='date' value='{$row['Date']}'>
                    </div>                                
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnupdate' class='btn btn-{$color}'><i class='fas fa-edit'></i>  ပြင်ဆင်မည်</button>
                </div>";
        }
        echo $out;
    }
}


if($action == 'update'){
    $aid = $_POST["aid"];  
    $reason = $_POST["reason"];
    $amount = $_POST["amount"];
    $date = $_POST["date"];
   
    $sql = "update tblexpense set Reason='{$reason}',Amount='{$amount}',
    Date='{$date}' where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]."သည် expense အား update လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}


if($action == 'delete'){
    $aid = $_POST["aid"];
    $path = $_POST["path"];
    $sql = "delete from tblexpense where AID=$aid";
    if(mysqli_query($con,$sql)){
        if($path!=""){
            unlink(root.'upload/expense/'.$path);
        }
        save_log($_SESSION["eadmin_username"]." သည် expense အားဖျက်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }  
    
}


if($action == 'editfile'){
    $aid = $_POST["aid"];
    $sql = "select AID,File,ViewFile from tblexpense where AID=$aid";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                <input type='hidden' id='aid1' name='aid1' value='{$row['AID']}'/> 
                <input type='hidden' id='pathfile' name='pathfile' value='{$row['File']}'/>
                <input type='hidden' id='action' name='action' value='editfilesave' />                              
                    <div class='form-group'>
                        <label for='usr'> Old File :</label>
                        <input type='text' class='form-control border-success' readonly value='{$row['ViewFile']}'>
                    </div>
                    <div class='form-group'>
                        <label for='usr'>Change File :</label>
                        <div class='border border-success p-1'>
                            <input type='file' accept='.pdf,.xls,.xlsx,.docx' name='file1' id='file1'>
                        </div>
                    </div>                               
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnupdatefile' class='btn btn-{$color}'><i class='fas fa-edit'></i>  ပြင်ဆင်မည်</button>
                </div>";
        }
        echo $out;
    }
}


if($action == 'editfilesave'){
    $aid = $_POST["aid1"];
    $pathfile = $_POST["pathfile"];

    if($_FILES['file1']['name'] != ''){
        $filename = $_FILES['file1']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file1']['tmp_name'];
        $valid_extension = array("xls","xlsx","docx","pdf");
        if(in_array($extension,$valid_extension)){
            $new_filename = rand() .".". $extension;
            $new_path = root."upload/expense/". $new_filename;

            if(move_uploaded_file($file,$new_path)){

                if($pathfile != ""){
                    unlink(root.'upload/expense/'.$pathfile);
                }

                $sql = "update tblexpense set File='{$new_filename}',ViewFile='{$filename}' 
                where AID=$aid";
                if(mysqli_query($con,$sql)){
                    save_log($_SESSION["eadmin_username"]." သည် expense file အား update သွားသည်။");
                    echo "success";
                }
                else{
                    echo "fail";
                }
            }
        }
        else{
            echo "wrongtype";
        }
    }
    else{
        echo "nofile";
    }
}


if($action == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){    
        $a = " and (u.UserName like '%$search%' or e.Reason like '%$search%') ";
    }    
    $dtfrom = $_POST['dtfrom'];
    $dtto = $_POST['dtto'];
    $b = "";
    if($dtfrom!='' || $dtto!=''){
        $b = " and e.Date>='{$dtfrom}' and e.Date<='{$dtto}' ";
    }
    $userid = $_POST['userid'];
    $c = "";
    if($userid != ""){
        $c = " and e.UserID={$userid} ";
    }
    $sql="select e.*,u.UserName from tblexpense e,tbluser u 
    where e.UserID=u.AID  ".$a.$b.$c." 
    order by AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "ExpenseReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="6" align="center"><h3>Expense</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">UserName</th>  
                <th style="border: 1px solid ;">Reason</th>  
                <th style="border: 1px solid ;">Amount</th>
                <th style="border: 1px solid ;">Date</th>  
                <th style="border: 1px solid ;">File</th>       
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["UserName"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Reason"].'</td>  
                    <td style="border: 1px solid ;">'.number_format($row["Amount"]).'</td> 
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>  
                    <td style="border: 1px solid ;">'.$row["ViewFile"].'</td> 
                
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
                <td colspan="6" align="center"><h3>Expense</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">UserName</th>  
                <th style="border: 1px solid ;">Reason</th>  
                <th style="border: 1px solid ;">Amount</th>
                <th style="border: 1px solid ;">Date</th>  
                <th style="border: 1px solid ;">File</th>       
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