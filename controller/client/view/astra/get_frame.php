<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

include_once ("../../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../../class/config.php");		// загружаем классы настроек
include_once("../../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../../class/employees.php");		// загружаем классы работы с профилем пользователя


// загружаем все что нужно для работы движка

include_once("../../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../../inc/functions.php");		// загружаем функции
include_once("../../../../inc/login.php");		// логинимся

echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/upload.css'>";    

$frame_id=GetDef("frame_id");

$sql="SELECT * FROM astra_info where id='$frame_id'";
$result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу выбрать список серверов LanBilling!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {
  $tbody=$row["tbody"];   
  $tframe=$row["tframe"];   
  $pic_file=$row["pic_file"];   
  $muz_file=$row["muz_file"];   
};


?>
<link href="js/jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>
<script src="js/FileAPI/FileAPI.min.js"></script>
<script src="js/FileAPI/FileAPI.exif.js"></script>
<script src="js/jquery.fileapi.js"></script>
<script src="js/jcrop/jquery.Jcrop.min.js"></script>
<script src="js/statics/jquery.modal.js"></script>
<div id="messenger"></div>
<form id="myForm" class="well form-inline" ENCTYPE="multipart/form-data" action="controller/server/astra/astra_save_form.php?frame_id=<?php echo "$frame_id"; ?>" method="post" name="form1" target="_self">
<label>Титры:</label>
<textarea rows="3" class="span12" name="tbody" id="tbody">
<?php echo "$tbody";?>
</textarea></br>    
<span class="help-block">Время жизни фрейма:</span>        
<input class="span4" name="tframe" type="text" id="tframe" value="<?php echo "$tframe";?>">
<div align=center><input type="submit" class="btn btn-primary" name="Submit" value="Сохранить"></div>       
</form>
<label>Фото:</label>

                                        <div id="userpic">
                                            <div class="js-preview span12">
                                                <?php
                                                if ($pic_file!="") {echo "<img src='photos/$pic_file'>";};                                                
                                                ?>        
                                            </div>
						<div class="btn btn-success js-fileapi-wrapper">
							<div class="js-browse">
								<span class="btn-txt">Сменить фото</span>
								<input type="file" name="filedata"/>
							</div>                                                    
							<div class="js-upload" style="display: none;">                                                            
								<div class="progress progress-success"><div class="js-progress bar"></div></div>
								<span class="btn-txt">Загружаем</span>
							</div>
						</div>
					</div>         
    <input name=picname id=picname TYPE=HIDDEN value="<?php echo "$photo";?>">    
<label>Звук:</label>
<div id="mz_file" name="mz_file">
<?php
 if ($muz_file!=""){
     echo "$muz_file";     
 };
?>
</div>    
<div id="bt_muz_del" name="bt_muz_del" <?php if ($muz_file==""){echo 'style="visibility:hidden"';} else {echo 'style="visibility:visible"';} ?>>
    <button onclick="DelMuz()">Удалить</button>
</div>    
<div align="center" id="simple-btn" class="btn btn-primary js-fileapi-wrapper">
    <div class="js-browse" align="center">
        <span class="btn-txt">Загрузить файл</span>
        <input type="file" name="filedata">
    </div>
    <div class="js-upload" style="display: none">
    <div class="progress progress-success"><div class="js-progress bar"></div></div>
    <span align="center" class="btn-txt">Загружаю (<span class="js-size"></span>)</span>
    </div>
</div> 

<div id="popup" class="popup" style="display: none;">
		<div class="popup__body"><div class="js-img"></div></div>
		<div style="margin: 0 0 5px; text-align: center;">
			<div class="js-upload btn btn_browse btn_browse_small">Загрузить</div>
		</div>
	</div>
 <script>
$(document).ready(function() { 
            // навесим на форму 'myForm' обработчик отлавливающий сабмит формы и передадим функцию callback.
            $('#myForm').ajaxForm(function(msg) {                 
              if (msg=="") {$('#messenger').html("Успешно сохранено!");} else 
              {$('#messenger').html(msg);};  
            }); 
        });     
     
function DelMuz(){
  $("#bt_muz_del").css('visibility','hidden');                      
  $("#mz_file").load("controller/server/astra/delmuz.php?frame_id="+<?php echo "$frame_id";?>);
};    
$('#simple-btn').fileapi({
                        url: 'controller/server/astra/uploadmp3file.php',
                        data: { 'frame_id': "<?php echo "$frame_id";?>" },
                        multiple: true,
                        maxSize: 20 * FileAPI.MB,
                        autoUpload: true,
                         onFileComplete: function (evt, uiEvt){                                                              
                             if (uiEvt.result.msg!="error") {
                                 $("#mz_file").html(uiEvt.result.msg);
                                 $("#bt_muz_del").css('visibility','visible');                      
                             };                             
                          },                         
                          elements: {
                                size: '.js-size',
                                active: { show: '.js-upload', hide: '.js-browse' },
                                progress: '.js-progress'
                            }
});     
$('#userpic').fileapi({
                url: 'controller/server/astra/uploadpicfile.php',
                accept: 'image/*',
                imageSize: { minWidth: 960, minHeight: 540 },
                data: { 'frame_id': "<?php echo "$frame_id";?>" },
                elements: {
                        active: { show: '.js-upload', hide: '.js-browse' },
                        preview: {
                                el: '.js-preview',
                                width: 384,
                                height: 216
                        },
                        progress: '.js-progress'
                },
                onFileComplete: function (evt, uiEvt){                                                              
                      $("#picname").val(uiEvt.result.msg);
                },                                                        
                onSelect: function (evt, ui){
                        var file = ui.files[0];

                        if( file ){
                                $('#popup').modal({
                                        closeOnEsc: true,
                                        closeOnOverlayClick: false,
                                        onOpen: function (overlay){
                                                $(overlay).on('click', '.js-upload', function (){
                                                        $.modal().close();
                                                        $('#userpic').fileapi('upload');
                                                });

                                                $('.js-img', overlay).cropper({
                                                        file: file,
                                                        bgColor: '#fff',
                                                        maxSize: [$(window).width()-100, $(window).height()-100],
                                                        minSize: [384, 216],
                                                        selection: '90%',
                                                        onSelect: function (coords){
                                                                $('#userpic').fileapi('crop', file, coords);
                                                        }
                                                });
                                        }
                                }).open();
                        }
                }
});
</script>    