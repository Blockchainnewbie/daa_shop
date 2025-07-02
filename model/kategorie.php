<?php

require_once( "model/db.php" );

class Kategorie
{
	// welche Variablen braucht ein Kategorie ?
    private  $id;
	public   $name;
	public   $beschreibung;
	private  $deleted;


	function __construct( $id )
	{	
        if( $id < 1 ) // wenn leerer Kategorie >> mache nichts 
        {
        	return;
        }

		// user über ID aus DB lesen und in PHP Variablen schreiben
		$db  = DB::getInstanz();
		$res = $db->query( "SELECT * FROM kategorie 
									 WHERE deleted = 0 AND  
									       id      = ". $id );
		$row = $db->nextRow( $res );
		
		if( $row ) 
		{
			// db werte in php kopieren 
			$this->id       	= $id;
			$this->name 		= $row['name'];
			$this->beschreibung = $row['beschreibung'];		
			$this->deleted      = $row['deleted'];	
		}	
	}


	public function getId()  // Getter 
	{
		return $this->id;
	}
	

	function create()
	{
		$db  = DB::getInstanz();

		$sql = " INSERT INTO kategorie  
		                   (    name              ,   beschreibung                , deleted  )  
		             VALUES 
		                   ( '". $this->name ."'  ,  '". $this->beschreibung ."'  , 0     	) ";

		$res = $db->query( $sql );				
	}


	function update()
	{
		$db  = DB::getInstanz();

		$sql = "UPDATE kategorie SET  name 	          = '". $this->name         ."' ,
								      beschreibung    = '". $this->beschreibung ."' ,  
								      deleted		  =  ". $this->deleted      ."  
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
	  if(  $this->id > 0 )
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
		$sql = "SELECT * FROM kategorie WHERE deleted = 0 ";
        $res = $db->query( $sql );

        $rueckgabe = array(); // leeres array für rückgabe machen

        // solange es eine weitere Zeile gibt .... 
        while(    $row = $db->nextRow( $res )    )  
        {
        	//  []  << heißt: neues Elment hinten ans Array dranhängen
        	$rueckgabe[] = new Kategorie(  $row['id']  );
        }

        return $rueckgabe;
	}

}


?>