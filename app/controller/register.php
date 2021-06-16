<?php

namespace Controller;

class Register {
  public function get() {
    session_start();

    if (isset($_SESSION['id'])) {
      header('Location: /');
    }
    else {
      echo \View\Loader::make()->render(
          "templates/user/register.twig"
      );
    }
  }

  public function post() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    
    $name = $_POST["name"];
    $email = $_POST["email"];
    $pass = $_POST["pass"];

    $user_already_exists = \Model\User::does_user_exist($name, $email, $pass);
    if ($user_already_exists) {
      header('Content-Type: application/json');
      echo json_encode(array("repeat" => true));
    }
    else {
      \Model\User::add_pending_user($name, $email, password_hash($pass, PASSWORD_DEFAULT));
      header('Content-Type: application/json');
      echo json_encode(array("success" => true));
    }
  }
  
}
