<?php

require_once "Fidelity.php";

$datas = file("resultats_users.csv");
array_shift($datas);

$user_fidelity = Fidelity::create("123456789");
foreach ($datas as $line) {
  list($user, $p1, $p2, $p3, $p4, $date) = explode(";", $line);
  $date = str_replace('/', '-', $date);
  if ($user != $user_fidelity->get_username()) {
    continue;
  }
  $user_fidelity->add_points([
    1 => $p1,
    2 => $p2,
    3 => $p3,
    4 => $p4
  ], $date);
}

echo "<html>";
echo "<head><link rel=\"stylesheet\" href=\"style.css\"></head>";
echo "<body><table>";
echo "<tr><td>PERIODE</td><td>POINTS</td><td>EUROS</td></tr>";
foreach ($user_fidelity->get_points() as $periode_name => $values) {
  echo "<tr>";
  echo "<td>$periode_name</td><td>{$values['points']}</td><td>{$values['euros']}</td>";
  echo "</tr>";
}
echo "</table></body>";
echo "</html>";
