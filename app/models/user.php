<?php

namespace Model;

class User {
  public static function get_user($email, $pass) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from user where email=? and active=true");
    $stmt->execute(array($email));
    $result = $stmt->fetch();
    if ($result) {
      if (password_verify($pass, $result["pass"])) {
        return $result;
      }
    }

    return NULL;
  }

  public static function does_user_exist($name, $email, $pass) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("SELECT * from user where email=?");
    $stmt->execute(array($email));
    $results = $stmt->fetchAll();

    return count($results) > 0;
  }

  public static function add_pending_user($name, $email, $pass) {
    $db = \DB::get_instance();
    $stmt = $db->prepare("INSERT into user (email, name, pass, active) values (?, ?, ?, false)");
    $stmt->execute(array($email, $name, $pass));
  }
    
}

