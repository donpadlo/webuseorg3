var lastsel3;
jQuery("#list_rep").jqGrid({
   	url:'controller/server/equipment/repair.php?step=list&id='+repid+'&eqid='+repid,
	datatype: "json",
   	colNames:['Id','Контрагент','ТМЦ','Дата начала','Дата конца','Стоимость','Комментарий','Статус'],
   	colModel:[
   		{name:'rpid',index:'rpid', width:35},
                {name:'namekont',index:'namekont', width:100,editable:false},
                {name:'namenome',index:'namenome', width:100,editable:false},
                {name:'dt',index:'dt', width:80,editable:true,sorttype:"date"},
                {name:'dtend',index:'dtend', width:80,editable:true, sorttype:"date"},
                {name:'cost',index:'cost', width:80,editable:true},
                {name:'comment',index:'comment', width:80,editable:true},
                {name:'rstatus',index:'rstatus', width:100,editable:true,edittype:"select",editoptions:{value:"1:Ремонт;0:Сделано"}}                
   	],
	onSelectRow: function(id){
                $("#comment_rep").html($("#"+id+"_comment").val());
		if(id && id!==lastsel3){
			jQuery('#list_rep').jqGrid('restoreRow',lastsel3);
			jQuery('#list_rep').jqGrid('editRow',id,true,pickdates);
			lastsel3=id;
		};                                
	},        
                       
	autowidth: true,		
   	rowNum:10,	
   	rowList:[10,20,30],
   	pager: '#pager_rep',
   	sortname: 'rpid',	
        viewrecords: true,
        sortorder: "asc",
        editurl:"controller/server/equipment/repair.php?step=edit",
        caption:"Реестр ремонтов"
});

function pickdates(id){
	jQuery("#"+id+"_dt","#list_rep").datepicker({dateFormat:"dd.mm.yy"});
	jQuery("#"+id+"_dtend","#list_rep").datepicker({dateFormat:"dd.mm.yy"});        
        $("#comment_rep").html($("#"+id+"_comment").val());
}
  


//jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );