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
			'header'=>"Accept-language: en\r\n" 
		  )
		);

		$context = stream_context_create($opts);

		// Open the file using the HTTP headers set above
		$file = file_get_contents($url, false, $context);
		return $file;
    }
    
    //get Data from curl
    function getDataFromCurl($url){
	
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$file = curl_exec($ch);
		curl_close($ch);

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

    function _get_title() {
        if (!empty($this->markup))
        { 
		preg_match('/<title>(.*)<\/title>/siU', $this->markup, $title);

        $title = preg_replace('/\s+/', ' ', $title[1]);
        $title = trim($title);
        return $title;
    }
}

}  // End of Crawler class
 
if(isset($_POST['url']) && $_POST['url'] != '')
{
	include('csv.php');
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

	echo "<pre>";print_r($etst);
	//$csv = convertToCsv($etst);
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
	//echo "<pre>";
	//print_r($images);die;
	$images = convertToString($images );
}
//Call the function get() with argument "hyperlinks"
if(is_array($crawl->get('links'))){
	$links  = array_unique($crawl->get('links'));
	$links =  convertToString($links );
}

//Call the function get() with argument "title"
if($crawl->get('title')){
	$title= $crawl->get('title');
}

//Creating data Array
$dataArray['title']= $title;
$dataArray['images'] = $images;
$dataArray['hyperlinks']= $links;

return $dataArray;
}

function convertToString($array){
    $out = implode(",", $array) . "\r\n";
	return $out;
}

/*function convertToCsv($data){
	header('Content-Type: application/excel');
	header('Content-Disposition: attachment; filename="sitemap.csv"');

	$fp = fopen('sitemap.csv', 'w+');
	chmod("sitemap.csv",777);
	foreach ( $data as $line ) {
		foreach($line as $l):
		$val = explode(",", $l);
		fputcsv($fp, $val);
		endforeach;
	}
	fclose($fp);
	
}*/

function convertToCsv($data){
	$csv = new CSV(array('date', 'name', 'address'));
	$csv->addRow(array('2/2/2010', 'John', 'Portland, OR'));
 
        // export csv as a download
        $filename = 'names';
	$csv->export($filename);
 
        // *or* pass the csv data to a variable as a string
        $string = $csv;
	
	}
?>
