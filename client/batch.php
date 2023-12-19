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
                id="payment_form" >

                <div class="container">
                <h1>Payment</h1>
                <p>Select desired Batch</p>
                <hr>
                <table>
                    <tr>
                        <td class="lb">
                            <label for="batch"><b>Select Batch</b></label>
                        </td>
                        <td>
                            <select required id="batch">
                                <option value="1">6-7 AM</option>
                                <option value="2">7-8 AM</option>
                                <option value="3">8-9 AM</option>
                                <option value="4">5-6 PM</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="lb">
                            <label ><b>Price</b></label>
                        </td>
                        <td class="inp">
                            <label >500/-</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="lb">
                            <label for="month"><b>Month</b></label>
                        </td>
                        <td class="inp">
                            <input type="text" id="month" disabled
                            />
                        </td>
                    </tr>
                </table>
                    <div class="clearfix">
                        <button type="button" class=" button loginbtn" id="payment">Make Payment</button>
                        <button type="button" class=" button cancelbtn hidden" >Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
      $("document").ready(function() {
        const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        const d = new Date();
        let name = month[d.getMonth()];

        $("#month").val(name);

        $(".cancelbtn").click(function () {
          window.location = "new_user.php";
        });

        $("#payment").click(function () {
            
            var batch=$("#batch").val();
            var mon=$("#month").val();
            $.ajax({
                url: "server/main_backend.php",
                data:{
                    batch:batch,
                    month:mon,
                },
                success: function(responseTxt) {
                    if(responseTxt=="success")
                    {
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Successful',
                            text: 'You have successfully paid for the batch',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "new_user.php";
                            }
                        })
                    }
                    else if(responseTxt=="Paid")
                    {
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Failed',
                            text: 'You have already paid for this batch',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "new_user.php";
                            }
                        })
                    }
                    else
                    {
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Failed',
                            text: 'Payment Failed',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "new_user.php";
                            }
                        })
                    }
                }
            });
            
        });
    });
    </script>
</body>
</html>