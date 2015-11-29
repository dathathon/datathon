<?php
include "mysqlconnect.php";
class processdata {
  private $filtercomponents;
  private $connection;
  private $mysql;
  private $heatmap;
  private $chart;
  private $country;
  private $tables;
  private $filters;

  public function __construct() {
    $this->connection = mysql::connect("localhost", "datathon", "root", "123");
    $this->tables = [];
    $this->country = 'USA';
  }

  public function processfilters($filters) {
    $this->populatetables($filters);
    $this->filters = ["category" => "AppSiteCategory", "time" => "Timestamp"];
    $heatmap = $this->getheatmap();
    $topwins = $this->topwins();
    $topapps = $this->topappswins();
    $topwinsclicks = $this->topclicks();
    $topappsclicks = $this->topappsclicks();
    $data = new stdClass();
    $data->heatmap = $heatmap;
    $data->topwins = $topwins;
    $data->topapps = $topapps;
    $data->topwinsclicks = $topwinsclicks;
    $data->topappsclicks = $topappsclicks;
    return $data;
  }

  private function getheatmap() {
    $data = [];
    foreach ($this->tables as $value) {
      $query = "select * from $value";
      $data = array_merge(mysql::getdata($this->connection, $query), $data);
    }
    return $data;
  }

  private function topwins() {
    $data = [];
    $campaigns = Array();
    foreach ($this->tables as $value) {
      $query = "select CampaignId,count(Outcome) as wins from $value where Outcome='w' group by CampaignId order by wins desc limit 10";
      $data = array_merge(mysql::getdata($this->connection, $query), $data);
    }
    foreach ($data as $key => $value) {
      if (isset($campaigns[$value['CampaignId']])) {
        $campaigns[$value['CampaignId']] = $campaigns[$value['CampaignId']] + $value['wins'];
      } else {
        $campaigns[$value['CampaignId']] = $value['wins'] + 0;
      }
    }
    $campaigns = array_slice($campaigns, 0, 9, true);
    return $campaigns;
  }

  private function topappswins() {
    $data = [];
    $campaigns = [];
    foreach ($this->tables as $value) {
      $query = "select AppSiteId,count(Outcome) as wins from $value where Outcome='w' group by AppSiteId order by wins desc limit 10";
      $data[] = mysql::getdata($this->connection, $query);
    }
    foreach ($data as $key => $value) {
      if (isset($campaigns[$value['AppSiteId']])) {
        $campaigns[$value['AppSiteId']] = $campaigns[$value['AppSiteId']] + $value['wins'];
      } else {
        $campaigns[$value['AppSiteId']] = $value['wins'] + 0;
      }
    }
    arsort($campaigns);
    $campaigns = array_slice($campaigns, 0, 9);
    return $campaigns;
  }

  private function topclicks() {
    $data = [];
    $campaigns = [];
    foreach ($this->tables as $value) {
      $query = "select CampaignId,count(Outcome) as wins from $value where Outcome='c' group by CampaignId order by wins desc limit 10";
      $data[] = mysql::getdata($this->connection, $query);
    }
    foreach ($data as $key => $value) {
      if (isset($campaigns[$value['CampaignId']])) {
        $campaigns[$value['CampaignId']] = $campaigns[$value['CampaignId']] + $value['wins'];
      } else {
        $campaigns[$value['CampaignId']] = $value['wins'] + 0;
      }
    }
    arsort($campaigns);
    $campaigns = array_slice($campaigns, 0, 9);
    return $campaigns;
  }

  private function topappsclicks() {
    $data = [];
    $campaigns = [];
    foreach ($this->tables as $value) {
      $query = "select AppSiteId,count(Outcome) as wins from $value where Outcome='c' group by AppSiteId order by wins desc limit 10";
      $data[] = mysql::getdata($this->connection, $query);
    }
    foreach ($data as $key => $value) {
      if (isset($campaigns[$value['AppSiteId']])) {
        $campaigns[$value['AppSiteId']] = $campaigns[$value['AppSiteId']] + $value['wins'];
      } else {
        $campaigns[$value['AppSiteId']] = $value['wins'] + 0;
      }
    }
    arsort($campaigns);
    $campaigns = array_slice($campaigns, 0, 9);
    return $campaigns;
  }

  private function populatetables($filters) {
    if (count($filters) == 1) {
      $this->tables = explode(",", array_values($filters));
    } else if (empty($filters)) {
      $this->tables[0] = 'usa';
    } else {
      $count = 0;
      foreach ($filters as $key => $value) {
        $temp[$count++] = explode(",", $value);
      }
      if (is_string($temp[0][0])) {
        $key = 0;
      } else {
        $key = 1;
      }
      $other_key = !$key;
      foreach ($temp[$key] as $value) {
        $value = substr($value, 0, 20);
        foreach ($temp[$other_key] as $sub_value) {
          $this->tables[] = $value . "_" . $sub_value;
        }
      }
    }
  }
  public function getCategory() {
    $query = "select DISTINCT(AppSiteCategory) from usa ";
    $result = mysql::getdata($this->connection, $query);
    foreach ($result as $key => $value) {
      $category[] = $value['AppSiteCategory'];
    }
    return $category;
  }
}
?>