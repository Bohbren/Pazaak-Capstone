<!-- 
    Things to fix:
    -Footer on pages outside of DeckBuilder
    -Back button when coming from index
    -Profile page
    -file name consistency 

    Things to work on tomorrow:
    -Continue validation for front end
    -Make create deck button actually do something (Other than display numbers in console)
 -->



<?php
//Controller for the application holding case statements that 
//navigate you to the different pages of the website

//Required Model files
require_once('models/controllerSecurity.php');
require_once("models/validation.php");
require_once("models/Player.php");
require_once("models/PlayerDB.php");
require_once("models/database.php");
require_once("models/DeckDB.php");


//Sets the cookie for the session
$lifetime = 60 * 60 * 24 * 14; //Two weeks
session_set_cookie_params($lifetime);
session_start();

//Variables to hold values/wipe values for page loading and errors
$fName = "";
$lName = "";
$uName = "";
$emailCheck = "";
$pWord = "";
$error = "";

//Session variables that contain information for current use
if (empty($_SESSION['player'])) {
    $_SESSION['player'] = "";
}

//Defines the action statement used for the switch statement - and then takes you to 
//the desired page
$action = filter_input(INPUT_POST, "action");

//Checks if the action variable is null - if it is, go back to home page view
if ($action == NULL) {
    $action = filter_input(INPUT_GET, "action");
    if ($action == NULL) {
        $action = "homeView";
    }
}

//Switch statement containing actions that take you to different views of the site.
switch ($action) {
    case 'homeView':
        include("views/homeView.php");
        die();
        break;
    case 'deckBuilderView':
        $player = $_SESSION['player'];
        $errorMessage = "";
        include('views/deckBuilderView.php');
        die();
        break;
    case 'buildDeck':
        $player = $_SESSION['player'];
        $errorMessage = "";
        include('views/homeView.php');
        die();
        break;
    case 'registerView':
        $firstName = filter_input(INPUT_POST, 'fName');
        $lastName = filter_input(INPUT_POST, 'lName');
        $userName = filter_input(INPUT_POST, 'uName');
        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'password');
        $fName = "";
        $lName = "";
        $uName = "";
        $emailCheck = "";
        $pWord = "";
        include('views/registerView.php');
        die();
        break;
    case 'createUser':
        $firstName = filter_input(INPUT_POST, 'fName');
        $lastName = filter_input(INPUT_POST, 'lName');
        $userName = filter_input(INPUT_POST, 'uName');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $fName = Validation::isNotEmpty($firstName, "First Name");
        $lName = Validation::isNotEmpty($lastName, "Last Name");
        $uName = Validation::isNotEmpty($userName, "User Name");
        if ($fName === "") {
            $fName = Validation::checkNames($firstName, "First Name");
        }

        $emailCheck = "";
        if (!$email) {
            $emailCheck = "Email is invalid";
        }
        if ($lName === "") {
            $lName = Validation::checkNames($lastName, "Last Name");
        }
        $pWord = Validation::checkPassword($password);
        if ($uName === "") {
            $uName = Validation::checkUserName($userName, "User Name");
        }
        if ($uName === "") {
            $uName = Validation::isUnique($userName, "User Name", "userName");
        }
        if ($emailCheck === "") {
            $emailCheck = Validation::isUnique($email, "Email", "email");
        }
        if ($fName === "" && $lName === "" && $uName === "" && $emailCheck === "" && $pWord === "") {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $currentPlayer = new Player($userName, $firstName, $lastName, $email, $password);
            $currentPlayer->setId(PlayerDB::insertNewPlayer($currentPlayer));
            $_SESSION['player'] = $currentPlayer;
            include('views/homeView.php');
        } else {
            include('views/registerView.php');
        }
        die();
        break;
    case 'loginPlayer':
        $userName = filter_input(INPUT_POST, 'uName');
        $password = filter_input(INPUT_POST, 'password');
        $uName = Validation::isNotEmpty($userName, "UserName");
        $pWord = Validation::checkPassword($password);

        if ($uName === "" && $pWord === "") {
            $hashedPasswordList = PlayerDB::getPasswordHash($userName);
            if (!empty($hashedPasswordList)) {
                $hashedPassword = $hashedPasswordList[0]['password'];
                if (password_verify($password, $hashedPassword)) {
                    $playerList = PlayerDB::getPlayer($userName);
                    $player = new Player($userName, $playerList[0]['firstName'], $playerList[0]['lastName'], $playerList[0]['email'], $playerList[0]['password']);
                    if (!is_null($playerList[0]['imagePath'])) {
                        $player->setImagePath($playerList[0]['imagePath']);
                    }
                    if (!is_null($playerList[0]['imageExtension'])) {
                        $player->setImageExt($playerList[0]['imageExtension']);
                    }
                    $player->setId($playerList[0]['playerID']);
                    $_SESSION['player'] = $player;
                    $decks = DeckDB::select_all($player);

                    $firstName = $player->getFirstName();
                    $lastName = $player->getLastName();
                    $email = $player->getEmail();
                    $password = "";
                    $fName = "";
                    $lName = "";
                    $emailCheck = "";
                    $pWord = "";
                    $image = "images/";
                    if ($player->getImagePath() === NULL || $player->getImagePath() === "") {
                        $image = $image . "defaultImg.png";
                    } else {
                        $image = $image . $player->getImagePath() . $player->getImageExt();
                    }
                    $errorMessage = "";
                    include('views/profilePage.php');
                } else {
                    $error = "Username or Password is not correct";
                    $password = "";
                    $hashedPasswordList = [];
                    include('views/loginView.php');
                }
            } else {
                $error = "Username Doesn't exist";
                $password = "";
                $hashedPasswordList = [];
                include('views/loginView.php');
            }
        } else {
            $error = "Username or Password is not correct";
            $password = "";
            $hashedPasswordList = [];
            include('views/loginView.php');
        }
        die();
        break;
    case 'loginView':
        $userName = filter_input(INPUT_POST, 'uName');
        $password = "";
        $uName = "";
        $pWord = "";
        $error = "";
        include('views/loginView.php');
        die();
        break;
    case 'profileView':
        $player = $_SESSION['player'];
        $decks = DeckDB::select_all($player);

        if (empty($player)) {
            $userName = "";
            $password = "";
            $uName = "";
            $pWord = "";
            $error = "";
            include('views/loginPage.php');
            die();
        }
        $firstName = $player->getFirstName();
        $lastName = $player->getLastName();
        $email = $player->getEmail();
        $userName = $player->getUserName();
        $password = "";
        $fName = "";
        $lName = "";
        $emailCheck = "";
        $pWord = "";
        $image = "images/";
        if ($player->getImagePath() === NULL || $player->getImagePath() === "") {
            $image = $image . "defaultImg.png";
        } else {
            $image = $image . $player->getImagePath() . $player->getImageExt();
        }
        $errorMessage = "";
        include('views/profilePage.php');
        die();
        break;
    
}
?>