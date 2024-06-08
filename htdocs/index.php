
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/>
    <title>Apache HTTP Server Version 2.4 [WinLux]</title>
    <link href="https://httpd.apache.org/docs/2.4/style/css/manual.css" rel="stylesheet" media="all" type="text/css" title="Main stylesheet" />
</head>

<body id="manual-page">
<div id="page-header">
    <p class="menu">&nbsp </p>
    <p class="apache">Apache HTTP Server Version 2.4 [WinLux]</p>
    <img alt="" src="https://httpd.apache.org/docs/2.4/images/feather.png">
</div>
<div id="path">
    &nbsp
</div>

<div id="preamble"><h1>Congratulations!</h1>

    <p>This page means you've set up Apache properly.</p>

    <p id="content"></p>

</div>

<script>
    var contentDiv = document.getElementById("content");

    // Fetch the content of check_php.php using Fetch API
    fetch('check.php')
        .then(response => response.text())
        .then(data => {
            // Check the response from PHP file and display messages accordingly
            if (data.includes('function_exists')) {
                contentDiv.innerHTML = "<p>Because a scripting language has not yet been configured, I can't tell you anything about your setup.</p>";
            } else {
                contentDiv.innerHTML = "<p> And PHP is working!</p>" + data;
            }
        })
        .catch(error => console.error('Error:', error));
</script>

</body>

</html>




