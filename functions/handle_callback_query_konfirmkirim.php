<?php
function handle_callback_query_konfirmkirim($botdata){
    if(!empty($botdata["data"]) 
    and $botdata["data"] == "kirim"
    and !empty($botdata["message"]["reply_to_message"])
    ){
        $datakirim = $botdata["message"]["reply_to_message"];
        f("bot_kirim_perintah")('answerCallbackQuery',[
            'callback_query_id' => $botdata['id'],
            'text' => "Underconstruction",
            'show_alert' => true,
        ]);
        $chat_id = $botdata["message"]["chat"]["id"];
        $message_id = $botdata["message"]["message_id"];
        $result = f("bot_kirim_perintah")("deleteMessage",[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
        ]);
        if(!empty($result['ok'])){
            f("bot_kirim_perintah")("sendMessage",[
                "chat_id"=>$chat_id,
                "text"=>"Mohon maaf, saat ini sedang dalam pengembangan, coba lagi nanti.\n\n".print_r($datakirim,true),
            ]);
        }
        return true;
    }
    return false;
}