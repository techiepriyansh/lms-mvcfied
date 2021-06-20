<?php

namespace Controller;

class BooksData {
  public function get() {
    session_start();

    if (!isset($_SESSION['admin'])) {
      echo 'Access denied';
      return;
    }

    header('Content-Type: application/json');
    echo json_encode(array(
      "arr" => \Model\Book::get_books()
    ));
  }
      
}
