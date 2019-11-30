<?php
include "connect.php";
include "functies.php";
session_start();

// Kijkt of de gebruiker is ingelogd
if(!isset($_SESSION["logged_in"]))
{
    $_SESSION["logged_in"] = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1 ">
    <title>Login</title>
</head>
<body>
    <a href="index.php">Home</a> <br>
    <?php
    if($_SESSION["logged_in"] == true)
    {
        echo "Ingelogd als: ";
        echo get_gebruikersnaam($_SESSION["gebruiker"])."<br>";
        echo "<form action='login.php' method='post'>
                  <input type='submit' name='loguit' value='Loguit'>
              </form>";
    }
    if(isset($_GET["nr"]))
    {
        if (get_openbaar($_GET["nr"]) == 1)
        {
            $peilingnr = $_GET["nr"];
            echo get_peilingtitel($peilingnr);
            echo "<form action='peilingen.php?nr=$peilingnr' method='post'>";
            for ($i = 1; $i <= count_vragen($peilingnr); $i++)
            {
                if(get_m_antwoorden($peilingnr, $i) == 1)
                {
                    $selecttype = "checkbox";
                }
                else
                {
                    $selecttype = "radio";
                }

                echo "Vraag $i: ".get_vraag($peilingnr, $i)."<br>";
                
                for ($j = 1; $j <= count_antwoorden($peilingnr, $i, $j); $j++)
                {
                    echo "<input type='$selecttype' name='vraag$i' value='$j'>";
                    echo get_antwoord($peilingnr, $i, $j)."<br>";
                }
            } 
            echo "<input type='submit' name='verzend' value='Verzend'> <br>";
            echo "</form>";
        }
        else
        {
            echo "Deze peiling is niet openbaar";
        }
    }
    else 
    {
        $gebruikersnr = $_SESSION["gebruiker"];
        
        $mysql = mysqli_connect($server,$user,$pass,$db) 
            or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

        $resultaat = mysqli_query($mysql,"SELECT peilingnr, titel
                                          FROM peilingen
                                          WHERE openbaar = '1'") 
            or die("De query 1 op de database is mislukt!");
        
        mysqli_close($mysql) 
            or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
        
        echo "Openbare peilngen: <br>";
        while(list($peilingnr, $peilingtitel) = mysqli_fetch_row($resultaat))  
        {
            echo"<a href='peilingen.php?nr=$peilingnr'>$peilingtitel<a/><br />"; 
        }
    }
    ?>
</body>
</html>
