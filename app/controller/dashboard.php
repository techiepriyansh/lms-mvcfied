<?php

namespace Controller;

class Dashboard {
  public function get() {
    session_start();

    if (!isset($_SESSION['admin'])) {
      header('Location: /admin-login');
    }
    else {
      echo \View\Loader::make()->render(
        "templates/admin/dashboard.twig"
      );
    }
  }
  
}
