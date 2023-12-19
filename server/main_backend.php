<?php
session_start();
header_remove('X-Powered-By');
if($_SESSION["phone"]==NULL)
{
    header("Location: new_login.html");
    die();
}

include "boilerplate.php";
$phone = $_SESSION["phone"];
$type = $_SESSION["user_type"];

$off=0;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["logout"])) { //this logs out the user
        $res = $_GET["logout"];
        if ($res == "yes") {
            session_unset();
            session_destroy();
            mysqli_close($con);
        }
    }
    
    else if (isset($_REQUEST["output"])) { // for printing the table in user listing
        if ($_REQUEST["output"] == "table" &&$type==1 ) {
            if(isset($_REQUEST["page"]))
            {
                $e=$_REQUEST["page"];
                $off=10*($e-1);  // calculate offset
            }
            
            $sql = "Select id,firstname,lastname,phone,password,batch,month,dob,status,DATE_FORMAT(joined_at,'%D %b %Y') as joined_at from user where user_type!='$type' limit $off,10";
            $all_rows = mysqli_fetch_all(mysqli_query($con, $sql), MYSQLI_ASSOC);
            foreach ($all_rows as $key => $row)
                $all_rows[$key]["password"] = base64_decode($all_rows[$key]["password"]);  // decrypting passwords
            echo json_encode($all_rows);
            
        } else if ($_REQUEST["output"] == "record") {  // fetch record details for editing
            $sql = "Select id,firstname,lastname,dob,batch,phone,password from user where phone='$phone'";
            $user_row = mysqli_fetch_assoc(mysqli_query($con, $sql));
            $user_row["password"] = base64_decode($user_row["password"]);
            echo json_encode($user_row);
        }
    }
    else if(isset($_REQUEST["batch"])&&isset($_REQUEST["month"]))  // payment and switching batches
    {
        $batch=$_REQUEST["batch"];
        $month=$_REQUEST["month"];
        if($batch==1)
            $batch="6-7 AM";
        else if($batch==2)
            $batch="7-8 AM";
        else if($batch==3)
            $batch="8-9 AM";
        else if($batch==4)
            $batch="5-6 PM";
        $sql="Select month from payment where phone='$phone'";
        $result=mysqli_fetch_assoc(mysqli_query($con, $sql));
        if($result["month"]==date("F", strtotime('m')))
            echo "Paid";
        else
        {
            if(completePayment()){  // redirects to payment gateway, returns true for now
            $sql="Insert into user(batch,month) values('$batch','$month') where phone='$phone'";
            if(mysqli_query($con,$sql))
                echo "success";
            }
            else
                echo "failed";
        }
    }
    
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_REQUEST["id"]) && isset($_REQUEST["firstname"]) && isset($_REQUEST["lastname"]) && isset($_REQUEST["phone"]) && isset($_REQUEST["stat"])&&isset($_REQUEST["dob"]) && isset($_REQUEST["pass"])&&$type==1) {  // FOR RECORDS THAT ARE UPDATED BY ADMIN
        $id = $_REQUEST["id"];
        $upfname = test_input($_REQUEST["firstname"]);
        $uplname = test_input($_REQUEST["lastname"]);
        $upphone = $_REQUEST["phone"];
        $status = $_REQUEST["stat"];
        $dob = $_REQUEST["dob"];
        $uppass = test_input($_REQUEST["pass"]);

        $status = strtolower($status);
        $uppass = base64_encode($uppass);

        $sql = "Select * from user where phone='$upphone'";
        $result=mysqli_query($con,$sql);
        if($result)
        {
            $uprows = mysqli_fetch_assoc($result);
            if(mysqli_affected_rows($con)>0)
            {
                
                if ($uprows["id"] != $id) // phone has been changed but it already exists in the DB
                    echo "Phone Number Already exists!";
                else{
                    $pass=$uprows["password"];
                    $stmt = "Update user set firstname='$upfname',lastname='$uplname',phone='$upphone',dob='$dob',password='$uppass',status='$status' where id='$id'"; // update record 
                    if (mysqli_query($con, $stmt)) {
                        if($phone!=$upphone||$pass!=$uppass)
                        {
                            echo "relogin";
                            session_unset();
                            session_destroy();
                        }
                        else{
                            $sql = "Select id,firstname,lastname,phone,password,batch,month,dob,status,DATE_FORMAT(joined_at,'%D %b %Y') as joined_at from user where id='$id'";  //fetch updated record
                            $admin_row = mysqli_fetch_assoc(mysqli_query($con, $sql));
                            $admin_row["password"] = base64_decode($admin_row["password"]);
                            echo json_encode($admin_row);
                        }
                    }
                }
            }
            else{
                $sql = "Select password from user where phone='$upphone'";
                $row=mysqli_fetch_assoc(mysqli_query($con,$sql));
                $pass=$row["password"];
                $stmt = "Update user set firstname='$upfname',lastname='$uplname',phone='$upphone',password='$uppass',status='$status' where id='$id'";
                if (mysqli_query($con, $stmt)) {
                    if($phone!=$upphone||$pass!=$uppass)
                    {
                        echo "relogin";
                        session_unset();
                        session_destroy();
                    }
                    else{
                        $sql = "Select id,firstname,lastname,phone,password,batch,month,status,dob,DATE_FORMAT(joined_at,'%D %b %Y') as joined_at from user where id='$id'";
                        $admin_row = mysqli_fetch_assoc(mysqli_query($con, $sql));
                        $admin_row["password"] = base64_decode($admin_row["password"]);
                        echo json_encode($admin_row);
                    }
                }
            }
        }
    }
    else if (isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["phone"]) && isset($_POST["psw"])) { // FOR RECORDS UPDATED BY USER
        $upfname = $_POST["fname"];
        $uplname = $_POST["lname"];
        $upphone = $_POST["phone"];
        $uppass = $_POST["psw"];
        $upfname = test_input($upfname);
        $uplname = test_input($uplname);
        $upphone = test_input($upphone);
        $uppass = base64_encode(test_input($uppass));

        $sql = "Select * from user where phone='$upphone'";

        $result=mysqli_query($con, $sql);
        if($result)
        {
            if (mysqli_affected_rows($con) > 0 && $upphone != $phone) // user entered a new phone number and it already exists
                echo "phone Already exists!"; 
            else {
                $sql = "Select id,password from user where phone='$phone'";
                $row = mysqli_fetch_assoc(mysqli_query($con, $sql));
                $id = $row["id"];
                $stmt = "Update user set firstname='$upfname',lastname='$uplname',phone='$upphone',password='$uppass' where id='$id'";
                $pass=$row["password"];
                if(mysqli_query($con,$stmt))
                {
                    if($phone!=$upphone||$pass!=$uppass)
                    {
                        echo "relogin";
                        session_unset();
                        session_destroy();
                    }
                    else{
                        $sql = "Select id,firstname,lastname,phone,dob,batch,password from user where id='$id'";
                        $user_row = mysqli_fetch_assoc(mysqli_query($con, $sql));  // fetch updated user record
                        $user_row["password"] = base64_decode($user_row["password"]);
                        echo json_encode($user_row);
                    }
                }
            }
        }
    }
    else if (isset($_REQUEST["uid"])) { // fetch user record details for editing from user listing
        $uid = $_REQUEST["uid"];
        $edt = "Select * from user where id='$uid'";
        $edit_rows = mysqli_fetch_assoc(mysqli_query($con, $edt));
        $edit_rows["password"] = base64_decode($edit_rows["password"]);
        echo json_encode($edit_rows);
    }
    else if(isset($_REQUEST["column"])&&isset($_REQUEST["sort"])&&isset($_REQUEST["page"])) // FOR SORTING VIA COLUMN
    {
        $col=$_REQUEST["column"];
        $e=$_REQUEST["page"];
        $off=10*($e-1); // calculate offset
        $order="asc"; //default
        if($_REQUEST["sort"]==2)
            $order="desc";

        $sql = "Select * from (Select id,firstname,lastname,phone,batch,month,dob,password,status,DATE_FORMAT(joined_at,'%D %b %Y') as joined_at from user where user_type!='$type' limit $off,10) as result order by $col $order"; // performs sorting on 10 rows
        $all_rows = mysqli_fetch_all(mysqli_query($con, $sql), MYSQLI_ASSOC);
        
        foreach ($all_rows as $key => $row)
            $all_rows[$key]["password"] = base64_decode($all_rows[$key]["password"]);
        echo json_encode($all_rows);
    }
    else if(isset($_REQUEST["total"])) // returns the total number of user records in the database
    {
        $sql="select * from user where user_type!='$type'";
        echo mysqli_num_rows(mysqli_query($con,$sql));
    }
    else if (isset($_REQUEST["del_id"])) { // FOR HARD DELETE BY ADMIN
        $del_id = $_REQUEST["del_id"];
        $del = "delete from user where id='$del_id'";
        if (mysqli_query($con, $del))
            echo "Record deleted successfully!";
    }
}
mysqli_close($con);
