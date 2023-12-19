<?php
session_start();
header_remove('X-Powered-By');
if($_SESSION["phone"]==NULL)
{
    header("Location: new_login.html");
    die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="function.js"></script>
</head>

<body>
    <!-- nav bar -->
    <div class="nav">
        <button type="button" class="button logoutbtn" id="logout">Logout</button>
    </div>
    
    <div class="outer">
        <span class="error bknd"></span>
        <div class="extra">
            <form 
                style="border:1px solid #ccc" 
                id="user_form" >

                <div class="container">
                <h1>My Profile</h1>
                <p>Click Edit to update your details.</p>
                <hr>
                <table>
                    <tr>
                        <td class="lb">
                            <label for="fname"><b>First Name</b></label>
                        </td>
                        <td class="inp">
                            <input type="text" class="firstname" value="" placeholder="Enter First Name" id="fname" required disabled>
                            <span class="error" id="FNerror"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="lb">
                            <label for="lname"><b>Last Name</b></label>
                        </td>
                        <td class="inp">
                            <input type="text" class="lastname" value=""  placeholder="Enter Last Name" id="lname" required disabled>
                            <span class="error" id="LNerror"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="lb">
                            <label for="dob"><b>Date of Birth</b></label>
                        </td>
                        <td class="inp">
                            <input type="date" placeholder="Enter Date of Birth" id="dob" required disabled
                            />
                            <span class="error" id="DOBerror"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="lb">
                            <label for="batch"><b>Currrent Batch</b></label>
                        </td>
                        <td class="inp">
                            <input type="text"  id="batch"  disabled
                            />
                        </td>
                    </tr>
                    <tr>
                        <td class="lb">
                            <label for="phone"><b>Phone</b></label>
                        </td>
                        <td class="inp">
                            <input type="number" class="phone" value="" placeholder="Enter Phone number" id="phone" required disabled maxlength="10">
                            <span class="error" id="Pherror"></span>
                        </td>
                        </tr>
                    <tr>
                        <td class="lb">
                            <label for="psw"><b>Password</b></label>
                        </td>
                        <td class="inp">
                            <input type="password" class="password" value="" placeholder="Enter Password" id="psw" required disabled>
                            <span class="error" id="Perror"></span>
                        </td>
                    </tr>
                </table>
                    <div class="clearfix">
                        <button type="button" style="background-color:green" class=" button batch" id="payment">Select Batch</button>
                        <button type="button" class=" button loginbtn" id="edit">Edit</button>
                        <button type="submit" class=" button updatebtn hidden" >Update</button>
                        <button type="button" class=" button cancelbtn hidden" >Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
      $("document").ready(function() {
        $.ajax({                                //fetches the details of this profile
                url: "server/main_backend.php",
                data:{
                    output:"record"
                },
                success: function(responseTxt) {
                    var arr = JSON.parse(responseTxt);
                    $("span").text("");
                    $("#fname").val(arr.firstname);
                    $("#lname").val(arr.lastname);
                    $("#phone").val(arr.phone);
                    $("#batch").val(arr.batch);
                    $("#dob").val(arr.dob);
                    $("#psw").val(arr.password);
                }
            });
        $("#edit").click(function() {           // makes the input fields editable
            $(".hidden").css({
                "display": "inline"
            });
            $("#edit").css({
                "display": "none"
            });
            $("input")
                .attr({
                    "disabled":false,
                }).css({
                    "background-color":"#f1f1f1"
            });
            $("#dob,#batch")
                .attr({
                    "disabled":true,
                }).css({
                    "background-color":"#ffffff"
            });
        });
        $(".cancelbtn").click(function() {
            $.ajax({
                url: "server/main_backend.php",
                data:{
                    output:"record"
                },
                success: function(responseTxt) {
                    var arr = JSON.parse(responseTxt);
                    $("span").text("");
                    $("#fname").val(arr.firstname);
                    $("#lname").val(arr.lastname);
                    $("#phone").val(arr.phone);
                    $("#dob").val(arr.dob);
                    $("#psw").val(arr.password);
                }
            });
            setDefault();
        });
        $(".batch").click(function () {
          window.location = "batch.php";
        });
    });
    </script>
</body>
</html>