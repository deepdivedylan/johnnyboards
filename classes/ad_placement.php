<?php
	class AdPlacement
	{
		// state (member) variables
		private $clientId;
		private $boardId;
		private $contactId;
		
		/* constructor for a printer object
		 * input: (int) new client id
		 * input: (int) new board id
		 * input: (int) new contact id
		 * throws: when invalid input detected */
		public function __construct($newClientId, $newBoardId, $newContactlId)
		{
			try
			{
				// use the mutator methods since they have all input sanitization built in
				$this->setClientId($newClientId);
				$this->setBoardId($newBoardId);
				$this->setContactId($newContactId);
				
			}
			catch(Exception $exception)
			{
				// rethrow the exception to the caller
				throw(new Exception("Unable to build ad placement", 0, $exception));
			}
		}	
		
		

		/* accessor method for client id
		 * input: N/A
		 * output: value of id */
		public function getClientId()
		{
		    return($this->clientId);
		}
	    
		/* mutator method for client id
		 * input: new value of client id
		 * output: N/A */
		public function setClientId($newClientId)
		{
			// throw out obviously bad IDs 
			if(is_numeric($newClientId) === false)
			{
				throw(new Exception("Invalid client id detected: $newClientId is not numeric"));
			}
			// convert the ID to an integer 
			$newClientId = intval($newClientId);
		    
			$this->clientId = $newClientId;
		}
		
		/* accessor method for board id
		 * input: N/A
		 * output: value of id */
		public function getBoardId()
		{
		    return($this->boardId);
		}
	    
		/* mutator method for board id
		 * input: new value of board id
		 * output: N/A */
		public function setBoardId($newBoardId)
		{
			// throw out obviously bad IDs 
			if(is_numeric($newBoardId) === false)
			{
				throw(new Exception("Invalid board id detected: $newBoardId is not numeric"));
			}
			// convert the ID to an integer 
			$newBoardId = intval($newBoardId);
		    
			$this->boardId = $newBoardId;
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
				throw(new Exception("Invalid id detected: $newContactId is not numeric"));
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
            
            // a create a query template
            $query = "INSERT INTO adPlacement (clientId, boardId, contactId) VALUES(?, ?, ?)";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare the statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("iii", $this->clientId, $this->boardId, $this->contactId);
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
            $query = "DELETE FROM adPlacement WHERE clientId = ? AND boardId = ?";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("ii", $this->clientId, $this->boardId);
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
            $query = "UPDATE adPlacement SET clientId = ?, boardId = ?, contactId = ? WHERE clientId = ? AND boardId = ?";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("iiiii", $this->clientId, $this->boardId, $this->contactId, $this->clientId, $this->boardId);
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

		
		/* static method to get ad placement by client id
		 * input: (pointer) to mysql
		 * input: (int) id to search by
		 * output: (array of objects) ads */
		public static function getAdPlacementByClientId(&$mysqli, $id)
		{	
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT clientId, boardId, contactId FROM adPlacement WHERE clientId = ?";
			
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
			$ads = array();
			while($row = $result->fetch_assoc())
			{
			    // get the row
			    $ads[] = new AdPlacement($row["clientId"], $row["boardId"], $row["contactId"]);
			}
			$statement->close();
			return($ads);
		}
		
		/* static method to get ad placement by board id
		 * input: (pointer) to mysql
		 * input: (int) id to search by
		 * output: (array of objects) ads */
		public static function getAdPlacementByBoardId(&$mysqli, $id)
		{	
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT clientId, boardId, contactId FROM adPlacement WHERE boardId = ?";
			
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
			$ads = array();
			while($row = $result->fetch_assoc())
			{
			    // get the row
			    $ads[] = new AdPlacement($row["clientId"], $row["boardId"], $row["contactId"]);
			}
			$statement->close();
			return($ads);
		}
		
		/* static method to get ad placement by contact id
		 * input: (pointer) to mysql
		 * input: (int) id to search by
		 * output: (array of objects) ads */
		public static function getAdPlacementByContactId(&$mysqli, $id)
		{	
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT clientId, boardId, contactId FROM adPlacement WHERE contactId = ?";
			
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
			$ads = array();
			while($row = $result->fetch_assoc())
			{
			    // get the row
			    $ads[] = new AdPlacement($row["clientId"], $row["boardId"], $row["contactId"]);
			}
			$statement->close();
			return($ads);
		}
		
		/* static method to get ad placement by client and board id
		 * input: (pointer) to mysql
		 * input: (int) id to search by
		 * output: (array of objects) ads */
		public static function getAdPlacementByClinetAndBoardId(&$mysqli, $clientId, $boardId)
		{	
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT clientId, boardId, contactId FROM adPlacement WHERE clientId = ? AND boardId = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("ii", $clientId, $boardId);
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
			
			// get the row
			$row = $result->fetch_assoc();
			$ad = new AdPlacement($row["clientId"], $row["boardId"], $row["contactId"]);
			$statement->close();
			return($ad);
		}
	}
?>