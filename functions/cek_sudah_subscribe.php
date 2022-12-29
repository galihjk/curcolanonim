<?php
function cek_sudah_subscribe($userid){
    $chatmember = f("bot_kirim_perintah")("getChatMember",[
        'chat_id'=>f("get_config")("channel"),
        'user_id'=>$userid,
    ]);
    f("bot_kirim_perintah")("sendMessage",[
        "chat_id"=>$userid,
        "text"=>"nih:".print_r($chatmember,true),
    ]);
}