<?php

namespace Controller;

class PendingCheckouts {
  public function get() {
    session_start();

    if (!isset($_SESSION['admin'])) {
      echo 'Access denied';
      return;
    }

    $results = \Model\Admin::get_pending_checkouts();
    $res_data = array();
    foreach($results as $result) {
      $obj = array(
        "id" => $result["transaction_id"],

        "user" => array(
          "id" => $result["user_id"],
          "name" => $result["user_name"],
          "email" => $result["user_email"]
        ),

        "book" => array(
          "title" => $result["book_title"],
          "id" => $result["book_id"]
        )
      );

      array_push($res_data, $obj);
    }

    header('Content-Type: application/json');
    echo json_encode(array(
      "arr" => $res_data
    ));
  }

  public function post() {
    session_start();
    $_POST = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['admin'])) {
      echo 'Access denied';
      return;
    }

    if (isset($_POST['approve'])) {
      \Model\Admin::approve_checkout_request($_POST['id'], $_POST['requestee'], $_POST['book'], time()*1000);
    }
    else if (isset($_POST['reject'])) {
      \Model\Admin::reject_checkout_request($_POST['id'], $_POST['book']);
    }
    else {
      header('Content-Type: application/json');
      echo json_encode(array("msg" => "flag (approve/reject) not set"));
      return;
    }

    header('Content-Type: application/json');
    echo json_encode(array("success" => true));
  }
  
}
