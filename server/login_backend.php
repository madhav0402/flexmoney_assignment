<?php
    session_start();
    header_remove('X-Powered-By');
    include "boilerplate.php";
    
    if($_SERVER["REQUEST_METHOD"]=="POST")
    { 
        if(isset($_POST["fname"])&&isset($_POST["lname"])&&isset($_POST["phone"])&&isset($_POST["dob"])&&isset($_POST["psw"])) //FOR SIGN UP
        {
            $fname = $_POST["fname"];
            $lname = $_POST["lname"];
            $phone = $_POST["phone"];
            $dob = $_POST["dob"];     
            $pass = $_POST["psw"];
            $fname=test_input($fname);
            $lname=test_input($lname);
            $pass=base64_encode(test_input($pass));  //password encryption

            $sql="Select * from user where phone='$phone'";
            if(mysqli_query($con,$sql))
            {
                if(mysqli_affected_rows($con)>0)
                    echo 'User already exists!';
                else
                {
                    $stmt="Insert into user (firstname,lastname,phone,dob,password) values('$fname','$lname','$phone','$dob','$pass')";
                    if (mysqli_query($con, $stmt)) {
                        echo "You have signed up successfully!"; 
                    }
                }
            }
        }
        else if(isset($_POST["phone"])&&isset($_POST["psw"])) // FOR LOGIN
        {
            $phone=$_POST["phone"];
            $pass=$_POST["psw"];
            $pass=base64_encode(test_input($pass));

            $sql="Select * from user where phone='$phone'";
            $res=mysqli_query($con, $sql);
            if($res)
            {
                $rows=mysqli_fetch_assoc($res);
                if(mysqli_affected_rows($con)==0)  // if true, then  user does not exist
                    echo "1";
                else if($rows["password"]!=$pass)  // if true, then  user entered incorrect password
                    echo "2";
                else if($rows["user_type"]=='1')  // if true, then this is admin 
                {
                    echo "3";
                    $_SESSION["phone"]=$phone;
                    $_SESSION["user_type"]='1';
                }
                else if($rows["status"]=='2')
                    echo "Account is Deactivated!";
                else if($rows["status"]=='3')
                    echo "Account is deleted!";
                else
                {
                    $_SESSION["phone"]=$phone;
                    $_SESSION["user_type"]=$rows["user_type"];
                    echo "4";
                }
            }
        }
        
    }
    mysqli_close($con);
?>