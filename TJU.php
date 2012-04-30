<?php
    include("header.php");
    include("functions.php");
    include("classes/Problem.class.php");
?>

<?php

$problems = array();

for($vol = 1;$vol <= 29;++$vol){
    $body = file_get_contents("http://acm.tju.edu.cn/toj/list$vol.html");

    $count = preg_match_all('/p\([01],[012],([0-9]{4}),"(.*)",[0-9]+,([0-9]+),[0-9]+,"[0-9]+\.[0-9]+",[01],[01],[01]\)/', $body, $match,  PREG_SET_ORDER);
    
    for($i = 0;$i<$count;++$i)
        $problems[] = new Problem((int)$match[$i][1], $match[$i][2], (int)$match[$i][3]);
}

usort($problems, 'compProblems');

$users = array("marioyc" => array(), "trulo17" => array(), "hamlet" => array(), "roypalacios" => array(), "forifchen" => array(),"a20012251" => array(), );

foreach($users as $user=>$solved){
    $body = file_get_contents("http://acm.tju.edu.cn/toj/user_$user.html");
    
    $count = preg_match_all('/p\(([0-9]{4})\)/', $body, $match,  PREG_SET_ORDER);
    
    for($i = 0;$i<$count;++$i)
        $users[$user][$match[$i][1]] = TRUE;
}

$total = count($problems);
echo "<h1>$total problemas</h1><br>";

echo "\n<table class=\"tableWithFloatingHeader\" border=1 bordercolor=lightgrey bordercolordark=gray cellpadding=5 style='border-collapse: collapse' align=center><thead>";
echo "<tr bgcolor=#FFFFD0>";
echo "<th><font color=blue>ID</font></th>";
echo "<th><font color=green>Title</font></th>";
echo "<th><font color=red>AC</font></th>";

$sum = array();

foreach($users as $user=>$solved){
	$sum[$user] = 0;
    $solved = count($users[$user]);
    echo "<th width = 100>$user<br>($solved)</th>";
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
    echo "<td><a href=\"http://acm.tju.edu.cn/toj/showp$id.html\">".$name."</a></td>";
    echo "<td>".$AC."</td>";
    
    foreach($users as $user=>$solved){
        $state = "";
        if(array_key_exists($id,$users[$user])){
			$state = "AC";
			$sum[$user] += $i;
		}
        
        echo "<td align=center><font color=red>$state</font></td>";
        //<IMG SRC="images/ok.gif" ALT="Ok">
    }
    
    echo "</tr>";
}
echo "\n</tbody></table>";

echo "<br><h1>Next to solve</h1><br>";
echo "\n<table class=\"tableWithFloatingHeader\" border=1 bordercolor=lightgrey bordercolordark=gray cellpadding=5 style='border-collapse: collapse' align=center><thead>";
echo "<tr bgcolor=#FFFFD0>";

foreach($users as $user=>$solved){
    echo "<th width = 100>$user</th>";
}

echo "</tr></thead><tbody><tr>\n";

foreach($users as $user=>$solved){
    $solved = count($users[$user]);
    //echo $sum[$user]." ".$solved."<br>";
	$next = 0;
	if($solved > 0) $next = ($sum[$user] + $solved - 1) / $solved;
	
	while(TRUE){
		if(!array_key_exists($problems[$next]->id,$users[$user])){
			$id = $problems[$next]->id;
			echo "<td><a href=\"http://acm.tju.edu.cn/toj/showp$id.html\">".$id."</a></td>\n";
			break;
		}
		
		--$next;
	}
}
echo "</tr></tbody></table>";

?>

<?php
    include("footer.php");
?>
