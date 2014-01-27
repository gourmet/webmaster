<?php
ob_start();
phpinfo();
$info = ob_get_contents();
ob_end_clean();

$info = str_replace(array('module_Zend Optimizer', '<hr />'), array('module_Zend_Optimizer', ''), $info);
$info = preg_replace("%<h1><a.[^>]*>PHP Credits</a></h1>%ms", '', $info);

echo $info;
