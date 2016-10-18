<?php
/**
 * Configration
 */
require_once "./_config.php";

echo('<!DOCTYPE html>').PHP_EOL;
echo('<html lang="ko">').PHP_EOL;
echo('<head>').PHP_EOL;
echo('<title>'.$display->title.'</title>').PHP_EOL;
echo('<meta http-equiv="content-type" content="text/html; charset='.$cfg['charset'].'" />').PHP_EOL;
echo('<meta http-equiv="content-style-type" content="text/css" />').PHP_EOL;
echo('<meta http-equiv="imagetoolbar" content="no" />').PHP_EOL;
echo('<meta name="description" content="'.$display->description.'" />').PHP_EOL;
echo('<meta name="keywords" content="'.$display->keyword.'" />').PHP_EOL;
echo('<meta name="robots" content="ALL" />').PHP_EOL;
echo('<meta name="Generator" content="wcms'.$cfg['version'].'" />').PHP_EOL;
echo('<meta name="publisher" content="(주)10억홈피" />').PHP_EOL;
?>
</head>
<body>
</body>
</html>