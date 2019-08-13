<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$l = json_decode(file_get_contents("php://input"), true)["links"];
exec("echo '$l' | ./gfonts 2>&1", $output, $status);

$o = [];
$o["output"] = implode("<br>", $output);
$o["status"] = $status;

echo json_encode($o);

?>