<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Password Recovery</title>
    </head>
    <body>
        <form action="" autocomplete="off" method="POST">
            <fieldset>
                <legend>Password Recovery</legend>
                <label for="user-recover">User*</label>
                <input type="text" id="user-recover" name="user-recover" required autofocus/><br/>

                <label for="new-passwd">New Password*</label>
                <input type="password" id="new-passwd" name="new-passwd" required /><br/>

                <label for="new-passwd-confirmation">Password Confirmation*</label>
                <input type="password" id="new-passwd-confirmation" name="new-passwd-confirmation" required /><br/>
                
                <button type="submit">Send</button>
                <button type="reset">Cancel</button>
            </fieldset>
        </form>

        <?php
            require "utilities.php";
            
            if(isset($_GET['username'])){
                $username = $_GET['username'];
            
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $usernameRecover = $_POST['user-recover'];
                    $newPassword = $_POST['new-passwd'];
                    $newPasswordConfirmation = $_POST['new-passwd-confirmation'];

                    if($newPassword === $newPasswordConfirmation && $username === $usernameRecover){
                        $encryptedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        $query = "UPDATE chatapp.users SET users.passwd = '$encryptedPassword' WHERE users.username LIKE '$username'";
                        $result = $bd->query($query);
                        if($result){		
                            header('Location: login.php');		
                        } else {
                           echo'<p style="color:red">**ERROR: the password could not be updated.</p>';
                        }
                    } else {
                        echo '<p style="color:red">**ERROR: the password and its confirmation do not match!</p>';
                    }
                }
            } else {
                echo 'Something went wrong.';
            }
        ?>

        <br/><br/><a href="login.php">Back to Login</a>
    </body>
</html>