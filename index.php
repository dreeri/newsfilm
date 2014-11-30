<?php
session_start();
if(isset($_POST['pass']))
{
	$pass = $_POST['pass'];
}

if(isset($pass) && $pass == "password")
{
	$_SESSION['auth'] = 1;
}

if(isset($_SESSION['auth']) && $_SESSION['auth'] == 1)
{
        include("frnt.php");
}

else
{
    if(isset($_POST))
    {?>

            <form method="POST" action="index.php">
            Pass <input type="password" name="pass"></input><br/>
            <input type="submit" name="submit" value="Go"></input>
            </form>
    <?php
    }
}
?>
