<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

//include_once("class/mod.php");                  // класс работы с модулями

 $bp_xml_id = GetDef('bp_xml_id');
 $step = PostDef('sub');
 $comment = PostDef('comment');
  // таки двигаем процесс по пользователю
 if ($bp_xml_id!=""){
    $SQL="SELECT * FROM bp_xml_userlist WHERE id='$bp_xml_id'";    
    $result22 = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список пользователей БП!".mysqli_error($sqlcn->idsqlconnection));
    //echo "!".mysqli_error($sqlcn->idsqlconnection)."!";
    //var_dump($result22);    
    //echo "!$SQL!";
    while ($row = mysqli_fetch_array($result22)){
        if ($step=='Согласовать'){$result=$row['accept'];};
        if ($step=='Отвергнуть'){$result=$row['cancel'];};
        if ($step=='Доработать'){$result=$row['thinking'];};
        if ($step=='Да'){$result=$row['yes'];};
        if ($step=='Нет'){$result=$row['no'];};
        if ($step=='1)'){$result=$row['one'];};
        if ($step=='2)'){$result=$row['two'];};
        if ($step=='3)'){$result=$row['three'];};
        if ($step=='4)'){$result=$row['four'];};
        
    };
    $sql="UPDATE bp_xml_userlist SET comment='$comment',result='$result',status=1,dtend=NOW() WHERE id='$bp_xml_id'";
    $result23 = $sqlcn->ExecuteSQL( $sql ) or die("Не могу обновить статус БП в bp_xml_userlist!".mysqli_error($sqlcn->idsqlconnection));
    //echo "!$sql!";   
// ну и до кучи пробежимся по всем БП, и двинем их дальше....  
    $SQL = "SELECT * FROM bp_xml WHERE status<>2"; // выбираем все не утвержденные БП...
    $result24 = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список БП-xml!".mysqli_error($sqlcn->idsqlconnection));
    while($row = mysqli_fetch_array($result24)) {
      // пробегаем по всем пользователям, проверяем консенсус.
        $bpid=$row['id'];
        $st=$row['step'];        
        $SQL = "SELECT * FROM bp_xml_userlist WHERE bpid='$bpid' and step='$st' order by id"; // выбираем все не утвержденные БП...
        $result25 = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список БП-xml из bp_xml_userlist!".mysqli_error($sqlcn->idsqlconnection));
         $cnt=0; // всего пользователей
         $st1=0; // проголосовавших пользователей
         $cnt_cancel=0;
         $cnt_accept=0;
         $cnt_thinking=0;
         $cnt_yes=0;
         $cnt_no=0;
         $cnt_one=0;
         $cnt_two=0;
         $cnt_three=0;
         $cnt_four=0;
         
         while($row2 = mysqli_fetch_array($result25)) {
             $cnt++;
             if ($row2['status']=='1'){$st1++;};
             if ($row2['result']==$row2['accept']){$cnt_accept++;};
             if ($row2['result']==$row2['cancel']){$cnt_cancel++;};
             if ($row2['result']==$row2['thinking']){$cnt_thinking++;};
             if ($row2['result']==$row2['yes']){$cnt_yes++;};
             if ($row2['result']==$row2['no']){$cnt_no++;};
             if ($row2['result']==$row2['one']){$cnt_one++;};
             if ($row2['result']==$row2['two']){$cnt_two++;};
             if ($row2['result']==$row2['three']){$cnt_three++;};
             if ($row2['result']==$row2['four']){$cnt_four++;};
             
             $cancel=$row2['cancel'];
             $accept=$row2['accept'];
             $thinking=$row2['thinking'];
             $yes=$row2['yes'];
             $no=$row2['no'];
             $one=$row2['one'];
             $two=$row2['two'];
             $three=$row2['three'];
             $four=$row2['four'];
         };
         
         $bb=new Tbp;
         $bb->GetById($row['id']);
         // если в БП проголосовали все, то думаем "Чё делать"
         if ($cnt==$st1) {
             // если хоть ктото нажал "Отмена" то 
             if ($cnt_cancel!=0) {
                 // если в случае отмены завершаем БП, то завершаем...
                 if ($cancel==-1) {
                   $bb->SetStatus(3);
                 } else {
                     $bb->SetNodeToBase($cancel);
                 };
                 
             };
             // если хоть ктото нажал "Нет" то 
             if ($cnt_no!=0) {
                 // если в случае отмены завершаем БП, то завершаем...
                 if ($no==-1) {
                   $bb->SetStatus(3);
                 } else {
                     $bb->SetNodeToBase($no);
                 };
                 
             };
             
             // если хоть ктото нажал "доработать", и никто "отменить" то
            if (($cnt_thinking!=0) and ($cnt_cancel==0)) {
                //echo "!$thinking!";
                 $bb->SetNodeToBase($thinking);
             };
             // если все "согласны", то 
             if ($cnt_accept==$cnt) {                                  
               if ($accept==-1) {
                   $bb->SetStatus(2);
                 } else {
                     $bb->SetNodeToBase($accept);
                 };  
             };                
             // если все 1) то 
             if ($cnt_one==$cnt) {                                  
               if ($one==-1) {
                   $bb->SetStatus(2);
                 } else {
                     $bb->SetNodeToBase($one);
                 };  
             };               
             // если все 2) то 
             if ($cnt_two==$cnt) {                                  
               if ($two==-1) {
                   $bb->SetStatus(2);
                 } else {
                     $bb->SetNodeToBase($two);
                 };  
             };               
             // если все 3) то 
             if ($cnt_three==$cnt) {                                  
               if ($three==-1) {
                   $bb->SetStatus(2);
                 } else {
                     $bb->SetNodeToBase($three);
                 };  
             };               
             // если все 4) то 
             if ($cnt_four==$cnt) {                                  
               if ($four==-1) {
                   $bb->SetStatus(2);
                 } else {
                     $bb->SetNodeToBase($four);
                 };  
             };               
             
             // если все "согласны", то 
             if ($cnt_yes==$cnt) {                                  
               if ($yes==-1) {
                   $bb->SetStatus(2);
                 } else {
                     $bb->SetNodeToBase($yes);
                 };  
             };              
             
         };
         
    };
 };
 

?>