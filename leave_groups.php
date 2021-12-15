<?php
    require 'utilities.php';

    if(isset($_GET['chat-id']) && isset($_GET['user-id'])){
        $userID = $_GET['user-id'];
        $chatID = $_GET['chat-id'];
    }
    // var_dump($userID);
    // var_dump($chatID);
    $left = leave_group($userID, $chatID, $bd);

    if(!$left){
        header('Location: chats.php');
    } else {
        echo '<p style="color:red">ERROR: Something went wrong and you couldn\'t leave the group</p>';
    }