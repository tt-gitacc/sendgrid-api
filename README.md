# How to Integrate a SendGrid Mail Server with an Azure Application
This is a snippet from my senior project at ASU. In this repository, you will find my source code and a PDF that explains how I got it integrated with SendGrid.

The snippet in this repository enables the following workflow:
1. Data Submission: A user enters his account information into a web form.
2. Data Validation: The user’s data is then validated in real time (with JavaScript, PHP and MongoDB).
3. Account Verification: If the system determines that the user does not already have an account on file:<br>
        a. An API call will be made to SendGrid.<br>
            b. SendGrid will then utilize the data from that call to send a confirmation email to the user's ASU account. 

By validating the user’s ASU email, I added an additional layer of security and exclusivity to the platform.
