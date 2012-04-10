<?php
    include("header.php");
    include("classes/Problem.class.php");
    include("functions.php");
?>

<?php

$problems = array();

for($i = 0;$i<31;++$i){
    $lim = $i*50;
    $body = file_get_contents("http://www.spoj.pl/problems/classical/sort=-6,start=$lim");
    
    $problemPosition = getPositions($body, '<tr class="problemrow">');
    if(count($problemPosition)==0) break;
    $end = getTableEnd($body, $problemPosition[count($problemPosition)-1]);
    
    $body = substr($body,$problemPosition[0],$end-$problemPosition[0]-1);
    $desc = explodeX(array("<", ">", ), $body);
    
    $n = count($desc);
    
    for($j = 0;$j+43<$n;$j += 44)
        $problems[] = new Problem(substr($desc[$j+22],1,strlen($desc[$j+22])-1), $desc[$j+12], (int)$desc[$j+30]);
}


$users = array( "marioyc" => array(), "trulo_17" => array(), "hamlet_fiis" => array(), "roypalacios" => array(), "a20012251" => array(),);

foreach($users as $user=>$solved){
    $body = file_get_contents("http://www.spoj.pl/users/$user/");
    
    preg_match('/<tr class="lightrow">\s<td><b>([0-9]+)<\/b>\<\/td>/',$body,$match);
    $AC = (int)$match[1];
    
    $count = preg_match_all('/<a [^<>]+>([A-Z0-9]+)<\/a>/', $body, $match,  PREG_SET_ORDER);
    //var_dump($match);
    for($i = 0;$i<$AC;++$i)
        $users[$user][$match[$i][1]] = TRUE;
}

$total = count($problems);
echo "<h1>$total problemas</h1><br>";

echo "\n<table border=1 bordercolor=white cellpadding=5 style='border-collapse: collapse; color: #000020; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px' align=center>";
echo "<thead><tr style='background-color: #000080; color: #DDDDFF'><th style='background-image: url(img/sulcorner.png); background-position:left top; background-repeat:no-repeat;'>CODE</th>";
echo "<th>NAME</th>";
echo "<th>USERS</th>";

$i = 1;

foreach($users as $user=>$solved){
    $solved = count($users[$user]);
	
    if($i==count($users)) echo "<th style='background-image: url(img/surcorner.png); background-position:right top; background-repeat:no-repeat;'>$user<br>($solved)</th>";
    else echo "<th>$user<br>($solved)</th>";
	
    ++$i;
}
echo "</tr></thead><tbody>";

for($i = 0;$i < $total;++$i){
    $id = $problems[$i]->id;
    $name = $problems[$i]->name;
    $AC = $problems[$i]->AC;
    
    echo "\n<tr align=center bgcolor=#D0D0D0 style='color: #0000A0'>";
    
    echo "<td>".$id."</td>";
    echo "<td><a href=\"http://www.spoj.pl/problems/$id/\">".$name."</a></td>";
    echo "<td>".$AC."</td>";
    
    foreach($users as $user=>$solved){
        $state = "";
        if(array_key_exists($id, $users[$user])) $state = "AC";
        
        echo "<td align=center><font color=red>$state</font></td>";
    }
    
    echo "</tr>";
}
echo "\n</tbody></table>";

?>

<?php
    include("footer.php");
?>
