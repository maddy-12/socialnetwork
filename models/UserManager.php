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
    $response = $PDO->prepare("SELECT id FROM user WHERE nickname = :username and password = MD5(:password) ");
    $response->execute(
      array(
        "username" => $username,
        "password" => $password
      )
    );
    if ($response->rowCount() == 1) {
      $row = $response->fetch();
      return $row['id'];
    } else {
      return -1;
    }
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

//
function IsNicknameFree($nickname)
{
  global $PDO;
  $response = $PDO->prepare("SELECT * FROM user WHERE nickname = :nickname ");
  $response->execute(
    array(
      "nickname" => $nickname
    )
  );
  return $response->rowCount() == 0;
}

//Create new user 
function CreateNewUser($nickname, $password)
{
  global $PDO;

  //Put the MD5 to encrypt the password
  $response = $PDO->prepare("INSERT INTO user (nickname, password) values (:nickname , MD5(:password) )");
  $response->execute(
    array(
      "nickname" => $nickname,
      "password" => $password
    )
  );
  return $PDO->lastInsertId();
}
