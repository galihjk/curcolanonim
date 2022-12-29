<?php
function handle_message($botdata){
    $channel = f("get_config")("channel");
    $chat = $botdata["chat"];
    $chat_id = $chat["id"];
    if(f("is_private")($botdata)){
        if(f("cek_sudah_subscribe")($chat_id)){
            $text = $botdata["text"] ?? "";
            if($text == "/start"){
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>"Kirim curhatan kamu ke sini, nanti bakal ke $channel (gak akan ketahuan bahwa kamu pengirimnya).\n"
                    ."<b>FORMAT</b>:\n\n#(jenis)(spasi)(curhatan kamu)\n\ncontoh:\n"
                    ."#random Jadi waktu itu temen aku tuh kan lagi jalan sebelahan ma aku, terus tiba-tiba.....\n\n"
                    ."jenisnya bebas, yg penting diawali tanda pagar (#).. Selamat ber-curhat yaa :D\n\n",
                    "parse_mode"=>"HTML"
                ]);
            }
            elseif(f("str_is_diawali")($text, "#")){
                $channelpost = f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$channel,
                    "text"=>$text,
                    "parse_mode"=>"HTML",
                    "disable_web_page_preview"=>true,
                ]);
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>"nih:".print_r($channelpost,true),
                    "parse_mode"=>"HTML"
                ]);
            }
        }
    }
    else{
        f("bot_kirim_perintah")("sendMessage",[
            "chat_id"=>$chat_id,
            "text"=>"yuk, ke sini ==> $channel",
        ]);
    }
}