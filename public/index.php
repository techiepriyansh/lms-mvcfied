<?php

require __DIR__."/../vendor/autoload.php";

Toro::serve(array(
  "/" => "\Controller\Home",
  "/login" => "\Controller\Login",
  "/logout" => "\Controller\Logout",
  "/user-book-library" => "Controller\UserBookLibrary",
  "/user-books-data" => "Controller\UserBooksData",
  "/register" => "\Controller\Register",
  "/request-checkout" => "\Controller\Checkout",
  "/request-checkin" => "\Controller\Checkin",
  "/admin" => "\Controller\Dashboard",
  "/admin-login" => "\Controller\AdminLogin",
  "/books-data" => "\Controller\BooksData",
  "/add-book" => "\Controller\AddBook",
  "/edit-book-data" => "Controller\EditBookData",
  "/pending-requests" => "\Controller\PendingRequests",
  "/pending-checkouts" => "\Controller\PendingCheckouts",
  "/pending-checkins" => "\Controller\PendingCheckins",
));

