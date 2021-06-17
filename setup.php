<?php

function createDatabase() {
  include "./config/config.php";

  echo "creating a new database ".$DB_NAME."\n";

  $db = new PDO(
    "mysql:host=".$DB_HOST.";port=".$DB_PORT,
    $DB_USERNAME,
    $DB_PASSWORD
  );
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "CREATE DATABASE IF NOT EXISTS ".$DB_NAME;
  $db->exec($sql);

  $db->exec("use ".$DB_NAME);
  return $db;
}

function createTables($db) {
  echo "creating tables\n";

  $sql = file_get_contents("./schema/schema.sql");
  $db->exec($sql);
}

function addAdmin($db, $email, $pass) {
  echo "adding admin credentials\n";

  $stmt = $db->prepare("INSERT into admin (email, pass) values (?, ?)");
  $stmt->execute(array($email, password_hash($pass, PASSWORD_DEFAULT)));
}

function addSampleBooks($db) {
  echo "adding sample books\n";

  $obj = json_decode(file_get_contents("./books.json"), true);
  // print_r($obj);

  $num = "60";

  foreach($obj["books"] as $book) {
    $stmt = $db->prepare(
      "insert into book (title, author, publisher, pages, total, available, info) values ( ".
      "?, ?, ?, ".(string)$book["pages"].", ".$num.", ".$num.", ?)"
    );

    $stmt->execute(array($book["title"], $book["author"], $book["publisher"], $book["description"]));
  }
}

function init() {
  $email = getenv("lms_admin_email");
  $pass = getenv("lms_admin_pass");

  if (!$email) {
    $email = "admin@lib.com";
  }

  if(!$pass) {
    $pass = "admin";
  }

  $db = createDatabase();
  createTables($db);
  addAdmin($db, $email, $pass);
  addSampleBooks($db);
}

init();

