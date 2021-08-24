<?php

class Period {
  private $start = 0;
  private $end = 0;

  function __construct($start, $end) {
    $this->start = strtotime($start);
    $this->end = strtotime($end);
  }

  function is_in_period($date) {
    $date = strtotime($date);
    if (($date >= $this->start) && ($date <= $this->end)) {
      return true;
    }
    return false;
  }
}

class Fidelity {
  const PRODUCTS_VALUES = [
    1 => 5,
    2 => 5,
    3 => 15,
    4 => 35
  ];
  const DATES = [
    "periode1" => ["01-01-2021", "30-04-2021"],
    "periode2" => ["01-05-2021", "31-08-2021"],
    "periode3" => ["01-10-2021", "31-12-2021"]
  ];
  const EUROS_PER_PTS = 0.001;

  private $username;
  private $pts_per_dates;

  private function __construct($username) {
    $this->username = $username;
    foreach (self::DATES as $periode => $_) {
      $this->pts_per_dates[$periode] = [
        "points" => 0,
        "euros" => 0,
      ];
    }
  }

  static function create($username) {
    return new self($username);
  }

  function get_points() {
    return $this->pts_per_dates;
  }

  function get_username() {
    return $this->username;
  }

  # products indexed by there id, starting at 1 (shame)
  function add_points(array $products, $date){
    for ($i = 1; $i < 5; $i++) {
      if ($products[$i] < 0) {
        throw new Exception("Invalid number of products");
      }
    }

    $period_name = NULL;
    foreach (self::DATES as $name => [$start, $end]) {
      $period = new Period($start, $end);
      if ($period->is_in_period($date)) {
        $period_name = $name;
        break;
      }
      unset($period);
    }

    if ($period_name == NULL) {
      # out of range period, 0 pts earned
      return;
    }

    # Setup pts
    $products[2] = ($products[1] > 0) ? $products[2] : 0;
    $products[3] = (int)round($products[3] / 2, 0, PHP_ROUND_HALF_DOWN);

    # Calculate pts according to previous setup
    $pts = 0;
    foreach (self::PRODUCTS_VALUES as $p => $val) {
      $pts += $products[$p] * $val;
    }
    $this->pts_per_dates[$period_name]["points"] += $pts;
    $this->pts_per_dates[$period_name]["euros"] += $pts * self::EUROS_PER_PTS;
  }
}
