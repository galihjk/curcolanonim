<?php
include("init.php");

$jenis_update = [
    "message"
];

f("handle_update_sesuai_jenis")($jenis_update);

file_put_contents(date("Y-m-d H:i:s").".txt",file_get_contents("php://input"));