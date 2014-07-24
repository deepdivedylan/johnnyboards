<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("/var/www/html/php/classes/board.php");
	
	class BoardTest extends UnitTestCase
	{
		// variable to hold the mysqli objects
		private $sqlBoard;
		private $board;
            
		// constant variables to reuse
		private $idboard;
		private $boardStatus = "Good";
		            
		// setup() is before *EACH test  
		public function setUp()
		{
			mysqli_report(MYSQLI_REPORT_STRICT);
			try
			{
				$this->mysqli = new mysqli("localhost","johnnyboards-dba","achemythratiopaganfacesoap","jb_posting");
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "unable to connect to mySQL: ". $exception->getMessage();
			}
		}   
            
		public function testGetBoardsByUserId()
		{
			$this->$sqlBoard = Board::getBoardsByUserId($this->mysqli, $this->userId);
			$this->assertIdentical($this->job, $this->$sqlBoard[0]);
		}

		public function testGetBoardsByUserIdInvalid()
		{
			$this->expectException("Exception");
			@Board::getBoardsByUserId($this->mysqli, 0);
		}
     
		public function testValidUpdateBoard()
		{	
			$newTitle = "HTML is better";
			$newDetails = "Just ask me and I'll tell you.";
			$this->board->setTitle($newTitle);
			$this->board->setDetails($newDetails);
			$this->board->update($this->mysqli);
		
			//select the user from mySQL and assert it was inserted properly
			$this->sqlBoard = Board::getBoardByUserId($this->mysqli, $this->userId);
		
			// verify the title and details changed
			$this->assertIdentical($this->sqlBoard[0]->getTitle(), $newTitle);
			$this->assertIdentical($this->sqlBoard[0]->getDetails(), $newDetails);
            }
            
            // teardown
            public function tearDown()
            {
                $this->board->delete($this->mysqli);
            }
        }
?>