<?php

 //require once the class cache
 require_once('easy.cache.class.php');

//set up endpoint API YQL
 $endpoint = "http://query.yahooapis.com/v1/public/yql?q="; 

 //configure YQL statement
 $yqlforge = 'select * from html where url="http://mootools.net/forge/profile/thinkphp" and xpath="//ul[@class=\'projects\']"';

 //show me the URL
 $url = $endpoint  . urlencode($yqlforge) . '&diagnostics=false&format=xml';

 //util function to retrieve the content from service
 function get($url) {
          $ch = curl_init();
          curl_setopt($ch,CURLOPT_URL,$url);
          curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
          curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
          $data = curl_exec($ch);
          $data = preg_replace('/<\?.*?>/','',$data);
          $data = preg_replace('/<\!--.*-->/','',$data);
          $data = preg_replace('/.*?<results>/','',$data);
          $data = preg_replace('/<\/results>.*/','',$data);
          $data = preg_replace('/ href="/',' href="http://mootools.net',$data);
          $data = preg_replace('/ src="/',' src="http://mootools.net',$data);
          curl_close($ch); 
          if(empty($data)) {return 'Server Timeout. Try again later!';}
                 else {return $data;}
 }//end function get

//function callback to execute
//@param  - String input url to grab
//@return - Mixed return the real plugins content from mootools
function doit($url) {

  //get the data from service
  $data = get($url);

return $data;
}
?>

<style type="text/css">
ul.projects {width: 700px; position: relative;margin-left: -50px;margin-top: 10px;}
.projects li{float:left;list-style-type:none;margin-bottom:15px;margin-right:52px;margin-top: 20px;position:relative;height:100px;width:187px;border: 1px solid #ccc;}
.block {clear: both}
ul, ol {margin:0 1.5em 1.5em;}
.projects li span.downloads {font-size:30px;position:absolute;top:0;right:3px;color: orange}
.projects li .name {color: #393;display:block;font-weight:bold;margin-bottom:5px;}li a {text-decoration: none}
.blue {color: orange}
a{color: #393}
</style>
<h1>/ MooTools / Forge / <a href="http://mootools.net/forge/profile/thinkphp">My Plugins</a></h1>

<?php

//create an instance of cache and set the expire time to 3 hours
$cache = new EasyCache(10);

//display the plugins from cache if any, otherwise returns fresh content with a new request!
echo $cache->getData('forge',$url,"doit");

?>
