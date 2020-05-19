<?php
include_once "PDO.php";

function GetOneUserFromId($id)
{
  global $PDO;
  $response = $PDO->query("SELECT * FROM user WHERE id = $id");
  return $response->fetch();
}

function GetAllUsers()
{
  global $PDO;
  $response = $PDO->query("SELECT * FROM user ORDER BY nickname ASC");
  return $response->fetchAll();
}

function GetUserIdFromUserAndPassword($username, $password)
{
  global $PDO;

  //On verifie que les champs sont remplis
  if (!empty($username) && !empty($password)) {

    //Request to get the user ID and the PWD
    $preReq = $PDO->prepare("SELECT * FROM user WHERE nickname = :username and password = :password");
    $preReq->execute(
      array(
        "username" => $username,
        "password" => $password
      )
    );
  }

  $users = $preReq->fetchAll();
  //If user exists
  if (count($users) == 1) {
    return $users[0]['id'];
  } else {
    return -1;
  }
}
