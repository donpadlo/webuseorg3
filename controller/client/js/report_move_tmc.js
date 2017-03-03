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
  $("#prnt").click(function() {
    var newWin3=window.open('','Печатная форма','');
    newWin3.focus();
    newWin3.document.write('<html>'); 
    newWin3.document.write("<script>printable=true;\x3C/script>"); 
    newWin3.document.write($("#idheader").html());		    
    newWin3.document.write('<body>');     
    newWin3.document.write($("#if_for_report").html());    
    newWin3.document.write('</body></html>');  
    newWin3.document.close();      
      
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
