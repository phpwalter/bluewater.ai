<?php
if (function_exists('phpversion')) {
    echo '<h2>' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '</h2>';
    echo '<h2>' . __FILE__ . '</h2>';
    phpinfo();
}
?>
