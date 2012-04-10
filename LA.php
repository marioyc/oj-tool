<?php
    include("header.php");
    include("functions.php");
    include("classes/Problem.class.php");
?>

<?php

$regionals = array('wfi', 'aa', 'saf', 'as1', 'as2', 'as3', 'as4', 'as5', 'as6', 'as7', 'as8', 'as9', 'as10', 'as11', 'as12', 'as13', 'as14', 'as15', 'ce', 'mce', 'nea', 'nw', 'sea', 'sw', 'ca', 'sa', 'ec', 'gny', 'ma', 'mc', 'nc', 'ne', 'pn', 'rm', 'sce', 'se', 'sc', 'sp', );
$problems = array();

foreach($regionals as $regional){
    for($year = 2000;$year<=date('Y');++$year){
        $body = file_get_contents("http://acmicpc-live-archive.uva.es/nuevoportal/region.php?r=$regional&year=$year");
        
        $count = preg_match_all('/<tr>\s<td [^<>]+><a [^<>]+>[^<>]+<\/a><\/td>\s<td [^<>]+><a [^<>]+><img [^<>]+><\/a><\/td>\s<td [^<>]+><a [^<>]+>([^<>]+)[<\/a>]+<\/td>/', $body, $match1,  PREG_SET_ORDER);
        
        $count = preg_match_all('/<tr>\s<td [^<>]+><a [^<>]+>([^<>]+)<\/a><\/td>\s<td [^<>]+><a [^<>]+>[1-9][^<>]*<\/a><\/td>\s<td [^<>]+><a [^<>]+>([^<>]+)<\/a><\/td>/', $body, $match2,  PREG_SET_ORDER);
        
        for($i = 0;$i<$count;++$i)
            $problems[] = new Problem((int)$match2[$i][1], $match1[$i][1], (int)$match2[$i][2]);
    }
}

usort($problems, 'compProblems');

$users = array("14339" => array(), "1945" => array(), "9460" => array(), "21503" => array(), "13781" => array(), "10261" => array(), "9242" => array(), "18487" => array(), "16616" => array(), "10194" => array(), "11033" => array(), );

foreach($users as $user=>$solved){
    $body = file_get_contents("http://acmicpc-live-archive.uva.es/nuevoportal/users.php?user=$user");
    
    preg_match('/<tr><td>Name:<\/td><td>([^<>]+)<\/td><\/tr>/',$body,$match);
    $users[$user]['name'] = $match[1];
    
    $count = preg_match_all('/<tr [^<>]+><td [^<>]+><a [^<>]+>([0-9]+) - [^<>]+<\/a><\/td>\s<td>Accepted<\/td>\s<td>[^<>]+<\/td><td>[^<>]+<\/td><td>[^<>]+<\/td><\/tr>/', $body, $match,  PREG_SET_ORDER);
    
    for($i = 0;$i<$count;++$i)
        $users[$user][$match[$i][1]] = TRUE;
}

$total = count($problems);
echo "<h1>$total problemas</h1><br>";
?>

<style type="text/css">
tr.f {color:000000;font-size:medium;text-decoration:none;font-family: arial;}
a.g {color:000000;font-size:medium; text-decoration:none;font-family: arial;}
a.g:link { color:blue; font-family: sans-serif; font-size: medium; font-style: normal; font-variant: normal; text-decoration: none; }
a.g:visited { color: red; font-family: sans-serif; font-size: medium; font-style: normal; font-variant: normal; text-decoration: none; }
</style>


<table border=0 cellspacing=2 cellpadding=5 bgcolor=#000000 >
<tr bgcolor=#ffffff><th width=40>ID</th><th width=380>Title</th><th width=70>Users</th>

<?php
foreach($users as $user=>$solved){
    $name = $users[$user]['name'];
    $solved = count($users[$user]);
    echo "<th style='font-size: 13px'>$name<br>($solved)</th>";
}
?>

</tr>

<?php
for($i = count($problems)-1,$even = 1;$i>=0;--$i){
    $id = $problems[$i]->id;
    $name = $problems[$i]->name;
    $AC = $problems[$i]->AC;
    
    $bgcolor = ($even==1? "bgcolor=#ddf3ff" : "bgcolor=#ffffff");
    $even = 1-$even;
    
    echo "\n<tr class=\"f\" align=center cellspacing=\"0\">";
    
    echo "<td $bgcolor>".$id."</td>";
    echo "<td $bgcolor align=left><a class=\"g\" href=\"http://acmicpc-live-archive.uva.es/nuevoportal/data/problem.php?p=$id\">".$name."</a></td>";
    echo "<td $bgcolor>".$AC."</td>";
    
    
    foreach($users as $user=>$solved){
        $state = "";
        if(array_key_exists($id,$users[$user])) $state = "AC";
        
        echo "<td align=center $bgcolor><font color=red>$state</font></td>";
    }
    
    echo "</tr>";
}
?>

</table>

<?php
    include("footer.php");
?>
