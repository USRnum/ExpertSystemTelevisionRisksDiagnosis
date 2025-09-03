<?php

class koneksi
{

  private $_host = 'localhost';
  private $_username = 'root';
  private $_password = '';
  private $_database = 'kerusakan_televisi';

  public $conn;

  public function __construct()
  {
    $this->conn = new mysqli($this->_host, $this->_username, $this->_password, $this->_database);

    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
  }
}

$database = new koneksi();
$conn = $database->conn;
?>