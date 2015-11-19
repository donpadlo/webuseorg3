$("#orgs").change(function(){
	var exdate=new Date();
        exdate.setDate(exdate.getDate() + 365);
        orgid=$("#orgs :selected").val();
        document.cookie="defaultorgid="+orgid+"; path=/; expires="+exdate.toUTCString();
});