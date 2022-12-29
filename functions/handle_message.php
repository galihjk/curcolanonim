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
                    "text"=>"Kirim curhatan kamu ke sini, nanti bakal masuk ke $channel secara anonim.\n\n"
                    ."<b>FORMAT</b>:\n#(jenis)(spasi)(curhatan kamu)\n\ncontoh:\n"
                    ."#random Jadi waktu itu temen aku tuh kan lagi jalan sebelahan ma aku, terus tiba-tiba.....\n\n"
                    ."jenisnya bebas, yg penting diawali tanda pagar (#).. Selamat ber-curhat yaa :D\n\n",
                    "parse_mode"=>"HTML"
                ]);
            }
            elseif(f("str_is_diawali")($text, "/start bls_")){
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>$text."OK",
                    "parse_mode"=>"HTML"
                ]);
            }
            elseif(f("str_is_diawali")($text, "#")){
                $channelpost = f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$channel,
                    "text"=>"loading...",
                ]);
                if($channelpost["result"]["message_id"]){
                    $msgid = $channelpost["result"]["message_id"];
                    $channelpost = f("bot_kirim_perintah")("editMessageText",[
                        "chat_id"=>$channel,
                        "message_id"=>$msgid,
                        "text"=>$text."\n\n<a href='t.me/$botname?start=bls_$msgid'>balas</a>",
                        "parse_mode"=>"HTML",
                        "disable_web_page_preview"=>true,
                    ]);
                    $send_text = "<a href='"
                    .str_replace("@","https://t.me/",$channel) . "/$msgid"
                    ."' >lihat</a>";
                }
                else{
                    $send_text = "maaf ERROR";
                }
                
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>$send_text,
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