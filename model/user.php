<?php

require_once( "db.php" );

class User
{
	// welche Variablen braucht ein User ?
    private  $id;
    public   $api_id;
	public   $username;
	public   $password;
	private  $deleted;


	function __construct( $id )
	{	
        if( $id < 1 ) // wenn leerer user >> mache nichts 
        {
        	return;
        }

		// user über ID aus DB lesen und in PHP Variablen schreiben
		$db  = DB::getInstanz();
		$res = $db->query( "SELECT * FROM user 
									 WHERE deleted = 0 AND
									       id = ". $id );
		$row = $db->nextRow( $res );
		
		if( $row ) //  ur wenn es eine Zeile gibt 
		{
			// db werte in php kopieren 
			$this->id       = $id;
			$this->api_id   = $id;
			$this->username = $row['username'];
			$this->password = $row['password'];	
		}	
	}


	public function getId()  // Getter 
	{
		return $this->id;
	}


	function create()
	{
		$db  = DB::getInstanz();

		$sql = " INSERT INTO user  ( username  , 
									 password   )  
		                     VALUES 
		                           ( '". $this->username ."'  ,   
		                           	 '". $this->password ."'  ) ";

		$res = $db->query( $sql );				
	}


	function update()
	{
		$db = DB::getInstanz();

		$sql = "UPDATE user SET  username = '". $this->username ."' ,
								 password = '". $this->password ."' , 
								 deleted  = '". $this->deleted  ."'  
							WHERE
								 id = ". $this->id ."
								 ";
        $db->query( $sql );
	} 


	function del()
	{
		$this->deleted = 1;
		$this->update();
	}


	function save()
	{
		if( $this->id > 0 )
		{
			$this->update();
		}
		else
		{
			$this->create();
		}
	}


	static function getAll()
	{
		$db  = DB::getInstanz();
		$sql = "SELECT id FROM user WHERE deleted = 0 ";
        $res = $db->query( $sql );

        $rueckgabe = array(); // leeres array für rückgabe machen

        // solange es eine weitere Zeile gibt .... 
        while(    $row = $db->nextRow( $res )    )  
        {
        	//  []  << heißt: neues Elment hinten ans Array dranhängen
        	$rueckgabe[] = new User(  $row['id']  );
        }

        return $rueckgabe;
	}


	static function getUserData( $username , $password  )
	{
		$db  = DB::getInstanz();

		$sql = "SELECT id FROM  user 
						  WHERE deleted  = 0 AND 
						        username = '". $username ."' AND
						        password = '". $password ."'     ";

        $res = $db->query( $sql );

        // wie viele Regebniszeilen sind im Result ???
        if(  $res->num_rows == 1 )
        {
        	// die ID des Users zurückgeben
        	 $row = $db->nextRow( $res ) ;

        	 return $row['id'];
        } 
        else
        {
        	return 0; // keinen User (oder mehrere) gefunden 
        }
	}


	static function checkUserData( $username , $password  )
	{
		$db  = DB::getInstanz();

		$sql = "SELECT id FROM  user 
						  WHERE deleted  = 0 AND 
						        username = '". $username ."' AND
						        password = '". $password ."'     ";

        $res = $db->query( $sql );

        // wie viele Regebniszeilen sind im Result ???
        if(  $res->num_rows == 1 )
        {
        	// die ID des Users zurückgeben
        	 $row = $db->nextRow( $res ) ;

        	 return $row['id'];
        } 
        else
        {
        	return 0; // keinen User (oder mehrere) gefunden 
        }
	}

}


?>