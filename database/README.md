## Database Description

The description of each column is given below:

1. id - This contains a serial number which increments after every new insertion. It serves as the primary key of the table.
2. firstname
3. lastname
4. phone - The phone number of the user, which is used to login/signup. Also can be used to send payment reminders via SMS/whatsapp. Has the constraint "UNIQUE"
5. batch - The batch for the classes selected by the user
6. month - This contains the month for which the user has paid the fee, so to check whether the user has paid or not, we can compare the month and the current month(from system date)
7. dob - the date of birth of the user to track their age
8. user_type - It denotes the privileges of the account, 1 is for admin, 2 is for user
9. status - It denotes whether the account is active or not, 1 is for active, 2 is for inactive and 3 is for soft-delete
10. password
11. joined_at - The date the user signed up on the website.

## SQL for this table
```
Create table user(
  id int primary key,
  firstname varchar(40) NOT NULL,
  lastname varchar(40) NOT NULL,
  phone bigint UNIQUE NOT NULL,
  month varchar(15),
  dob date NOT NULL,
  user_type int DEFAULT '2',
  batch varchar(10),
  status int DEFAULT '1',
  password varchar(30) NOT NULL,
  joined_at date DEFAULT GET_DATE()
);
```
