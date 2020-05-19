<?php

$action = $_GET["action"] ?? "display";

switch ($action) {

    //////////Register//////////
  case 'register':
    include "../models/UserManager.php";
    //If user fill the username and password and retypes the correct password
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['passwordRetype'])) {
      //Don't show the error message
      $errorMsg = NULL;
      //If the username exists already
      if (!IsNicknameFree($_POST['username'])) {
        //Show error message
        $errorMsg = "Nickname already used.";

        //If the retyped password is diffrent from the pwd filled previously
      } else if ($_POST['password'] != $_POST['passwordRetype']) {

        //Show error message
        $errorMsg = "Passwords are not the same.";

        //If the password is too short (less than 8 characters)
      } else if (strlen(trim($_POST['password'])) < 8) {

        //Show error message
        $errorMsg = "Your password should have at least 8 characters.";

        //if the username is too short ( less than 4 characters)
      } else if (strlen(trim($_POST['username'])) < 4) {

        //Show error message
        $errorMsg = "Your nickame should have at least 4 characters.";
      }

      if ($errorMsg) {
        include "../views/RegisterForm.php";
      } else {
        $userId = CreateNewUser($_POST['username'], $_POST['password']);
        $_SESSION['userId'] = $userId;
        header('Location: ?action=display');
      }
    } else {
      include "../views/RegisterForm.php";
    }
    break;

    /////////////Logout///////////
  case 'logout':
    if (isset($_SESSION['userId'])) {
      unset($_SESSION['userId']);
    }
    header('Location: ?action=display');

    break;

    ///////////////LOGIN//////////
  case 'login':

    include "../models/UserManager.php";
    if (isset($_POST['username']) && isset($_POST['password'])) {
      $userId = GetUserIdFromUserAndPassword($_POST['username'], $_POST['password']);
      if ($userId > 0) {
        $_SESSION['userId'] = $userId;
        header('Location: ?action=display');
      } else {
        $errorMsg = "Wrong login and/or password.";
        include "../views/LoginForm.php";
      }
    } else {
      include "../views/LoginForm.php";
    }
    break;

    ///////////////Write a new post/////////
  case 'newMsg':
    include "../models/PostManager.php";
    if (isset($_SESSION['userId']) && isset($_POST['msg'])) {
      CreateNewPost($_SESSION['userId'], $_POST['msg']);
    }
    header('Location: ?action=display');
    break;

    //Write a new comment///////////////////
  case 'newComment':
    // code...
    break;

    //////////////Default display/////////////
  case 'display':
  default:
    include "../models/PostManager.php";
    $posts = GetAllPosts();

    ////// Search //////////
    if (isset($_GET["search"])) {
      $posts = SearchInPosts($_GET["search"]);
    } else {
      $posts = GetAllPosts();
    }

    include "../models/CommentManager.php";
    $comments = array();

    foreach ($posts as $onePost) {
      $idPost = $onePost['id'];
      $comments[$idPost] = GetAllCommentsFromPostId($idPost);
    }

    include "../views/DisplayPosts.php";
    break;
}
