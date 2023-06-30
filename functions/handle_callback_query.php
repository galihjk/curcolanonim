<?php
function handle_callback_query($botdata){
    if(!f("handle_botdata_functions")($botdata,[
        "handle_callback_query_konfirmkirim",
    ])){
        // file_put_contents("log/unhandledcallback_query_".date("Y-m-d-H-i").".txt", file_get_contents("php://input"));
        file_put_contents("log/unhandledcallback_query_LAST.txt", file_get_contents("php://input"));
    };
}