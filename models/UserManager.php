<?php
include_once "PDO.php";

//Get a user from its ID
function GetOneUserFromId($id)
{
  global $PDO;
  $response = $PDO->query("SELECT * FROM user WHERE id = $id");
  return $response->fetch();
}

//Get all the users
function GetAllUsers()
{
  global $PDO;
  $response = $PDO->query("SELECT * FROM user ORDER BY nickname ASC");
  return $response->fetchAll();
}

//Get the username & pwd in order to login
function GetUserIdFromUserAndPassword($username, $password)
{
  global $PDO;

  //Check if the inputs are properly filled
  if (!empty($username) && !empty($password)) {

    //Request to get the user ID and the PWD
    $response = $PDO->prepare("SELECT * FROM user WHERE nickname = :username and password = :password");
    $response->execute(
      array(
        "username" => $username,
        "password" => $password
      )
    );
  }

  $users = $response->fetchAll();
  //If user exists
  if (count($users) == 1) {
    return $users[0]['id'];
    //If it doesn't exist
  } else {
    return -1;
  }
}
