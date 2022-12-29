<?php
if(empty($global_config)) $global_config = include("config.php");

function get_config($key){
    global $global_config;
    return $global_config[$key];
}