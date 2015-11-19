var
 pznews=0;

$("#newsprev").click(function() {
    if (pznews>1) {
        pznews--;
        url="controller/server/news/getnews.php?num="+pznews;
        $("#newslist").load(url);    
    };
});

$("#newsnext").click(function() {
    pznews++;
    url="controller/server/news/getnews.php?num="+pznews;
    $prev=$("#newsnext").html();
    $("#newslist").load(url, function(responseText, textStatus, XMLHttpRequest) {
        if (responseText=='error') {
            pznews--;
            url="controller/server/news/getnews.php?num="+pznews;
            $("#newslist").load(url);            
            };
    });    
});

url="controller/server/news/getnews.php?num=0";
$("#newslist").load(url);    
