<?php
	class Poster
	{
		// state (member) variables
		private $id;
		private $posterType;
		private $contactId;
		
		/* constructor for a printer object
		 * input: (int) new Id
		 * input: (int) posterType
		 * input: (int) new contact id
		 * throws: when invalid input detected */
		public function __construct($newId, $newPosterType, $newContactId)
		{
			try
			{
				// use the mutator methods since they have all input sanitization built in
				$this->setId($newId);
				$this->setPosterType($newPosterType);
				$this->setContactId($newContactId);
				
			}
			catch(Exception $exception)
			{
				// rethrow the exception to the caller
				throw(new Exception("Unable to build poster", 0, $exception));
			}
		}	
		
		

		/* accessor method for id
		 * input: N/A
		 * output: value of id */
		public function getId()
		{
		    return($this->id);
		}
	    
		/* mutator method for id
		 * input: new value of id
		 * output: N/A */
		public function setId($newId)
		{
			// throw out obviously bad IDs 
			if(is_numeric($newId) === false)
			{
				throw(new Exception("Invalid poster id detected: $newId is not numeric"));
			}
			// convert the ID to an integer 
			$newId = intval($newId);
			// throw out negative IDs except -1, which is our "new" poster 
			if($newId < -1)
			{
				throw(new Exception("Invalid poster id detected: $newId is less than -1"));
			}
		    
			$this->id = $newId;
		}
	    
		/* accessor method for posterType
		 * input: N/A
		 * output: value of posterType */
		public function getPosterType()
		{
		    return($this->posterType);
		}
	    
		/* mutator method for posterType
		 * input: new value of posterType
		 * output: N/A */
		public function setPosterType($newPosterType)
		{
			// throw out obviously bad IDs 
			if(is_numeric($newPosterType) === false)
			{
				throw(new Exception("Invalid poster id detected: $newPosterType is not numeric"));
			}
			// convert the ID to an integer 
			$newPosterType = intval($newPosterType);
		    
			$this->posterType = $newPosterType;
		}
	    
		/* accessor method for contactId
		 * input: N/A
		 * output: value of contactId */
		public function getContactId()
		{
		    return($this->contactId);
		}
	    
		/* mutator method for contactId
		 * input: new value of contactId
		 * output: N/A */
		public function setContactId($newContactId)
		{
			 // throw out obviously bad IDs 
			if(is_numeric($newContactId) === false)
			{
				throw(new Exception("Invalid poster id detected: $newContactId is not numeric"));
			}
			// convert the ID to an integer 
			$newContactId = intval($newContactId);
		    
			 $this->contactId = $newContactId;
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
            $query = "INSERT INTO poster (idposter, posterType, contactId) VALUES(?, ?, ?)";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare the statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("iii", $this->id, $this->posterType, $this->contactId);
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
                $this->setId($mysqli->insert_id);
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
            $query = "DELETE FROM poster WHERE idposter = ?";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("i", $this->id);
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
            $query = "UPDATE printer SET posterType = ?, contactId = ? WHERE idposter = ?";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("iii", $this->posterType, $this->contactId, $this->id);
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
		
		/* static method to get poster by id
		 * input: (pointer) to mysql
		 * input: (int) id to search by
		 * output: (object) poster */
		public static function getPosterById(&$mysqli, $id)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT idposter, posterType, contactId FROM poster WHERE idposter = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("i", $id);
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
				throw(new Exception("Unable to determine poster: id not found."));
			}
			
			// get the row and create the poster object
			$row = $result->fetch_assoc();
			$poster = new Poster($row["idposter"], $row["posterType"], $row["contactId"]);
			$statement->close();
			return($poster);
		}
		
		/* static method to get poster by id
		 * input: (pointer) to mysql
		 * input: (int) poster id to search by
		 * output: (array of objects) posters */
		public static function getPostersByPosterType(&$mysqli, $posterType)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT idposter, posterType, contactId FROM poster WHERE posterType = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("i", $posterType);
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
			if($result === false)
			{
				throw(new Exception("Unable to determine poster: poster type not found."));
			}
		
			// get the rows
			$posters = array();
			while($row = $result->fetch_assoc())
			{
			    // get the row
			    $posters[] = new Poster($row["idposter"], $row["posterType"], $row["contactId"]);
			}
			$statement->close();
			return($posters);
		}
		
		/* static method to get poster by contact id
		 * input: (pointer) to mysql
		 * input: (int) id to search by
		 * output: (arry of objects) posters */
		public static function getPostersByContactId(&$mysqli, $id)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT idposter, posterType, contactId FROM poster WHERE contactId = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("i", $id);
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
			if($result === false)
			{
				throw(new Exception("Unable to determine poster: id not found."));
			}
			
			// get the rows
			$posters = array();
			while($row = $result->fetch_assoc())
			{
			    // get the row
			    $posters[] = new Poster($row["idposter"], $row["posterType"], $row["contactId"]);
			}
			$statement->close();
			return($posters);
		}
	}
?>