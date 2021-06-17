<?php

namespace Model;

class Admin {
  public static function get_admin($email, $pass) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from admin where email=?");
    $stmt->execute(array($email));
    $result = $stmt->fetch();
    if ($result) {
      if (password_verify($pass, $result["pass"])) {
        return true;
      }
    }

    return false;
  }

  public static function get_pending_requests() {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from user where active=false");
    $stmt->execute();
    $results = $stmt->fetchAll();

    $res_data = array();
    foreach($results as $result) {
      $obj = array(
        "email" => $result["email"],
        "name" => $result["name"]
      );

      array_push($res_data, $obj);
    }

    return array("arr" => $res_data);
  }

  public static function approve_registration_request($name, $email) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("UPDATE user set active=true where name=? and email=?");
    $stmt->execute(array($name, $email));
  }

  public static function reject_registration_request($name, $email) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("DELETE from user where name=? and email=? and active=false");
    $stmt->execute(array($name, $email));
  }

  public static function get_pending_checkouts() {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from transaction");
    $stmt->execute();
    $results = $stmt->fetchAll();

    $res_data = array();
    foreach($results as $result) {
      $book = $result["book"];
      $requestee = $result["requestee"];
      $id = $result["id"];

      $user_data = \Model\User::get_user_by_id($requestee);
      $book_data = \Model\Book::get_book_by_id($book);

      $obj = array(
        "id" => $id,

        "user" => array(
          "id" => $user_data["id"],
          "name" => $user_data["name"],
          "email" => $user_data["email"]
        ),

        "book" => array(
          "title" => $book_data["title"],
          "id" => $book_data["id"]
        )
      );

      array_push($res_data, $obj);
    }

    return array("arr" => $res_data);
  }

  public static function approve_checkout_request($id, $requestee, $book, $timestamp) {
    \Model\Book::add_currently_issued($requestee, $book, $timestamp);
    \Model\Book::remove_from_transaction($id);
  }

  public static function reject_checkout_request($id, $book) {
    \Model\Book::remove_from_transaction($id);
    \Model\Book::update_book_available($book, 1);
  }

  public static function get_pending_checkins() {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from checkin");
    $stmt->execute();
    $results = $stmt->fetchAll();

    $res_data = array();
    foreach($results as $result) {
      $book = $result["book"];
      $requestee = $result["requestee"];
      $id = $result["id"];

      $user_data = \Model\User::get_user_by_id($requestee);
      $book_data = \Model\Book::get_book_by_id($book);

      $obj = array(
        "id" => $id,

        "user" => array(
          "id" => $user_data["id"],
          "name" => $user_data["name"],
          "email" => $user_data["email"]
        ),

        "book" => array(
          "title" => $book_data["title"],
          "id" => $book_data["id"]
        )
      );

      array_push($res_data, $obj);
    }

    return array("arr" => $res_data);
  }

  public static function approve_checkin_request($id, $requestee, $book, $timestamp) {
    $in_checkin = \Model\Book::get_checkin_by_id($id);
    $issue_id = $in_checkin["issue_id"];

    $in_currently_issued = \Model\Book::get_currently_issued_by_id($issue_id);
    $time_issued = $in_currently_issued["time_issued"];

    \Model\Book::remove_from_currently_issued($issue_id);
    \Model\Book::remove_from_checkin($id);
    \Model\Book::insert_into_history($requestee, $book, $time_issued, $timestamp);
    \Model\Book::update_book_available($book, 1);
  }

  public static function reject_checkin_request($id) {
    \Model\Book::remove_from_checkin($id);
  }
    
}

