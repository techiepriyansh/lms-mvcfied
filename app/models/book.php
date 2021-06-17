<?php

namespace Model;

class Book {
  public static function get_book_by_id($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from book where id=".$id);
    $stmt->execute();
    $result = $stmt->fetch();

    return $result;
  }

  public static function get_books() {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from book");
    $stmt->execute();
    $results = $stmt->fetchAll();

    $res_data = array();
    foreach($results as $result) {
      array_push($res_data, $result);
    }

    return array("arr" => $res_data);
  }

  public static function edit_book($id, $title, $author, $publisher, $pages, $total, $available, $info) {
    $db = \DB::get_instance();
    $stmt = $db->prepare(
      " update book "

      ."set title = ?, "
      ."author = ?, "
      ."publisher = ?, "
      ."pages = ".$pages.", "
      ."total = ".$total.", "
      ."available = ".$available.", "
      ."info = ? "

      ."where id = ".$id
    );
    $stmt->execute(array($title, $author, $publisher, $info));
  }

  public static function add_book($title, $author, $publisher, $pages, $total, $available, $info) {
    $db = \DB::get_instance();
    $stmt = $db->prepare(
      "insert into book (title, author, publisher, pages, total, available, info) values ( ".
      "?, ?, ?, ".$pages.", ".$total.", ".$available.", ?)"
    );
    $stmt->execute(array($title, $author, $publisher, $info));
  }

  public static function get_user_library($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from book where available > 0");
    $stmt->execute();
    $results = $stmt->fetchAll();

    $res_data = array();
    foreach($results as $result) {
      $stmt = $db->prepare("SELECT * from currently_issued where book=".$result["id"]." and bearer=".$id);
      $stmt->execute();
      $in_currently_issued = $stmt->fetch();
      if ($in_currently_issued) continue;

      $stmt = $db->prepare("SELECT * from transaction where book=".$result["id"]." and requestee=".$id);
      $stmt->execute();
      $in_transaction = $stmt->fetch();
      $requested = isset($in_transaction["id"]);

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
      $book_data = self::get_book_by_id($result["book"]);

      $stmt = $db->prepare("SELECT * from checkin where book=".$result["id"]." and requestee=".$id);
      $stmt->execute();
      $in_checkin = $stmt->fetchAll();

      $requested = isset($in_checkin["id"]);

      $book_data["requested"] = $requested;
      $book_data["issueId"] = $result["id"];
      $book_data["timeIssued"] = $result["time_issued"];

      unset($book_data["total"]);
      unset($book_data["available"]);

      array_push($res_data, $book_data);
    }

    return array("arr" => $res_data);
  }
  
  public static function update_book_available($id, $inc) {
    $book_available_data = self::get_book_by_id($id);
    $available = (string)(((int)$book_available_data['available']) + $inc);

    $db = \DB::get_instance();
    $stmt = $db->prepare("UPDATE book set available=".$available." where id=".$id);
    $stmt->execute(); 
  }

  public static function add_transaction($user_id, $book_id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("INSERT into transaction (book, requestee) VALUES (".$book_id.", ".$user_id.")");
    $stmt->execute();

    self::update_book_available($book_id, -1);
  }

  public static function add_checkin($user_id, $book_id, $issue_id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("INSERT into checkin (book, requestee, issue_id) VALUES (".$book_id.", ".$user_id.", ".$issue_id.")");
    $stmt->execute();
  }

  public static function get_checkin_by_id($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from checkin where id=".$id);
    $stmt->execute();
    $result = $stmt->fetch();

    return $result;
  }

  public static function add_currently_issued($requestee, $book, $timestamp) {
    $db = \DB::get_instance();
    $stmt = $db->prepare(" INSERT into currently_issued (bearer, book, time_issued) values ( ".$requestee.", ".$book.", ".$timestamp.")");
    $stmt->execute();
  }

  public static function get_currently_issued_by_id($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from currently_issued where id=".$id);
    $stmt->execute();
    $result = $stmt->fetch();

    return $result;
  }

  public static function remove_from_transaction($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("DELETE from transaction where id=".$id);
    $stmt->execute();
  }

  public static function remove_from_currently_issued($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("DELETE from currently_issued where id=".$id);
    $stmt->execute();
  }

  public static function remove_from_checkin($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("DELETE from checkin where id=".$id);
    $stmt->execute();
  }

  public static function insert_into_history($requestee, $book, $time_issued, $time_returned) {
    $db = \DB::get_instance();
    $stmt = $db->prepare(
      "INSERT into history (bearer, book, time_issued, time_returned) values ( "
        .$requestee.", ".$book.", ".$time_issued.", ".$time_returned.
      ")"
    );
    $stmt->execute();
  }
    
}

