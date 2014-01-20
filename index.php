<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Web crawling using php</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
  $("#submit").click(function()
  {
    var url = $("#url").val();
    if(url.length > 0)
    {
      //A loading or waiting gif image will display in the demo_output div until the extract data will appearing
      $("#demo_output").html('&nbsp;&nbsp;<img src="loading.gif">');
      $.ajax
      ({
         type: "POST",
         url: "crawl.php",
         data: "url="+url,
         success: function(option)
         {
           $("#demo_output").html(option);
         }
      });
    }
  });
});
</script>
</head>
<body>
<div>
  <div id="demo_input">
    &nbsp;Enter Url :&nbsp;&nbsp;&nbsp;<input type="text" name="url" id="url" value="" />&nbsp;<input type="submit" id="submit" value="Process" />
  </div><!-- End of #demo_input -->
 
  <div id="demo_output">
  </div><!-- End of #demo_output -->
</div><!-- End of .demo_wrapper -->
</body>
</html>
