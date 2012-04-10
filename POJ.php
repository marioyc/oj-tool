<?php
    include("header.php");
    include("functions.php");
    include("classes/Problem.class.php");
?>

<?php

$problems = array();

for($vol = 1;$vol <= 31;++$vol){
    $body = file_get_contents("http://poj.org/problemlist?volume=$vol");
	
	$count = preg_match_all('/<tr align=center><td>([0-9]{4})<\/td><td align=left><a[^<>]+>([^<>]+)<\/a><\/td><td>[^<>]+<a[^<>]+>([^<>]+)<\/a>\//', $body, $match,  PREG_SET_ORDER);
	
    for($i = 0;$i < $count;++$i)
        $problems[] = new Problem((int)$match[$i][1], $match[$i][2], (int)$match[$i][3]);
}

usort($problems, 'compProblems');

$users = array("MarioYC" => array(), "a20012251" => array(), "sonyckson" => array(), "pedro_victor" => array(), "pab2" => array(), "fmm" => array(), "MauricioC" => array(), "pdallago" => array(), "atolfortin" => array(), "marxi" => array(), );

foreach($users as $user=>$solved){
    $body = file_get_contents("http://poj.org/userstatus?user_id=$user");
    
    $count = preg_match_all('/p\(([0-9]{4})\)/', $body, $match,  PREG_SET_ORDER);
    
    for($i = 0;$i<$count;++$i)
        $users[$user][$match[$i][1]] = TRUE;
}

$total = count($problems);
echo "<h1>$total problemas</h1><br>";

echo "\n<table border=1 bordercolor=lightgrey bordercolordark=gray cellpadding=5 style='border-collapse: collapse' align=center><thead>";
echo "<tr bgcolor=#FFFFD0>";
echo "<th width=50><font color=blue>ID</font></th>";
echo "<th width=320><font color=green>Title</font></th>";
echo "<th width=70><font color=red>AC</font></th>";

foreach($users as $user=>$solved){
    $solved = count($users[$user]);
    echo "<th>$user<br>($solved)</th>";
}
echo "</tr></thead><tbody>"; 

for($i = $total - 1,$even = 1;$i >= 0;--$i){
    $id = $problems[$i]->id;
    $name = $problems[$i]->name;
    $AC = $problems[$i]->AC;
    
    if($even==1) echo "\n<tr align=center bgcolor=#F0F0F0>";
    else echo "\n<tr align=center>";
    $even = 1-$even;
    
    echo "<td>".$id."</td>";
    echo "<td><a href=\"http://poj.org/problem?id=$id\">".$name."</a></td>";
    echo "<td>".$AC."</td>";
    
    foreach($users as $user=>$solved){
        $state = "";
        if(array_key_exists($id,$users[$user])) $state = "AC";
        
        echo "<td align=center><font color=red>$state</font></td>";
        //<IMG SRC="images/ok.gif" ALT="Ok">
    }
    
    echo "</tr>";
}
echo "\n</tbody></table>";

?>

<?php
    include("footer.php");
?>
