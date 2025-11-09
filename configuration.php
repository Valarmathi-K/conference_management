 <?php
 $hostname = 'localhost';
 $username = 'root';
 $password = '';
 $databasename = 'home page';
 try{
    $con = mysqli_connect($hostname, $username, $password, $databasename);
   /* echo "connection successful";*/
 }
catch(mysqli_sql_exception $e)
{
    die("Connection Failed :".$e->getMessage());
}

?> 
