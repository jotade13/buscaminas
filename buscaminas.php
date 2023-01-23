<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>tabla</title>
</head>
<body>
    <h1>BUSCAMINAS</h1>
    <?php 
       
            if(!isset($_SESSION["matriz"]))
            {
                
                $cont=0;
                for($i=0;$i<10;$i++)
                {
                    for($j=0;$j<10;$j++)
                    {
                        $mat[$i][$j]=false;
                        $mina[$i][$j]=false;
                        $minaAlr[$i][$j]=0;
                        $nro[$i][$j]=$cont;
                        $cont++;
                    }
                   
                }
                $_SESSION["minaAlrededor"]=$minaAlr;
                $_SESSION["numero"]=$nro;
                $_SESSION["matriz"]=$mat;
                $_SESSION["mina"]=$mina;
                $_SESSION["primer"]=false;
            }
        $_SESSION["numerosAlr"]=[];
        
        function Alrededor($f,$c,$opcion)
        {
            
            $contador=0;
            for($i=$f-1;$i<=$f+1;$i++)
            {
                    for($j=$c-1;$j<=$c+1;$j++)
                    {
                       if(($i>=0&&$i<10)&&($j>=0&&$j<10))
                       {
                       
                            switch($opcion)
                            {
                               
                                case 1: $_SESSION["numerosAlr"][$contador]=$_SESSION["numero"][$i][$j];     //Saber los numeros alrededor
                                break;    
                                case 2: if(!$_SESSION["primer"]){$_SESSION["matriz"][$i][$j]=true;}
                                /*if($_SESSION["minaAlrededor"][$i][$j]==0&&($i!=$f||$j!=$c)&&$_SESSION["matriz"][$i][$j]){Alrededor($i,$j,2); echo "prueba"; } *////Destapar los numeros alrededor
                                break;
                                case 3: if($_SESSION["mina"][$i][$j]){$_SESSION["minaAlrededor"][$f][$c]++;} break;//minas alrededor
                            }
                            $contador++;
                       }
                    }
            }
            
        }
        
            if(isset($_GET["fil"])&&isset($_GET["col"]))
            {
                if(!$_SESSION["primer"])
                {
                    
                    Alrededor($_GET["fil"],$_GET["col"],1);     
                    for($i=0;$i<20;$i++)
                    {
                        $vectorMinas[$i]=rand(0,99);
                        for($j=0;$j<$i;$j++)
                        { 
                            for($k=0;$k<sizeof($_SESSION["numerosAlr"]);$k++)
                            {
                                if($vectorMinas[$i]==$vectorMinas[$j]||$vectorMinas[$i]==$_SESSION["numerosAlr"][$k])
                                {
                                    
                                    $i--;
                                    $j=0;
                                    break;
                                }
                            }
                               
                        }
                    }
                    for($f=0;$f<20;$f++)
                    {
                        for($i=0;$i<10;$i++)
                        {
                            for($j=0;$j<10;$j++)
                            {
                                if($_SESSION["numero"][$i][$j]==$vectorMinas[$f])
                                {
                                    $_SESSION["mina"][$i][$j]=true;
                                }
                            }
                        }
                    } 
                    for($i=0;$i<10;$i++)
                    {
                        for($j=0;$j<10;$j++)
                        {
                            Alrededor($i,$j,3);
                        }
                    }
                    Alrededor($_GET["fil"],$_GET["col"],2);
                    $_SESSION["primer"]=true;
                }else
                {
                    Alrededor($_GET["fil"],$_GET["col"],2);
                }
                
            }
    ?>
    <table id="tabla"  cellpadding="0">
        <?php
           
            for($i=0;$i<10;$i++)
                {
                    echo "<tr>";
                    for($j=0;$j<10;$j++)
                    {   
                        
                        if(!$_SESSION["matriz"][$i][$j])
                        {
                            echo "<td><a onclick='selec(",$i,",",$j,");'><img src='assets/tapado.png' id='imagenfila",$i,"columna",$j,"'
                            width='30px' heigth='30px'></a></td>";
                        }else{
                            if(!$_SESSION["mina"][$i][$j])
                            {
                                echo"<td><a onclick='selec(",$i,",",$j,");'><img src='assets/",$_SESSION["minaAlrededor"][$i][$j],".png' id='imagenfila",$i,"columna",$j,"'
                                width='30px' heigth='30px'></a></td>";
                            }else{
                                echo"<td><a onclick='selec(",$i,",",$j,");'><img src='assets/mina.png' id='imagenfila",$i,"columna",$j,"'
                                width='30px' heigth='30px'></a></td>";
                            }         
                        }  
                    }
                    echo "</tr>";
                }
        ?>
    </table>
   
    <button onclick="reiniciar()">Reiniciar</button>
</body>
<script>
    function selec(f,c)
    {
        // alert("fila: "+f+" columna: "+c+" seleccionada");
        document.location="buscaminas.php?fil="+f+"&col="+c;
    }
    function perdiste(f,c)
    {
        alert("fila: "+f+" columna: "+c+" seleccionada");
       
    }
    function reiniciar(f,c)
    {
        document.location="reiniciar.php";
       
    }
        
</script>
</html>