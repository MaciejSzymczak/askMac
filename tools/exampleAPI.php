<?php
$txt = isset($_POST['txt']) ? ($_POST['txt']) : $_GET['txt'];
?>

<?php
echo $txt.PHP_EOL ."National chars: ��ka ���";
?>