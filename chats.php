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
    $userActive = $_SESSION['user-active'];
    $role = $_SESSION['user-role'];
    // var_dump($pfp);

    $query = "SELECT * FROM chatapp.users WHERE users.username NOT LIKE '$user'";
    $result = $bd->query($query);
    $users = $result->fetchAll();
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/chats_style.css">
        <title>Chats</title>
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
                <?php if($role == 'admin'){echo '<a href="admin.php">Admin Zone</a>';} ?>
            </nav>
        </div>
        <div class="main-container">
            <div class="sidebar">
                <div class="profile">
                    <img src="<?= $pfp ?>" onerror="this.src='assets/files/img/default/pfp_default.jpg'" alt="Profile Picture">
                    <div class="profile-data-box">
                        <p class="data-item"><?= $user ?></p>
                        <p class="data-item"><?= $userName . ' ' . $userSurname ?></p>
                        <p class="data-item">Status</p>
                    </div>
                    <a class="edit-link" href="edit_profile.php">Edit</a>
                </div>
                <div class="search-contacts">
                    <?php
                        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['contact-list'])){
                            $alias = $_POST['chat-alias'];
                            $chatID = create_chat_get_id($alias, $bd);
                            // var_dump($chatID);

                            $resultInsertParticipant = add_participants_chat($userID, $chatID, $bd);
                            // var_dump($_POST['contactlist']);
                            foreach($_POST['contact-list'] as $participant){
                                // var_dump($participant);
                                $resultInsertParticipant = add_participants_chat($participant, $chatID, $bd);
                            }
                            header('Location: index.php?page=current_chat&chat-id='. $chatID .'');
                        }
                    ?>
                    <form action="" method="POST" autocomplete="off">
                        <input type="text" name="chat-alias" placeholder="Alias..."/>
                        <select name="contact-list[]" multiple placeholder="Search...">
                        <?php foreach ($users as $user) : ?>
                            <option value="<?= $user['id'] ?>"><?=$user['username']?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">OK</button>
                        <button type="reset">Cancel</button>
                    </form>
                </div>
                <div class="chats">
                    <?php
                        $query = "SELECT chats.* FROM chatapp.chats   
                                    INNER JOIN chatapp.participate_users_chats ON chats.id = participate_users_chats.chatID
                                    INNER JOIN chatapp.users ON users.id = participate_users_chats.userID
                                    WHERE users.id LIKE '$userID'";
                        $result = $bd->query($query);
                        $chats = $result->fetchAll(PDO::FETCH_ASSOC); //chats[][]

                        foreach($chats as $chat){
                            $query2 = "SELECT group_concat(users.username separator ', ') FROM chatapp.users
                                        INNER JOIN chatapp.participate_users_chats ON participate_users_chats.userID = users.id
                                        WHERE participate_users_chats.chatID LIKE " . $chat['id'];
                            $result2 = $bd->query($query2);
                            $usersInChat = $result2->fetch();
                            echo '
                                <a href="index.php?page=current_chat&chat-id='. $chat['id'] .'">
                                    <div class="chat">
                                        <img src="' . $chat['pfp'] . '" alt="Profile picture">
                                        <div class="profile-data-box">
                                            <p class="data-item">' . $chat['alias'] . '</p>
                                            <p class="data-item">' . $usersInChat[0] . '</p>
                                        </div>
                                    </div>
                                </a>
                            ';  
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>