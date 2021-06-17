<?php

namespace Controller;

class EditBookData {
  public function post() {
    session_start();
    $_POST = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['admin'])) {
      echo 'Access denied';
      return;
    }

    \Model\Book::edit_book(
      $_POST['id'], 
      $_POST['title'], 
      $_POST['author'],
      $_POST['publisher'],
      $_POST['pages'],
      $_POST['total'],
      $_POST['available'],
      $_POST['info']
    );

    header('Content-Type: application/json');
    echo json_encode(array("success" => true));
  }
      
}
