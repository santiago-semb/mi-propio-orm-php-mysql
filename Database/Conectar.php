<?php 
require 'config.php';

class Connect {
    public static function connection()
    {
        try
        {
            $conexion=new PDO("$GLOBALS[MYSQL_DRIVER]:host=$GLOBALS[MYSQL_HOST]; dbname=$GLOBALS[MYSQL_DATABASE]",$GLOBALS['MYSQL_USERNAME'], $GLOBALS['MYSQL_PASSWORD']);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec('SET CHARACTER SET utf8');
        }
        catch(Exception $e)
        {
            $errors = [
                'Message' => "<span style='color:red'><b>Message</b>: ".$e->getMessage().'</span>',
                'Line' => "<span style='color:red'><b>Line</b>: ".$e->getLine().'</span>',
                'Code' => "<span style='color:red'><b>Code</b>: ".$e->getCode().'</span>',
            ];
            foreach($errors as $error){
                echo $error . "<br>";
            }
            
        }
        return $conexion;
    }
}


?>