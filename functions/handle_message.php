<?php
function handle_message($botdata){
    $chat = $botdata["chat"];
    $chat_id = $chat["id"];
    f("bot_kirim_perintah")("sendMessage",[
        "chat_id"=>$chat_id,
        "text"=>"Halo.. :D",
    ]);
    sleep(2);
    f("bot_kirim_perintah")("sendMessage",[
        "chat_id"=>$chat_id,
        "text"=>"Hehe :D",
    ],);
}