A simple class for caching 3rd party API calls in PHP
-----------------------------------------------------

How to use
==========

example 1

     #PHP 
     require_once('easy.cache.class.php');
     $url = "http://api.twitter.com/1/users/lookup.json?screen_name=thinkphp&include_entities=true";
     $cache = new EasyCache(5);//cache for 5 hours
     $resp = $cache->getData('twitterchart',$url);
     $out = json_decode($resp, true);
     print_r($out);

example 2

     #PHP
     //require once the class cache
     require_once('easy.cache.class.php');
     //set up endpoint API YQL
     $endpoint = "http://query.yahooapis.com/v1/public/yql?q="; 
     //configure YQL statement
     $yqlforge = 'select * from html where url="http://mootools.net/forge/profile/thinkphp" and xpath="//ul[@class=\'projects\']"';
     //show me the URL assembled
     $url = $endpoint  . urlencode($yqlforge) . '&diagnostics=false&format=xml';
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
     }
     function doit($url) {
        $data = get($url); 
       return $data;
     }
     $cache = new EasyCache(10);//cache for 10 hours
     echo $cache->getData('forge',$url,"doit");

You can view in action:
[http://thinkphp.ro/apps/php-hacks/cache.php/forge.php](http://thinkphp.ro/apps/php-hacks/cache.php/forge.php)
[http://thinkphp.ro/apps/php-hacks/cache.php/twittertimeline.php](http://thinkphp.ro/apps/php-hacks/cache.php/twittertimeline.php)
[http://localhost/php-hacks/cache.php/twitterchart.php?user=mootools](http://localhost/php-hacks/cache.php/twitterchart.php?user=mootools)