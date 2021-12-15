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

    // var_dump($_SESSION['user-id']);
    if(!isset($userID) || $role != 'admin'){
        header('Location: logout.php');
    }

    $queryUsers = "SELECT * FROM users";
    $result = $bd->query($queryUsers);
    $resultUsers = $result->fetchAll();

    if(isset($_GET['verification'])){
        
        $usID = $_GET['user-id'];
        $query = "SELECT * FROM users WHERE id LIKE $usID";
        $result = $bd->query($query);
        $result = $result->fetch();

        if($result['isActive'] == 0){
            $query = "UPDATE `users` SET `isActive` = 1
                    WHERE `id` LIKE '$usID'";
            $result = $bd->query($query);
            header('Location: admin.php');
        } else {
            $query = "UPDATE `users` SET `isActive` = 0
            WHERE `id` LIKE '$usID'";
            $result = $bd->query($query);
            header('Location: admin.php');
        }
    }

    if(isset($_GET['role'])){
        
        $usID = $_GET['user-id'];
        $query = "SELECT * FROM users WHERE id LIKE $usID";
        $result = $bd->query($query);
        $result = $result->fetch();

        if($result['usRole'] == 'client'){
            $query = "UPDATE `users` SET `usRole` = 'admin'
                    WHERE `id` LIKE '$usID'";
            $result = $bd->query($query);
            header('Location: admin.php');
        } else {
            $query = "UPDATE `users` SET `usRole` = 'client'
            WHERE `id` LIKE '$usID'";
            $result = $bd->query($query);
            header('Location: admin.php');
        }
    }
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <!-- <link rel="stylesheet" href="assets/css/admin_style.css"> -->
        <title>ADMIN ZONE</title>
    </head>

    <body>
        <div class="container">
            <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
                <span class="fs-4">Admin Zone</span>
            </a>

            <ul class="nav nav-pills">
                <li class="nav-item"><a href="logout.php" class="nav-link active" aria-current="page">Logout</a></li>
                <li class="nav-item"><a href="chats.php" class="nav-link">Home</a></li>
            </ul>   
            </header>
        </div>

        <table class="table table-success table-striped">
            <thead>
                <tr>
                <th scope="col">ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Age</th>
                <th scope="col">Telephone</th>
                <th scope="col">Profile Picture</th>
                <th scope="col">Verified</th>
                <th scope="col">Role</th>
                <th scope="col"></th>
                <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($resultUsers as $user) : ?>
                    <tr>
                    <th scope='row'><?=$user['id']?></th>
                    <td><?=$user['usName']?></td>
                    <td><?=$user['usSurname']?></td>
                    <td><?=$user['username']?></td>
                    <td><?=$user['email']?></td>
                    <td><?=$user['age']?></td>
                    <td><?=$user['telephone']?></td>
                    <td><?=$user['pfp']?></td>
                    <td><?=$user['isActive']?></td>
                    <td><?=$user['usRole']?></td>
                    <td><a class="btn btn-outline-success" href="index.php?page=admin&verification=1&user-id=<?=$user['id']?>" role="button">Verification</a></td>
                    <td><a class="btn btn-outline-success" href="index.php?page=admin&role=1&user-id=<?=$user['id']?>" role="button">Role</a></td>
                    </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </body>
</html>