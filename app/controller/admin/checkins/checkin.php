<?php

namespace Controller;

class Checkin {
  public function post() {
    session_start();
    $_POST = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['id'])) {
      echo 'Access denied';
      return;
    }

    \Model\Book::add_checkin($_SESSION['id'], $_POST['book'], $_POST['issueId']);

    header('Content-Type: application/json');
    echo json_encode(array("success" => true));
  }
  
}
