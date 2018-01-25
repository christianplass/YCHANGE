<?php
/**
 *
 */

$generalContact = elgg_echo('ychange:contact:general');
$teachnicalIssues = elgg_echo('ychange:contact:technical');

echo <<<EOT
$generalContact - <a href="mailto:contact@ychange.eu">contact@ychange.eu</a></br>
$teachnicalIssues - <a href="mailto:webmaster@ychange.eu">webmaster@ychange.eu</a></br>
<br>
<a href="mailto:germany@ychange.eu">germany@ychange.eu</a><br>
<a href="mailto:switzerland@ychange.eu">switzerland@ychange.eu</a><br>
<a href="mailto:czechia@ychange.eu">czechia@ychange.eu</a><br>
<a href="mailto:estonia@ychange.eu">estonia@ychange.eu</a><br>
EOT;
