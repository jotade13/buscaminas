<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buscaminas</title>
</head>
<body>
    <h1>BUSCAMINAS</h1>
    <?php 
    $_SESSION["fila"]=10; $_SESSION["col"]=10;
    $minasT=20;
      
            if(!isset($_SESSION["matriz"])||$_SESSION["victoria"])
            {
                $cont=0;
                for($i=0;$i<$_SESSION["fila"];$i++)
                {
                    for($j=0;$j<$_SESSION["col"];$j++)
                    {
                        $mat[$i][$j]=false;
                        $mina[$i][$j]=false;
                        $minaAlr[$i][$j]=0;
                        $nro[$i][$j]=$cont;
                        $cont++;
                        $expan[$i][$j]=false;
                        $bandera[$i][$j]=0;
                    }
                   
                }
                $_SESSION["minaAlrededor"]=$minaAlr;
                $_SESSION["numero"]=$nro;
                $_SESSION["matriz"]=$mat;
                $_SESSION["mina"]=$mina;
                $_SESSION["expandir"]=$expan;
                $_SESSION["primer"]=$_SESSION["victoria"]=$_SESSION["derrota"]=false;
                $_SESSION["bandera"]=$bandera;
            }
            $_SESSION["numerosAlr"]=[]; 
        
        
            if(isset($_GET["fil"])&&isset($_GET["col"])&&isset($_GET["band"]))
            {
                if($_GET["band"]==0)
                {
                    if($_SESSION["bandera"][$_GET["fil"]][$_GET["col"]]==0)
                    {
                        if(!$_SESSION["primer"])        //primer click
                        {
                            
                            Alrededor($_GET["fil"],$_GET["col"],1);   //saber cuales hay alrrededor para no poner ninguna mina alrededor  
                            for($i=0;$i<$minasT;$i++)
                            {
                                $bandP=true;
                                $vectorMinas[$i]=rand(0,(($_SESSION["fila"]*$_SESSION["col"])-1));
                                for($j=0;$j<$i;$j++)
                                {  
                                    if($vectorMinas[$i]==$vectorMinas[$j])
                                    {
                                            $j=$i;
                                            $i--;
                                            $bandP=false;
                                    }
                                }
                                if($bandP)  
                                {
                                    for($k=0;$k<sizeof($_SESSION["numerosAlr"]);$k++)
                                    {
                                        if($vectorMinas[$i]==$_SESSION["numerosAlr"][$k])
                                        {
                                            $k=sizeof($_SESSION["numerosAlr"]);
                                            $i--;
                                        }
                                    }
                                }
            
                            }
                            for($f=0;$f<$minasT;$f++)
                            {
                                for($i=0;$i<$_SESSION["fila"];$i++)
                                {
                                    for($j=0;$j<$_SESSION["col"];$j++)
                                    {
                                        if($_SESSION["numero"][$i][$j]==$vectorMinas[$f])
                                        {
                                            $_SESSION["mina"][$i][$j]=true;
                                        }
                                    }
                                }
                            } 
                            for($i=0;$i<$_SESSION["fila"];$i++)
                            {
                                for($j=0;$j<$_SESSION["col"];$j++)
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
                    ganar();
                }else
                {
                    if($_SESSION["bandera"][$_GET["fil"]][$_GET["col"]]==0)
                    {
                        $_SESSION["bandera"][$_GET["fil"]][$_GET["col"]]=1;
                    }else{
                        $_SESSION["bandera"][$_GET["fil"]][$_GET["col"]]=0;
                    }
                    
                }  
            }
        function Alrededor($f,$c,$opcion)
        {
            
            $contador=0;
            for($i=$f-1;$i<=$f+1;$i++)
            {
                    for($j=$c-1;$j<=$c+1;$j++)
                    {
                       if(($i>=0&&$i<$_SESSION["fila"])&&($j>=0&&$j<$_SESSION["col"]))
                       {
                       
                            switch($opcion)
                            {
                               
                                case 1: $_SESSION["numerosAlr"][$contador]=$_SESSION["numero"][$i][$j];  //echo"",$_SESSION["numerosAlr"][$contador],"<br></br>";    //Saber los numeros alrededor
                                break;    
                                case 2: if(!$_SESSION["primer"]||$_SESSION["minaAlrededor"][$f][$c]==0){$_SESSION["matriz"][$i][$j]=true;}else{$_SESSION["matriz"][$f][$c]=true;}
                                        if($_SESSION["minaAlrededor"][$i][$j]==0&&($i!=$f||$j!=$c)&&$_SESSION["matriz"][$i][$j]
                                        &&!$_SESSION["expandir"][$i][$j])
                                        {
                                         $_SESSION["expandir"][$i][$j]=true; Alrededor($i,$j,2);
                                        } //Destapar los numeros alrededor
                                break;
                                case 3: if($_SESSION["mina"][$i][$j]){$_SESSION["minaAlrededor"][$f][$c]++;} 
                                break;//minas alrededor
                            }
                            $contador++;
                       }
                    }
            }
            
        }
        function  ganar()
        {
            $bandG=true;
            for($i=0;$i<$_SESSION["fila"];$i++)
            {
                for($j=0;$j<$_SESSION["col"];$j++)
                {   
                    if(!((($_SESSION["matriz"][$i][$j]&&!$_SESSION["mina"][$i][$j])||(
                    !$_SESSION["matriz"][$i][$j]&&$_SESSION["mina"][$i][$j]))&&$bandG))
                    {
                        $bandG=false;
                        //echo "",$_SESSION["numero"][$i][$j],"<br></br>";
                    }
                }
            }
            if($bandG)
            {
                $_SESSION["victoria"]=true;
            }
       }
    

            
    ?>
    <table id="tabla"  cellpadding="0">
        <?php
           
            for($i=0;$i<$_SESSION["fila"];$i++)
                {
                    echo "<tr>";
                    for($j=0;$j<$_SESSION["col"];$j++)
                    {   
                        
                        if(!$_SESSION["matriz"][$i][$j])
                        {
                            if($_SESSION["bandera"][$i][$j]==0)
                            {
                                echo "<td><a onmousedown='selec(",$i,",",$j,");'><img src='assets/tapado.png' id='imagenfila",$i,"columna",$j,"'
                                width='30px' heigth='30px'></a></td>";
                            }else
                            {
                                echo "<td><a onmousedown='selec(",$i,",",$j,");'><img src='assets/bandera.png' id='imagenfila",$i,"columna",$j,"'
                                width='30px' heigth='30px'></a></td>";
                            }
                        }else{
                            if(!$_SESSION["mina"][$i][$j])
                            { 
                                echo"<td><a onmousedown='selec(",$i,",",$j,");'><img src='assets/",$_SESSION["minaAlrededor"][$i][$j],".png' id='imagenfila",$i,"columna",$j,"'
                                width='30px' heigth='30px'></a></td>";
                            }else{
                                echo"<td><a><img src='assets/mina.png' id='imagenfila",$i,"columna",$j,"'
                                width='30px' heigth='30px'></a></td>";

                               $_SESSION["derrota"]=true;  
                            }         
                        }
                    }
                    echo "</tr>";
                }   
        ?>
    </table>
   
    <button onclick="reiniciar()">Reiniciar</button>

    <?php
         if($_SESSION["derrota"])
         {
             echo '<script language="javascript">window.addEventListener("load", function(event){alert("Perdiste");document.location="reiniciar.php";})</script>';
         }
         if($_SESSION["victoria"])
         {
             echo '<script language="javascript">window.addEventListener("load", function(event){alert("Eres un Crack");})</script>';
         }
    ?>
</body>
<script>
    function selec(f,c)
    { 
        let aux=event.button
        switch(aux)
        {
            case 0: document.location="buscaminas.php?fil="+f+"&col="+c+"&band=0"; break;
            case 2: document.location="buscaminas.php?fil="+f+"&col="+c+"&band=1"; break;
        }
    }
    function reiniciar()
    {
        document.location="reiniciar.php";
       
    }
    function inhabilitar()
    {
        "prueba";
        return false;
    }
    document.oncontextmenu=inhabilitar;
</script>
</html>