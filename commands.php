<?php
use SLACKBOT\Filemanager;
    $response = file_get_contents("php://input");

    parse_str( $response, $array);

    if( array_key_exists("text",$array)){

    $file_manager=new Filemanager();
    $file_manager->writePromptTofile($array["text"]);
    
 
    

 if (isset($event_data['event']) && $event_data["event"]["type"] == "file_shared"){
    $fp=fopen("exe.log","r+");
    fwrite($fp,"app menstion!!!!!!!!!");
}  
    }
    