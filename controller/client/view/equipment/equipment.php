<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
include_once ("class/mod.php");
if (($user->mode == 1) or ($user->TestRoles('1,3'))) {
    echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/upload.css'>";
    ?>
<link href="js/jcrop/jquery.Jcrop.min.css" rel="stylesheet"
	type="text/css" />
<script>         
 var examples = [];    
 $(function(){
        var field = new Array("dtpost", "sorgid", "splaces","suserid","sgroupname","svendid","snomeid");//поля обязательные
        $("form").submit(function() {// обрабатываем отправку формы
            var error=0; // индекс ошибки
            $("form").find(":input").each(function() {// проверяем каждое поле в форме
                for(var i=0;i<field.length;i++){ // если поле присутствует в списке обязательных
                    if($(this).attr("name")==field[i]){ //проверяем поле формы на пустоту
                        if(!$(this).val()){// если в поле пустое
                            $(this).css('border', 'red 1px solid');// устанавливаем рамку красного цвета
                            error=1;// определяем индекс ошибки
                        }
                        else{
                            $(this).css('border', 'gray 1px solid');// устанавливаем рамку обычного цвета
                        }

                    }
                }
           })
            if(error==0){ // если ошибок нет то отправляем данные
                return true;
            }
            else {
            if(error==1) var err_text = "Не все обязательные поля заполнены!<hr>";
            $("#messenger").addClass("alert alert-error");
            $("#messenger").html(err_text);
            $("#messenger").fadeIn("slow");
            return false; //если в форме встретились ошибки , не  позволяем отослать данные на сервер.
            }
        })
    });
$(document).ready(function() { 
            // навесим на форму 'myForm' обработчик отлавливающий сабмит формы и передадим функцию callback.
            $('#myForm').ajaxForm(function(msg) {                 
                if (msg!="ok"){
                    $('#messenger').html(msg); 
                } else {
                    $( "#dtpost" ).datepicker( "destroy" );
                    $("#pg_add_edit" ).html("");
                    $("#pg_add_edit" ).dialog( "destroy" );
                    jQuery("#tbl_equpment").jqGrid().trigger('reloadGrid');                    
                };
                
            }); 
        }); 
    
</script>
<?php
    
    if (isset($_GET["step"])) {
        $step = $_GET['step'];
    } else {
        $step = "add";
    }
    if (isset($_GET["id"])) {
        $id = $_GET['id'];
    }
    // $step=$_GET["step"];
    // $id=$_GET["id"];
    
    echo "<script>orgid=''</script>";
    echo "<script>placesid=''</script>";
    echo "<script>userid=''</script>";
    echo "<script>vendorid=''</script>";
    echo "<script>groupid=''</script>";
    echo "<script>nomeid=''</script>";
    echo "<script>step='$step'</script>";
    
    if ($step == "edit") {
        $result = $sqlcn->ExecuteSQL("SELECT * FROM equipment WHERE id='$id';");
        while ($myrow = mysqli_fetch_array($result)) {
            $dtpost = MySQLDateTimeToDateTimeNoTime($myrow["datepost"]);
            echo "<script>dtpost='$dtpost'</script>";
            $dtendgar = MySQLDateTimeToDateTimeNoTime($myrow["dtendgar"]);
            echo "<script>dtendgar='$dtendgar'</script>";
            $orgid = $myrow["orgid"];
            echo "<script>orgid='" . $orgid . "'</script>";
            $placesid = $myrow["placesid"];
            echo "<script>placesid='" . $placesid . "'</script>";
            $userid = $myrow["usersid"];
            echo "<script>userid='" . $userid . "';</script>";
            $nomeid = $myrow["nomeid"];
            echo "<script>nomeid='" . $nomeid . "'</script>";
            $buhname = $myrow["buhname"];
            $cost = $myrow["cost"];
            $currentcost = $myrow["currentcost"];
            $sernum = $myrow["sernum"];
            $invnum = $myrow["invnum"];
            $shtrihkod = $myrow["shtrihkod"];
            $os = $myrow["os"];
            $mode = $myrow["mode"];
            $mapyet = $myrow["mapyet"];
            $comment = $myrow["comment"];
            $photo = $myrow["photo"];
            $ip = $myrow["ip"];
            $kntid = $myrow["kntid"];
        }
        
        $result = $sqlcn->ExecuteSQL("SELECT * FROM nome WHERE id='$nomeid';");
        while ($myrow = mysqli_fetch_array($result)) {
            $vendorid = $myrow["vendorid"];
            echo "<script>vendorid='" . $vendorid . "'</script>";
            $groupid = $myrow["groupid"];
            echo "<script>grouid='" . $groupid . "'</script>";
        }        
    } else {
        $dtpost = "";
        echo "<script>dtpost='$dtpost'</script>";
        $orgid = $cfg->defaultorgid;
        echo "<script>orgid=defaultorgid</script>";
        $placesid = 1;
        echo "<script>placesid='" . $placesid . "'</script>";
        $userid = $user->id;
        echo "<script>userid='" . $userid . "';</script>";
        $nomeid = 1;
        echo "<script>nomeid='" . $nomeid . "'</script>";
        $buhname = "";
        $cost = 0;
        $currentcost = 0;
        $sernum = "";
        $invnum = "";
        $shtrihkod = "";
        $os = 0;
        $mode = 0;
        $mapyet = 0;
        $comment = "";
        $photo = "";
        $ip = "";
        $groupid = 1;
        $kntid = "";
        $dtendgar = "";
        echo "<script>dtendgar='$dtendgar'</script>";
    }
    ?>
<div class="container-fluid">
	<div class="row">
		<div id="messenger"></div>
		<div id="tabs">
		    <?php
		    	$md=new Tmod;
			if ($md->IsActive("dop-pol")==1) {
		     ?>
		      <ul>
			<li><a href="#tabs-1">Основное</a></li>
			<li><a href="#tabs-2">Дополнительно</a></li>
		      </ul>
		     <?php };?>
		<div id="tabs-1" style="overflow-y: hidden;">
		<form role="form" id="myForm" enctype="multipart/form-data"
			action="index.php?route=/controller/server/equipment/equipment_form.php?step=<?php echo "$step&id=$id"; ?>"
			method="post" name="form1" target="_self">
			<div class="row-fluid" style="padding-right: 0px; padding-left: 0px;">
				<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right: 0px; padding-left: 0px;">
					<div class="form-group">
						<label>Когда/Куда/Кому:</label><br> <input class="form-control"
							name=dtpost id=dtpost value="<?php echo "$dtpost"; ?>">
						<div id=sorg>
							<select class='chosen-select' name=sorgid id=sorgid>
           <?php
    $result = $sqlcn->ExecuteSQL("SELECT * FROM org WHERE active=1 order by binary(name);");
    while ($myrow = mysqli_fetch_array($result)) {
        if (($user->mode == 1) or ($user->orgid == $myrow['id'])) {
            echo "<option value=" . $myrow["id"];
            if ($myrow['id'] == $orgid) {
                echo " selected";
            }
            ;
            $nm = $myrow['name'];
            echo ">$nm</option>";
        }
        ;
    }
    ;
    ?>
           </select>
						</div>
						<div id=splaces>идет загрузка..</div>
						<div id=susers>идет загрузка..</div>
						<input title="Серийный номер" class="form-control"
							placeholder="Серийный номер" name=sernum
							value="<?php echo "$sernum";?>"> <input title="Статический IP"
							class="form-control" placeholder="Статический IP" name=ip id=ip
							value="<?php echo "$ip"; ?>">
					</div>
				</div>
				<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right: 0px; padding-left: 0px;">
					<label>От кого/Что:</label><br> <select class='chosen-select'
						name=kntid id=kntid>
                <?php
    $morgs = GetArrayKnt();
    for ($i = 0; $i < count($morgs); $i ++) {
        $nid = $morgs[$i]["id"];
        $nm = $morgs[$i]["name"];
        if ($nid == $kntid) {
            $sl = " selected";
        } else {
            $sl = "";
        }
        ;
        echo "<option value=$nid $sl>$nm</option>";
    }
    ;
    ?>
     </select>
					<div id=sgroups>
        <?php
    $SQL = "SELECT * FROM group_nome WHERE active=1 ORDER BY name";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список групп!" . mysqli_error($sqlcn->idsqlconnection));
    $sts = "<select class='chosen-select' name=sgroupname id=sgroupname>";
    while ($row = mysqli_fetch_array($result)) {
        $sts = $sts . "<option value=" . $row["id"] . " ";
        if ($groupid == $row["id"]) {
            $sts = $sts . "selected";
        }
        ;
        $sts = $sts . ">" . $row["name"] . "</option>";
    }
    ;
    $sts = $sts . "</select>";
    echo $sts;
    ?>
    </div>
					<div id=svendors>идет загрузка..</div>
					<div id=snomes>идет загрузка..</div>
					<input title="Инвентарный номер" class="form-control"
						placeholder="Инвентарный номер" id=invnum name=invnum
						value="<?php echo "$invnum";?>">
					<button class="form-control" class="btn btn-primary" name=binv
						id=binv>Создать</button>
					<div class="checkbox">
						<label> <input type="checkbox" name="os" value="1"
							<?php if ($os=="1") {echo "checked";};?>> Основные ср-ва
						</label>
					</div>
				</div>
				<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right: 0px; padding-left: 0px;">
					<label>Гарантия до:</label><br> <input class="form-control"
						name=dtendgar id=dtendgar value="<?php echo "$dtendgar"; ?>"> <input
						title="Имя по бухгалтерии" class="form-control"
						placeholder="Имя по бухгалтерии" name=buhname
						value="<?php $buhname=htmlspecialchars($buhname);echo "$buhname";?>">
					<input title="Стоимость покупки" class="form-control" name=cost
						value="<?php echo "$cost";?>" placeholder="Начальная стоимость"> <input
						title="Текущая стоимость" class="form-control" name=currentcost
						value="<?php echo "$currentcost";?>"
						placeholder="Текущая стоимость"> <input title="Штрихкод"
						class="form-control" placeholder="Штрихкод" name=shtrihkod
						id=shtrihkod value="<?php echo "$shtrihkod";?>">
					<button class="form-control" class="btn btn-primary" name=bshtr
						id=bshtr>Создать</button>
					<div class="checkbox">
						<label> <input type="checkbox" name="mode" value="1"
							<?php if ($mode=="1") {echo "checked";};?>> Списано
						</label> <label> <input type="checkbox" name="mapyet" value="1"
							<?php if ($mapyet=="1") {echo "checked";};?>> Есть на карте
						</label>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right: 0px; padding-left: 0px;">
					<div id="userpic" class="userpic">
						<div class="js-preview userpic__preview thumbnail">
				    <?php
    if ($photo == "") {} else {
        echo "<img width=\"100%\" src=\"photos/$photo\" >";
    }
    ;
    
    ?>						
                                    
                                            </div>
						<div class="btn btn-success js-fileapi-wrapper">
							<div class="js-browse">
								<span class="btn-txt">Сменить фото</span> <input type="file"
									name="filedata" />
							</div>
							<div class="js-upload" style="display: none;">
								<div class="progress progress-success">
									<div class="js-progress bar"></div>
								</div>
								<span class="btn-txt">Загружаем</span>
							</div>
						</div>
					</div>
					<input name=picname id=picname TYPE=HIDDEN
						value="<?php echo "$photo";?>">
				</div>
				<div class="col-xs-8 col-md-8 col-sm-8" style="padding-right: 0px; padding-left: 0px;">
					<textarea class="form-control" name=comment rows="8"><?php echo "$comment";?></textarea>
<?php
    $view = false;
    if ($step == "edit") {
        if (($user->mode == 1) or ($user->TestRoles('1,5'))) {
$view = true;}        
    } else if (($user->mode == 1) or ($user->TestRoles('1,4'))) {$view = true;};
    if ($view == true) {
        echo '<div align=center>
		<input type="submit" class="form-control btn btn-primary" name="Submit" value="Сохранить">
	      </div>       ';
    };
    
    ?>
  </div>
			</div>
<?php
?>
		</form>
		</div>   
		    <?php
		    	$md=new Tmod;
			if ($md->IsActive("dop-pol")==1) {
		     ?>		    
		<div id="tabs-2">
		    	<table id="tbl_dop_pol"></table>
			<div id="pg_nav_dop_pol">			    
			</div>
		</div>
		    <?php
			};
		    ?>
		</div>
	</div>
</div>
<div id="popup" class="popup" style="display: none;">
	<div class="popup__body">
		<div class="js-img"></div>
	</div>
	<div style="margin: 0 0 5px; text-align: center;">
		<div class="js-upload btn btn_browse btn_browse_small">Загрузить</div>
	</div>
</div>
<script>
    <?php
	$md=new Tmod;
	if ($md->IsActive("dop-pol")==1) {
     ?>    
    	$("#tabs").tabs({
	      classes: {
		"ui-tabs": "highlight"
		},
	      heightStyle: "fill"
	});	
	<?php };?>
examples.push(function (){
						$('#userpic').fileapi({
							url: 'controller/server/common/uploadfile.php',
							accept: 'image/*',
							imageSize: { minWidth: 200, minHeight: 200 },
                                                        data: { 'geteqid': "" },
							elements: {
								active: { show: '.js-upload', hide: '.js-browse' },
								preview: {
									el: '.js-preview',
									width: 200,
									height: 200
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
												minSize: [200, 200],
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
					});
$(document).ready(function() {									     
    $("#dtendgar").datepicker();
    $("#dtendgar").datepicker( "option", "dateFormat", "dd.mm.yy");
    $("#dtpost").datepicker();
    $("#dtpost").datepicker( "option", "dateFormat", "dd.mm.yy");
    
    if (step!="edit") {$("#dtpost").datepicker( "setDate" , "0");} else {$("#dtpost").datepicker( "setDate" , dtpost);}
    if (step!="edit") {$("#dtendgar").datepicker( "setDate" , "0");} else {$("#dtendgar").datepicker( "setDate" , dtendgar);}
    $( "#sernum" ).focus();
    $( "#pg_add_edit" ).dialog({
        close: function() {$( "#dtpost" ).datepicker( "destroy" );}
    });    
     $("#binv").click(function(){
     var today = new Date();
       $("#invnum").val(today.getDay()+""+today.getMonth()+""+today.getFullYear()+""+today.getUTCHours()+""+today.getMinutes()+""+today.getSeconds());       
       return false;
    });    
    // правка Мазур
    $("#bshtr").click(function Calculate() {
        
        $.get('controller/server/common/getean13.php', function( data ) {
            $("#shtrihkod").val(data);
        });
        return false;
    });  
    // конец правки Мазур
    load_dop_pol();

}); 
    function load_dop_pol(){
	if (step=="add"){
	    $().toastmessage('showWarningToast', 'Дополнительные поля доступны только после сохранения ТМЦ!');
	} else {
	    chosenmanager=<?php echo "\"eq$id\"";?>;
	    jQuery('#tbl_dop_pol').jqGrid({
		    height: 100,
		    autowidth: true,
		    url: route + 'controller/server/common/get_dop_pol_users.php&chosenmanager='+chosenmanager,
		    datatype: 'json',
		    colNames: ['Id', 'Имя','Идентификатор','Комментарий', 'Действия'],
		    colModel: [
			    {name: 'id', index: 'id', width: 55, fixed: true},
			    {name: 'name', index: 'name', width: 100,editable:true},
			    {name: 'name_id', index: 'name_id', width: 100,editable:false,hidden:true},
			    {name: 'comment', index: 'comment', width: 100,editable:false},
			    {name: 'myac', width: 80, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: true}}
		    ],
		    rowNum: 5,
		    pager: '#pg_nav_dop_pol',
		    sortname: 'id',
		    scroll: 1,
		    height: "auto",
		    viewrecords: true,
		    sortorder: 'asc',
		    caption: 'Дополнительные поля в разрезе ТМЦ',
		    editurl:route + 'controller/server/common/get_dop_pol_users.php&chosenmanager='+chosenmanager,
	    }).navGrid('#pg_nav_dop_pol', {add: false, edit: true, del: true, search: false}, {}, {top: 0, left: 0, width: 500}, {}, {multipleSearch: false}, {closeOnEscape: true});    	
	    $("#tbl_dop_pol").setGridWidth($("#myForm").width());    
	};
    };

    function UpdateChosen(){
        for (var selector in config) {
            $(selector).chosen({ width: '100%' });
            $(selector).chosen(config[selector]);
        };        
    };
    function GetListPlaces(orgid,placesid){
       url= route + "controller/server/common/getlistplaces.php?orgid="+orgid+"&placesid="+placesid;
       $("#splaces").load(url);
       UpdateChosen();
    };
    function GetListUsers(orgid,userid){
       //alert(userid+"!!");
     $("#susers").load("controller/server/common/getlistusers.php?orgid="+orgid+"&userid="+userid);
     UpdateChosen();
    };
    function GetListGroups(groupid){
      $("#sgroups").load(route + "controller/server/common/getlistgroupname.php?groupid="+groupid);
      UpdateChosen();
    };
    function GetListNome(groupid,vendorid,nmd){
	 $.ajax({
     			url: "controller/server/common/getlistnomes.php?groupid="+groupid+"&vendorid="+vendorid+"&nomeid="+nmd,
     			success: function(answ){
       			$("#snomes").html(answ);
                        UpdateChosen();
       			//GetListNome($("#sgroupname :selected").val(),$("#svendid :selected").val());
		        }
    		});
    };

    function GetListVendors(groupid,vendorid){
	 $.ajax({
     			url: "controller/server/common/getlistvendors.php?groupid="+groupid+"&vendorid="+vendorid,
     			success: function(answ){
       			$("#svendors").html(answ);
       			GetListNome($("#sgroupname :selected").val(),$("#svendid :selected").val(),nomeid);
			//      $("#svendid").click(function(){
                        $('#svendid').on('change', function(evt, params) {                                                  
			          $("#snomes").html="идет загрузка.."; // заглушка. Зачем?? каналы счас быстрые
				      GetListNome($("#sgroupname :selected").val(),$("#svendid :selected").val());
				    });

		        }
    		});
    };

       // Заполняем инвентарник и штрихкод    
       function getRandomNum(lbound, ubound) {
       return (Math.floor(Math.random() * (ubound - lbound)) + lbound);};

    
    // обрабатываем нажатие кнопки выбора организации
    
    //$("#sorgid").click(function(){
  $('#sorgid').on('change', function(evt, params) {        
      //  alert("!");
      $("#splaces").html="идет загрузка.."; // заглушка. Зачем?? каналы счас быстрые
      $("#susers").html="идет загрузка..";
      GetListPlaces($("#sorgid :selected").val(),''); // перегружаем список помещений организации
      GetListUsers($("#sorgid :selected").val(),'') // перегружаем пользователей организации
    });
    // выбираем производителя по группе
    //$("#sgroupname").click(function(){        
    $('#sgroupname').on('change', function(evt, params) {                
      console.log("--обработка выбора группы номенклатуры");  
      $("#svendors").html="идет загрузка.."; // заглушка. Зачем?? каналы счас быстрые
      GetListVendors($("#sgroupname :selected").val()); // перегружаем список vendors
    });

         // загружаем места  
         GetListPlaces($("#sorgid :selected").val(),placesid);
         // загружаем пользователей
	 //alert(userid)
         GetListUsers( $("#sorgid :selected").val(),userid);
         // загружаем производителя
         GetListVendors($("#sgroupname :selected").val(),vendorid);
         // номенклатура	 
         GetListNome($("#sgroupname :selected").val(),$("#svendid :selected").val(),nomeid);   
  </script>
<script>
		var FileAPI = {
			  debug: true
			, media: true
			, staticPath: './FileAPI/'
		};
</script>
<script src="js/FileAPI/FileAPI.min.js"></script>
<script src="js/FileAPI/FileAPI.exif.js"></script>
<script src="js/jquery.fileapi.js"></script>
<script src="js/jcrop/jquery.Jcrop.min.js"></script>
<script src="js/statics/jquery.modal.js"></script>


<script>
            
     for (var selector in config) {
      $(selector).chosen(config[selector]);
    };            
		jQuery(function ($){
			var $blind = $('.splash__blind');

			$('.splash')
				.mouseenter(function (){
					$('.splash__blind', this)
						.animate({ top: -10 }, 'fast', 'easeInQuad')
						.animate({ top: 0 }, 'slow', 'easeOutBounce')
					;
				})
				.click(function (){
					$(this).off();

					if( !FileAPI.support.media ){
						$blind.animate({ top: -$(this).height() }, 'slow', 'easeOutQuart');
					}

					FileAPI.Camera.publish($('.splash__cam'), function (err, cam){
						if( err ){
							alert("Unfortunately, your browser does not support webcam.");
						} else {
							$blind.animate({ top: -$(this).height() }, 'slow', 'easeOutQuart');
						}
					});
				})
			;


			$('.example').each(function (){
				var $example = $(this);

				

				$('<div></div>')
					.append('<div data-code="javascript"><pre><code>'+ $.trim(_getCode($example.find('script'))) +'</code></pre></div>')
					.append('<div data-code="html" style="display: none"><pre><code>'+ $.trim(_getCode($example.find('.example__left'), true)) +'</code></pre></div>')
					.appendTo($example.find('.example__right'))
					.find('[data-code]').each(function (){
						/** @namespace hljs -- highlight.js */
						if( window.hljs && (!$.browser.msie || parseInt($.browser.version, 10) > 7) ){
							this.className = 'example__code language-' + $.attr(this, 'data-code');
							hljs.highlightBlock(this);
						}
					})
				;
			});


			$('body').on('click', '[data-tab]', function (evt){
				evt.preventDefault();

				var el = evt.currentTarget;
				var tab = $.attr(el, 'data-tab');
				var $example = $(el).closest('.example');

				$example
					.find('[data-tab]')
						.removeClass('active')
						.filter('[data-tab="'+tab+'"]')
							.addClass('active')
							.end()
						.end()
					.find('[data-code]')
						.hide()
						.filter('[data-code="'+tab+'"]').show()
				;
			});


			function _getCode(node, all){
				var code = FileAPI.filter($(node).prop('innerHTML').split('\n'), function (str){ return !!str; });
				if( !all ){
					code = code.slice(1, -2);
				}

				var tabSize = (code[0].match(/^\t+/) || [''])[0].length;

				return $('<div/>')
					.text($.map(code, function (line){
						return line.substr(tabSize).replace(/\t/g, '   ');
					}).join('\n'))
					.prop('innerHTML')
						.replace(/ disabled=""/g, '')
						.replace(/&amp;lt;%/g, '<%')
						.replace(/%&amp;gt;/g, '%>')
				;
			}


			// Init examples
			FileAPI.each(examples, function (fn){
				fn();
			});
		});
	</script>

<?php
} else {
    ?>
<div class="alert alert-error">У вас нет доступа в данный раздел!</div>
<?php
}
;
?>