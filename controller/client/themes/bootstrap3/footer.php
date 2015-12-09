<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
$time_end = microtime(true);
$time = $time_end - $time_start;
?>
      <footer class="navbar">
          <div class="row-fluid container-fluid">
                <div class="span12" align="center">
                    <p>&copy; <a href="http://грибовы.рф" target="_blank"> 2011-<?php echo date("Y");?></a>. Собрано за <?php echo "$time";?>сек.</p>
                </div>
              </div>
      </footer>
</body>
</html>