# lms-mvcfied
Library Management System built with MVC architecture

## Setup
* Clone the repository
* Start a mysql server
* Create `config.php` file in the `config` directory and populate it with the required values (refer to `config/sample.config.php`)
* In the project root directory, execute `./setup.sh <admin_email> <admin_pass>` to create and initialize the database

## Run 
* In the `public` directory, execute `php -S localhost:<PORT>` to start the server
* Access the admin dashboard at `localhost:<PORT>/admin` and login using the credentials provided while initializing the database
