<?php

/**
 * Twitter: @thinkphp
 * Version: 2.0
 * Copyright (c) 2012, Adrian Statescu @thinkphp
 */

require_once('easy.cache.class..php');

$user = $_GET['user'];

$info = array();

$isjs = "/^[a-z|A-Z|_|-|\$|0-9|\.]+$/";

if(preg_match($isjs,$user)) {

  header('Content-type:image/png');

  $user = $_GET['user'];

  $url = "http://api.twitter.com/1/users/lookup.json?screen_name=". $user ."&include_entities=true";

  $cache = new EasyCache(5);

  $resp = $cache->getData('twitterchart',$url);

  $json = json_decode($resp, true);

  $profile = $json[0];

  $info['updater'] = convert($profile['statuses_count']);

  $info['follower'] = convert($profile['friends_count']);

  $info['followed'] = convert($profile['followers_count']);

   $disp = array();

   $max = max($info);

   $ratio = 100 / $max;

   foreach($info as $key=>$value) {
     if($max == $value) {
        $type = $key; 
     } 
     $disp[$key] = $value * $ratio;
   }

  $x = ($type == 'updater') ? (' is an ') : ($type == 'follower' ? ' is a ' : ($type == 'followed' ? ' is being ' : ''));

  $title = $user . $x. $type;

  $out = array();

  foreach($info as $k=>$v) {
     $out[] = $k .'%20('. $v .')';
  }

  $labels = join($out,"|");
  $values = join($disp,",");

  $image = get('http://chart.apis.google.com/chart?cht=p3&chco=F2E353,FFA305,54688E&'.'chtt='.urlencode($title).'&chd=t:'.$values.'&chs=580x300&chl='.$labels);

  echo$image;

} else {

  echo"<b>Usage: filename.php?user=thinkphp</b>"; 

}//endif-else

function convert($n) {

      //replace comma with space
      $n = str_replace(',','',$n);
      //convert to integer
      $n = (int)$n;
  //and return the integer
  return($n);
}

function get($url) {

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $out = curl_exec($ch);
      curl_close($ch);
          if(empty($out)) {
             return "timeout service"; 
          } else {
             return $out;
          }
}

?>
