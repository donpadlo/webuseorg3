function createTab() {
  var container = document.createElement('div')
  container.innerHTML = '<div id="sh_button" class="shc sh_btn sh_btn_right sh_btn_right_center" style="top: 65px; visibility: visible;">'+
            '<img style="margin: 6px;" class="shc sh_logo_btn sh_logo_img" src="'+helphref+helpsite+'/controller/client/themes/bootstrap/img/help.png">'+
            '<div class="shc sh_title_text" >'+
                '<div class="shc sh_btn_char">П </div>'+
                '<div class="shc sh_btn_char">о </div>'+
                '<div class="shc sh_btn_char">м </div>'+
                '<div class="shc sh_btn_char">о </div>'+
                '<div class="shc sh_btn_char">щ </div>'+
                '<div class="shc sh_btn_char">ь </div>'+
                '<div class="shc sh_btn_char">  </div>'+
                '<div class="shc sh_btn_char">о </div>'+
                '<div class="shc sh_btn_char">н </div>'+
                '<div class="shc sh_btn_char">л </div>'+
                '<div class="shc sh_btn_char">а </div>'+
                '<div class="shc sh_btn_char">й </div>'+
                '<div class="shc sh_btn_char">н </div>'+
            '</div>'+
        '</div>';
  return container.firstChild;
};
function createBoxChat(){

};

chat_tab=createTab();
document.body.appendChild(chat_tab);

document.getElementById('sh_button').onclick = function() {
    var el = document.getElementById('sh_button');
    el.parentNode.removeChild(el);    
    alert('Спасибо')
};
