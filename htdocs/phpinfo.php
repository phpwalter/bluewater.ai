<html lang="en">
<head><title>Test Page of new Virtual Domain</title></head>

<style>
    h2 {
        text-align: center;
    }
</style>
<body>

<?php


// var_dump(opcache_get_configuration());

echo '<h2>http://' . $_SERVER['SERVER_NAME'] . '</h2>';

echo '<h2>' . __FILE__ . '</h2>';

phpinfo();

?>

</body>
</html>
