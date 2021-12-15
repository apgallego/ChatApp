<?php
    require_once 'utilities.php';

    if (isset($_GET['verification'])){
        $verifEmail = $_GET['userEmail'];
        $query1 = "UPDATE chatapp.users SET users.isActive = 1 WHERE users.email LIKE '$verifEmail'";
        $result1 = $bd->query($query1);
        // var_dump($result1);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    
        $usu = check_user_login($_POST['user-login'], $_POST['passwd-login'], $bd);
        if($usu === FALSE){
            // $err = TRUE;
            echo '<p style="color:red">**ERROR: Incorrect data or user not registered!</p>';
            $user = $_POST['user-login'];
        }else{
            session_start();
            $username = $_POST['user-login'];
            $id = $bd->query("SELECT * FROM chatapp.users WHERE users.username LIKE '$username'");
            $userData = $id->fetch();
            // var_dump($userData);
            // var_dump($userID);
            $_SESSION['user-id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['user-name'] = $userData['usName'];
            $_SESSION['user-surname'] = $userData['usSurname'];
            $_SESSION['user-email'] = $userData['email'];
            $_SESSION['user-age'] = intval($userData['age']);
            $_SESSION['user-telephone'] = $userData['telephone'];
            $_SESSION['user-pfp'] = $userData['pfp'];
            $_SESSION['user-active'] = $userData['isActive'];
            $_SESSION['user-role'] = $userData['usRole'];

            if($userData['usRole'] == 'admin'){
                header('Location: admin.php');
            } else {
                header("Location: chats.php");
            }
        }	
    }
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
   

    <body class="text-center">
        <main class="form-signin">
            <header>
                <h1>SAMPLE TEXT</h1>
            </header>
            <?php
                if(isset($err) and $err == TRUE){echo '<p style="color:red">ERROR: Check user and password</p>';}
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method='POST'>
                <fieldset>
                    <legend>Login</legend>
                    <label for="user-login">User</label>
                    <input type="text" id="user-login" value="<?php if(isset($user))echo $user;?>" name="user-login" placeholder="Username" required autofocus/><br/>

                    <label for="passwd-login">Password</label>
                    <input type="password" id="passwd-login" value = "<?php if(isset($passwd))echo $passwd;?>" name="passwd-login" placeholder="Password" required><br/>
                    <a href="passwd_recovery.php">Forgot your password?</a><br/><br/>

                    <button type="submit">Login</button>
                    <button type="reset">Cancel</button><br/><br/>

                    <a href="register.php">Or register if you do not have a user</a>
                </fieldset>
            </form>
        </main>
    </body>
</html>