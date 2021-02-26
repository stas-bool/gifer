# gifer
Generate gif animation from text


Usage:<br>
`composer require stas-bool/gifer`
```
<?php

require_once __DIR__.'/vendor/autoload.php';

$text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

$gif = Gifer\Gifer::createGif(
	__DIR__.'/NotoSans-Regular.ttf', //font file
	$text, //text
	'#000000', //background color in hex format
	'#FFFFFF', //font color in hex format
	5 //speed 1(slowest) - 10(fastest),
);
file_put_contents('/tmp/test.gif', $gif);
```
Result:<br>
![Result gif](https://biche-ool.ru/pub/test.gif)
