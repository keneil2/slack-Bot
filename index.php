<?php
use SLACKBOT\Filemanager;
use SLACKBOT\OpenAiHandler;
use SLACKBOT\Slackbot;
spl_autoload_register(function($class){
     if(file_exists($class.".php")){
      require_once "$class.php";
     }
});
require "helper.php";
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















































































































































// $slack_token = $_ENV["SLACK_BOT_TOKEN"];
// $gemini_token = $_ENV["OPENAI_API_KEY"];
// $channel_id=null;
// $response = file_get_contents("php://input");
// $event_data = json_decode($response, true);



// // preventing slack from sending multiple retries
// if (isset($_SERVER['HTTP_X_SLACK_RETRY_NUM'])) {
//    exit();
// }

// try {
//    if ($event_data == null) {
//       exit();
//    }

//    if ($event_data["type"] == "url_verification") {
//       echo json_encode($event_data["challenge"]);
//    }

//    if (isset($event_data['event']) && $event_data["event"]["type"] == "app_mention") {
//       $fp = fopen("exe.log", "r+");
//       fwrite($fp, "app menstion!!!!!!!!!");
//    }

//    if (isset($event_data['event']) && $event_data["event"]["type"] == "file_shared") {

//       $file_id = $event_data["event"]["file_id"];
      
// $file_object = getFileInfo($file_id, $slack_token);

//       // getting the file url from slack
//       $file_url = json_decode($file_object, true)["file"]["url_private_download"];

//       DownloadFile($file_url, $slack_token);

//       $latest_prompt=file("prompts.txt");
      
//         $channel_id = $event_data['event']['channel_id']; 









//       $allowedfileTypes=[
//          "image/jpeg",
//          "image/png",
//          "image/avif",
//          "image/svg+xml",
//          "image/webp"
//         ];
 
//      $finfo = finfo_open(FILEINFO_MIME_TYPE);
 
//      $fileType=  $finfo->file(__DIR__."/files/".$_SESSION["File_name"]);
 
//        if(!in_array($fileType,$allowedfileTypes)){
//          sendMessageToSlack($channel_id, "Only images allowed for now 😓", $slack_token);
//           throw new FileNotAllowedException("Only images supported for now 😓");
//        }
 




//         sendMessageToSlack($channel_id, "Hi there! I'm fetching the code for you now. This might take a moment, but hang tight—I'll have it ready shortly! 😊", $slack_token);
//          $prompt = openAi(prompt: $latest_prompt[0]);
      
      

     
     
//       sendMessageToSlack($channel_id, json_decode($prompt, true)["choices"][0]["message"]["content"], $slack_token);
//    }
// } catch (Exception $e) {
//    // logging errors 
   
//    $fp = fopen("exe.log", "a+");

//    fwrite($fp, $e . "\n");
  

// }catch(FileNotAllowedException $e){

//    $fp = fopen("exe.log", "a+");

//    fwrite($fp, $e->getMessage() . "\n");
// }

