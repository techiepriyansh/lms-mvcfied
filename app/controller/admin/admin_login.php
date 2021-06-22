<?php

namespace Controller;

class AdminLogin {
  public function get() {
    session_start();

    if (isset($_SESSION['admin'])) {
      header('Location: /admin');
    }
    else {
      echo \View\Loader::make()->render(
          "templates/admin/login.twig"
      );
    }
  }

  public function post() {
    $email = $_POST["email"];
    $pass = $_POST["pass"];

    $result = \Model\Admin::get_admin($email);
    if ($result) {
      if (password_verify($pass, $result["pass"])) {
        session_start();
        $_SESSION['admin'] = 'true';
        header('Location: /admin');
        return;
      }  
    }

    echo 'Access denied';
  }
  
}
