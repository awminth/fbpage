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
    $no=0;
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
        $a = " where UserName like '%$search%' or UserType like '%$search%' ";
    }      
    $sql="select * from tbluser ".$a." 
    order by AID desc limit {$offset},{$limit_per_page}";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">No</th>
            <th>User Name</th>
            <th>User Type</th>
            <th width="10%;" class="text-center">Action</th>            
        </tr>
        </thead>
        <tbody>
        ';        
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["UserName"]}</td>
                <td>{$row["UserType"]}</td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                            <a href='#' id='btnedit' class='dropdown-item'
                                data-aid='{$row['AID']}' data-toggle='modal'
                                data-target='#editmodal'><i class='fas fa-edit text-primary'
                                style='font-size:13px;'></i>
                            Edit</a>
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}'><i
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

        $sql_total="select AID from tbluser ".$a." 
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
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">No</th>
            <th>User Name</th>
            <th>User Type</th>
            <th width="10%;" class="text-center">Action</th>            
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        '; 
        echo $out;
    }

}


if($action == 'save'){
    $username = $_POST["username"];
    $password = $_POST["password"];
    $usertype = $_POST["usertype"];
    $sql = "insert into tbluser (UserName,Password,UserType) 
    values ('{$username}','{$password}','{$usertype}')";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]." သည် user အားအသစ်သွင်းသွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}


if($action == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select * from tbluser where AID=$aid";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/>                                              
                    <div class='form-group'>
                        <label for='usr'> User Name :</label>
                        <input type='text' class='form-control border-success' id='username1' name='username1' value='{$row['UserName']}'>
                    </div>
                    <div class='form-group'>
                        <label for='usr'>Password :</label>
                        <input type='password' value='{$row['Password']}' required class='form-control border-success' name='password1'
                            id='password1'>
                    </div>
                    <div class='form-group'>
                        <label for='usr'>User Type :</label>
                        <select required class='form-control border-success' name='usertype1' id='usertype1'>
                            <option value='{$row['UserType']}'>{$row['UserType']}</option>
                            <option value='Admin'>Admin</option>
                            <option value='User'>User</option>                            
                        </select>
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
    $username = $_POST["username"];
    $password = $_POST["password"];
    $usertype = $_POST["usertype"];
    
    $sql = "update tbluser set UserName='{$username}',Password='{$password}',
    UserType='{$usertype}' where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]."သည် user အား update လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}


if($action == 'delete'){
    $aid = $_POST["aid"];
    $sql = "delete from tbluser where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]." သည် user အားဖျက်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
    
}


if($action=='backup'){
    $aid=$_POST["aid"];
    $path=$_POST["path"];
    
    $sql="delete from tblbackup where AID=$aid";
    if(mysqli_query($con,$sql)){
        if($path != ""){
            unlink(root.'backup/'.$path);
        }        
        save_log("Delete BackUp File."); 
        echo 1;
    }else{
        echo 0;
    }
}


if($action == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){  
        $a = " where UserName like '%$search%' or UserType like '%$search%' ";
    }      
    $sql="select * from tbluser ".$a." 
    order by AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "UserControlReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="3" align="center"><h3>User Control</h3></td>
            </tr>
            <tr><td colspan="3"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">User Name</th>  
                <th style="border: 1px solid ;">User Type</th> 
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["UserName"].'</td>  
                    <td style="border: 1px solid ;">'.$row["UserType"].'</td>  
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
                <td colspan="3" align="center"><h3>User Control</h3></td>
            </tr>
            <tr><td colspan="3"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">User Name</th>  
                <th style="border: 1px solid ;">User Type</th> 
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid ;" align="center">No data</td>
            </tr>';
        $out .= '</table>'; 
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    } 
}




?>