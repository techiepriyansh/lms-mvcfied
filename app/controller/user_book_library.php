<?php

namespace Controller;

class UserBookLibrary {
  public function get() {
    session_start();

    if (!isset($_SESSION['id'])) {
      echo 'Access denied';
      return;
    }

    header('Content-Type: application/json');
    echo json_encode(\Model\Book::get_user_library($_SESSION['id']));
  }
      
}
