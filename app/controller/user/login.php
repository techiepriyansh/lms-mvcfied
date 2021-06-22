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

    $result = \Model\User::get_user($email);
    if ($result) {
      if (password_verify($pass, $result["pass"])) {
        session_start();
        $_SESSION['id'] = $result['id'];
        header('Location: /');
        return;
      }  
    }
    
    echo 'Access denied';
  }
  
}
