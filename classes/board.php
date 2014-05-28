<?php
        class Board
        {
            //member variables
            private $idboard;
            private $boardStatus;
            
            /*contstructor
             *input: (integer) idboard
             *input: (string) boardStatus
             *throws: when bad input is detected*/
            
            public function __construct($newIdboard, $newBoardStatus)
            {
                try
                {
                    $this->setIdboard($newIdboard);
                    $this->setBoardStatus($newBoardStatus);
                }
                catch(Exception $e)
                {
                    throw(new Exception("Unable to build board", 0, $e));
                }
            }
            
            /*******************************GETTERS & SETTERS************************************/
            

            /* accessor method for idboard
             * input: N/A
             * output: value of idboard */
            public function getIdboard()
            {
                return($this->idboard);
            }
         
            /* mutator method for idboard
             * input: new value of idboard
             * output: N/A */
            public function setIdboard($newIdboard)
            {
                //throw out obviously bad IDs
                if(is_numeric($newIdboard) === false)
                {
                        throw(new Exception("Invalid id detected: $newIdboard"));
                }
                
                //convert the ID to an integer
                $newIdboard = intval($newIdboard);
                
                // throw out negative IDs, except -1 which is our "new" client
                if($newIdboard < -1)
                {
                        throw(new Exception("Invalid id detected: $newIdboard"));
                }
                
                //sanitized; assign the value
                $this->idboard = $newIdboard;
            }
         
            /* accessor method for boardStatus
             * input: N/A
             * output: value of boardStatus */
            public function getContractStart()
            {
                return($this->boardStatus);
            }
         
            /* mutator method for boardStatus
             * input: new value of boardStatus
             * output: N/A */
            public function setBoardStatus($newBoardStatus)
            {
                //trim the input
                $newBoardStatus = trim($newBoardStatus);
                
                //strip out HTML special characters
                $newBoardStatus = htmlspecialchars($newBoardStatus);
                
                //sanitized, assign
                $this->boardStatus = $newBoardStatus;
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
                   if($this->idboard !== -1)
                   {
                           throw(new Exception("Non new id detected"));
                   }
                   
                   //create query template
                   $query = "INSERT INTO board(boardStatus) VALUES(?)";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   
                   //bind parameters to the query template
                   $wasClean = $statement->bind_param("s", $this->boardStatus);
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
                   
                   //reassign the id to the object
                   try
                   {
                        $this->setId($mysqli->insert_id);
                   }
                   catch(Exception $e)
                   {
                        throw(new Exception("Unable to determine board id", 0, $e)); 
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
                   
                   if($this->idboard === -1)
                   {
                           throw(new Exception("New idboard detected"));
                   }
                   
                   //create query template
                   $query = "DELETE FROM board WHERE idboard = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                   $wasClean = $statement->bind_param("i", $this->idboard);
                   
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
                   if($this->idboard === -1)
                   {
                           throw(new Exception("New id detected"));
                   }
                   
                   //create query template
                   $query = "UPDATE board SET boardStatus = ? WHERE idboard = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                    $wasClean = $statement->bind_param("si", $this->boardStatus, $this->idboard);
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
           
           /* static method to get board by id
            * input: (pointer) mysqli
            * input: (string) $key idboard to search by
            * output: (object) client */
           public static function getBoardById(&$mysqli, $idboard)
           {
                   //handle degenerate cases
                   if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
                   {
                           throw(new Exception("Non mySQL pointer detected"));	
                   }
                   
                   //create query template
                   $query = "SELECT idboard, boardStatus FROM client WHERE idboard = ?";
                   
                   //prepare the query statement
                   $statement = $mysqli->prepare($query);
                   if($statement === false)
                   {
                           throw(new Exception("Unable to prepare statement"));
                   }
                   //bind parameters to the query templated
                   $wasClean = $statement->bind_param("i", $idboard);
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
                           throw(new Exception("Unable to determine board: id not found"));
                   }
                   
                   //get the row
                   $row   = $result->fetch_assoc();
                   $board = new Board($row["idboard"], $row["boardStatus"]);
                   return($board);
           
                   //clean up the statement
                   $statement->close();
           }
            
        }
?>