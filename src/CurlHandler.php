<?php 
 namespace SLACKBOT;
 class CurlHandler{
    public $handle;
    public function initializeEndpoint($url){
       
         $this->handle=curl_init($url);
        return $this;
    }

    public function setPOSTInputFeilds(array $feilds){
     curl_setopt($this->handle,CURLOPT_POSTFIELDS,json_encode($feilds));
     return $this;
    }

    public function setSlackHeaders(array $headers){
        curl_setopt($this->handle,CURLOPT_HTTPHEADER,$headers);
        return $this;
    }


    public function SaveFile($f_stream){
        curl_setopt($this->handle, CURLOPT_FILE, $f_stream);  
        return $this;
    }

    public function dispatcher(){
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
      $response=  curl_exec($this->handle);
       

        if (curl_errno($this->handle)) {
            throw new \Exception(message: curl_error($this->handle));
        } 
        
        curl_close($this->handle);

        if(isset($response)){
         return $response;
        }
    }
 }

 