var addOptions={
    top: 0, left: 0, width: 500,
    addCaption: "Добавить запись",
    closeAfterAdd: true,
    closeOnEscape: true
};
jQuery("#list2").jqGrid({
   	url:'controller/server/knt/libre_knt.php?org_status=list',
	datatype: "json",
   	colNames:[' ','Id','Имя','Инн','Кпп','Пок','Прод','Контролировать','ERPCode','Комментарий','Действия'],
   	colModel:[
   		{name:'active',index:'active', width:20,search: false},
   		{name:'id',index:'id', width:55,search: false,hidden:true},
   		{name:'name',index:'name', width:200,editable:true},
                {name:'INN',index:'INN', width:100,editable:true},
                {name:'KPP',index:'KPP', width:100,editable:true,hidden:true},
                {name:'bayer',index:'bayer', width:50,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'},search: false,hidden:true},
                {name:'supplier',index:'supplier', width:50,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'},search: false,hidden:true},
                {name:'dog',index:'dog', width:50,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'},search: false},
                {name:'ERPCode',index:'ERPCode', width:100,editable:true,search: false,hidden:true},
   		{name:'comment',index:'comment', width:200,editable:true},
		{name:'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true},search: false}
   	],
	autowidth: true,		        
   	pager: '#pager2',
   	sortname: 'id',        
	scroll:1,
        viewrecords: true,
        sortorder: "asc",
        editurl:"controller/server/knt/libre_knt.php?org_status=edit",
        caption:"Справочник контрагентов",        
onSelectRow: function(ids) { 
    
$("#list4").css('visibility','hidden');
//$("#uploadButton").css('visibility','hidden');
$("#simple-btn").css('visibility','hidden');
//$("#list3").html("");
jQuery("#list3").jqGrid('setGridParam',{url:"controller/server/knt/getcontrakts.php?idknt="+ids});
jQuery("#list3").jqGrid('setGridParam',{editurl:"controller/server/knt/getcontrakts.php?idknt="+ids});
jQuery("#list3").jqGrid({
   	url:'controller/server/knt/getcontrakts.php?idknt='+ids,
	datatype: "json",
   	colNames:[' ','Id','Номер','Название','Начало','Конец','Рабочий','Комментарий','Действия'],
   	colModel:[
   		{name:'active',index:'active', width:20},
   		{name:'id',index:'id', width:55,hidden:true},
                {name:'num',index:'num', width:50,editable:true},
   		{name:'name',index:'name', width:100,editable:true},
                {name:'datestart',index:'datestart', width:100,editable:true,editoptions:
                        {
                                dataInit:function(el){
                                     $(el).datepicker({
                                        dateFormat:'dd.mm.yy',
                                        weekStart: 1,
                                        dayNamesMin: ["Вс","По","Вт","Ср","Чт","Пт","Сб"],
                                        monthNames: ["Январь","Февраль","Март","Апрель","Мая","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"]                                         
                                     });
                                    },
			},
                 },
                {name:'dateend',index:'dateend', width:100,editable:true,editoptions:
                        {
                                dataInit:function(el){
                                     $(el).datepicker({
                                        dateFormat:'dd.mm.yy',
                                        weekStart: 1,
                                        dayNamesMin: ["Вс","По","Вт","Ср","Чт","Пт","Сб"],
                                        monthNames: ["Январь","Февраль","Март","Апрель","Мая","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"]                                         
                                     });
                                    },
			},                    },
                {name:'work',index:'work', width:50,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'}},
                {name:'comment',index:'comment', width:200,editable:true},
		{name:'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true},search: false}
   	],
        
	autowidth: true,		
   	pager: '#pager3',
   	sortname: 'id',
	scroll:1,
    viewrecords: true,
    sortorder: "asc",
    editurl:'controller/server/knt/getcontrakts.php?idknt='+ids,
    caption:"Заключенные договора",
    onSelectRow: function(ids) {                        
                    $("#list4").css('visibility','visible');
                    //$("#uploadButton").css('visibility','visible');
                    $("#simple-btn").css('visibility','visible');                    
                    $('#simple-btn').fileapi('data', {'contractid':ids});
                    //$("#loadfiles").html('<div id="uploadButton" class="button">Загрузить</div>');
                    jQuery("#list4").jqGrid('setGridParam',{url:route+"controller/server/knt/getfilescontrakts.php&idcontract="+ids});
                    jQuery("#list4").jqGrid('setGridParam',{editurl:route+"controller/server/knt/getfilescontrakts.php&idcontract="+ids});
                    jQuery("#list4").jqGrid({
                        url:route+'controller/server/knt/getfilescontrakts.php&idcontract='+ids,
                        datatype: "json",
                        colNames:['Id','Имя файла','Действия'],
                        colModel:[
                            {name:'id',index:'id', width:55,hidden:true},
                            {name:'filename',index:'filename', width:100},
                            {name:'myac',  width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true},search: false}
   	],
        	autowidth: true,		                
                height:100,
                pager: '#pager4',
                sortname: 'id',
                scroll:1,
                viewrecords: true,
                sortorder: "asc",
                editurl:route+'controller/server/knt/getfilescontrakts.php&idcontract='+ids,
                caption:"Прикрепленные файлы"
                }).trigger('reloadGrid');	
                jQuery("#list4").jqGrid('navGrid','#pager4',{edit:false,add:false,del:false,search:false});
    }    
}).trigger('reloadGrid');	
jQuery("#list3").jqGrid('navGrid','#pager3',{edit:true,add:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
}

});
jQuery("#list2").jqGrid('setGridHeight',$(window).innerHeight()/3);
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
jQuery("#list2").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#list2").jqGrid('bindKeys',''); 
jQuery("#list2").jqGrid('navButtonAdd','#pager2',{
    caption: "<i class=\"fa fa-tag\" aria-hidden=\"true\"></i>",
    title: "Выбор колонок",
    buttonicon: 'none',
    onClickButton : function (){
        jQuery("#list2").jqGrid('columnChooser',{
            "done": function(perm) {
                             if (perm) {
                                 this.jqGrid("remapColumns", perm, true);
                                 var outerwidth = $('#grid').width();
                                 $('#list2').setGridWidth(outerwidth);                                 
                             }
                         }
        });        
    }
});


$('#simple-btn').fileapi({
                        url: 'controller/server/common/uploadanyfiles.php',
                        data: {'geteqid':0},
                        multiple: true,
                        maxSize: 20 * FileAPI.MB,
                        autoUpload: true,
                         onFileComplete: function (evt, uiEvt){                                                              
                             if (uiEvt.result.msg!="error") {
                                 jQuery("#list4").jqGrid().trigger('reloadGrid');
                             } else {alert("Ошибка загрузки файла!");};                             
                          },                         
                          elements: {
                                size: '.js-size',
                                active: { show: '.js-upload', hide: '.js-browse' },
                                progress: '.js-progress'
                            }
});   

