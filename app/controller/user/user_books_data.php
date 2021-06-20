<?php

namespace Controller;

class UserBooksData {
  public function get() {
    session_start();

    if (!isset($_SESSION['id'])) {
      echo 'Access denied';
      return;
    }

    header('Content-Type: application/json');
    echo json_encode(array(
      "arr" => \Model\Book::get_issued_books($_SESSION['id'])
    ));
  }
      
}
