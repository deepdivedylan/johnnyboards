<?php
	class Printer
	{
		// state (member) variables
		private $id;
		private $hoursOpen;
		private $longitude;
		private $latitude;
		private $areasCovered;
		private $contactId;
		
		/* constructor for a printer object
		 * input: (int) new Id
		 * input: (string) new hours open
		 * input: (double) new longitude
		 * input: (double) new latitude
		 * input: (int) new contact id
		 * throws: when invalid input detected */
		public function __construct($newId, $newHoursOpen, $newLongitude, $newLatitude, $newAreasCovered, $newContactlId)
		{
			try
			{
				// use the mutator methods since they have all input sanitization built in
				$this->setId($newId);
				$this->setHoursOpen($newHoursOpen);
				$this->setLongitude($newLongitude);
				$this->setLatitude($newLatitude);
				$this->setAreasCovered($newAreasCovered);
				$this->setContactId($newContactId);
				
			}
			catch(Exception $exception)
			{
				// rethrow the exception to the caller
				throw(new Exception("Unable to build printer", 0, $exception));
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
				throw(new Exception("Invalid printer id detected: $newId is not numeric"));
			}
			// convert the ID to an integer 
			$newId = intval($newId);
			// throw out negative IDs except -1, which is our "new" printer 
			if($newId < -1)
			{
				throw(new Exception("Invalid printer id detected: $newId is less than -1"));
			}
		    
		    $this->id = $newId;
		}
	    
		/* accessor method for hoursOpen
		 * input: N/A
		 * output: value of hoursOpen */
		public function getHoursOpen()
		{
		    return($this->hoursOpen);
		}
	    
		/* mutator method for hoursOpen
		 * input: new value of hoursOpen
		 * output: N/A */
		public function setHoursOpen($newHoursOpen)
		{
		    // strip out tags
		    $newHoursOpen = htmlspecialchars($newHoursOpen);
		    
		    $this->hoursOpen = $newHoursOpen;
		}
	    
		/* accessor method for longitude
		 * input: N/A
		 * output: value of longitude */
		public function getLongitude()
		{
		    return($this->longitude);
		}
	    
		/* mutator method for longitude
		 * input: new value of longitude
		 * output: N/A */
		public function setLongitude($newLongitude)
		{
			// throw out obviously bad longitudes
			if(is_numeric($newLongitude) === false)
			{
				throw(new Exception("Invalid longitude detected: $newLongitude is not numeric"));
			}
			// convert the ID to a double
			$newLongitude = floatval($newLongitude);
		    
			$this->longitude = $newLongitude;
		}
	    
		/* accessor method for latitude
		 * input: N/A
		 * output: value of latitude */
		public function getLatitude()
		{
		    return($this->latitude);
		}
	    
		/* mutator method for latitude
		 * input: new value of latitude
		 * output: N/A */
		public function setLatitude($newLatitude)
		{
			// throw out obviously bad latitudes
			if(is_numeric($newLatitude) === false)
			{
				throw(new Exception("Invalid latitude detected: $newLatitude is not numeric"));
			}
			// convert the ID to a double
			$newLatitude = floatval($newLatitude);
		    
			$this->latitude = $newLatitude;
		}
	    
		/* accessor method for areasCovered
		 * input: N/A
		 * output: value of areasCovered */
		public function getAreasCovered()
		{
		    return($this->areasCovered);
		}
	    
		/* mutator method for areasCovered
		 * input: new value of areasCovered
		 * output: N/A */
		public function setAreasCovered($newAreasCovered)
		{
		    // strip out tags
		    $newAreasCovered = htmlspecialchars($newAreasCovered);
		    
		    $this->areasCovered = $newAreasCovered;
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
				throw(new Exception("Invalid printer id detected: $newContactId is not numeric"));
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
            $query = "INSERT INTO printer (idprinter, hoursOpen, longitude, latitude, areasCovered, contactId) VALUES(?, ?, ?, ?, ?, ?)";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare the statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("isddsi", $this->id, $this->hoursOpen, $this->longitude, $this->latitude, $this->areasCovered, $this->contactId);
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
                throw(new Exception("Unable to determine printer id", 0, $exception));
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
            $query = "DELETE FROM printer WHERE idprinter = ?";
            
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
            $query = "UPDATE printer SET hoursOpen = ?, longitude = ?, latitude = ?, areasCovered = ?, contactId = ? WHERE idprinter = ?";
            
            // prepare the query statement
            $statement = $mysqli->prepare($query);
            if($statement === false)
            {
                throw(new Exception("Unable to prepare statement."));
            }
            
            // bind parameters to the query template
            $wasClean = $statement->bind_param("sddsii", $this->hoursOpen, $this->longitude, $this->latitude, $this->areasCovered, $this->contactId, $this->id);
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

		
		/* static method to get printer by id
		 * input: (pointer) to mysql
		 * input: (int) id to search by
		 * output: (object) printer */
		public static function getPrinterById(&$mysqli, $id)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT idprinter, hoursOpen, longitude, latitude, areasCovered, contactId FROM printer WHERE idprinter = ?";
			
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
				throw(new Exception("Unable to determine printer: id not found."));
			}
			
			// get the row and create the printer object
			$row = $result->fetch_assoc();
			$printer = new Printer($row["idprinter"], $row["hoursOpen"], $row["longitude"], $row["latitude"], $row["areasCovered"], $row["contactId"]);
			$statement->close();
			return($printer);
		}
		
		
		/* static method to get printer by contact id
		 * input: (pointer) to mysql
		 * input: (int) contact id to search by
		 * output: (object) printer */
		public static function getPrinterByContactId(&$mysqli, $id)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT idprinter, hoursOpen, longitude, latitude, areasCovered, contactId FROM printer WHERE contactId = ?";
			
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
				throw(new Exception("Unable to determine printer: id not found."));
			}
			
			// get the row and create the printer object
			$row = $result->fetch_assoc();
			$printer = new Printer($row["idprinter"], $row["hoursOpen"], $row["longitude"], $row["latitude"], $row["areasCovered"], $row["contactId"]);
			$statement->close();
			return($printer);
		}
	}
?>