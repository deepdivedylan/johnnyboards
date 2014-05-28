<?php
        class Venue
        {
            //member variables
            private $idVenue;
            private $longitude;
            private $latitude;
            private $contactId;
            
            /*contstructor
             *input: (integer) idVenue 
             *input: (double) longitude
             *input: (double) latitude
             *input: (integer) contactId
             *throws: when bad input is detected*/
            
            public function __construct($newIdVenue, $newLongitude, $newLatitude, $newContactId)
            {
                try
                {
                    $this->setIdVenue($newIdVenue);
                    $this->setCompanyName($newLongitude);
                    $this->setLatitude($newLatitude);
                    $this->setContactId($newContactId);
                }
                catch(Exception $e)
                {
                    throw(new Exception("Unable to build venue", 0, $e));
                }
            }
            
            /*******************************GETTERS & SETTERS************************************/
            

            /* accessor method for idVenue
             * input: N/A
             * output: value of idVenue */
            public function getIdVenue()
            {
                return($this->idVenue);
            }
         
            /* mutator method for idVenue
             * input: new value of idVenue
             * output: N/A */
            public function setIdVenue($newIdVenue)
            {
                //throw out obviously bad IDs
                if(is_numeric($newIdVenue) === false)
                {
                        throw(new Exception("Invalid id detected: $newIdVenue"));
                }
                
                //convert the ID to an integer
                $newIdVenue = intval($newIdVenue);
                
                // throw out negative IDs, except -1 which is our "new" client
                if($newIdVenue < -1)
                {
                        throw(new Exception("Invalid id detected: $newIdVenue"));
                }
                
                //sanitized; assign the value
                $this->idVenue = $newIdVenue;
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
                //trim the input
                $newLongitude = trim($newLongitude);
                
                //throw out non-numeric
                if(is_numeric($newLongitude) === false)
                {
                        throw(new Exception("Invalid longitude detected: $newLongitude"));
                }
                
                //sanitized, assign
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
                //trim the input
                $newLatitude = trim($newLatitude);
                
                 //throw out non-numeric
                if(is_numeric($newLatitude) === false)
                {
                        throw(new Exception("Invalid latitude detected: $newLatitude"));
                }
                
                //sanitized, assign
                $this->latitude = $newLatitude;
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
                 //throw out obviously bad IDs
                if(is_numeric($newContactId) === false)
                {
                        throw(new Exception("Invalid contact id detected: $newContactId"));
                }
                
                //convert the ID to an integer
                $newContactId = intval($newContactId);
                
                // throw out negative client types
                if($newContactId <= 0)
                {
                        throw(new Exception("Invalid contact id detected: $newContactId"));
                }
                
                //sanitized; assign the value
                $this->contactId = $newContactId;
            }
            
            /****************************INSERT, UPDATE & DELETE*********************************/
            /* inserts a new object into mySQL
            * input: (pointer) mySQL connection, by reference
            * output: n/a
            * throws: if the object could not be inserted */
            public function insert(&$mysqli)
            {
                   //handle degenerate cases
                   if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
                   {
                           throw(new Exception("Non mySQL pointer detected"));
                   }
                   
                   //verify the id is -1 (i.e., a new user)
                   if($this->idVenue !== -1)
                   {
                           throw(new Exception("Non new id detected"));
                   }
                   
                   //create query template
                   $query = "INSERT INTO client(longitude, latitude, contactId) VALUES(?, ?, ?)";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   
                   //bind parameters to the query template
                   $wasClean = $statement->bind_param("ddi", $this->longitude,$this->latitude, $this->contactId);
                   if($wasClean === false)
                   {
                           throw(new Exception("Unable to bind parameters"));
                   }
                   
                   //Ok, let"s rock!
                   if($statement->execute() === false)
                   {
                           throw(new Exception("Unable to execute statement"));
                   }
                   
                   //clean up the statement
                   $statement->close();
                   
                   //update the venue id in the object
                   try
                   {
                        $this->setId($mysqli->insert_id);
                   }
                   catch(Exception $e)
                   {
                        throw(new Exception("Unable to determine venue id", 0, $e)); 
                   }
            }
            
            /*method  to delete
             *input:(pointer) mySQL connection, by reference
             *output: n/a
             *throws: if the object could not be delete */
            public function delete(&$mysqli)
            {
                   if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
                   {
                           throw(new Exception("Unable to prepare statement"));	
                   }
                   
                   if($this->idVenue === -1)
                   {
                           throw(new Exception("New idVenue detected"));
                   }
                   
                   //create query template
                   $query = "DELETE FROM venue WHERE idVenue = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                   $wasClean = $statement->bind_param("i", $this->idVenue);
                   
                   if($wasClean === false)
                   {
                           throw(new Exception("Unable to bind parameters"));
                   }
                   
                   //Ok, let"s rock yet again!
                   if($statement->execute() === false)
                   {
                           throw(new Exception("Unable to execute statement"));
                   }
                   
                   //clean up the statement
                   $statement->close();
            }
            /* updates this object in mySQl
             * input: (pointer) mySQL connection, by reference
             * output: n/a
             * throws: if the object could not be updated */
           public function update(&$mysqli)
           {
                   //handle degenerate cases
                   if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
                   {
                           throw(new Exception("Non mySQL pointer detected"));	
                   }
                   
                   //verify the id is not -1 (i.e., an existing user)
                   if($this->idVenue === -1)
                   {
                           throw(new Exception("New id detected"));
                   }
                   
                   //create query template
                   $query = "UPDATE venue SET longitude = ?, latitude = ?, contactId = ? WHERE idVenue = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                    $wasClean = $statement->bind_param("ddii", $this->longitude,$this->latitude, $this->contactId, $this->idVenue);
                   if($wasClean === false)
                   {
                           throw(new Exception("Unable to bind parameters"));
                   }
                   
                   //Ok, let"s rock yet again!
                   if($statement->execute() === false)
                   {
                           throw(new Exception("Unable to execute statement"));
                   }
                   
                   //clean up the statement
                   $statement->close();
           }
           
           /***********************************static methods***********************************/
           
           /* static method to get venue by email
            * input: (pointer) mysqli
            * input: (string) $key contactId to search by
            * output: (object) venue */
           public static function getVenueByContactId(&$mysqli, $contactId)
           {
                   //handle degenerate cases
                   if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
                   {
                           throw(new Exception("Non mySQL pointer detected"));	
                   }
                   
                   //create query template
                   $query = "SELECT idVenue, longitude, latitude, contactId FROM client WHERE contactId = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                   $wasClean = $statement->bind_param("i", $contactId);
                   if($wasClean === false)
                   {
                           throw(new Exception("Unable to bind parameters"));
                   }
                   
                   //Ok, let"s rock yet again!
                   if($statement->execute() === false)
                   {
                           throw(new Exception("Unable to execute statement"));
                   }
                   
                   //get the result $ make sure only 1 row is there
                   $result = $statement->get_result();
                   if($result === false || $result->num_rows !== 1)
                   {
                           throw(new Exception("Unable to determine client: contactId not found"));
                   }
                   
                   //get the row
                    $row   = $result->fetch_assoc();
                    $venue = new Venue($row["idVenue"], $row["longitude"], $row["latitude"], $row["contactId"]);
                   return($venue);
           
                   //clean up the statement
                   $statement->close();
           }
           
           /* static method to get venue by id
            * input: (pointer) mysqli
            * input: (string) $key idVenue to search by
            * output: (object) venue */
           public static function getVenueById(&$mysqli, $idVenue)
           {
                   //handle degenerate cases
                   if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
                   {
                           throw(new Exception("Non mySQL pointer detected"));	
                   }
                   
                   //create query template
                   $query = "SELECT idVenue, longitude, latitude, contactId FROM client WHERE idVenue = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                   $wasClean = $statement->bind_param("i", $idVenue);
                   if($wasClean === false)
                   {
                           throw(new Exception("Unable to bind parameters"));
                   }
                   
                   //Ok, let"s rock yet again!
                   if($statement->execute() === false)
                   {
                           throw(new Exception("Unable to execute statement"));
                   }
                   
                   //get the result $ make sure only 1 row is there
                   $result = $statement->get_result();
                   if($result === false || $result->num_rows !== 1)
                   {
                           throw(new Exception("Unable to determine contact: id not found"));
                   }
                   
                   //get the row
                   $row   = $result->fetch_assoc();
                   $venue = new Venue($row["idVenue"], $row["longitude"], $row["latitude"], $row["contactId"]);
                   return($venue);
           
                   //clean up the statement
                   $statement->close();
           }
            
        }
?>