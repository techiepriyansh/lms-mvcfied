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

    $is_admin = \Model\Admin::get_admin($email, $pass);
    if ($is_admin) {
      session_start();
      $_SESSION['admin'] = 'true';
      header('Location: /admin');
    }
    else {
      echo 'Access Denied';
    }
  }
  
}
