<?php
namespace SLACKBOT;
Trait  RequestTrait{
    protected $event_data;

    public function isarray(){
        if(is_array($this->event_data)){
          return true;
        }
    }
    public function getRequestStream(){
        $response = file_get_contents("php://input");
        // $file= new Filemanager;
        // $file->LogInfo($response);
$this->event_data = json_decode($response, true);


if (isset($_SERVER['HTTP_X_SLACK_RETRY_NUM'])) {
      exit();
   }

   if ($this->event_data["type"] == "url_verification") {
            echo json_encode($this->event_data["challenge"]);
      
         }
         return $this->event_data;
    }

    public function getFileId(){
        if($this->event_data["event"] && $this->event_data["event"]["type"] == "file_shared"){
         $file_id = $this->event_data["event"]["file_id"];
         
         $need_params=[
            // "file_id"=>,
               
         ];
         return $file_id;

        }
    }


public function getChannelId(){
    $channel_id = $this->event_data['event']['channel_id']; 
    return $channel_id;
}
}