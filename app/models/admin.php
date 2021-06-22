<?php

namespace Model;

class Admin {
  public static function get_admin($email) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from admin where email=?");
    $stmt->execute(array($email));
    $result = $stmt->fetch();

    return $result;
  }

  public static function get_pending_requests() {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT email, name from user where active=false");
    $stmt->execute();
    $results = $stmt->fetchAll();

    return $results;
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
    $sql = "SELECT a.id as transaction_id, b.id as user_id, b.name as user_name, b.email as user_email, c.title as book_title, c.id as book_id from transaction a, user b, book c where a.requestee = b.id and a.book = c.id";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();

    return $results;
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
    $sql = "SELECT a.id as checkin_id, b.id as user_id, b.name as user_name, b.email as user_email, c.title as book_title, c.id as book_id from checkin a, user b, book c where a.requestee = b.id and a.book = c.id";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();

    return $results;
  }

  public static function approve_checkin_request($id, $requestee, $book, $timestamp) {
    $db = \DB::get_instance();
    $sql = "SELECT a.issue_id, b.time_issued from checkin a, currently_issued b where a.id = ? and b.id = a.issue_id";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch();

    \Model\Book::remove_from_currently_issued($result["issue_id"]);
    \Model\Book::remove_from_checkin($id);
    \Model\Book::insert_into_history($requestee, $book, $result["time_issued"], $timestamp);
    \Model\Book::update_book_available($book, 1);
  }

  public static function reject_checkin_request($id) {
    \Model\Book::remove_from_checkin($id);
  }
    
}

