<?php
set_time_limit(120);
 
class Crawler
{
 
   protected $markup = "";
   public function __construct($url)
    {
      $this->markup = $this->getMarkup($url);
    }
 
   public function getMarkup($url)
    {
	// Create a stream
		$opts = array(
		  'http'=>array(
			'method'=>"GET",
			'header'=>"Accept-language: en\r\n" .
					  "Cookie: foo=bar\r\n"
		  )
		);

		$context = stream_context_create($opts);

		// Open the file using the HTTP headers set above
		$file = file_get_contents($url, false, $context);
		return $file;
    }
 
   public function get($type)
    {
      $method = "_get_{$type}";
      if (method_exists($this, $method))
      {
        return call_user_func(array($this, $method));
      }
    }
 
   protected function _get_images()
    {
      if (!empty($this->markup))
      {
        preg_match_all('/<img [^>]*src="?([^ ">]+)"?/i', $this->markup, $images);
        return !empty($images[1]) ? $images[1] : FALSE;
      }
    }
 
   protected function _get_links()
    {
      if (!empty($this->markup))
      {
        preg_match_all('/<a [^>]*href="?([^ ">]+)"?/i', $this->markup, $links);
        return !empty($links[1]) ? $links[1] : FALSE;
      }
    }
}  // End of Crawler class
 
if(isset($_POST['url']) && $_POST['url'] != '')
{
$url = $_POST['url'];
//We must enter http:// or https:// before the url, if it does not, then we check here
//and write http if needed.
if(substr($url, 0, 4) != 'http') $url = 'http://'.$url;
//Create an object of class Crawler.
$crawl = new Crawler($url);
//Call the function get() with argument "links"
$links  = array_unique($crawl->get('links'));

if(!empty($links))
{
    //get all hyperlinks
    foreach($links as $link)
    {
      if($link[0] == "'") $link = substr($link,1,-1);
      if($link[0] == "/") $link = $_POST['url'].$link;
     $etst[$link] =  makeCrawl($link);//call function recursively to get all hyperlinks of website pages
    }
	echo "<pre>";print_r($etst);die;
	foreach($etst as $key=>$values):
		//echo "<h3>Page Url: ".$key."</h3><br>";
		
		//Arrange site map accordingly and display it on browser.
	endforeach;
}
}

function makeCrawl($url){
$images = null;
$links = null;
//We must enter http:// or https:// before the url, if it does not, then we check here
//and write http if needed.
if(substr($url, 0, 4) != 'http') $url = 'http://'.$url;
//Create an object of class Crawler.
$crawl = new Crawler($url);
//Call the function get() with argument "images"
if(is_array($crawl->get('images'))){
$images = array_unique($crawl->get('images'));
}
if(is_array($crawl->get('links'))){
$links  = array_unique($crawl->get('links'));
}


$dataArray['images'] = $images;
$dataArray['hyperlinks']= $links;


//Call the function get() with argument "links"
/*
$i = 0;
echo "<table cellpadding='5'>";
echo "<tr><td id='title'>IMAGE LINKS</td></tr>";
//Here we check if array $images is empty or not. If it empty then we just pass the control to
//the else condition.
if(!empty($images))
{
    //Here we print the image links
    foreach($images as $img)
    {
      if($i%2 == 0) $style = "style='background-color:#cccccc;'";
      else $style="style='background-color:#eeeeee;'";
      if($img[0] == "'") $img = substr($img,1,-1);
      echo "<tr><td ".$style.">".$img."</td></tr>";
      $i++;
    }
}
else echo "<tr><td>No Image!</td></tr>";
echo "</table>";
 
$j = 0;
echo "<table cellpadding='5'>";
echo "<tr><td id='title'>HYPERLINKS</td></tr>";
//Here we check if array $links is empty or not. If it empty then we just pass the control to
//the else condition.
if(!empty($links))
{
    // print the hyperlinks
    foreach($links as $link)
    {
      if($j%2 == 0) $style = "style='background-color:#cccccc;'";
      else $style="style='background-color:#eeeeee;'";
      if($link[0] == "'") $link = substr($link,1,-1);
      if($link[0] == "/") $link = $_POST['url'].$link;
      echo "<tr><td ".$style.">".$link."</td></tr>";
      $j++;
    }
}
else echo "<tr><td>No Hyperlink!</td></tr>";
echo "</table>";
*/

return $dataArray;
}


?>
