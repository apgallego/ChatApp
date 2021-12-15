<?php
    require_once 'utilities.php';

    if($_SERVER["REQUEST_METHOD"] == 'POST'){
        $user = check_user_registration($_POST['user-register'], $_POST['email-register'], $bd);
        if($user === TRUE){
            // $err = TRUE;
            echo '<p style="color:red">**ERROR: the username or the email is already registered.</p>';
            // $user = $_POST['user-register'];
            $name = $_POST['name-register'];
            $surname = $_POST['surname-register'];
            $user = '';
            $age = intval($_POST['age-register']);
            $tel = $_POST['tel-register'];
        } else {
            $name = $_POST['name-register'];
            $surname = $_POST['surname-register'];
            $user = $_POST['user-register'];
            $email = $_POST['email-register'];
            $passwd = $_POST['passwd-register'];
            $passwdConf = $_POST['passwd-conf-register'];
            $age = intval($_POST['age-register']);
            $tel = $_POST['tel-register'];

            // var_dump($passwd);
            // var_dump($passwdConf);

            if($passwd == $passwdConf){
                $encryptedPasswd = password_hash($passwd, PASSWORD_DEFAULT);
                $pfp = upload_file();
                register_user($name, $surname, $user, $email,$encryptedPasswd, $age, $tel, $pfp, $bd);
                // $toUserName = $name . ' ' . $surname;
                $emailContent = "Click here to finish your registration:<br/>
                <a href='http://localhost/project_first_term/index.php?page=login&verification=1&userEmail=$email'>CLICK ME!</a><br/><br/>
                Welcome to our app!";
                send_email($email, $emailContent);
                header('Location: login.php');
            } else {
                echo '<p style="color:red">**ERROR: passwords do not match!</p>';
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
        <title>Register</title>
    </head>
    <body>
        <header>
            <h1>SAMPLE TEXT</h1>
        </header>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method='POST' autocomplete="off" enctype="multipart/form-data">
            <fieldset>
                <legend>Registration</legend>
                <label for="name-register">Name*</label>
                <input type="text" id="name-register" name="name-register" placeholder="Name" required autofocus/><br/>

                <label for="surname-register">Surname(s)*</label>
                <input type="text" id="surname-register" name="surname-register" placeholder="Surname" required /><br/>
                
                <label for="user-register">Username*</label>
                <input type="text" id="user-register" name="user-register" placeholder="Username" required/><br/>
                
                <label for="email-register">Email*</label>
                <input type="email" id="email-register" name="email-register" placeholder="Email address" required/><br/>

                <label for="passwd-register">Password*</label>
                <input type="password" id="passwd-register" name="passwd-register" placeholder="Password" required /><br/>

                <label for="passwd-conf-register">Confirm Password*</label>
                <input type="password" id="passwd-conf-register" name="passwd-conf-register" placeholder="Password Confirmation" required /><br/>

                <label for="age-register">Age*</label>
                <input type="number" min=16 max=99 id="age-register" name="age-register" placeholder="Age" /><br/>

                <label for="tel-register">Telephone number</label>
                <input type="tel" id="tel-register" name="tel-register" placeholder="Phone number" /><br/>

                <label for="pfp-register">Profile picture</label>
                <input type="file" id="pfp-register" name="file" placeholder="Choose a picture" /><br/>
                
                <p>An email will be sent to your email to verify your account.</p>

                <button type="submit">Register</button>
                <button type="reset">Cancel</button><br/>

            </fieldset>
        </form>
    </body>
</html>