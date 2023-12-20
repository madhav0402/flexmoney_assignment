<?php
session_start();
header_remove('X-Powered-By');
if ($_SESSION["phone"] == NULL) {  // prevents access to users who are not logged in
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
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <script src="function.js"></script>
</head>

<body>
    <!-- Modal (for editing records) -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Details</h4>
                </div>
                <div class="modal-body">
                    <span class="error bknd"></span>
                    <form  id="admin_form">
                    <div class="mytable">
                        <table>
                            <tr style="display: none;">
                                <td><input type="text" disabled value="" id="serial"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="lb-edit">
                                    <label for="firstname"><b>First Name</b></label>
                                </td>
                                <td class="inp">
                                    <input type="text" value="" placeholder="Enter First Name" id="fname" required>
                                    <span class="error" id="FNerror"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="lb-edit">
                                    <label for="lastname"><b>Last Name</b></label>
                                </td>
                                <td class="inp">
                                    <input type="text" value="" placeholder="Enter Last Name" id="lname" required>
                                    <span class="error" id="LNerror"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="lb-edit">
                                    <label for="phone"><b>Phone</b></label>
                                </td>
                                <td class="inp">
                                    <input type="number" value="" placeholder="Enter Phone number" id="phone" required>
                                    <span class="error" id="Pherror"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="lb-edit">
                                    <label for="dob"><b>Date of Birth</b></label>
                                </td>
                                <td class="inp">
                                    <input type="date" value="" placeholder="Enter date of birth" id="dob" required>
                                    <span class="error" id="DOBerror"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="lb-edit">
                                    <label for="Status"><b>Status</b></label>
                                </td>
                                <td class="inp">
                                    <!-- <input type="text" value="" placeholder="Enter Status" id="status" required> -->
                                    <select required id="status">
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                        <option value="3">Delete</option>
                                    </select>
                                    <span class="error" id="Serror"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="lb-edit">
                                    <label for="password"><b>Password</b></label>
                                </td>
                                <td class="inp ">
                                    <input type="password" value="" placeholder="Enter Password" id="psw" required>
                                    <span class="error" id="Perror"></span>
                                </td>
                            </tr>
                        </table>
                        </div>
                        <button type="submit" value="" class="button updatebtn">Make Changes</button>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- nav bar -->
    <div class="nav">
        <button type="button" class="button logoutbtn" id="logout">Logout</button>
    </div>
    <!-- side-nav -->
    <div class="side_nav">
        <h2 style="font-size:xx-large"><b>Menu</b></h2>
        <br><br>
        <a href="admin_profile.php">My profile</a><br>
        <a href="user_listing.php">User Listing</a><br>
    </div>
    <!-- Table -->
    <div class="extra">
        <div class="container">
            <h1><b>User Listing</b></h1>
            <p style="font-size: medium;">Click Edit to update details,Delete to delete records.</p>
            <hr>
            <table>
                <thead>
                    <tr>
                        <th class="lb">
                            <label for="id">Serial No.</label>
                        </th>
                        <th class="lb">
                            <a class="sort" onclick="col_sort('fn')"><label for="firstname">First Name <i class="fas fa-sort" id="fn"></i></label></a>
                        </th>
                        <th class="lb">
                            <a class="sort" onclick="col_sort('ln')"><label for="lastname">Last Name <i class="fas fa-sort" id="ln"></i></label></a>
                        </th>
                        <th class="lb">
                            <a class="sort" onclick="col_sort('ph')"><label for="phone">Phone <i class="fas fa-sort" id="ph"></i></label></a>
                        </th>
                        <th class="lb">
                            <label for="password">Password</label>
                        </th>
                        <th class="lb">
                            <label for="batch">Batch</label>
                        </th>
                        <th class="lb">
                            <label for="month">Month</label>
                        </th>
                        <th class="lb">
                            <label for="dob">Date of Birth</label>
                        </th>
                        <th class="lb">
                            <label for="status">Status</label>
                        </th>
                        <th class="lb">
                            <a class="sort" onclick="col_sort('dt')"><label for="joined">Date Joined <i class="fas fa-sort" id="dt"></i></label></a>
                        </th>
                        <th class="lb action">
                            <label for="action">Action</label>
                        </th>
                    </tr>
                </thead>
                <tbody id="result"></tbody>
            </table>
        </div><br>
        <span id="pagination">
            <button class="page prev" onclick="change_page('prev')">&lt</button>
            <span id="index"></span>
            <button class="page next" onclick="change_page('next')">&gt</button>
        </span>
    </div>
    <br>
    <script>
        $("document").ready(function() {
            $.ajax({  // printing the first 10 records
                url: "server/main_backend.php",
                data: {
                    output: "table"
                },
                success: function(responseTxt) {
                    var res = JSON.parse(responseTxt);
                    var total = 1;
                    $.ajax({
                        url: "server/main_backend.php",
                        method:"post",
                        data: {
                            total: "yes"
                        },
                        success: function(responseTxt) { // prints the entire table containing atmost 10 records
                            total = parseInt(responseTxt);
                            var pages = parseInt(total / 10) + 1;
                            if (total % 10 == 0)
                                pages--;
                            for (var row = 1; row <= pages; ++row) { // creating buttons depending on total pages
                                $("#index").append("<button class=\"page\" id=\"page"+row+"\" value=\"" + row + "\" onclick=\"change_page(this.value)\">" + row + "</button>");
                            }
                            // $("#index").append(str);
                            $("#page1").css({"background-color":"#5a83c5"}); // highlight the current page button
                            $("#page1").css({"color":"white"});
                        }
                    });
                    var l = res.length;
                    var sn = 1;
                    if (l == 0)
                        $("#result").append("<td>NO RECORDS FOUND</td>");
                    else 
                        print_table(res,sn);  // prints atmost 10 records starting for serial number 'sn'
                }
            });
        });
    </script>
</body>

</html>
