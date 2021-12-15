<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Password Recovery</title>
    </head>
    <body>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" autocomplete="off" method="POST">
            <fieldset>
                <legend>Password Recovery</legend>
                <label for="user-passwd-recover">Your user*</label>
                <input type="text" id="user-passwd-recovery" name="user-passwd-recovery" required autofocus/><br/>
                
                <label for="email-passwd-recover">Email for the recovery*</label>
                <input type="email" id="email-passwd-recovery" name="email-passwd-recovery" required /><br/>

                <button type="submit">Send</button>
                <button type="reset">Cancel</button>
            </fieldset>
        </form>

        <?php
            require_once "utilities.php";

            //if the email is correct, print: An email has been send for the recovery of your password.
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $recovery_email = $_POST['email-passwd-recovery'];
                $user = $_POST['user-passwd-recovery'];
                $content = "
                    Follow this link to complete your password recovery process:<br/>
                    <a href='http://localhost/project_first_term/index.php?page=passwd_recovery_process&username=$user'>CLICK ME!</a><br/><br/>
                    Thank you for using our services!
                ";
                // $url = 'passwd_recovery_process.php';
                send_email($recovery_email, $content);
            }
        ?>
        <br/><br/><a href="login.php">Back to Login</a>
    </body>
</html>