<?php

function encodep($text) {
     $data = utf8_encode($text);
     $compressed = gzdeflate($data, 9);
     return encode64($compressed);
}

function encode6bit($b) {
     if ($b < 10) {
          return chr(48 + $b);
     }
     $b -= 10;
     if ($b < 26) {
          return chr(65 + $b);
     }
     $b -= 26;
     if ($b < 26) {
          return chr(97 + $b);
     }
     $b -= 26;
     if ($b == 0) {
          return '-';
     }
     if ($b == 1) {
          return '_';
     }
     return '?';
}

function append3bytes($b1, $b2, $b3) {
     $c1 = $b1 >> 2;
     $c2 = (($b1 & 0x3) << 4) | ($b2 >> 4);
     $c3 = (($b2 & 0xF) << 2) | ($b3 >> 6);
     $c4 = $b3 & 0x3F;
     $r = "";
     $r .= encode6bit($c1 & 0x3F);
     $r .= encode6bit($c2 & 0x3F);
     $r .= encode6bit($c3 & 0x3F);
     $r .= encode6bit($c4 & 0x3F);
     return $r;
}

function encode64($c) {
     $str = "";
     $len = strlen($c);
     for ($i = 0; $i < $len; $i+=3) {
            if ($i+2==$len) {
                  $str .= append3bytes(ord(substr($c, $i, 1)), ord(substr($c, $i+1, 1)), 0);
            } else if ($i+1==$len) {
                  $str .= append3bytes(ord(substr($c, $i, 1)), 0, 0);
            } else {
                  $str .= append3bytes(ord(substr($c, $i, 1)), ord(substr($c, $i+1, 1)),
                      ord(substr($c, $i+2, 1)));
            }
     }
     return $str;
}
?>

<?php
$txt = isset($_POST['txt']) ? ($_POST['txt']) : $_GET['txt'];
$encode = encodep($txt);
$imageUrl= "http://www.plantuml.com/plantuml/png/{$encode}";
?>

<?php
//echo '<!DOCTYPE html>';
//echo '<html>';
//echo '<head>';
//echo '<title>Image Embedding Example</title>';
//echo '</head>';
//echo '<body>';
//echo '<h4>' . $imageUrl . '</h4>';
echo '<img src="' . $imageUrl . '" alt="PlantUML Diagram">';
//echo '</body>';
//echo '</html>';
?>