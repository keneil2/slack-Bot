<?php
namespace SLACKBOT;
class Slackbot extends CurlHandler{
    use RequestTrait;
    protected $fileinfo_base_url="https://slack.com/api/files.info";
    protected $message_base_endpoint="https://slack.com/api/chat.postMessage";
    private $headers;
   

    public function __construct(){
       
        if(!isset($_ENV["SLACK_BOT_TOKEN"])){
              throw new \Exception("no env set for slack name is SLACK_BOT_TOKEN");
        }

         $this->headers=[
            'Content-Type: application/json',
            'Authorization: Bearer ' . $_ENV["SLACK_BOT_TOKEN"],
        ];
        
   }


    public function sendMessage($message,$channel_id){
        $this->handle=curl_init($this->message_base_endpoint);
        $post_fields = [
            "channel" => $channel_id,
            "text" => $message
        ];
        $this->messageEndPointinit();
        $this->setSlackHeaders($this->headers)

        ->setPOSTInputFeilds($post_fields) 
        
        ->dispatcher();
    }

    public function downloadFile($file_id){
        $dir= __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."files/";

    $file_object=json_decode($this->getFileInfo($file_id),true);

    $file_url=$file_object["file"]["url_private_download"];
  $file_manager=new Filemanager;
     
       $this->handle=curl_init($file_url);

      $filename = basename($file_url);
    

      $save_location = $dir . $filename;

     
 
      $_SESSION["File_name"]=$filename;
   
      $f_stream = fopen($save_location, 'wb');
       $file_manager->LogInfo($_SESSION["File_name"]);
        $handle=curl_init($file_url);

       curl_setopt($handle, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $_ENV["SLACK_BOT_TOKEN"],
            ]);
            curl_setopt($handle, CURLOPT_FILE, $f_stream);
        
        
           
            curl_exec($handle);
            curl_close($handle);

    //    $this->initializeEndpoint($file_url)
    //    ->setSlackHeaders($this->headers)

    //     ->SaveFile($f_stream)

    //     ->dispatcher();
        

        fclose($f_stream);
    }





    public function getFileInfo($file_id){
        $this->fileInfoEndPointinit($file_id);
        $this->setSlackHeaders($this->headers);
       return  $this->dispatcher();
    }

    public function messageEndPointinit(){
        $this->handle=curl_init($this->message_base_endpoint);
    }
    public function fileInfoEndPointinit($file_id){
     $this->handle=curl_init($this->fileinfo_base_url."?file=$file_id");
    }
}