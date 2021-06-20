<?php

namespace Controller;

class Login {
  public function get() {
    session_start();

    if (isset($_SESSION['id'])) {
      header('Location: /');
    }
    else {
      echo \View\Loader::make()->render(
          "templates/user/login.twig"
      );
    }
  }

  public function post() {
    $email = $_POST["email"];
    $pass = $_POST["pass"];

    $user_info = \Model\User::get_user($email, $pass);
    if ($user_info) {
      session_start();
      $_SESSION['id'] = $user_info['id'];
      header('Location: /');
    }
    else {
      echo 'Access Denied';
    }
  }
  
}
