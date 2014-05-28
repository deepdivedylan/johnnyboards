<?php
	class BoardLocation
	{
		// state (member) variables
		private $id;
		private $boardLocation;
		private $venueId;
		
		/* constructor for a printer object
		 * input: (int) new Id
		 * input: (string) boardLocation
		 * input: (int) new venue id
		 * throws: when invalid input detected */
		public function __construct($newId, $newBoardLocation, $newVenueId)
		{
			try
			{
				// use the mutator methods since they have all input sanitization built in
				$this->setId($newId);
				$this->setPosterType($newBoardLocation);
				$this->setContactId($newVenueId);
				
			}
			catch(Exception $exception)
			{
				// rethrow the exception to the caller
				throw(new Exception("Unable to build board location", 0, $exception));
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
	    
		/* accessor method for boardLocation
		 * input: N/A
		 * output: value of boardLocation */
		public function getBoardLocation()
		{
		    return($this->boardLocation);
		}
	    
		/* mutator method for boardLocation
		 * input: new value of boardLocation
		 * output: N/A */
		public function setBoardLocation($newBoardLocation)
		{
			$newBoardLocation = htmlspecialchars($newBoardLocation);
		    
			$this->boardLocation = $newBoardLocation;
		}
	    
		/* accessor method for venueId
		 * input: N/A
		 * output: value of venueId */
		public function getVenueId()
		{
		    return($this->venueId);
		}
	    
		/* mutator method for venueId
		 * input: new value of venueId
		 * output: N/A */
		public function setVenueId($newVenueId)
		{
			 // throw out obviously bad IDs 
			if(is_numeric($newVenueId) === false)
			{
				throw(new Exception("Invalid venue id detected: $newVenueId is not numeric"));
			}
			// convert the ID to an integer 
			$newVenueId = intval($newVenueId);
		    
			 $this->venueId = $newVenueId;
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
            $query = "INSERT INTO boardLocation (idBoardLocation, boardLocation, venueId) VALUES(?, ?, ?)";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare the statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("isi", $this->id, $this->boardLocation, $this->venueId);
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
                throw(new Exception("Unable to determine board location id", 0, $exception));
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
            $query = "DELETE FROM boardLocation WHERE idBoardLocation = ?";
            
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
            $query = "UPDATE boardLocation SET boardLocation = ?, venueId = ? WHERE idBoardLocation = ?";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("sii", $this->boardLocation, $this->venueId, $this->id);
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
		public static function getBoardLocationById(&$mysqli, $id)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT idBoardLocation, boardLocation, venueId FROM boardLocation WHERE idBoardLocation = ?";
			
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
				throw(new Exception("Unable to determine board location: id not found."));
			}
			
			// get the row and create the poster object
			$row = $result->fetch_assoc();
			$boardLocation = new BoardLocation($row["idBoardLocation"], $row["boardLocation"], $row["venueId"]);
			$statement->close();
			return($boardLocation);
		}
		
		/* static method to get board location by venue id
		 * input: (pointer) to mysql
		 * input: (int) id to search by
		 * output: (arry of objects) board locations */
		public static function getBoardLocationByVenueId(&$mysqli, $id)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT idBoardLocation, boardLocation, venueId FROM boardLocation WHERE venueId = ?";
			
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
				throw(new Exception("Unable to determine board location: venue id not found."));
			}
			
			// get the rows
			$boardLocations = array();
			while($row = $result->fetch_assoc())
			{
			    // get the row
			    $boardLocations[] = new BoardLocation($row["idBoardLocation"], $row["boardLocation"], $row["venueId"]);
			}
			$statement->close();
			return($boardLocations);
		}
	}
?>