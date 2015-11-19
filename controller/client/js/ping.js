$("#test_ping").click(function(){
  $("#ping_add").html("<img src=controller/client/themes/"+theme+"/img/loading.gif>");    
  $("#ping_add").load('controller/server/common/ping.php?orgid='+defaultorgid, function() {
    });
});