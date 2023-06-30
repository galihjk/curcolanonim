<?php
function handle_callback_query_konfirmkirim($botdata){
    if(!empty($botdata["data"]) 
    and $botdata["data"] == "kirim"
    and !empty($botdata["message"]["reply_to_message"])
    ){
        $datakirim = $botdata["message"]["reply_to_message"];
        f("bot_kirim_perintah")('answerCallbackQuery',[
            'callback_query_id' => $botdata['id'],
            'text' => "SIAP!",
            'show_alert' => true,
        ]);
        $chat_id = $botdata["message"]["chat"]["id"];
        $message_id = $botdata["message"]["message_id"];
        $result = f("bot_kirim_perintah")("deleteMessage",[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
        ]);
        if(!empty($result['ok'])){
            $channel = f("get_config")("channel");
            $botuname = f("get_config")("botuname");
            $text = $datakirim["text"] ?? "";
            $channelpost = f("bot_kirim_perintah")("sendMessage",[
                "chat_id"=>$channel,
                "text"=>"loading...",
            ]);
            if($channelpost["result"]["message_id"]){
                $rchatid = strrev($chat_id);
                $msgid = $channelpost["result"]["message_id"];
                $kode = substr($rchatid,0,3)."_".(999+(int)$msgid)."_".substr($rchatid,3);
                $jenis = explode(" ",$text)[0];
                $jenis = str_replace("#","",$jenis);
                $channelpost = f("bot_kirim_perintah")("editMessageText",[
                    "chat_id"=>$channel,
                    "message_id"=>$msgid,
                    "text"=>$text
                        ."\n\n<a href='t.me/$botuname?start=bls_$kode'>[balas secara anonim]</a>"
                        ."\n<a href='t.me/$botuname?start=lapor_$kode'>[laporkan penyalahgunaan]</a>"
                        ."\n<a href='t.me/$botuname?start=buat_$jenis'>[buat curhatan baru]</a>\n",
                    "parse_mode"=>"HTML",
                    "disable_web_page_preview"=>true,
                ]);
                $send_text = "<a href='"
                .str_replace("@","https://t.me/",$channel) . "/$msgid"
                ."' >Berhasil!</a>";
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
        return true;
    }
    return false;
}