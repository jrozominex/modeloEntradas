<?php
/*$file = fopen("1.txt", "r") or exit("Unable to open file!");
//Output a line of the file until the end is reached
while(!feof($file))
{
echo fgets($file). "<br />";
}
fclose($file);*/

$lineas = file("1.txt");
$palabra="a";    
$pos = 0;
    // Podemos mostrar / trabajar con todas las líneas:
    foreach($lineas as $linea){
        
if (strstr($linea,$palabra)){
            echo "si esta la palabra $linea, está en la linea : ".$pos."<br>";
}
   $pos++;
}
?>