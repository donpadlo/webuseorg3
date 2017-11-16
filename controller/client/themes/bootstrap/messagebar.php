<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
if (count($err) != 0) {
    echo "<div class='alert alert-error'>";
    echo "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
    for ($i = 0; $i < count($err); $i ++) {
        echo "<p>$err[$i]</p>";
    }
    ;
    echo "</div>";
}
if (count($ok) != 0) {
    echo "<div class='alert alert-success'>";
    echo "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
    for ($i = 0; $i < count($ok); $i ++) {
        echo "<p>$ok[$i]</p>";
    }
    ;
    echo "</div>";
}

?>
<div id="common_messages_for_all"></div>