<?php
function handle_message($botdata){
    $chat = $botdata["chat"];
    $chat_id = $chat["id"];
    if(f("is_private")($botdata)){
        //
        if(f("cek_sudah_subscribe")($chat_id)){
            //
        }
    }
    else{
        f("bot_kirim_perintah")("sendMessage",[
            "chat_id"=>$chat_id,
            "text"=>"yuk, ke sini ==> @curcolanonim",
        ]);
    }
}