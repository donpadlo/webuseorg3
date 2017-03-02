function RefreshPlaces(){	 
	 $.ajax({
     			url: route + "controller/server/reports/getlistplaces.php&orgid="+$("#sorgsname").val()+"&onchange=onchangesplaces()",
     			success: function(answ){
			    $("#id_for_place").html(answ);			    			    
			    UpdateChosen();
		        }
    		});    
	 $.ajax({
     			url: route + "controller/server/reports/getlistpeoples.php&orgid="+$("#sorgsname").val()+"&onchange=onchangesplaces()",
     			success: function(answ){
			    $("#id_for_user").html(answ);			    			    
			    UpdateChosen();
		        }
    		});    
	 $.ajax({
     			url: route + "controller/server/reports/getlisttmc.php&onchange=onchangesplaces()",
     			success: function(answ){
			    $("#id_for_tmc").html(answ);			    			    
			    UpdateChosen();
		        }
    		});    
		
    
};
function onchangesplaces(){
  
};
 $("#sorgsname").change(function() {
    RefreshPlaces();     
 });
 $("#viewwork").click(function() {
    $("#if_for_report").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
    $("#if_for_report").load(route + "controller/client/view/reports/report_move_tmc.php&orgid="+$("#sorgsname").val()+"&speoples="+$("#speoples").val()+"&splaces="+$("#splaces").val()+"&stmc="+$("#stmc").val()+"&dtstart="+$("#dtstart").val()+"&dtend="+$("#dtend").val());        
 });
//////////
UpdateChosen();
RefreshPlaces();

$("#dtstart").datepicker();
$("#dtstart").datepicker( "option", "dateFormat", "dd.mm.yy");
$("#dtstart").datepicker({    
    beforeShow: function() {
	setTimeout(function(){$(".ui-datepicker").css("z-index", 99999999999999);}, 0);
    }
});    			  
$("#dtstart").datepicker( "setDate" , "0");

$("#dtend").datepicker();
$("#dtend").datepicker( "option", "dateFormat", "dd.mm.yy");
$("#dtend").datepicker( "setDate" , "0");
