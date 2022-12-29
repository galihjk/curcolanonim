<?php
function handle_message($botdata){
    $chat = $botdata["chat"];
    $chat_id = $chat["id"];
    if(f("is_private")($botdata)){
        //
        f("bot_kirim_perintah")("sendMessage",[
            "chat_id"=>$chat_id,
            "text"=>"ini private",
        ]);
    }
    else{
        f("bot_kirim_perintah")("sendMessage",[
            "chat_id"=>$chat_id,
            "text"=>"ini group",
        ]);
    }
}