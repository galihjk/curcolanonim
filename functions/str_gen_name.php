<?php
function str_gen_name(){
    $VOKAL = ['A','I','U','E','O'];
    $KONSONSAN = [
        'B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z'
    ];
    $NAME = "";
    $NAME .= $KONSONSAN[array_rand($KONSONSAN,1)];
    $NAME .= $VOKAL[array_rand($VOKAL,1)];
    $NAME .= $KONSONSAN[array_rand($KONSONSAN,1)];
    $NAME .= $VOKAL[array_rand($VOKAL,1)];
    $NAME .= $KONSONSAN[array_rand($KONSONSAN,1)];
    $NAME .= $VOKAL[array_rand($VOKAL,1)];
    return $NAME;
}