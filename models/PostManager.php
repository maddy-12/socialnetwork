<?php
include_once "PDO.php";

//Get a post by its ID
function GetOnePostFromId($id)
{

  global $PDO;
  $response = $PDO->prepare("SELECT * FROM post WHERE id = :id ");
  $response->execute(
    array(
      "id" => $id
    )
  );
}
//Get all the posts
function GetAllPosts()
{
  global $PDO;
  $response = $PDO->query(
    "SELECT post.*, user.nickname "
      . "FROM post LEFT JOIN user on (post.user_id = user.id) "
      . "ORDER BY post.created_at DESC"
  );
  return $response->fetchAll();
}

//Retrieve Posts by user ID
function GetAllPostsFromUserId($userId)
{
  global $PDO;
  $response = $PDO->query("SELECT * FROM post WHERE user_id = $userId ORDER BY created_at DESC");
  return $response->fetchAll();
}
//Search methode
function  SearchInPosts($search)
{
  global $PDO;
  $response = $PDO->prepare(
    "SELECT post.*, user.nickname "
      . "FROM post LEFT JOIN user on (post.user_id = user.id) "
      . "WHERE content like :search "
      . "ORDER BY post.created_at DESC"
  );
  $searchWithPercent = "%$search%";
  $response->execute(
    array(
      "search" => $searchWithPercent
    )
  );
  return $response->fetchAll();
}

//Create new post

function CreateNewPost($userId, $msg)
{
  global $PDO;

  $response = $PDO->prepare("INSERT INTO post(user_id, content) values (:user_id, :content)");
  $response->execute(
    array(
      "user_id" => $userId,
      "content" => $msg

    )
  );
}
