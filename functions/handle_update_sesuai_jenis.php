<?php 
function handle_update_sesuai_jenis($jenis){
    $update = json_decode(file_get_contents("php://input"), TRUE);
    foreach($jenis as $item_jenis){
        if(!empty($update[$item_jenis])){
            f("handle_$item_jenis")($update[$item_jenis]);
        }
    }
}