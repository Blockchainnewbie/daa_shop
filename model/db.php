<?php


class DB
{
  // Instanz Variablen 
  private $host    = "127.0.0.1";
  private $port    = 3306;
  private $db_user = "root"; 
  private $db_pass = ""; 
  private $db_name = "daa_shop";
  
  private $con     = null;

  // SINGLETON  
  // speicher "DIE EINE" DB Instanz 
  static public $instance = null;
  
  
  function __construct()
  {
      // Verbindung aufbauen 
      $this->con = mysqli_connect(   $this->host    , 
                                     $this->db_user , 
                                     $this->db_pass ,
                                     $this->db_name , 
                                     $this->port     );
      echo "<h3> Neue Verbindung .............</h3> \n ";
  }

  public static function getInstanz()
  {
    // wenn es schon eine Instanz gibt >> diese verwenden (zurückgeben)
    if( DB::$instance != null )
     {       
        return DB::$instance;
     }
        
    // die ERSTE Instanz die je von der DB Klasse gemacht wird speichern.
    DB::$instance = new DB();

    return DB::$instance;
  }
  

  function query( $sql_txt )
  {
  	 $result = mysqli_query(  $this->con ,  $sql_txt  );  
  	 
  	 return $result;
  }
  
  
  function nextRow( $result )
  {
  	 $row = mysqli_fetch_assoc( $result );

  	 return $row;
  }
  

}


?>