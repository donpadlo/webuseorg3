<?php
if (count($cfg->navbar) > 0) {
    ?>
<ul class="breadcrumb" id="breadcrumb">
<?php
    for ($i = count($cfg->navbar) - 1; $i >= 0; $i --) {
        $ntxt = $cfg->navbar[$i];
        echo "<li>$ntxt <span class='divider'>/</span></li>";
    }
    ;
    echo "<script> document.title = '$ntxt';</script>";
    echo '<li><button onclick="AddToNavBarQuick(\'' . $cfg->navbar[0] . '\');" title="Прибить страницу в быстрых ссылках" type=\'button\' class=\'btn btn-default navbar-btn \'><i class="fa fa-link"></i></button></li>';
    ?>        
</ul>
<?php
}
;
?>