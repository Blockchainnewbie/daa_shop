<?php

require_once( "model/db.php" );

class Artikel
{
	// welche Variablen braucht ein Artikel ?
    private  $id;
	public   $name;
	public   $beschreibung;
	public   $preis;
	private  $deleted;


	function __construct( $id )
	{	
        if( $id < 1 ) // wenn leerer artikel >> mache nichts 
        {
        	return;
        }

		// user über ID aus DB lesen und in PHP Variablen schreiben
		$db  = DB::getInstanz();
		$res = $db->query( "SELECT * FROM artikel 
									 WHERE deleted = 0 AND  id = ". $id );
		$row = $db->nextRow( $res );
		
		if( $row ) 
		{
			// db werte in php kopieren 
			$this->id       	= $id;
			$this->name 		= $row['name'];
			$this->beschreibung = $row['beschreibung'];	
			$this->preis        = $row['preis'];	
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

		$sql = " INSERT INTO artikel  ( name  , 
									    beschreibung ,
									    preis  )  
		                     VALUES 
		                           ( '". $this->name ."'  ,   
		                           	 '". $this->beschreibung ."'  ,
		                           	 '". $this->preis ."'  
		                           	) ";

		$res = $db->query( $sql );				
	}


	function update()
	{
		$db = DB::getInstanz();

		$sql = "UPDATE artikel SET  name 	      = '". $this->name         ."' ,
								    beschreibung  = '". $this->beschreibung ."' , 
								    preis  		  = '". $this->preis        ."' , 
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
		$sql = "SELECT * FROM artikel WHERE deleted = 0 ";
        $res = $db->query( $sql );

        $rueckgabe = array(); // leeres array für rückgabe machen

        // solange es eine weitere Zeile gibt .... 
        while(    $row = $db->nextRow( $res )    )  
        {
        	//  []  << heißt: neues Elment hinten ans Array dranhängen
        	$rueckgabe[] = new Artikel(  $row['id']  );
        }

        return $rueckgabe;
	}

}


?>