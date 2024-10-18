<?php
use SLACKBOT\Filemanager;
use SLACKBOT\OpenAiHandler;
use SLACKBOT\Slackbot;
spl_autoload_register(function($class){
     if(file_exists($class.".php")){
      require_once "$class.php";
     }
});

require "envLoader.php";
require "commands.php";


$slackbot=new Slackbot();
$open_ai=new OpenAiHandler();
$file_manager=new Filemanager();


try{
    $slackbot->getRequestStream();
   if($slackbot->isarray()){
   

$file_id=$slackbot->getFileId();

 $file_obj=$slackbot->getFileInfo($file_id);

 $slackbot->downloadFile($file_id);

 $finfo = finfo_open(FILEINFO_MIME_TYPE);
 $channel_id=$slackbot->getChannelId();
     $fileType=  $finfo->file(__DIR__."/files/".$_SESSION["File_name"]);
 
      $allowedfileTypes=[
         "image/jpeg",
         "image/png",
         "image/avif",
         "image/svg+xml",
         "image/webp"
        ];



       if(!in_array($fileType,$allowedfileTypes)){
         $slackbot->sendMessage("Only images allowed for now 😓",$channel_id);
          throw new FileNotAllowedException("Only images supported for now 😓");
       }
 
 



 // open ai 

 $prompt=$file_manager->getLatestPrompt();
  
 $open_ai_response=$open_ai->sendPrompt($prompt,$file_manager->convertImageToBase64());
 // slack sending messages 
 $slackbot->sendMessage("Hi there! I'm fetching the code for you now. This might take a moment, but hang tight—I'll have it ready shortly! 😊",$channel_id);
 $slackbot->sendMessage(json_decode($open_ai_response,true)["choices"][0]["message"]["content"],$channel_id);  
   }

 
}catch(Exception $e){
   $slackbot->sendMessage("were so sorry but seems somthing went wrong",$channel_id);
  $file_manager->LogErrors($e);

}catch(FileNotAllowedException $e){
   $file_manager->LogErrors($e);

}finally{

}
