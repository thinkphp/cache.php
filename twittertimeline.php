<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>A simple class for caching 3rd party API calls in PHP</title>
<style type="text/css">
* {margin:0;padding:0;}
html,body{font-family: georgia,arial,verdana;font-size: 25px;background: #111}
form{-moz-border-radius:5px;-webkit-border-radius:5px;background:#00B7FF;padding: 5px;width: 60%;margin-left: 220px;margin-bottom: 10px;}
form label {font-size: 17px}
div#content_tweets{width: 567px;background: #fff;}
ol#timeline {padding: 10px}
ol.statuses {font-size:15px;list-style:none outside none;}
ol.statuses > li:first-child {border-top:0 none;}
ol.statuses li.latest-status {border-top-width:0;line-height:1.5em;padding:1.5em 0 1.5em 0.5em;}
ol.statuses li {padding-left:0.5em;}
ol.statuses li {line-height:20px;margin-bottom: 20px}
.meta {color:#999999;display:block;font-size:11px;}
ol.statuses li.status, ol.statuses li.direct_message {border-bottom:1px solid #EEEEEE;line-height:20px;padding:10px 0 8px;position:relative;}
ol.statuses .latest-status .entry-content {font-size:1.77em;}
div#content_tweets {padding:0 220px;}
div#content_tweets {word-wrap:break-word;}
li:hover {background: #F7F7F7}
a{color: #2FC2EF}
ol .image_profile img {margin-left:0;float: left;padding-right: 10px}
#content_tweets img#loader {margin-left: 270px}
#logo a {background: url("logo.png") no-repeat scroll 20px 9px transparent;color: #FFFFFF;display: block;height: 50px; margin-left: 205px;outline: medium none;text-indent: -9999px;width: 240px;}
#ft{font-size:80%;color:#888;margin:2em 0;font-size: 16px;padding-left: 230px}
#ft p a{color:#2FC2EF;}
</style>
</head>
<body>

<div id="logo"><a href="#">Twitter</a></div>
<form id="f" name="f">
  <label for="user">Username:</label><input type="text" id="username" name="user" value="<?php echo$_GET['user'];?>"/><input type="submit" value="Grab">
</form>  

<?php
require_once('easy.cache.class.php');

  $user = isset($_GET['user']) ? $_GET['user'] : 'thinkphp'; 

  $url = "http://api.twitter.com/1/statuses/user_timeline.json?screen_name=".$user."&count=10";

  $cache = new EasyCache(5);

  $resp = $cache->getData($user.'_timeline',$url);
 
  $json = json_decode($resp,true);


  echo"<div id='content_tweets'>";
  echo"<ol id='timeline' class='statuses'>";


  foreach($json as $key=>$info) {
    $status = tweetify($info['text']);
    echo"<li>";
    echo"<span class='status-body'><span class='status-content'>";
    echo"<span class='status-entry'>{$status}</span></span>";
    echo"<span class='meta entry-meta'>{$info['created_at']}</span>";
    echo"</li>";

  }

  echo"</ol>";
  echo"</div>";

    function tweetify($text) {

       $text = preg_replace("/(https?:\/\/[\w\-:;?&=+.%#\/]+)/i", '<a href="$1">$1</a>',$text);
       $text = preg_replace("/(^|\W)@(\w+)/i", '$1<a href="http://twitter.com/$2">@$2</a>',$text);
       $text = preg_replace("/(^|\W)#(\w+)/i", '$1#<a href="http://search.twitter.com/search?q=%23$2">$2</a>',$text); 

      return $text;
    }

?>

<div id="ft"><p>Written by @<a href="http://twitter.com/thinkphp">thinkphp</a> | source on <a href="#">Github</a></p></div>
</body>
</html>
