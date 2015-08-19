<?php

// Base URL for manual
$base = 'http://php.net/manual/en/';

// Canonical URL for app
$canonical = 'https://richjenks.com/dev/php-fotd/';

// Numeric date
$today = date('Ymd');

// Open archive
$archive = file_get_contents('archive.txt');
$archives = explode("\n", $archive);

// Check if today's function is in archive
foreach ($archives as $key => $archive) {
	$parts = explode('|', $archive);
	if ($parts[0] === $today) {
		$daily_function['href'] = $parts[1];
		$daily_function['function'] = $parts[2];
		$daily_function['description'] = $parts[3];
		$already_chosen = true;
	} else {
		$already_chosen = false;
	}
}

// Check if we haven't already chosen
if (!$already_chosen) {

	if ($html = file_get_contents($base.'indexes.functions.php')) {

		// Remove all but functions list
		$functions = explode("\n", $html);
		foreach ($functions as $key => $function) {
			if (strpos($function, 'class="index"') === false) {
				unset($functions[$key]);
			}
		}

		// Sort functions to fill missing indexes
		sort($functions);

		// Select random function
		$count = count($functions);
		srand($today);
		$rand = rand(0, $count);
		$chosen =  $functions[$rand];

		// Make HTML
		$chosen = htmlspecialchars_decode($chosen);

		// EXPLODE!
		$parts = explode('"', $chosen);

		// Get href
		$daily_function['href'] = $parts[1];

		// Get function
		$parts = explode(' - ', $parts[4]);
		$daily_function['function'] = $parts[0];
		$daily_function['function'] = str_replace('</a>', '', $daily_function['function']);
		$daily_function['function'] = str_replace('>', '', $daily_function['function']);

		// Get description
		$daily_function['description'] = $parts[1];
		$daily_function['description'] = str_replace('</li>', '', $daily_function['description']);

		// Add to archive
		file_put_contents('archive.txt', "\n".$today.'|'.$daily_function['href'].'|'.$daily_function['function'].'|'.$daily_function['description'], FILE_APPEND | LOCK_EX);

	} else {

		// If all else fails, fail
		$fail = true;

	}

}

?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>PHP Function of the Day</title>
	<link rel="canonical" href="http://richjenks.com/dev/apps/php-fotd/">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<style>
		*, *:before, *:after {
			-webkit-box-sizing: border-box;
			   -moz-box-sizing: border-box;
					box-sizing: border-box;
		}
		body {
			font-family: Ubuntu, 'Helvetica Nueue', Helvetica, Arial, sans-serif;
			color: #222;
			background: #C6C6E2;
			margin: 0;
			padding: 1.6em;
			word-wrap: break-word;
		}
		.wrapper {
			max-width: 40em;
			margin: auto;
			background: #fff;
			padding: 1em 1.6em .5em 1.6em;
			box-shadow: 0 3px 4px rgba(0, 0, 0, .5);
			border-radius: 3px;
		}
		h1 {
			margin: 0;
		}
		p {
			margin: 0 0 1.6em 0;
		}
		code {
			font-family: 'Ubuntu Mono', monospace;
			font-size: 1.1em;
			background: #ccc;
			border-radius: 3px;
			padding: .1em .3em;
		}
		.faded {
			font-size: .8em;
			color: #888;
		}
		abbr {
			border-bottom: 1px dotted #888;
		}
	</style>
</head>
<body>
	<div class="wrapper">
		<h1>PHP Function of the Day</h1>
		<?php if (isset($daily_function['function']) && isset($daily_function['href']) && isset($daily_function['description'])):?>
			<p><time datetime="<?=date('Y-m-d')?>" class="faded"><?=date('l, F j<\s\u\p>S</\s\u\p> Y')?></time></p>
			<p><code><?=$daily_function['function']?></code></p>
			<p><?=$daily_function['description']?></p>
			<p><a href="<?=$base.$daily_function['href']?>" target="_blank"><?=$base.$daily_function['href']?></a></p>
		<?php endif;?>
		<?php if (isset($fail)):?>
			<p>Looks like something went wrong! <a href="<?=$canonical?>">Try again!</a></p>
		<?php endif;?>
		<hr>
		<p class="faded">Why did I make this? PHP currently has more than 5,000 functions, of which you probably only use couple of hundred regularly. Taking inspiration from the idea behind "Learn a Word a Day", PHP <abbr title="Function of the Day">FOTD</abbr> selects a new PHP function every day so 5,000 isn't so intimidating!</p>
	</div>

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-48459309-1', 'auto');
		ga('send', 'pageview');
	</script>
</body>
</html>
