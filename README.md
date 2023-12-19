# Task - Admission form for Yoga Classes
This repo consists of the code for the assignment required by Flexmoney.

## Problem Statement:
Assume that you are the CTO of an outsourcing firm which has been chosen to build an
admission form for the Yoga Classes which happen every month.

Requirements for the admission form are:
  - Only people within the age limit of 18-65 can enroll for the monthly classes and they will
  be paying the fees on a month on month basis. I.e. an individual will have to pay the fees
  every month and he can pay it any time of the month.
  - They can enroll any day but they will have to pay for the entire month. The monthly fee is
  500/- Rs INR.
  - There are a total of 4 batches a day namely 6-7AM, 7-8AM, 8-9AM and 5-6PM. The
  participants can choose any batch in a month and can move to any other batch next
  month. I.e. participants can shift from one batch to another in different months but in the
  same month they need to be in the same batch.

## Implementation details:

You will be building a simple form in React (preferably or any basic frontend language) to
accept the details from user and make a call to a REST api (in any backend language) which
does the following:

● Accepts the user data, does basic validations

● Store the data in database (please provide the ER diagram for the database design)

● Assume that you have a mock function named CompletePayment() which accepts the
details of user and payment and does the payment for you. You don’t need to
implement it.

● Return the response to front-end depending on the payment response from
CompletePayment() function

● Host or containerize your application

## Technologies Used:
1. HTML
2. CSS
3. PHP
4. MySQL
5. jQuery (from DOM manipulation and AJAX)
6. JavaScript
7. XAMPP server (for testing)

## Consists of:
1. Login
2. Signup
3. The list of all signed-up users which will be managed by the admin
4. Payment and Batch Selection (Batch can only be changed when paying for the current month)
5. Profile page where the user can edit their details and proceed to Batch selection
6. All signups have only user privileges by default, admin privileges have to be given manually

## Assumptions Made: 
1. Yoga classes are offline
2. They only cater to people of India as of now (hence default country code: +91 and timezone: IST)
3. The age of the new members will be verified in person via IDs
4. People in the older age groups may not have an email, hence used mobile numbers
5. It is easier to contact people via mobile numbers than email.
6. People can only change batches when renewing the membership for the new month, so for any other circumstances, the instructor shall manually change batches
7. The instructor will have to deactivate a user's account if he/she decides to leave the classes.
