<?php
	class Type
	{
		// state (member) variables
		private $idtype;
		private $name;
		
		/* constructor for a printer object
		 * input: (int) new Id
		 * input: (string) name
		 * throws: when invalid input detected */
		public function __construct($newIdtype, $newName)
		{
			try
			{
				// use the mutator methods since they have all input sanitization built in
				$this->setId($newIdtype);
				$this->setName($newName);
				
			}
			catch(Exception $exception)
			{
				// rethrow the exception to the caller
				throw(new Exception("Unable to build type", 0, $exception));
			}
		}	
		
		/* accessor method for id
		 * input: N/A
		 * output: value of id */
		public function getIdtype()
		{
		    return($this->idtype);
		}
	    
		/* mutator method for id
		 * input: new value of id
		 * output: N/A */
		public function setIdtype($newIdtype)
		{
			// throw out obviously bad IDs 
			if(is_numeric($newIdtype) === false)
			{
				throw(new Exception("Invalid printer id detected: $newIdtype is not numeric"));
			}
			// convert the ID to an integer 
			$newIdtype = intval($newIdtype);
			// throw out negative IDs except -1, which is our "new" poster 
			if($newIdtype < -1)
			{
				throw(new Exception("Invalid poster id detected: $newIdtype is less than -1"));
			}
		    
			$this->idtype = $newIdtype;
		}
	    
		/* accessor method for name
		 * input: N/A
		 * output: value of name */
		public function getName()
		{
		    return($this->name);
		}
	    
		/* mutator method for name
		 * input: new value of name
		 * output: N/A */
		public function setName($newName)
		{
		    //strip out tags
		    $newName = htmlspecialchars($newName);
		    
		    $this->name = $newName;
		}

// **********************************************mySQL mutator methods**************************************************
    
        /* insert a new object into mySQL
         * function insert
         * input: (pointer) mySQL connection, by reference
         * output: N/A
         * throws if the object could not be inserted
         */
        
        public function insert(&$mysqli)
        {
            // handle degenerate cases
            if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
            {
                throw(new Exception("Non mySQL pointer detected."));
            }
            
            // verify the id is -1 (i.e., a new experience)
            if($this->id !== -1)
            {
                throw(new Exception("Non new id detected."));
            }
            
            // a create a query template
            $query = "INSERT INTO type(idtype, name) VALUES(?, ?)";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare the statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("is", $this->idtype, $this->name);
            if($wasClean === false)
            {
                throw(new Exception("Unable to bind paramenters."));
            }
            
            // ok, let's rock!
            if($statement->execute() === false)
            {
                throw(new Exception("Unable to execute the statement."));
            }
            
            $statement->close();
            
            // reassign the id, grabbing it from mySQL
            try
            {
                $this->setIdtype($mysqli->insert_id);
            }
            catch(Exception $exception)
            {
                throw(new Exception("Unable to determine poster id", 0, $exception));
            }
        }
        
        /* function to delete
         * input: (pointer) mySQL connection, by reference
         * output: N/A
         * throws: if the object could not be deleted */
        public function delete(&$mysqli)
        {
            // check for a good mySQL pointer
            if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
            {
                throw(new Exception("Non mySQL pointer detected."));
            }
            
            // verify the id is not -1 (which would be a new user)
            if($this->id === -1)
            {
                throw(new Exception("New id detected"));
            }
            
            // create the query template
            $query = "DELETE FROM type WHERE idtype = ?";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("i", $this->idtype);
            if($wasClean === false)
            {
                throw(new Exception("Unable to bind paramenters."));
            }
            
            // ok, let's rock!
            if($statement->execute() === false)
            {
                throw(new Exception("Unable to execute the statement."));
            }
            
            $statement->close();
               
        }
        
        /* update function
         * input: (pointer) mysql connection
         * output: n/a
         * throws: when the object was not updated */
        public function update(&$mysqli)
        {
            // check for a good mySQL pointer
            if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
            {
                throw(new Exception("Non mySQL pointer detected."));
            }
            
            // verify the id is not -1 (which would be a new user)
            if($this->id === -1)
            {
                throw(new Exception("New id detected"));
            }
            
            // create the query template
            $query = "UPDATE type SET name = ? WHERE idtype = ?";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("si", $this->name, $this->id);
            if($wasClean === false)
            {
                throw(new Exception("Unable to bind paramenters."));
            }
            
            // ok, let's rock!
            if($statement->execute() === false)
            {
                throw(new Exception("Unable to execute the statement."));
            }
            
            $statement->close();
        }
        
// *****************************************************Static Methods**************************************************
		
		/* static method to get poster type by id
		 * input: (pointer) to mysql
		 * input: (int) id to search by
		 * output: (object) poster type */
		public static function getPosterTypeById(&$mysqli, $idtype)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT idtype, name FROM type WHERE idtype = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("i", $idtype);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute the statement."));
			}
			
			// get the result and make a new object
			$result = $statement->get_result();
			if($result === false || $result->num_rows !== 1)
			{
				throw(new Exception("Unable to determine poster type: id not found."));
			}
			
			// get the row and create the poster object
			$row = $result->fetch_assoc();
			$type = new Type($row["idtype"], $row["name"]);
			$statement->close();
			return($type);
		}
		
		/* static method to get poster type by id
		 * input: (pointer) to mysql
		 * input: (string) name to search by
		 * output: (object) poster type */
		public static function getPosterTypeByName(&$mysqli, $name)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT idtype, name FROM type WHERE name = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("s", $name);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute the statement."));
			}
			
			// get the result and make a new object
			$result = $statement->get_result();
			if($result === false || $result->num_rows !== 1)
			{
				throw(new Exception("Unable to determine poster type: name not found."));
			}
			
			// get the row and create the poster object
			$row = $result->fetch_assoc();
			$type = new Type($row["idtype"], $row["name"]);
			$statement->close();
			return($type);
		}
	}
?>