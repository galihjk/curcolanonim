<?php
function handle_message($botdata){
    $channel = f("get_config")("channel");
    $botuname = f("get_config")("botuname");
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
                $text_isi = str_ireplace("/start bls_","",$text);
                $explode = explode("|",$text_isi);
                $msgid = $explode[0];

                $text = "Mau balas apa<a href='"
                .str_replace("@","https://t.me/",$channel) . "/$msgid"
                ."' >?</a>\n\n~$text_isi";

                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>$text."OK",
                    "parse_mode"=>"HTML"
                ]);
            }
            elseif(!empty($botdata['reply_to_message'])
            and $botdata['reply_to_message']['from']['username'] == $botname
            and strpos($botdata['reply_to_message']['text'],"au balas apa") !== false){
                $kode = explode("~",$botdata['reply_to_message']['text'])[1];
                $explode = explode("|",$kode);
                $msgid_curhat = $explode[0];
                $curhater = strrev($explode[1]);
                $channelpost = f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$channel,
                    "text"=>"loading...",
                    "reply_to_message_id"=>$msgid_curhat,
                ]);
                if($channelpost["result"]["message_id"]){
                    $msgid = $channelpost["result"]["message_id"];
                    $channelpost = f("bot_kirim_perintah")("editMessageText",[
                        "chat_id"=>$channel,
                        "message_id"=>$msgid,
                        "text"=>$text,
                        "parse_mode"=>"HTML",
                        "disable_web_page_preview"=>true,
                    ]);
                    $send_text = "<a href='"
                    .str_replace("@","https://t.me/",$channel) . "/$msgid"
                    ."' >lihat</a>";
                    f("bot_kirim_perintah")("sendMessage",[
                        "chat_id"=>$curhater,
                        "text"=>"<a href='"
                        .str_replace("@","https://t.me/",$channel) . "/$msgid"
                        ."' >ada yang membalas curhatmu</a>",
                        "parse_mode"=>"HTML"
                    ]);
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
            elseif(f("str_is_diawali")($text, "#")){
                $channelpost = f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$channel,
                    "text"=>"loading...",
                ]);
                if($channelpost["result"]["message_id"]){
                    $msgid = $channelpost["result"]["message_id"];
                    $rchatid = strrev($chat_id);
                    $channelpost = f("bot_kirim_perintah")("editMessageText",[
                        "chat_id"=>$channel,
                        "message_id"=>$msgid,
                        "text"=>$text."\n\n<a href='t.me/$botuname?start=bls_$msgid|$rchatid'>[balas secara anonim]</a>",
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