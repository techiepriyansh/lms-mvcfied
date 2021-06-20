<?php

namespace Controller;

class Home {
  public function get() {
    session_start();

    if (!isset($_SESSION['id'])) {
      header('Location: /login');
    }
    else {
      echo \View\Loader::make()->render(
        "templates/user/home.twig"
      );
    }
  }
  
}
