<?php
class mysql {
  static function connect($host, $database, $username, $password) {
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
  }

  static function closeconnection($conn) {
    $conn->close();
  }

  static function getdata($conn, $query) {
    $result = $conn->query($query);
    if ($result == false) {
      return -1;
    } else {
      $data = [];
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $data[] = $row;
        }
      }
      return $data;
    }
  }
}
?>