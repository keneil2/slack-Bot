<?php
    $response = file_get_contents("php://input");

    parse_str( $response, $array);

    if( array_key_exists("text",$array)){

    $fp=fopen("prompts.txt","r+");

    fwrite($fp,$array["text"]."\n");

 if (isset($event_data['event']) && $event_data["event"]["type"] == "file_shared"){
    $fp=fopen("exe.log","r+");
    fwrite($fp,"app menstion!!!!!!!!!");
}  
    }
    