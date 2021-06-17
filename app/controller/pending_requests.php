<?php

namespace Controller;

class PendingRequests {
  public function get() {
    session_start();

    if (!isset($_SESSION['admin'])) {
      echo 'Access denied';
      return;
    }

    header('Content-Type: application/json');
    echo json_encode(\Model\Admin::get_pending_requests());
  }

  public function post() {
    session_start();
    $_POST = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['admin'])) {
      echo 'Access denied';
      return;
    }

    if (isset($_POST['approve'])) {
      \Model\Admin::approve_registration_request($_POST['name'], $_POST['email']);
    }
    else if (isset($_POST['reject'])) {
      \Model\Admin::reject_registration_request($_POST['name'], $_POST['email']);
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
