<?php
    include("header.php");
    include("functions.php");
    include("classes/Problem.class.php");
?>

<?php

$problems = array();

$body = file_get_contents("http://acm.timus.ru/problemset.aspx?space=1&page=all");

$count = preg_match_all('/<TD><[^<>]+><\/TD><TD>([0-9]{4})<\/TD><TD CLASS="name"><A[^<>]+>([^<>]+)<\/A><\/TD><TD CLASS="source">[^<>]*<\/TD><TD><A[^<>]+>[^<>]+<\/A><\/TD><TD>([0-9]+)<\/TD>/', $body, $match, PREG_SET_ORDER);

for($i = 0;$i<$count;++$i)
    $problems[] = new Problem((int)$match[$i][1], $match[$i][2], (int)$match[$i][3]);

usort($problems, 'compProblems');

$users = array( "69974" => array(), "70163" => array(), "92807" => array(), "35562" => array(), "45060" => array(), );//"34002" => array(), "16705" => array(), "16727" => array(), );

foreach($users as $user=>$solved){
    $body = file_get_contents("http://acm.timus.ru/author.aspx?id=$user");
    
	preg_match('/<H2 CLASS="author_name">([^<>]+)<\/H2>/',$body,$match);
    $users[$user]['name'] = $match[1];

    $count = preg_match_all('/<TD CLASS="accepted"><A[^<>]+>([0-9]{4})<\/A><\/TD>/', $body, $match, PREG_SET_ORDER);
    
    for($i = 0;$i<$count;++$i)
        $users[$user][$match[$i][1]] = TRUE;
}

$total = count($problems);
echo "<h1>$total problemas</h1><br>";

echo "\n<table border=1 bordercolor=#999999 bordercolordark=gray cellpadding=5 style='border-collapse: collapse' align=center><thead>";
echo "<tr bgcolor=#A4C6FF>";
echo "<th width=50><font color=black>ID</font></th>";
echo "<th width=320><font color=black>Title</font></th>";
echo "<th width=65><font color=black>Difficulty</font></th>";


foreach($users as $user=>$solved){
    $solved = count($users[$user]) - 1;
    echo "<th width=150>".$users[$user]['name']."<br>($solved)</th>";
}
echo "</tr></thead><tbody>";

for($i = 0;$i < $total;++$i){
    $id = $problems[$i]->id;
    $name = $problems[$i]->name;
    $AC = $problems[$i]->AC;
    
    echo "\n<tr align=center bgcolor=#C6C6C6>";
    
    echo "<td>".$id."</td>";
    echo "<td align=left><a href=\"http://acm.timus.ru/problem.aspx?space=1&num=$id\">".$name."</a></td>";
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
