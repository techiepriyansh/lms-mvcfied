<?php

require __DIR__."/../vendor/autoload.php";

Toro::serve(array(
  "/" => "\Controller\Home",
  "/admin" => "\Controller\Dashboard",
  "/login" => "\Controller\Login",
  "/user-book-library" => "Controller\UserBookLibrary",
  "/user-books-data" => "Controller\UserBooksData",
  "/register" => "\Controller\Register",
  "/request-checkout" => "\Controller\Checkout",
  "/request-checkin" => "\Controller\Checkin"
));

