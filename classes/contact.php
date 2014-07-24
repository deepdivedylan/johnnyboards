<?php
        class Contact
        {
            //member variables
            private $idcontact;
            private $companyName;
            private $address1;
            private $address2;
            private $city;
            private $zipcode;
            private $state;
            private $phoneNumber;
            private $email;
            
            /*contstructor
             *input: (integer) idcontact
             *input: (string) company name
             *input: (string) address line 1
             *input: (string) address line 2
             *input: (string) city
             *input: (string) zipcode
             *input: (string) state
             *input: (string) phone
             *input: (string) email
             *throws: when bad input is detected*/
            
            public function __construct($newIdcontact, $newCompanyName, $newAddress1, $newAddress2, $newCity, $newZipcode, $newState, $newPhoneNumber, $newEmail)
            {
                try
                {
                    $this->setIdcontact($newIdcontact);
                    $this->setCompanyName($newCompanyName);
                    $this->setAddress1($newAddress1);
                    $this->setAddress2($newAddress2);
                    $this->setCity($newCity);
                    $this->setZipcode($newZipcode);
                    $this->setState($newState);
                    $this->setPhoneNumber($newPhoneNumber);
                    $this->setEmail($newEmail);
                }
                catch(Exception $e)
                {
                    throw(new Exception("Unable to build contact", 0, $e));
                }
            }
            
            /*******************************GETTERS & SETTERS************************************/
            

            /* accessor method for idcontact
             * input: N/A
             * output: value of idcontact */
            public function getIdcontact()
            {
                return($this->idcontact);
            }
         
            /* mutator method for idcontact
             * input: new value of idcontact
             * output: N/A */
            public function setIdcontact($newIdcontact)
            {
                //throw out obviously bad IDs
                if(is_numeric($newIdcontact) === false)
                {
                        throw(new Exception("Invalid id detected: $newIdcontact"));
                }
                
                //convert the ID to an integer
                $newIdcontact = intval($newIdcontact);
                
                // throw out negative IDs, except -1 which is our "new" contact
                if($newIdcontact < -1)
                {
                        throw(new Exception("Invalid id detected: $newIdcontact"));
                }
                
                //sanitized; assign the value
                $this->idcontact = $newIdcontact;
            }
         
            /* accessor method for companyName
             * input: N/A
             * output: value of companyName */
            public function getCompanyName()
            {
                return($this->companyName);
            }
         
            /* mutator method for companyName
             * input: new value of companyName
             * output: N/A */
            public function setCompanyName($newCompanyName)
            {
                //trim the input
                $newCompanyName = trim($newCompanyName);
                
                //strip out HTML special characters
                $newCompanyName = htmlspecialchars($newCompanyName);
                
                //test against a regular expression
                $regexp = "/[\p{L}\p{N}\p{P}\p{Z}]+/u";
                if(preg_match($regexp, $newCompanyName) !== 1)
                {
                    throw(new Exception("Invalid company detected: $newCompanyName"));
                }
                
                //sanitized, assign
                $this->companyName = $newCompanyName;
            }
         
            /* accessor method for address1
             * input: N/A
             * output: value of address1 */
            public function getAddress1()
            {
                return($this->address1);
            }
         
            /* mutator method for address1
             * input: new value of address1
             * output: N/A */
            public function setAddress1($newAddress1)
            {
                //trim the input
                $newAddress1 = trim($newAddress1);
                
                //strip out HTML special characters
                $newAddress1 = htmlspecialchars($newAddress1);
                
                //test against a regular expression
                $regexp = "/[\p{L}\p{N}\p{P}\p{Z}]+/u";
                if(preg_match($regexp, $newAddress1) !== 1)
                {
                    throw(new Exception("Invalid company detected: $newAddress1"));
                }
                
                //sanitized, assign
                $this->address1 = $newAddress1;
            }
         
            /* accessor method for address2
             * input: N/A
             * output: value of address2 */
            public function getAddress2()
            {
                return($this->address2);
            }
         
            /* mutator method for address2
             * input: new value of address2
             * output: N/A */
            public function setAddress2($newAddress2)
            {
                //trim the input
                $newAddress2 = trim($newAddress2);
                
                //strip out HTML special characters
                $newAddress2 = htmlspecialchars($newAddress2);
                
                //test against a regular expression
                $regexp = "/[\p{L}\p{N}\p{P}\p{Z}]+/u";
                if(preg_match($regexp, $newAddress2) !== 1)
                {
                    throw(new Exception("Invalid company detected: $newAddress2"));
                }
                
                //sanitized, assign
                $this->address2 = $newAddress2;
            }
         
            /* accessor method for city
             * input: N/A
             * output: value of city */
            public function getCity()
            {
                return($this->city);
            }
         
            /* mutator method for city
             * input: new value of city
             * output: N/A */
            public function setCity($newCity)
            {
                //trim the input
                $newCity = trim($newCity);
                
                //strip out HTML special characters
                $newCity = htmlspecialchars($newCity);
                
                //test against a regular expression
                $regexp = "/[\p{L}\p{P}\p{Z}]+/u";
                if(preg_match($regexp, $newCity) !== 1)
                {
                    throw(new Exception("Invalid company detected: $newCity"));
                }
                
                //sanitized, assign
                $this->city = $newCity;
            }
         
            /* accessor method for zipcode
             * input: N/A
             * output: value of zipcode */
            public function getZipcode()
            {
                return($this->zipcode);
            }
         
            /* mutator method for zipcode
             * input: new value of zipcode
             * output: N/A */
            public function setZipcode($newZipcode)
            {
                //trim the input
                $newZipcode = trim($newZipcode);
                
                //strip out HTML special characters
                $newZipcode = htmlspecialchars($newZipcode);
                
                //test against a regular expression
                $regexp = "/[\p{N}\p{Z}\p{Pd]+/u";
                if(preg_match($regexp, $newZipcode) !== 1)
                {
                    throw(new Exception("Invalid company detected: $newZipcode"));
                }
                
                //sanitized, assign
                $this->zipcode = $newZipcode;
            }
         
            /* accessor method for state
             * input: N/A
             * output: value of state */
            public function getState()
            {
                return($this->state);
            }
         
            /* mutator method for state
             * input: new value of state
             * output: N/A */
            public function setState($newState)
            {
                //trim the input
                $newState = trim($newState);
                
                //strip out HTML special characters
                $newState = htmlspecialchars($newState);
                
                //test against a regular expression
                $regexp = "/[\p{L}\p{P}\p{Z}]+/u";
                if(preg_match($regexp, $newState) !== 1)
                {
                    throw(new Exception("Invalid company detected: $newState"));
                }
                
                //sanitized, assign
                $this->state = $newState;
            }
         
            /* accessor method for phoneNumber
             * input: N/A
             * output: value of phoneNumber */
            public function getPhoneNumber()
            {
                return($this->phoneNumber);
            }
         
            /* mutator method for phoneNumber
             * input: new value of phoneNumber
             * output: N/A */
            public function setPhoneNumber($newPhoneNumber)
            {
                //trim the input
                $newPhoneNumber = trim($newPhoneNumber);
                
                //strip out HTML special characters
                $newPhoneNumber = htmlspecialchars($newPhoneNumber);
                
                //test against a regular expression
                $regexp = "/[\p{N}\p{Pd}\p{Ps}\p{Pe}\p{Z}\+]+/u";
                if(preg_match($regexp, $newPhoneNumber) !== 1)
                {
                    throw(new Exception("Invalid company detected: $newPhoneNumber"));
                }
                
                //sanitized, assign
                $this->phoneNumber = $newPhoneNumber;
            }
         
            /* accessor method for email
             * input: N/A
             * output: value of email */
            public function getEmail()
            {
                return($this->email);
            }
         
            /* mutator method for email
             * input: new value of email
             * output: N/A */
            public function setEmail($newEmail)
            {
                //trim the email
                $newEmail = trim($newEmail);
                
                //require an @ character in the email
                if(strpos($newEmail, "@") === false)
                {
                        throw(new Exception("Invalid email detected: $newEmail"));
                }
                //santized; assign the value
                $this->email = $newEmail;
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
                   if($this->idcontact !== -1)
                   {
                           throw(new Exception("Non new id detected"));
                   }
                   
                   //create query template
                   $query = "INSERT INTO contact(companyName, address1, address2, city, zipcode, state, phoneNumber, email) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   
                   //bind parameters to the query template
                   $wasClean = $statement->bind_param("ssssssss", $this->companyName,$this->address1,$this->address2, $this->city, $this->zipcode, $this->state, $this->phoneNumber, $this->email);
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
                    $contact = Contact::getContactByEmail($mysqli, $this->email);
                    $newIdcontact = $contact["idcontact"];
                    try
                    {
                            $this->setIdcontact($newIdcontact);
                    }
                    catch (Exception $e)
                    {
                            //rethrow if the id is bad
                            throw(new Exception("Unable to determine user id", 0, $e));
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
                   
                   if($this->idcontact === -1)
                   {
                           throw(new Exception("New idcontact detected"));
                   }
                   
                   //create query template
                   $query = "DELETE FROM contact WHERE idcontact = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                   $wasClean = $statement->bind_param("i", $this->idconact);
                   
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
                   if($this->idcontact === -1)
                   {
                           throw(new Exception("New id detected"));
                   }
                   
                   //create query template
                   $query = "UPDATE contact SET companyName = ?, address1 = ?, address2 = ?, city = ?, zipcode = ?, state = ?, phoneNumber = ?, email = ? WHERE idcontact = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                   $wasClean = $statement->bind_param("ssssssssi", $this->companyName,$this->address1,$this->address2, $this->city, $this->zipcode, $this->state, $this->phoneNumber, $this->email, $this->idcontact);
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
            * input: (string) $key email to search by
            * output: (object) contact */
           public static function getContactByEmail(&$mysqli, $email)
           {
                   //handle degenerate cases
                   if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
                   {
                           throw(new Exception("Non mySQL pointer detected"));	
                   }
                   
                   //create query template
                   $query = "SELECT idcontact, companyName, address1, address2, city, zipcode, state, phoneNumber, email FROM contact WHERE email = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                   $wasClean = $statement->bind_param("s", $email);
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
                           throw(new Exception("Unable to determine contact: email not found"));
                   }
                   
                   //get the row
                    $row   = $result->fetch_assoc();
                    $contact = new Contact($row["idcontact"], $row["companyName"], $row["address1"], $row["address2"], $row["city"], $row["zipcode"], $row["state"], $row["phoneNumber"], $row["email"]);
                   return($contact);
           
                   //clean up the statement
                   $statement->close();
           }
           
           /* static method to get contact by id
            * input: (pointer) mysqli
            * input: (string) $key id to search by
            * output: (object) ctonact */
           public static function getContactById(&$mysqli, $idcontact)
           {
                   //handle degenerate cases
                   if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
                   {
                           throw(new Exception("Non mySQL pointer detected"));	
                   }
                   
                   //create query template
                   $query = "SELECT idcontact, companyName, address1, address2, city, zipcode, state, phoneNumber, email FROM contact WHERE idcontact = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                   $wasClean = $statement->bind_param("i", $idcontact);
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
                   $contact = new Contact($row["idcontact"], $row["companyName"], $row["address1"], $row["address2"], $row["city"], $row["zipcode"], $row["state"], $row["phoneNumber"], $row["email"]);
                   return($contact);
           
                   //clean up the statement
                   $statement->close();
           }
            
        }
?>