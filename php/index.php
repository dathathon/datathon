<?php
include "processdata.php";
$query_string = $_SERVER['QUERY_STRING'];
$params = explode("&", $query_string);
$filters = [];
$category = $_GET['filter'];
$process = new processdata();
$response = new stdClass();
$response->status = "success";
if (isset($category)) {
  $categories = $process->getcategory();
  $response->data = $categories;
} else {
  if (!empty($query_string)) {
    foreach ($params as $value) {
      $filter = explode('=', $value);
      $filters[$filter[0]] = $filter[1];
    }
    $data = $process->processfilters($filters);
    $response->data = $data;
  } else {
    $response->data = [];
  }
}
header('Content-Type: application/json');
echo json_encode($response);

?>