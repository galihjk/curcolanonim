<?php
function handle_message($botdata){
    $channel = f("get_config")("channel");
    $botuname = f("get_config")("botuname");
    $commentgroup = f("get_config")("commentgroup");
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
            elseif(f("str_is_diawali")($text, "/start buat_")){
                $jenis = str_ireplace("/start buat_","",$text);
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>"Kirim curhatan mu di sini.\n\n"
                        ."Format:\n#(jenis)(spasi)(curhatan kamu)\n\ncontoh:\n"
                        ."`#$jenis Blablabla..`",
                    "parse_mode"=>"MarkDown"
                ]);
            }
            //penyalahgunaan=========
            elseif(f("str_is_diawali")($text, "/start lapor_")){
                $text_isi = str_ireplace("/start lapor_","",$text);
                $explode = explode("_",$text_isi);
                $msgid = (int)$explode[1]-999;

                $textkirim = "Penyalahgunaan apa?\n\n<a href='"
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
            and strpos($botdata['reply_to_message']['text'],"enyalahgunaan apa?") !== false){
                $kode = explode("~",$botdata['reply_to_message']['text'])[1];
                $explode = explode("_",$kode);
                $msgid_curhat = (int)$explode[1]-999;
                $curhater = strrev($explode[0].$explode[2]);
                f("bot_kirim_perintah")("forwardMessage",[
                    "chat_id"=>$curhater,
                    "from_chat_id"=>$channel,
                    "message_id"=>$msgid_curhat,
                ]);
                f("bot_kirim_perintah")("forwardMessage",[
                    "chat_id"=>'227024160',
                    "from_chat_id"=>$channel,
                    "message_id"=>$msgid_curhat,
                ]);
                f("bot_kirim_perintah")("editMessageText",[
                    "chat_id"=>$channel,
                    "message_id"=>$msgid_curhat,
                    "text"=>"<i>*Postingan ini telah dilaporkan sebagai penyalahgunaan.</i>\nAlasan: $text"
                        ."\nOleh: ".$botdata['from']['first_name'],
                    "parse_mode"=>"HTML",
                ]);
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$curhater,
                    "text"=>"<a href='"
                        .str_replace("@","https://t.me/",$channel) . "/$msgid_curhat"
                        ."'>Postingan anda</a>"
                        ." telah dilaporkan sebagai penyalahgunaan.",
                    "parse_mode"=>"HTML",
                    "disable_web_page_preview"=>true,
                ]);
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>"<a href='"
                        .str_replace("@","https://t.me/",$channel) . "/$msgid_curhat"
                        ."' >Berhasil!</a>",
                    "parse_mode"=>"HTML",
                ]);
            }
            //============================================================
            elseif(f("str_is_diawali")($text, "/start bls_")){
                $text_isi = str_ireplace("/start bls_","",$text);
                $explode = explode("_",$text_isi);
                $msgid = (int)$explode[1]-999;

                $textkirim = "Mau balas apa? (minimal 20 karakter)\n\n<a href='"
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
            and strpos($botdata['reply_to_message']['text'],"au balas apa?") !== false
            and strlen($text) >= 20
            ){
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
                    $rchatid = strrev($chat_id);
                    $kode = substr($rchatid,0,3)."_".(999+(int)$msgid)."_".substr($rchatid,3);
                    $channelpost = f("bot_kirim_perintah")("editMessageText",[
                        "chat_id"=>$channel,
                        "message_id"=>$msgid,
                        "text"=>$text
                            ."\n\n<a href='t.me/$botuname?start=bls_$kode'>[balas secara anonim]</a>"
                            ."\n<a href='t.me/$botuname?start=lapor_$kode'>[laporkan penyalahgunaan]</a>"
                            ."\n<a href='t.me/$botuname?start=buat_random'>[buat curhatan baru]</a>\n",
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
            elseif(f("str_is_diawali")($text, "#") and substr_count($text, ' ')>=2 and strlen($text)>=20){
                //kirim konfirmasi curhat
                $lastconfirm = f("data_load")("waitingsendconfirm$chat_id",0);
                if(!empty($lastconfirm)){
                    f("bot_kirim_perintah")("deleteMessage",[
                        'chat_id'=>$chat_id,
                        'message_id'=>$lastconfirm,
                    ]);
                }
                $kirimconfirm = f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>"KONFIRMASI\n<i>*Klik tombol kirim untuk melanjutkan.</i>",
                    "parse_mode"=>"HTML",
                    "reply_to_message_id"=>$botdata["message_id"],
                    'reply_markup'=>f("gen_inline_keyboard")([
                        ['❌ BATAL', "kirimbatal"],
                        ['✅ KIRIM', 'kirim']
                    ],2),
                ]);
                if(!empty($kirimconfirm["result"]["message_id"])){
                    f("data_save")("waitingsendconfirm$chat_id",$kirimconfirm["result"]["message_id"]);
                }
            }
            //balas comment
            elseif(!empty($botdata['reply_to_message'])
            and $botdata['reply_to_message']['from']['username'] == $botuname
            and strpos($botdata['reply_to_message']['text'],"alas di sini untuk mengirim pesan secara anoni") !== false){
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$commentgroup,
                    "text"=>"<i>Curhater:</i>\n$text",
                    "parse_mode"=>"HTML",
                    "disable_web_page_preview"=>true,
                    "reply_to_message_id"=>explode("~",$botdata['reply_to_message']['text'])[1],
                ]);
            }
            // donate
            elseif($text == "/donate"){
                $result = f("bot_kirim_perintah")("sendInvoice",[
                    "chat_id"=>$chat_id,
                    "title"=>"Donasi",
                    "description"=>"Donasi untuk Curcol Anonim",
                    "payload"=>"donate$chat_id",
                    "currency"=>"XTR",
                    "prices"=>[
                        [
                            "label"=>"donasi",
                            "amount"=>1,
                        ],
                    ],
                    "text"=>"<i>Curhater:</i>\n$text",
                    "parse_mode"=>"HTML",
                    "disable_web_page_preview"=>true,
                    "reply_to_message_id"=>explode("~",$botdata['reply_to_message']['text'])[1],
                ]);
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>"227024160",
                    "text"=>"hasil:".print_r($result,true),
                ]);
            }
            //================================================================
            // start conversation
            elseif((f("str_is_diawali")($text, "/start stcon_"))){
                $nama_samaran = f("str_gen_name")();
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>"Nama samaran anda: $nama_samaran",
                ]);
            }
            //================================================================
            else{
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$chat_id,
                    "text"=>"FORMAT:\n#(jenis)(spasi)(curhatan kamu)\n\ncontoh:\n"
                    ."`#ditolak Pengen move-on..`\n\n"
                    ."Jenisnya bebas, yg penting diawali tanda pagar (#).\n"
                    ."Minimal 20 karakter, 1 jenis, dan 2 kata.",
                    "parse_mode"=>"MarkDown"
                ]);
            }
        }
    }
    elseif($chat_id == $commentgroup){
        $text = $botdata["text"] ?? "";
        if($text and !empty($botdata['reply_to_message']['entities'])){
            $reply_to_message = $botdata['reply_to_message'];
            $entities = $reply_to_message['entities'];
            foreach($entities as $entity){
                if(!empty($entity['url'])
                and f("str_is_diawali")($entity['url'], "http://t.me/curcolanonimbot?start=lapor_")
                ){
                    $kode = str_replace("http://t.me/curcolanonimbot?start=lapor_","",$entity['url']);
                    $explode = explode("_",$kode);
                    $msgid_curhat = (int)$explode[1]-999;
                    $curhater = strrev($explode[0].$explode[2]);
                    $url = str_replace("@","https://t.me/",$channel)."/$msgid_curhat?comment=".$botdata['message_id'];
                    f("bot_kirim_perintah")("sendMessage",[
                        "chat_id"=>$curhater,
                        "text"=>"Ada <a href='$url'>komentar</a> untuk mu. \n<i>*Balas di sini untuk mengirim pesan secara anonim</i>.\n~".$botdata['message_thread_id'],
                        "parse_mode"=>"HTML",
                        "reply_markup"=>['force_reply' => true,],
                    ]);
                }
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