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
                $explode = explode("_",$text_isi);
                $msgid = (int)$explode[1]-999;

                $textkirim = "Mau balas apa?\n\n<a href='"
                .str_replace("@","https://t.me/",$channel) . "/$msgid"
                ."' >.</a>~$text_isi";

                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>$textkirim,
                    "parse_mode"=>"HTML",
                    "reply_markup"=>['force_reply' => true,],
                ]);
            }
            elseif(!empty($botdata['reply_to_message'])
            and $botdata['reply_to_message']['from']['username'] == $botuname
            and strpos($botdata['reply_to_message']['text'],"au balas apa") !== false){
                $kode = explode("~",$botdata['reply_to_message']['text'])[1];
                $explode = explode("_",$kode);
                $msgid_curhat = (int)$explode[1]-999;
                $curhater = strrev($explode[0].$explode[2]);
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
                    ."' >Berhasil!</a>";
                    f("bot_kirim_perintah")("sendMessage",[
                        "chat_id"=>$curhater,
                        "text"=>"<a href='"
                        .str_replace("@","https://t.me/",$channel) . "/$msgid"
                        ."' >Seseorang</a> membalas curhatanmu secara anonim",
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
            elseif(f("str_is_diawali")($text, "#") and substr_count($text, ' ')>=2 and strlen($text)>15){
                $channelpost = f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$channel,
                    "text"=>"loading...",
                ]);
                if($channelpost["result"]["message_id"]){
                    $rchatid = strrev($chat_id);
                    $msgid = $channelpost["result"]["message_id"];
                    $kode = substr($rchatid,0,3)."_".(999+(int)$msgid)."_".substr($rchatid,3);
                    $channelpost = f("bot_kirim_perintah")("editMessageText",[
                        "chat_id"=>$channel,
                        "message_id"=>$msgid,
                        "text"=>$text
                            ."\n\n<a href='t.me/$botuname?start=bls_$kode'>[balas secara anonim]</a>\n"
                            ."\n<a href='t.me/$botuname?start=lapor_$kode'>[laporkan penyalahgunaan]</a>\n",
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
            else{
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>"<b>FORMAT</b>:\n#(jenis)(spasi)(curhatan kamu)\n\ncontoh:\n"
                    ."<pre>#ditolak Pengen move-on..</pre>\n\n"
                    ."Jenisnya bebas, yg penting diawali tanda pagar (#).\n"
                    ."Minimal 15 karakter, 1 jenis, dan 2 kata.",
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