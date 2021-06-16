<?php

namespace Controller;

class Checkout {
  public function post() {
    session_start();
    $_POST = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['id'])) {
      header('Content-Type: application/json');
      echo json_encode(array("msg" => "error"));
      return;
    }

    \Model\Book::add_transaction($_SESSION['id'], $_POST['book']);

    header('Content-Type: application/json');
    echo json_encode(array("success" => true));
  }
  
}
