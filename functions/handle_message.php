<?php
function handle_message($botdata){
    $chat = $botdata["chat"];
    $chat_id = $chat["id"];
    f("bot_kirim_perintah")("sendMessage",[
        "chat_id"=>$chat_id,
        "text"=>"Halo..",
    ],
    "5621496544:AAHmKUHPavGnoifMZ0gfMYqGnOJaA51fofw");
    sleep(2);
    f("bot_kirim_perintah")("sendMessage",[
        "chat_id"=>$chat_id,
        "text"=>"Hehe",
    ],
    "5621496544:AAHmKUHPavGnoifMZ0gfMYqGnOJaA51fofw");
}