<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

if (($user->mode==1) or ($user->mode==0)){
 $uidview = GetDef('uidview');
 if ($uidview==""){$uidview=$user->id;}
 echo "<script>uidview=".$uidview.";</script>";
?>
<div class="content">
<div class="row-fluid">    
<div id="calhead" style="padding-left:1px;padding-right:1px;">          
            <div class="cHead"><div class="ftitle">Мой календарь</div>
            <div id="loadingpannel" class="ptogtitle loadicon" style="display: none;">Loading data...</div>
             <div id="errorpannel" class="ptogtitle loaderror" style="display: none;">Не могу загрузить данные. Попробуйте позже.</div>
            </div>          
            
            <div id="caltoolbar" class="ctoolbar">
              <div id="faddbtn" class="fbutton">
                <div><span title='Создать новую задачу' class="addcal">Новая задача</span></div>
            </div>
            <div class="btnseparator"></div>
             <div id="showtodaybtn" class="fbutton">
                <div><span title='Вернуться в сегодня ' class="showtoday">Сегодня</span></div>
            </div>
              <div class="btnseparator"></div>

            <div id="showdaybtn" class="fbutton">
                <div><span title='День' class="showdayview">День</span></div>
            </div>
              <div  id="showweekbtn" class="fbutton fcurrent">
                <div><span title='Неделя' class="showweekview">Неделя</span></div>
            </div>
              <div  id="showmonthbtn" class="fbutton">
                <div><span title='Месяц' class="showmonthview">Месяц</span></div>

            </div>
            <div class="btnseparator"></div>
              <div  id="showreflashbtn" class="fbutton">
                <div><span title='Обновить просмотр' class="showdayflash">Обновить</span></div>
                </div>
             <div class="btnseparator"></div>
            <div id="sfprevbtn" title="Предыдущие"  class="fbutton">
              <span class="fprev"></span>

            </div>
            <div id="sfnextbtn" title="Последующие" class="fbutton">
                <span class="fnext"></span>
            </div>
            <div class="fshowdatep fbutton">
                    <div>
                        <input type="hidden" name="txtshow" id="hdtxtshow" />
                        <span id="txtdatetimeshow">Выбрать дату</span>

                    </div>
            </div>
            
            <div class="clear"></div>
            </div>
      </div>
      <div style="padding:1px;">

        <div class="t1 chromeColor">
            &nbsp;</div>
        <div class="t2 chromeColor">
            &nbsp;</div>
        <div id="dvCalMain" class="calmain printborder">
            <div id="gridcontainer" style="overflow-y: visible;">
            </div>
        </div>
        <div class="t2 chromeColor">

            &nbsp;</div>
        <div class="t1 chromeColor">
            &nbsp;
        </div>   
        </div>    
    </div>      
  </div>      

 <script type="text/javascript" src="controller/client/js/myical.js"></script>
<?php
}
 else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}

?>