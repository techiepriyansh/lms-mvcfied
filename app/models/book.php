<?php

namespace Model;

class Book {
  public static function get_book_by_id($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from book where id=?");
    $stmt->execute(array((int)$id));
    $result = $stmt->fetch();

    return $result;
  }

  public static function get_books() {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from book");
    $stmt->execute();
    $results = $stmt->fetchAll();

    return $results;
  }

  public static function edit_book($id, $title, $author, $publisher, $pages, $total, $available, $info) {
    $db = \DB::get_instance();
    $stmt = $db->prepare(
      "
      update book
      set title = ?,
      author = ?,
      publisher = ?,
      pages = ?,
      total = ?,
      available = ?,
      info = ?

      where id = ?
      "
    );
    $stmt->execute(array($title, $author, $publisher, $pages, $total, $available, $info, $id));
  }

  public static function add_book($title, $author, $publisher, $pages, $total, $available, $info) {
    $db = \DB::get_instance();
    $stmt = $db->prepare(
      "insert into book (title, author, publisher, pages, total, available, info) values ( ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute(array($title, $author, $publisher, $pages, $total, $available, $info));
  }

  public static function get_user_library($id) {
    $db = \DB::get_instance();
    $sql = "SELECT a.*, exists(SELECT * from transaction b where b.book = a.id and b.requestee = ?) as requested from book a where not exists (SELECT * from currently_issued b where b.book = a.id and b.bearer = ?) and available > 0";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id, $id]);
    $results = $stmt->fetchAll();

    return $results;
  }

  public static function get_issued_books($id) {
    $db = \DB::get_instance();
    $sql = "SELECT a.*, b.id as issueId, b.time_issued as timeIssued, exists(SELECT * from checkin c where c.book = a.id and c.requestee = ?) as requested from book a, currently_issued b where a.id = b.book and b.bearer = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id, $id]);
    $results = $stmt->fetchAll();

    return $results;
  }
  
  public static function update_book_available($id, $inc) {
    $book_available_data = self::get_book_by_id($id);
    $available = (string)(((int)$book_available_data['available']) + $inc);

    $db = \DB::get_instance();
    $stmt = $db->prepare("UPDATE book set available=? where id=?");
    $stmt->execute([$available, $id]); 
  }

  public static function add_transaction($user_id, $book_id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("INSERT into transaction (book, requestee) VALUES (?, ?)");
    $stmt->execute([$book_id, $user_id]);

    self::update_book_available($book_id, -1);
  }

  public static function add_checkin($user_id, $book_id, $issue_id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("INSERT into checkin (book, requestee, issue_id) VALUES (?, ?, ?)");
    $stmt->execute([$book_id, $user_id, $issue_id]);
  }

  public static function add_currently_issued($requestee, $book, $timestamp) {
    $db = \DB::get_instance();
    $stmt = $db->prepare(" INSERT into currently_issued (bearer, book, time_issued) values (?, ?, ?)");
    $stmt->execute([$requestee, $book, $timestamp]);
  }

  public static function remove_from_transaction($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("DELETE from transaction where id=?");
    $stmt->execute([$id]);
  }

  public static function remove_from_currently_issued($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("DELETE from currently_issued where id=?");
    $stmt->execute([$id]);
  }

  public static function remove_from_checkin($id) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("DELETE from checkin where id=?");
    $stmt->execute([$id]);
  }

  public static function insert_into_history($requestee, $book, $time_issued, $time_returned) {
    $db = \DB::get_instance();
    $stmt = $db->prepare( "INSERT into history (bearer, book, time_issued, time_returned) values (?, ?, ?, ?)");
    $stmt->execute([$requestee, $book, $time_issued, $time_returned]);
  }
    
}

