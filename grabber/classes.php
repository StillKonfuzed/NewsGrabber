<?php

function url_Builder($request,$response){
       $http = $request->getUri()->getScheme();
       $host = $request->getUri()->getHost();
       $path = $request->getUri()->getBasePath(); 
       $api_path = $request->getUri()->getPath();
       $params = $request->getUri()->getQuery();
       $ip = $request->getServerParam('REMOTE_ADDR');
       //$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
       //$city = $details->city;
         if(!empty($params)){
            $append = "?";
            $final = $append.$params;
         }else{ 
             $final = '';
             
         }
            return $url = $ip." -> ".'city'.' || '.$http."://".$host.$path."/".$api_path.$params;
}
         
         
 
function logger($data){
      $path = __DIR__ . '/..';
      $date = date('D d/M/Y');
      $configFile = $path."/logs/access.txt";
         if (file_exists($configFile)) { 
             $f = fopen($configFile, 'a');// or die('cannot open file');
             date_default_timezone_set('asia/kolkata');
              $time = date('g:i:s A');
             fwrite($f, $date."  ".$time." || {$data}".PHP_EOL);
             fclose($f);
         }
   }
function err_logger($data,$ex){
      $error = $ex->getMessage();
      $path = __DIR__ . '/..';
      $date = date('D d/M/Y');
      $configFile = $path."/logs/errors.txt";
         if (file_exists($configFile)) { 
             $f = fopen($configFile, 'a');
             date_default_timezone_set('asia/kolkata');
              $time = date('g:i:s A');
             fwrite($f, $date."  ".$time." || {$data} ::".PHP_EOL."                 {$error}".PHP_EOL);
             fclose($f);
             echo json_encode(array('status'=>'fatal_error','message'=>$error));
         }
   }
   
  function sanitize_string($string){
      return filter_var($string,FILTER_SANITIZE_STRING);
  }

  function time_difference($from,$to){
        $to_time = strtotime($to);
        $from_time = strtotime($from);
        return  intval(round(abs($to_time - $from_time) / 60,2));
  }


  function timeago($date) {
     $timestamp = strtotime($date); 
     
     $strTime = array("second", "minute", "hour", "day", "month", "year");
     $length = array("60","60","24","30","12","10");

     $currentTime = time();
     if($currentTime >= $timestamp) {
      $diff     = time()- $timestamp;
      for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
      $diff = $diff / $length[$i];
      }

      $diff = round($diff);
      if($diff > 1){
         return $diff . " " . $strTime[$i] . "s ago ";
      }else{
        return $diff . " " . $strTime[$i] . " ago ";
      }
    }
      
  }


  function gsmContents2($link){
        $page2 = file_get_contents("https://gsmarena.com/".$link);
        $doc = new DOMDocument();
        @$doc->loadHTML($page2);
        $classname="review-body";
        $finder = new DomXPath($doc);
        $spaner = $finder->query("//*[contains(@class, '$classname')]");
        //print_r($spaner);
        foreach($spaner as $nodes){
            if (strpos($nodes->nodeValue, 'Source') !== false) {
                $variable = substr($nodes->nodeValue, 0, strpos($nodes->nodeValue, "Source")); //remove all from source
                $variable = str_replace("\n", '', $variable);
                $variable = str_replace("\r", '', $variable);
                return $variable = str_replace("  ", '', $variable);
            }else{
                $variable = $nodes->nodeValue;
                $variable = str_replace("\n", '', $variable);
                $variable = str_replace("\r", '', $variable);
                return $variable = str_replace("  ", '', $variable);
            }
        }
  }
  function grabberDependency($con,$filename){ //proxy
       $log_runtime = date('g:i:s a');
       $log_date = date('D d-M-Y');
       $log = $con->prepare("Insert into t_grabber_logs set script_file = ?,run_time = ?,run_date = ? ");
       $log->execute([$filename,$log_runtime,$log_date]);
       if($log->rowCount()>0){
          return $log_id = $con->lastInsertId();
       }else{
           return false;
       }
  }
  
  function grabberDependencyRefresh($con,$log_id,$resp){ //proxy
       $log_endtime = date('g:i:s a');
       $log = $con->prepare("update t_grabber_logs set end_time = ? , response = ? where  log_id = ?");
       $log->execute([$log_endtime,$resp,$log_id]);
       if($log->rowCount()>0){
          return true;
       }else{
           return false;
       }
  }
  