<?php
/* @var $this \yii\web\View */
/* @var $panels \yii\debug\Panel[] */
/* @var $tag string */
/* @var $position string */

use yii\helpers\Url;

$minJs = <<<EOD
document.getElementById('yii-debug-toolbar').style.display = 'none';
document.getElementById('yii-debug-toolbar-min').style.display = 'block';
if (window.localStorage) {
    localStorage.setItem('yii-debug-toolbar', 'minimized');
}
EOD;

$maxJs = <<<EOD
document.getElementById('yii-debug-toolbar-min').style.display = 'none';
document.getElementById('yii-debug-toolbar').style.display = 'block';
if (window.localStorage) {
    localStorage.setItem('yii-debug-toolbar', 'maximized');
}
EOD;

$firstPanel = reset($panels);
$url = $firstPanel->getUrl();
?>
