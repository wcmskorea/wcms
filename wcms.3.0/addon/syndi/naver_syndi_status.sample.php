<?php
require_once "../../_config.php";

include 'libs/SyndicationStatus.class.php';

$mydomain = __HOST__;

$oStatus = new SyndicationStatus;
$oStatus->setSite($mydomain);
$result = $oStatus->request();

print_r($result);
?>
