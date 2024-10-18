<?php
require "helper.php";
require "envLoader.php";
require "commands.php";


$slack_token = $_ENV["SLACK_BOT_TOKEN"];
$gemini_token = $_ENV["OPENAI_API_KEY"];

$response = file_get_contents("php://input");
$event_data = json_decode($response, true);

// preventing slack from sending multiple retries
if (isset($_SERVER['HTTP_X_SLACK_RETRY_NUM'])) {
   exit();
}

try {
   if ($event_data == null) {
      exit();
   }

   if ($event_data["type"] == "url_verification") {
      echo json_encode($event_data["challenge"]);

   }
   if (isset($event_data['event']) && $event_data["event"]["type"] == "app_mention") {
      $fp = fopen("exe.log", "r+");
      fwrite($fp, "app menstion!!!!!!!!!");
   }

   if (isset($event_data['event']) && $event_data["event"]["type"] == "file_shared") {

      $file_id = $event_data["event"]["file_id"];
      // $fp = fopen("exe.log", "r+");

      // fwrite($fp, $file_id . "\n");
$file_object = getFileInfo($file_id, $slack_token);

      // getting the file url from slack
      $file_url = json_decode($file_object, true)["file"]["url_private_download"];
     
 $fp = fopen("exe.log", "r+");

      fwrite($fp, $file_id . "\n");
      DownloadFile($file_url, $slack_token);

      $latest_prompt=file("prompts.txt");
      // $_SESSION["ChatPrompt"] = $latest_prompt[count($latest_prompt)=1? 0 : count($latest_prompt)-1];

         $prompt = openAi(prompt: $latest_prompt[0]);

      fwrite($fp, $prompt);


      $channel_id = $event_data['event']['channel_id'];

      sendMessageToSlack($channel_id, json_decode($prompt, true)["choices"][0]["message"]["content"], $slack_token);
   }
} catch (Exception $e) {
   // logging errors 

   $fp = fopen("exe.log", "r+");

   fwrite($fp, $e . "\n");

}
