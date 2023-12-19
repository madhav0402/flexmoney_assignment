var curr_page=1;  //keeps track of current page
var order=0,col="id";  // keep track of ascending descending and the selected column

// ***********INPUT VALIDATION STARTS*********************
function validateSignUp(){               
    var password = document.getElementById("psw").value;
    var password_repeat = document.getElementById("psw-repeat").value;
    if(password!==password_repeat)
    {
        $("#PRerror").html("Passwords do not match!");
        return false;
    }
    else
        $("#PRerror").html("");
    
    return true&&validateUpdate();
}
function validateUpdate(){                 //check
    // var fn = document.getElementById("fname").value;
    // var ln = document.getElementById("lname").value;
    var dob=new Date(document.getElementById("dob").value);
    var currentDate = new Date();
    var age = currentDate.getFullYear() - dob.getFullYear();
    
    if(age<18)
    {
        $("#DOBerror").html("People of age less than 18 years cannot apply!");
        return false;
    }
    else if(age>65)
    {
        $("#DOBerror").html("People of age more than 65 years cannot apply!");
        return false;
    }
    else
        $("#DOBerror").html("");

    return true&&validateForm();
}
function validateForm(){

    var ph=document.getElementById("phone").value;
    var password = document.getElementById("psw").value;
    if(ph.length!==10)
    {
        $("#Pherror").html("Invalid phone number, 10 digits required!");
        return false;
    }
    else
        $("#Pherror").html("");
    if (password.match(/[a-z]/) == null||password.match(/[A-Z]/) == null||password.match(/[0-9]/) == null||password.match(/[!@#$%^&*_]/) == null) {
        $("#Perror").html("Password should only have Uppercase,Lowercase,SpecialCharacters and digits!");
        return false;
    }
    else
        $("#Perror").html("");
    if(password.length<8){
        $("#Perror").html("Password cannot have less than 8 Characters!");
        return false;
    } else
        $("#Perror").html("");
    return true;
}
// ***********INPUT VALIDATION ENDS*********************

function setDefault()    // this function makes the input fields uneditable
{
    $(".hidden").css({
        "display": "none"
    });
    $("#edit").css({
        "display": "inline"
    });
    $("input")
        .attr({
            "disabled":true,
        }).css({
            "background-color":"#ffffff"
    });
}
function print_table(res,sn){  //takes a json array and the serial number of 1st row as parameters and prints the user listing
    var l=res.length;
    $("#result").empty();
    for (var row = 0; row < l; ++row) {
        var str = "";
        str += "<tr> ";
        var key=res[row].id;
        for (var it in res[row]) {
            if (it == "status") {
                if (res[row][it] == 1)
                    str += "<td class=\"" + sn + "\"><span class=\"" + sn + " " + it + "\">Active</span></td> ";
                else if (res[row][it] == 2)
                    str += "<td class=\"" + sn + "\"><span class=\"" + sn + " " + it + "\">Inactive</span></td> ";
                else if (res[row][it] == 3)
                    str += "<td class=\"" + sn + "\"><span class=\"" + sn + " " + it + "\">Deleted</span></td> ";
            } else if (it == "id")
                str += "<td class=\"" + sn + "\"><span class=\"" + sn + " " + it + "\">" + sn + "</span></td> ";
            else
                str += "<td class=\"" + sn + "\"><span class=\"" + sn + " " + it + "\">" + res[row][it] + "</span></td> ";
        }

        str += "<td><div class=\"clearfix\"> <button type=\"button\" value=\"" + key + "\" class=\"button " + key + " editbtn\"  data-toggle=\"modal\" onclick=\"edit_record(this.value,"+sn+")\" data-target=\"#myModal\"><i class=\"fas fa-edit\"></i></button> <button type=\"button\" value=\"" + key + "\" onclick=\"delete_record(this.value)\" class=\"button " + key + " deletebtn\"><i class=\"fas fa-trash\"></i></button></div> </td></tr>";  //Edit and delete button
        $("#result").append(str);
        sn += 1;
    }
}
function col_sort(str){  // this function is responsible for sorting a column

    $("i").attr("class","fas fa-sort");
    if(str!=col)
    {
        col=str;
        order=1;
        $("#"+col).attr("class","fas fa-sort-up");
    }
    else
    {
        order=(order+1)%3;          // order=1 is ascending, order=2 is descending
        if(order==0)
        {
            change_page(curr_page);
            return;
        }
        else if(order==2)
            $("#"+col).attr("class","fas fa-sort-down");
        else
            $("#"+col).attr("class","fas fa-sort-up");
    }
    var val="";
    if(col=="fn")
        val="firstname";
    else if(col=="ln")
        val="lastname";
    else if(col=="ph")
        val="phone";
    else if(col=="dt")
        val="joined_at";
    $.ajax({
        url: "server/main_backend.php",
        method:"post",
        data: {
            sort:order,
            page:curr_page,
            column:val
        },
        success: function(responseTxt) {
            var res = JSON.parse(responseTxt);
            var sn = (curr_page-1)*10+1;
            var l = res.length;
            if(l==0)
            {
                if(curr_page>1)
                    curr_page--;
                return;
            }
            print_table(res,sn);
        }
    });
}
function hl_pageNum()  // highlights current page button
{
    $("#page"+curr_page).css({"background-color":"#5a83c5"}); 
    $("#page"+curr_page).css({"color":"white"});
}
function delete_record(val){
    Swal.fire({
        title: 'Do you want to delete this record?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "server/main_backend.php",
                method:"post",
                data: {
                    del_id: val
                },
                success: function(responseTxt) {
                    if (responseTxt == "Record deleted successfully!") {
                        Swal.fire({
                            icon: 'success',
                            text: responseTxt
                        })
                        $.ajax({
                            url: "server/main_backend.php",
                            data: {
                                output: "table",
                                page:curr_page
                            },
                            success: function(responseTxt) {
                                var res = JSON.parse(responseTxt);
                                var sn = (curr_page-1)*10+1;
                                var l = res.length;
                                if(l==0)
                                {
                                    if(curr_page>1)
                                        curr_page--;
                                    else
                                        return;     
                                    change_page(curr_page);
                                }
                                else
                                   print_table(res,sn);
                            }
                        });
                        $("#index").empty();             //printing buttons again for removing any empty page
                        $.ajax({     
                            url:"server/main_backend.php",
                            method:"post",
                            data:{
                                total:"yes"
                            },
                            success:function(responseTxt){
                                total=parseInt(responseTxt);
                                var pages=parseInt(total/10)+1;
                                if(total%10==0)
                                    pages--;
                                for (var row = 1; row <= pages; ++row) { 
                                    $("#index").append("<button class=\"page\" id=\"page"+row+"\" value=\"" + row + "\" onclick=\"change_page(this.value)\">" + row + "</button>");
                                }
                                hl_pageNum();
                            }
                        });
                    }
                }
            });
        }
    })
}
function change_page(val)
{
    $("#page"+curr_page).css({"background-color":"#eae9e9"});
    $("#page"+curr_page).css({"color":"black"});
    if(val=="next")
    {
        curr_page++;
        if($("#page"+curr_page).is("button")==false)
        {
            curr_page--;
            hl_pageNum();
            return;
        }
    }
    else if(val=="prev")
    {
        if(curr_page>1)
            curr_page--;
        else
        {
            hl_pageNum();
            return;
        }
    }
    else
        curr_page=val;

    hl_pageNum();
    $.ajax({
        url: "server/main_backend.php",
        data: {
            output: "table",
            page:curr_page
        },
        success: function(responseTxt) {
            var res = JSON.parse(responseTxt);
            var sn = (curr_page-1)*10+1;
            var l = res.length;
            if(l==0)
            {
                if(curr_page>1)
                    curr_page--;
                return;
            }
            print_table(res,sn);
        }
    });
}
function edit_record(val,sn){  // sets values in the modal for editing user records
    $(".updatebtn").attr("value", val);
    $.ajax({
        url: "server/main_backend.php",
        method:"post",
        data: {
            uid: val
        },
        success: function(responseTxt) {
            var arr = JSON.parse(responseTxt);
            $(".error").html("");
            $("#serial").val(sn);
            $("#fname").val(arr.firstname);
            $("#lname").val(arr.lastname);
            $("#dob").val(arr.dob);
            $("#phone").val(arr.phone);
            $("#status").val(arr.status);
            $("#psw").val(arr.password);
        }
    });
}
$("document").ready(function(){
    $("#admin_form").on("submit",function(event) {      //RECORD TO BE EDITED
        event.preventDefault();
        if (validateUpdate()) {
            Swal.fire({
                title: 'Do you want to update this info?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    var fn = document.getElementById("fname").value;
                    var ln = document.getElementById("lname").value;
                    var ph = document.getElementById("phone").value;
                    var db = document.getElementById("dob").value;
                    var st = document.getElementById("status").value;
                    var psw = document.getElementById("psw").value;
                    var uid = $(".updatebtn").attr("value");            //id of record 
                    $.ajax({
                        url: "server/main_backend.php",
                        method: "post",
                        data: {
                            id: uid,
                            firstname: fn,
                            lastname: ln,
                            dob:db,
                            phone: ph,
                            stat: st,
                            pass: psw
                        },
                        success: function(responseTxt) {
                            if (responseTxt == "Phone Already exists!")
                                $(".bknd").text(responseTxt);
                            else if (responseTxt != null) {
                                Swal.fire({
                                    icon: 'success',
                                    text: 'Changes were made successfully!'
                                })
                                var arr = JSON.parse(responseTxt);
                                var sn=$("#serial").val();
                                for (var key in arr) {             //replacing edited record values with updated values
                                    if(key=='id')
                                        continue;
                                    if(key=="status")
                                    {
                                        if(arr[key]==1)
                                            $("." + sn + "." + key).text("Active");
                                        else if(arr[key]==2)
                                            $("." + sn + "." + key).text("Inactive");
                                        else if(arr[key]==3)
                                            $("." + sn + "." + key).text("Deleted");
                                    }
                                    else
                                        $("." + sn + "." + key).text(arr[key]);
                                }
                                
                            } else {
                                $(".bknd").text("Unknown Error");
                            }
                        }
                    });
                }
            })
        }
    });
    $("#user_form").submit(function(event) {        // USER PROFILE
        event.preventDefault();
        $(".error").html("");
        if(validateUpdate())
        {
            Swal.fire({
                title: 'Do you want to update your info?',
                showDenyButton: true,
                confirmButtonText:'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    var fn=document.getElementById("fname").value;
                    var ln=document.getElementById("lname").value;
                    var ph=document.getElementById("phone").value;
                    var password=document.getElementById("psw").value;

                    $.ajax({
                        url: "server/main_backend.php",
                        method:"post",
                        data: {
                            fname:fn,
                            lname:ln,
                            phone:ph,
                            psw: password
                        },
                        success: function(responseTxt) {
                            if (responseTxt == "Phone Already exists!")
                                $(".bknd").text(responseTxt);
                            else if(responseTxt=="relogin")
                            {
                                Swal.fire({
                                    title:"Please login again!"
                                }).then(()=>{
                                    window.location="new_login.html";
                                })
                            }
                            else if (responseTxt) {
                                Swal.fire({
                                    icon: 'success',
                                    text: 'Changes were made successfully!'
                                })
                                var arr = JSON.parse(responseTxt);
                                for (var key in arr) {
                                    $("." + key).val(arr[key]);
                                }
                                setDefault();
                            }
                            else
                                $(".bknd").text("Unknown Error Encountered!");
                        }
                    });
                }
            })
        }
    });
    $("#login_form").submit(function (event) {          //LOGIN FORM
        event.preventDefault();
        $(".error").html("");
        var ph = document.getElementById("phone").value;
        var password = document.getElementById("psw").value;
        $.ajax({
            url: "server/login_backend.php",
            method: "post",
            data: {
                phone: ph,
                psw: password,
            },
            success: function (result) {
                if (result == "1") $(".bknd").text("User Does not exist!");
                else if (result == "2")
                    $(".bknd").text("Wrong Password entered!");
                else if (result == "3") window.location = "user_listing.php";  // admin login
                else if (result == "4") window.location = "new_user.php";   //user login
                else $(".bknd").text(result);
            },
        });
      });
      $("#signup_form").submit(function(event){         // NEW SIGN IN FORM
        event.preventDefault();  
        $(".error").html("");
        if(validateSignUp())
        {
            var fn=document.getElementById("fname").value;
            var ln=document.getElementById("lname").value;
            var ph=document.getElementById("phone").value;
            var password=document.getElementById("psw").value;
            var dateofbirth=document.getElementById("dob").value;
            $.ajax({
                url: "server/login_backend.php",
                method:"post",
                data: {
                    fname:fn,
                    lname:ln,
                    phone:ph,
                    psw: password,
                    dob:dateofbirth 
                },
                success: function(responseTxt) {
                    if(responseTxt=="User already exists!")
                        $(".bknd").html(responseTxt);
                    else if(responseTxt=="You have signed up successfully!")
                    {
                        Swal.fire({
                            title:responseTxt
                        }).then((result)=>{
                            window.location="new_login.html";
                        })
                    }
                    else
                        $(".bknd").html("Unknown error encountered!");
                }
            });
        }
    });
    $("#forgot_form").submit(function(event){  // FORGOT PASSWORD FORM
        $(".error").html("");
        $(".bknd").text("Sending...").css("color","black");
        event.preventDefault();
        var ph = document.getElementById("phone").value;
        $.ajax({
            url: "server/login_backend.php",
            method: "post",
            data: {
                forgot: ph
            },
            success: function (result) {
                if (result == "1") $(".bknd").text("User Does not exist!");
                else if (result == "2")
                {
                    $(".bknd").text("");
                    Swal.fire({
                        title:"Your password has been sent to you successfully!"
                    })
                }
                else $(".bknd").text(result);
            },
        });
    });
    $("#logout").click(function() {
        Swal.fire({
            title: 'Do you want to logout?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "server/main_backend.php",
                    data: {
                        logout: "yes"
                    },
                    success: function() {
                        window.location = "new_login.html";
                    }
                });
            }
        })
    });
    $("#fname").on({        //prevents user from entering an invalid first name
        keydown: function(event){
            $(".error").text("");
            var c=String.fromCharCode(event.which);
            if((/[0-9!@#$%^&<>/*-]/).test(c) == true)
            {
                event.preventDefault();
                $("#FNerror").html("First name can only have alphabets!");
            }
        },
        paste:function(event){
            event.preventDefault();
            event.stopPropagation();
            var fn = event.originalEvent.clipboardData;
            if ((/[0-9!@#$%^&<>/*-]/).test(fn.getData("text/plain")) == true) {
                $("#FNerror").html("First name can only have alphabets!");    
            }
            else
                $("#fname").val(fn.getData("text/plain"));
        }
      });
      $("#lname").on({         //prevents user from entering an invalid last name
        keydown: function(event){
          var c=String.fromCharCode(event.which);
          $(".error").text("");
          if((/[0-9!@#$%^<>/&*-]/).test(c) == true)
          {
            event.preventDefault();
            $("#LNerror").html("Last name can only have alphabets!");
          }
        },
        paste:function(event){
            event.preventDefault();
            event.stopPropagation();
            var ln = event.originalEvent.clipboardData;
            if ((/[0-9!@#$%<>/^&*-]/).test(ln.getData("text/plain")) == true) {
                $("#LNerror").html("Last name can only have alphabets!");    
            }
            else
                $("#lname").val(ln.getData("text/plain"));
        }
      });
      $("#psw").on("copy paste cut", function (e) {
        e.preventDefault(); //disable cut,copy,paste
      });
      $("#psw-repeat").on("copy paste cut", function (e) {
        e.preventDefault(); //disable cut,copy,paste
      });
});