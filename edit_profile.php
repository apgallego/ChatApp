<?php
    require_once 'utilities.php';

    session_start();
    $userID = $_SESSION['user-id'];
    $user = $_SESSION['username'];
    $userName = $_SESSION['user-name'];
    $userSurname = $_SESSION['user-surname'];
    $email = $_SESSION['user-email'];
    $age = $_SESSION['user-age'];
    $telephone = $_SESSION['user-telephone'];
    $pfp = $_SESSION['user-pfp'];
    // var_dump($pfp);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {  
        $newUsername = $_POST['username-edit'];
        $newTelephone = $_POST['tel-edit'];
        $newPfp = upload_file();

        if(empty($newPfp)){
            $query2 = "UPDATE `users` SET `username` = '$newUsername',
                `telephone` = '$newTelephone'
                WHERE `id` LIKE '$userID'";
        } else {
            $query2 = "UPDATE `users` SET `username` = '$newUsername',
                    `telephone` = '$newTelephone',
                    `pfp` = '$newPfp'
                    WHERE `id` LIKE '$userID'";
        }
        $update = $bd->query($query2);

        // var_dump($result2);
        // var_dump($bd->query($query));
        if($update){
            $_SESSION['username'] = $newUsername;
            $_SESSION['user-telephone'] = $newTelephone;
            $_SESSION['user-pfp'] = $newPfp;
            header('edit_profile.php');
        } else {
            echo '<p style="color:red;">**ERROR: Something went wrong while editing your profile.</p>';
        }
    }
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="assets/css/edit_profile_style.css">
        <title>Edit Profile</title>
    </head>
    <body>
        <style>
            nav{background-color: white;}
            .nav-container{margin: 10px;background-color: white;position: sticky;
                top: 0px;display: flex;justify-content: space-between;
                align-items: center;z-index: 1;padding: 0 3em;border: 1px solid black;}
            nav{padding: 5px;width: 100%;height: 100%;}
            .nav-container a{margin: 1.5em;display: inline;
                text-decoration: none;line-height: 0.2em;width: 5em;}
            .nav-container a{color: black;}
            .nav-container a:hover{text-decoration: underline;}
        </style>
        <div class="nav-container">
            <nav>
                <a href="logout.php">Logout</a>
                <a href="chats.php">Home</a>
            </nav>
        </div>
        <?php
            $query = "SELECT * FROM chatapp.users WHERE users.id LIKE '$userID'";
            $result = $bd->query($query);
            $userData = $result->fetch(PDO::FETCH_ASSOC);
            // var_dump($userData);
        ?>
        <div class="main-container">
            <img src="<?= $userData['pfp'] ?>" alt="Profile picture">
            <div class="data-box">
                <div class="data-item"><?= $userData['username'] ?></div>
                <div class="data-item"><?= $userData['usName'] . ' ' . $userData['usSurname'] ?></div>
                <div class="data-item"><?php if($userData['telephone'] == ''){echo '*No telephone*';}else{echo $userData['telephone'];} ?></div>
                <div class="data-item"><?= 'Status' ?></div>
            </div>
        
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" autocomplete="off" enctype="multipart/form-data">

                <label for="username-edit">Change username</label>
                <input type="text" id="username-edit" name="username-edit" value="<?= $userData['username'] ?>" placeholder="Change your username" /><br/><br/>

                <label for="tel-edit">Change or add telephone</label>
                <input type="tel" id="tel-edit" name="tel-edit" value="<?= $userData['telephone'] ?>" placeholder="Add or Change your telephone" /><br/><br/>

                <label for="pfp-edit">Change profile picture</label>
                <input type="file" id="pfp-edit" name="file" value="<?= $userData['pfp'] ?>" placeholder="Choose a picture" /><br/><br/>

                <button type="submit">Change</button>
                <button type="reset">Cancel</button>
            </form>

        </div>
    </body>
</html>