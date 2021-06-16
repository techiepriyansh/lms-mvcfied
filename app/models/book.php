<?php

namespace Model;

class Book {
  public static function get_user_library($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from book where available > 0");
    $stmt->execute();
    $results = $stmt->fetchAll();

    $res_data = array();
    foreach($results as $result) {
      $stmt = $db->prepare("SELECT * from currently_issued where book=".$result["id"]." and bearer=".$id);
      $stmt->execute();
      $in_currently_issued = $stmt->fetchAll();
      if (count($in_currently_issued) > 0) continue;

      $stmt = $db->prepare("SELECT * from transaction where book=".$result["id"]." and requestee=".$id);
      $stmt->execute();
      $in_transaction = $stmt->fetchAll();
      $requested = count($in_transaction) > 0;

      $result["requested"] = $requested;

      array_push($res_data, $result);
    }

    return array("arr" => $res_data);
  }

  public static function get_issued_books($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from currently_issued where bearer=".$id);
    $stmt->execute();
    $results = $stmt->fetchAll();

    $res_data = array();
    foreach($results as $result) {
      $stmt = $db->prepare("SELECT * from book where id=".$result["id"]);
      $stmt->execute();
      $book_data = $stmt->fetchAll();

      $stmt = $db->prepare("SELECT * from checkin where book=".$result["id"]." and requestee=".$id);
      $stmt->execute();
      $in_checkin = $stmt->fetchAll();

      $requested = count($in_checkin) > 0;

      $result["requested"] = $requested;
      array_push($res_data, $result);
    }

    return array("arr" => $res_data);
  }

  public static function add_transaction($user_id, $book_id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("INSERT into transaction (book, requestee) VALUES (".$book_id.", ".$user_id.")");
    $stmt->execute();

    $stmt = $db->prepare("SELECT available from book where id=".$book_id);
    $stmt->execute();
    $book_available_data = $stmt->fetch();
    $available = (string)(((int)$book_available_data['available']) - 1); 

    $stmt = $db->prepare("UPDATE book set available=".$available." where id=".$book_id);
    $stmt->execute();

  }

  public static function add_checkin($user_id, $book_id, $issue_id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("INSERT into checkin (book, requestee, issue_id) VALUES (".$book_id.", ".$user_id.", ".$issue_id.")");
    $stmt->execute();
  }
    
}

