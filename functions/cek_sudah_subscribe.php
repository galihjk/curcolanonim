<?php
function cek_sudah_subscribe($userid){
    $channel = f("get_config")("channel");
    $chatmember = f("bot_kirim_perintah")("getChatMember",[
        'chat_id'=>$channel,
        'user_id'=>$userid,
    ]);
    $status = $chatmember["result"]["status"];
    if(in_array($status,["restricted","left","kicked"])){
        f("bot_kirim_perintah")("sendMessage",[
            "chat_id"=>$userid,
            "text"=>"Join $channel dulu yuk! Abis itu ke sini lagi.. :D \n/start",
        ]);
        return false;
    }
    return true;
}