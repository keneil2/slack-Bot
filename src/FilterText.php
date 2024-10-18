<?php
namespace SLACKBOT;
class FilterText{
    public function sanitizeInput($input){
       $sanintized_input=filter_var($input, FILTER_SANITIZE_STRING);
       return $sanintized_input;
    }
}