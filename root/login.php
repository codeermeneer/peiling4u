<?php
include "connect.php";

session_start();
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
        $gebruikersnaam = $_SESSION["gebruikersnaam"];
        echo "Ingelogd als: ";
        echo $gebruikersnaam;
        
        echo "<form action='login.php' method='post'>
                <input type='submit' name='loguit' value='Loguit'>
            </form>";
    }
    else 
    {
        echo "<form action='login.php' method='post'>
                Gebruikersnaam: <br>
                <input type='text' name='gebruikersnaam'/> <br>
                Wachtwoord: <br>
                <input type='password' name='wachtwoord'/> <br>
                <input type='submit' name='login' value='Login'> <br>
                <a href='aanmelden.php'>Heb je geen account? Meldt je aan via deze link</a>
	        </form>";
    }
    ?>
</body>
</html>

<?php
if(isset($_POST["loguit"]))
{
    header("Refresh:0");
    session_destroy();
}

if(isset($_POST["login"]))
{
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    $gebruikersnaam = mysqli_real_escape_string($mysql, $_POST["gebruikersnaam"]);

    $resultaat = mysqli_query($mysql,"SELECT gebruikersnr, gebruikersnaam, wachtwoord 
                                      FROM gebruikers 
                                      WHERE gebruikersnaam = '$gebruikersnaam'") 
        or die("De query 1 op de database is mislukt!");

    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($gebruikersnr, $gebruiker, $hash) =  mysqli_fetch_row($resultaat);

    if(password_verify($_POST["wachtwoord"], $hash))
    {
        header("Refresh:0");
        echo "Ingelogd als: ".$gebruikersnr.$gebruiker;
        $_SESSION["logged_in"] = true;
        $_SESSION["gebruiker"] = $gebruikersnr;
        $_SESSION["gebruikersnaam"] = $gebruikersnaam;
    }
    else
    {
        echo "De gebruikersnaam of het wachtwoord klopt niet";
    }

    
}
?>
