<?php
/*
 * Данный код создан и распространяется по лицензии GPL v3
 * Разработчики:
 * Грибов Павел,
 * (добавляйте себя если что-то делали)
 * http://грибовы.рф
 */

// Запрещаем прямой вызов скрипта.
defined('WUO_ROOT') or die('Доступ запрещён');

if ($user->mode != 1) {
    die('<div class="alert alert-danger">У вас нет доступа в данный раздел!</div>');
}
 echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/jquery.datetimepicker.css'/>";
    $sql = "select fio as name,usersid as id from users_profile";
    
    $sts1 = "<select  class='chosen-select'  style='width:100%;' tabindex='40' name='redirect-sfrom' id='redirect-sfrom'>";
    $sts2 = "<select  class='chosen-select'  style='width:100%;' tabindex='40' name='redirect-sto' id='redirect-sto'>";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список менеджеров!" . mysqli_error($sqlcn->idsqlconnection));
    while ($row = mysqli_fetch_array($result)) {
        $id = $row["id"];
        $name = $row["name"];
        $sts1 = $sts1 . "<option value='$id'>$name</option>";
        $sts2 = $sts2 . "<option value='$id'>$name</option>";
    };
    $sts1 = $sts1. "</select>";
    $sts2 = $sts2. "</select>";

    
?>
<script src="js/jquery.datetimepicker.full.min.js"></script>
<div class="container-fluid">
    <div class="row-fluid">
		<table id="list2"></table>
		<div id="pager2"></div>	
    </div>
</div>    
<div id="redirect-dialog" title="Добавить/Изменить переадресацию">
	<div class="form-group">
		c: <input name="redirect_dtstart" id="redirect_dtstart" size=16 value=""> 
                по: <input name="sredirect_dtend" id="redirect_dtend" size=16 value=""><br />
                <label>Тип переадресации:</label>
				<div id="redtype" name="redtype">
					<select name="redirect-type" id="redirect-type">
						<option value=0>СМС</option>
                                                <option value=1>Задачи SBSS</option>
					</select>
				</div>                
                <label>С кого на кого переадресация:</label>
                <?php echo "С $sts1 на $sts2";?>
		<label>Комментарий к переадресации:</label>
                <textarea class="form-control" rows="3" name="redirect-comment" id="redirect-comment">
                    
                </textarea>
	</div>
</div>
<script>
function GetGrid(){
    jQuery("#list2").jqGrid({
	    url:route+'controller/server/lanbilling/redirect_add_edit.php',
	    datatype: "json",
	    colNames:['Id','Пользователь','Тип переадрессации','На кого','С','По','Действия'],
	    colModel:[   		
		    {name:'id',index:'id', width:25},
		    {name:'sfrom',index:'sfrom', width:50,editable:false},
		    {name:'redtype',index:'redtype', width:50,editable:false},
		    {name:'sto',index:'sto', width:50,editable:false},
		    {name:'dtstart',index:'dtstart', width:50,editable:false},
		    {name:'dtend',index:'dtend', width:50,editable:false},
		    {name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
	    ],
	    autowidth: true,			
	    rowNum:50,	   	
	    pager: '#pager2',
	    sortname: 'id',
	    scroll:1,
	    height: 400,
	viewrecords: true,
	sortorder: "asc",
	editurl:route+"controller/server/lanbilling/redirect_add_edit.php",
	caption:"Настройки переадресации"  
    });
    jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:true,search:false},{},{top: 0, left: 0, width: 500},{},{multipleSearch:false},{closeOnEscape:true} );
    jQuery("#list2").jqGrid('navButtonAdd',"#pager2",{caption:"<i class=\"fa fa-plus\"></i> Добавить ",                              
        title: "Добавить переадресацию",
        buttonicon: "none",
        position:"last",
	onClickButton:function(){
	       schedule_mode="add";
	       $("#redirect-title").val("");
	       $("#redirect_dtstart").val("");
	       $("#redirect_dtend").val("");
	       $("#redirect-type").val("[]")
	       $("#redirect-comment").val("");
               $("#redirect-dialog" ).dialog("open" );                             
	} 
    });            
};
$( document ).ready(function() {
    jQuery.datetimepicker.setLocale('ru');
    $("#redirect_dtstart").datetimepicker({
	format:'Y-m-d H:i:00',		    
	dayOfWeekStart: 1,		
    });
    $("#redirect_dtend").datetimepicker({
	format:'Y-m-d H:i:00',		    
	dayOfWeekStart: 1,		
    });
    $("#redirect-dialog" ).dialog({
      autoOpen: false,        
      resizable: false,      
      minHeight:"auto",      
      width: 640,
      modal: true,
      buttons: {
	    "Ok": function() {					
            if ($("#redirect_dtstart").val()!=""){
                if ($("#redirect-type").val()!=null){
                    $.post(route+"controller/server/lanbilling/redirect_add_edit.php",{
                        oper: "add",
                        id:jQuery("#list2").jqGrid('getGridParam','selrow'),
                        redirect_comment:$("#redirect-comment").val(),	    
                        redirect_dtstart:$("#redirect_dtstart").val(),
                        redirect_dtend:$("#redirect_dtend").val(),
                        redirect_type:$("#redirect-type").val(),
                        redirect_sfrom:$("#redirect-sfrom").val(),
                        redirect_sto:$("#redirect-sto").val()
                    },
                    function(data){
                        $("#redirect-dialog" ).dialog("close"); 
                        $().toastmessage('showWarningToast',data);      
                        jQuery("#list2").jqGrid().trigger('reloadGrid');                    				    				    
                    });			    
                } else {$().toastmessage('showWarningToast', 'Не выбран тип переадресации!');};   
            } else {$().toastmessage('showWarningToast', 'Дата начала должна быть заполнена!');};   
            }
        }
    });            
    UpdateChosen();    
    GetGrid();    
});

</script>    