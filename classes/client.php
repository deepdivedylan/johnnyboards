<?php
        class Client
        {
            //member variables
            private $idclient;
            private $contractStart;
            private $contractRenew;
            private $clientType;
            private $contactId;
            
            /*contstructor
             *input: (integer) idclient
             *input: (string) contract start
             *input: (string) contract renew
             *input: (integer) client type
             *input: (string) contactId
             *throws: when bad input is detected*/
            
            public function __construct($newIdclient, $newContractStart, $newContractRenew, $newClientType, $newContactId)
            {
                try
                {
                    $this->setIdclient($newIdclient);
                    $this->setContractStart($newContractStart);
                    $this->setContractRenew($newContractRenew);
                    $this->setClientType($newClientType);
                    $this->setContactId($newContactId);
                }
                catch(Exception $e)
                {
                    throw(new Exception("Unable to build contact", 0, $e));
                }
            }
            
            /*******************************GETTERS & SETTERS************************************/
            

            /* accessor method for idclient
             * input: N/A
             * output: value of idclient */
            public function getIdclient()
            {
                return($this->idclient);
            }
         
            /* mutator method for idclient
             * input: new value of idclient
             * output: N/A */
            public function setIdclient($newIdclient)
            {
                //throw out obviously bad IDs
                if(is_numeric($newIdclient) === false)
                {
                        throw(new Exception("Invalid id detected: $newIdclient"));
                }
                
                //convert the ID to an integer
                $newIdclient = intval($newIdclient);
                
                // throw out negative IDs, except -1 which is our "new" client
                if($newIdclient < -1)
                {
                        throw(new Exception("Invalid id detected: $newIdclient"));
                }
                
                //sanitized; assign the value
                $this->idclient = $newIdclient;
            }
         
            /* accessor method for contractStart
             * input: N/A
             * output: value of contractStart */
            public function getContractStart()
            {
                return($this->contractStart);
            }
         
            /* mutator method for contractStart
             * input: new value of contractStart
             * output: N/A */
            public function setContractStart($newContractStart)
            {
                //trim the input
                $newContractStart = trim($newContractStart);
                
                //strip out HTML special characters
                $newContractStart = htmlspecialchars($newContractStart);
                
                //test against a regular expression
                $regexp = "/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})$/";
                if(preg_match($regexp, $newContractStart) !== 1)
                {
                    throw(new Exception("Invalid contract date detected: $newContractStart"));
                }
                
                //sanitized, assign
                $this->contractStart = $newContractStart;
            }
         
            /* accessor method for contractRenew
             * input: N/A
             * output: value of contractRenew */
            public function getContractRenew()
            {
                return($this->contractRenew);
            }
         
            /* mutator method for contractRenew
             * input: new value of contractRenew
             * output: N/A */
            public function setContractRenew($newContractRenew)
            {
                //trim the input
                $newContractRenew = trim($newContractRenew);
                
                //strip out HTML special characters
                $newContractRenew = htmlspecialchars($newContractRenew);
                
                //test against a regular expression
                $regexp = "/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})$/";
                if(preg_match($regexp, $newContractRenew) !== 1)
                {
                    throw(new Exception("Invalid contract date detected: $newContractRenew"));
                }
                
                //sanitized, assign
                $this->contractRenew = $newContractRenew;
            }
         
            /* accessor method for clientType
             * input: N/A
             * output: value of clientType */
            public function getClientType()
            {
                return($this->clientType);
            }
         
            /* mutator method for clientType
             * input: new value of clientType
             * output: N/A */
            public function setClientType($newClientType)
            {
                 //throw out obviously bad IDs
                if(is_numeric($newClientType) === false)
                {
                        throw(new Exception("Invalid client type detected: $newClientType"));
                }
                
                //convert the ID to an integer
                $newClientType = intval($newClientType);
                
                // throw out negative client types
                if($newClientType <= 0)
                {
                        throw(new Exception("Invalid client type detected: $newClientType"));
                }
                
                //sanitized; assign the value
                $this->clientType = $newClientType;
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
                   if($this->idclient !== -1)
                   {
                           throw(new Exception("Non new id detected"));
                   }
                   
                   //create query template
                   $query = "INSERT INTO client(contractStart, contractRenew, clientType, contactId) VALUES(?, ?, ?, ?)";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   
                   //bind parameters to the query template
                   $wasClean = $statement->bind_param("ssii", $this->contractStart,$this->contractRenew,$this->clientType, $this->contactId);
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
                   
                   //update the contact id in the object
                    $contact = Contact::getClientByContactId($mysqli, $this->idcontact);
                    $newIdclient = $contact["idclient"];
                    try
                    {
                            $this->setIdclient($newIdclient);
                    }
                    catch (Exception $e)
                    {
                            //rethrow if the id is bad
                            throw(new Exception("Unable to determine contact id", 0, $e));
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
                   
                   if($this->idclient === -1)
                   {
                           throw(new Exception("New idclient detected"));
                   }
                   
                   //create query template
                   $query = "DELETE FROM client WHERE idclient = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                   $wasClean = $statement->bind_param("i", $this->idclient);
                   
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
                   if($this->idclient === -1)
                   {
                           throw(new Exception("New id detected"));
                   }
                   
                   //create query template
                   $query = "UPDATE client SET contractStart = ?, contractRenew = ?, clientType = ?, contactId = ? WHERE idclient = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                    $wasClean = $statement->bind_param("ssiii", $this->contractStart,$this->contractRenew,$this->clientType, $this->contactId, $this->idclient);
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
           
           /* static method to get contact by email
            * input: (pointer) mysqli
            * input: (string) $key contactId to search by
            * output: (object) client */
           public static function getClientByContactId(&$mysqli, $contactId)
           {
                   //handle degenerate cases
                   if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
                   {
                           throw(new Exception("Non mySQL pointer detected"));	
                   }
                   
                   //create query template
                   $query = "SELECT idclient, contractStart, contractRenew, clientType, contactId FROM client WHERE contactId = ?";
                   
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
                    $client = new Client($row["idclient"], $row["contractStart"], $row["contractRenew"], $row["clientType"], $row["contactId"]);
                   return($client);
           
                   //clean up the statement
                   $statement->close();
           }
           
           /* static method to get contact by id
            * input: (pointer) mysqli
            * input: (string) $key idclient to search by
            * output: (object) client */
           public static function getClientById(&$mysqli, $idclient)
           {
                   //handle degenerate cases
                   if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
                   {
                           throw(new Exception("Non mySQL pointer detected"));	
                   }
                   
                   //create query template
                   $query = "SELECT idclient, contractStart, contractRenew, clientType, contactId FROM client WHERE idclient = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                   $wasClean = $statement->bind_param("i", $idclient);
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
                   $client = new Client($row["idclient"], $row["contractStart"], $row["contractRenew"], $row["clientType"], $row["contactId"]);
                   return($client);
           
                   //clean up the statement
                   $statement->close();
           }
            
        }
?>