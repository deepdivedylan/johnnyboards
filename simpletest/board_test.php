<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("../php/job.php");
        
	// grab the config file
	require_once("/home/bradg/tutorconnect/config.php");
	
	class JBoardTest extends UnitTestCase
	{
		private $mysqli = null;
            
		// variable to hold the mysqli objects
		private $sqlBoard;
		private $board;
            
		// constant variables to reuse
		private $idboard;
		private $boardStatus = "CSS help for YOU";
		            
		public function setUp()
		{
			try
			{
				if($this->mysqli === null)
				{
					$this->mysqli = Pointer::getMysqli();
				}
				$this->job = new Job (-1, $this->userId, $this->title, $this->details);			
				$this->job->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "Unable to connect to mySQL: " . $exception->getMessage();
			}
		}
            
		public function testGetBoardsByUserId()
		{
			$this->$sqlBoard = Job::getBoardsByUserId($this->mysqli, $this->userId);
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