<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    require "../vendor/autoload.php";

        //db connection
		try {
			$connection_data = configBD("conf.xml");
			$bd = new PDO($connection_data[0], $connection_data[1], $connection_data[2]);
			// echo "Connected";
		}catch (PDOException $e) {
			echo '**Database error: ' . $e->getMessage();
		}
    
    //returns an array with db connection data using xpath
	function configBD($file){
		$data = simplexml_load_file($file);
		$dbname = $data->xpath('//dbname');
		$host = $data->xpath('//host');
		$port = $data->xpath('//port');
		$user = $data->xpath('//user');
		$password = $data->xpath('//password');
		return array('mysql:dbname=' . $dbname[0] . ';host=' . $host[0], $user[0], $password[0]);
	}
    
    //validates XML
	function validateXML(){
		$dept = new DOMDocument();
		$dept->load('conf.xml');
		$res = $dept->schemaValidate('configuration_schema.xsd');
		if ($res){ 
			echo "<br/>The file is valid";
		} 
		else { 
			echo "<br/>The file is not valid"; 
		}
	}

    //cheks if a user is already registered
    function check_user_registration($user, $email, $bd){
        $query = "SELECT * FROM chatapp.users WHERE users.username like '$user' or users.email like '$email'";
        $bd->query($query);
        $result = $bd->query($query);
        // var_dump($result);
        $result = $result->fetch();
        // $result->fetch();

        if($result['username'] == $user || $result['email'] == $email){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //registers a user
    function register_user($name, $surname, $username, $email, $passwd, $age, $tel, $pfp, $bd){
        if(empty($pfp)){
            $query = "INSERT INTO chatapp.users (usName, usSurname, username, email, passwd, age, telephone) VALUES ('$name', '$surname', '$username', '$email', '$passwd', '$age', '$tel');";
            $result = $bd->query($query);
            //insert confirmation
            if($result->rowCount() > 0){
                echo '<p style="color:lightgreen">**SUCCESS: Registration complete!</p>';		
                // return $result->fetch();
            } else {
                echo '<p style="color:red">**ERROR: Check user or password!</p>';
            }
        } else {
            $query = "INSERT INTO chatapp.users (usName, usSurname, username, email, passwd, age, telephone, pfp) VALUES ('$name', '$surname', '$username', '$email', '$passwd', '$age', '$tel', '$pfp');";
            $result = $bd->query($query);
            //insert confirmation
            if($result->rowCount() > 0){
                echo '<p style="color:lightgreen">**SUCCESS: Registration complete!</p>';		
            } else {
                echo '<p style="color:red">**ERROR: Check user or password!</p>';
            }
        }
    }

    //checks the login
    function check_user_login($user, $passwd, $bd){
        $query = $bd->query("SELECT users.* FROM chatapp.users WHERE users.username LIKE '$user'");
        $userExists = $query->fetch(PDO::FETCH_ASSOC);
        // var_dump($userExists);
        
        if($userExists){
            if($userExists['username'] === 'root'){
                if($passwd == strval($userExists['passwd'])){return TRUE;}
                else {return FALSE;}
            } else {
                if(password_verify($passwd, strval($userExists['passwd']))){
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        } else {
            return FALSE;
        }
    }

    //sends a message, with or without a file
    function send_message_file($message, $senderID, $chatID, $timestamp, $file, $bd){
        if($message == ''){return TRUE;} //in this way, blank messages won't be sent 
        // $query = "INSERT INTO chatapp.messages (id, senderID, receiverID, content, msgTime, isRead) VALUES (NULL, '', '', '$message', '', '');";
        $query = "INSERT INTO chatapp.messages (senderID, chatID, content, msgTime, msgFile) VALUES ('$senderID', '$chatID', '$message', '$timestamp', '$file');";
        $result = $bd->query($query);
        
        return $result;
    }

    #not used
    //initial send_message function (no files)
    // function send_message($message, $senderID, $chatID, $timestamp, $bd){
    //     if($message == ''){return TRUE;} //in this way, blank messages won't be sent 
    //     // $query = "INSERT INTO chatapp.messages (id, senderID, receiverID, content, msgTime, isRead) VALUES (NULL, '', '', '$message', '', '');";
    //     $query = "INSERT INTO chatapp.messages (senderID, chatID, content, msgTime) VALUES ('$senderID', '$chatID', '$message', '$timestamp');";
    //     $result = $bd->query($query);
        
    //     return $result;
    // }

    //gets all the messages from the database
    function get_messages($chatID, $bd){
        // $query = "SELECT * FROM chatapp.messages WHERE messages.chatID LIKE '$chatID'";
        $query = "SELECT * FROM chatapp.messages WHERE messages.chatID LIKE $chatID";
        $result = $bd->query($query);
        $result = $result->fetchAll();
        // var_dump($result);

        return $result;
    }

    //gets all the chats from the database
    function get_chats($userID, $bd){
        $query = "SELECT * FROM chatapp.chats
                INNER JOIN chatapp.participate_user_chats ON chats.id = participate_users_chats.chatID
                INNER JOIN chatapp.users ON users.id = participate_users_chats.userID
                WHERE users.id LIKE '$userID'";
        
        $result = $bd->query($query);
        $result = $result->fetchAll();

        return $result;
    }

    //creates a chat and returns its id
    function create_chat_get_id($alias, $bd){
        $query = "INSERT INTO `chats`(`alias`) VALUES ('$alias')";
        $result = $bd->query($query);

        $query2 = "SELECT chats.id FROM chatapp.chats WHERE chats.alias LIKE '$alias' ORDER BY chats.id DESC LIMIT 1";
        $result2 = $bd->query($query2);
        $id = $result2->fetch();
        return $id[0];
    }

    //adds participants to a chat
    function add_participants_chat($participant, $chatID, $bd){
        $query = "INSERT INTO `participate_users_chats`(`userID`, `chatID`) VALUES ('$participant','$chatID')";
        $result = $bd->query($query);

        $count = $result->rowCount();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    //function to leave a group
    function leave_group($userID, $chatID, $bd){
        $query = "DELETE FROM chatapp.participate_users_chats WHERE participate_users_chats.userID LIKE $userID AND participate_users_chats.chatID LIKE $chatID";
        // $result = $bd->query($query);
        $statement = $bd->query($query);
        // var_dump($statement);
        $result = $statement->fetch();
        // var_dump($result);
        
        return $result;
    }

    //function used to upload a file to the project, so that the profile pictures or files inside messages can also be displayed
    function upload_file(){
        if (empty($_FILES["file"]["name"])) {
            return "";
        }
        
        $target_dir = "./assets/files/uploads/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["file"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        // if (file_exists($target_file)) {
        //     echo "Sorry, file already exists.";
        //     $uploadOk = 0;
        // }

        // Check file size
        if ($_FILES["file"]["size"] > 5000000) {
            echo '<p style="color:red">ERROR: Sorry, your file is too large.</p>';
            $uploadOk = 0;
        }

        // Allow certain file formats
        // if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        //     echo '<p style="color:red">Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>';
        //     $uploadOk = 0;
        // }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo '<p style="color:red">ERROR: Sorry, your file was not uploaded.</p>';
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                    // echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
                } else {
                    echo '<p style="color:red">ERROR: Sorry, there was an error uploading your file.</p>';
                }
        }

        return $target_file;
    }

    // function to send emails (password recovery or validation when registering)
    function send_email($email = null, $content){
        //remember to change the email of the app
        try{
            $mail = new PHPMailer();
            $mail->IsSMTP();
            // comment the line below to hide the server messages after sending an email.
            // $mail->SMTPDebug  = 2;				
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "tls";                 
            $mail->Host       = "smtp.gmail.com";    
            $mail->Port       = 587;
            // --
            $mail->Username   = "talesdemiletoxd@gmail.com"; 
            $mail->Password   = "12345tales";   	
            // --
            $mail->SetFrom('talesdemiletoxd@gmail.com');
            $mail->Subject    = 'PASSWORD RECOVERY';
            $mail->MsgHTML($content);
            // $mail->addAttachment($_FILES['file-send']);
            $mail->AddAddress($email);
            $result = $mail->Send();
        } catch (Exception $e){
            //nothing here
        }
        
        if($email){
            if(!$result) {
                echo "<p style=color:red>Error" . $mail->ErrorInfo . '</p>';
            } else {
                echo "An email with instructions has been sent to: <b>" . $email.'</b>';
                $email = null;
            }
        }
    }

