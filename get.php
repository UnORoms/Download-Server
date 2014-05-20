<?php
$full_path = urldecode($_GET["p"]);
// echo getcwd() . DIRECTORY_SEPARATOR . $full_path;
if(!is_file(getcwd() . DIRECTORY_SEPARATOR . $full_path)) die("file is not exists");

//functions 

/**
 * get full path to file .md5 or /md5sum
 * @param  string $name filename  without ext
 * @return mixed
 */
function getMD5File($name){
	global $path;
	$ext = array("md5", "md5sum");
	$base = getcwd() . DIRECTORY_SEPARATOR . $path;
	$exts = array_filter($ext, function($e)use($name, $base){
		$pathToFile = $base . DIRECTORY_SEPARATOR . $name . "." . $e;
		return (is_file($pathToFile));
	});

	if(empty($exts)) return false;
	$ext = array_shift($exts);
	return $base . DIRECTORY_SEPARATOR . $name . "." . $ext;	
}

/**
 * Return md5 if file exist
 * @return mixed
 */
function getMD5(){
	global $name;
	$path = getMD5File($name);
	if($path == false) return;
	$content = array_shift(file($path));
	return array_shift(explode(" ", $content));
}

/**
 * return changeLog if file exists
 * @return mixed
 */
function getChangeLog(){
	global $name, $path;
	$chLogPath = getcwd() . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $name . ".changelog";
	return (is_file($chLogPath)) ? file_get_contents($chLogPath) : null;
}



//prepare variables
$temp = explode("/",  $full_path);

$file = array_pop($temp);
$path = implode("/",	 $temp);

//geting filename
$temp = explode(".", $file);
$name = array_shift($temp);

//get additional info
$md5 	   = getMD5();
$changeLog = getChangeLog();
?>



<?php 
/**
 * Header
 */
print "<?xml version='1.0' encoding='utf-8'?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.1//EN' 'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en'>
	<head>
		<title>Index of /" .$vpath. "</title>
		<style type='text/css'>
		a, a:active {text-decoration: none; color: blue;}
		a:visited {color: #48468F;}
		a:hover, a:focus {text-decoration: underline; color: red;}
		body {background-color: #F5F5F5; text-align: center}
		h2 {margin-bottom: 12px;}
		table {margin-left: 12px;}
		th, td { font-family: 'Courier New', Courier, monospace; font-size: 10pt; text-align: left;}
		th { font-weight: bold; padding-right: 14px; padding-bottom: 3px;}
		td {padding-right: 14px;}
		td.s, th.s {text-align: right;}
		div.list { background-color: white; border-top: 1px solid #646464; border-bottom: 1px solid #646464; padding-top: 10px; padding-bottom: 14px;}
		div.foot, div.script_title { font-family: 'Courier New', Courier, monospace; font-size: 10pt; color: #787878; padding-top: 4px;}
		div.script_title {float:right;text-align:right;font-size:8pt;color:#999;}
		textarea {width:640px; height:400px}
		a.download{font-size: 20px; margin-bottom: 20px; display: block}
		div.md5{margin-bottom: 10px}
		div.md5 span{color: grey}
		</style>
	</head>
	<body>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-26888993-2', 'shantur.com');
  ga('require', 'linkid', 'linkid.js');
  ga('send', 'pageview');

</script>";
?>

<?php 
/**
 * Content
 */
?>

<h2>File Name: <?php echo $file;?></h2>
<a class="download" href="/<?php echo $full_path;?>"?>download</a>

<?php if($md5):?>
<div class="md5">
	<span>MD5:</span> <?php echo $md5?>
</div>
<?php endif?>

<?php if($changeLog):?>
<textarea><?php echo $changeLog?></textarea>
<?php endif?>

<?php 
/**
 * Footer
 */
// Print ending stuff
print "
	<div class='foot'>". $_ENV['SERVER_SOFTWARE'] . "</div>
	</body>
	</html>";
?>