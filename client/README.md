## File Description

1. new_signup.html - This is the signup form. On a successful signup, it redirects to the login page.

2. new_login.html - This is the login form. On a successful login, it redirects to the user profile or user listing depending on the account.

3. new_user.php - This displays the details of the user which can be updated anytime, except for batch and date of birth. It leads to batch.php page.

4. batch.php - This page is for selecting batch and proceeding to the payment gateway.

5. forgot.html - If the user forgets his/her password, the password/reset link can be sent to their phone numbers(this feature is yet to be added)

6. admin_profile - Similar to new_user.php, but for admin

7. user_listing - This is the list of all users, where each user's details and payment status can be tracked by the admin. At most 10 records are visible at a time due to pagination and the records can be sorted according to name, phone, etc.

8. function.js - This consists of all the client-side logic like pagination, sorting columns, etc, and input validations(via regex). Every user interaction with the server happens via AJAX.
