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
			try
			{
				$this->board = new Board(-1, $this->boardStatus);
				$this->board->insert($this->mysqli);
				$this->idboard = $this->board->getIdboard();
				echo $this->idboard;
			}
			catch(Exception $exception)
			{
				echo $exception;
			}
		}   
            
		public function testGetBoardsById()
		{
			$this->sqlBoard = Board::getBoardById($this->mysqli, $this->idboard);
			$this->assertIdentical($this->board, $this->sqlBoard);
		}

		public function testGetBoardsByIdInvalid()
		{
			$this->expectException("Exception");
			@Board::getBoardById($this->mysqli, 0);
		}
     
		public function testValidUpdateBoard()
		{	
			$newStatus = "Fair";
			$this->board->setBoardStatus($newStatus);
			$this->board->update($this->mysqli);
		
			//select the user from mySQL and assert it was inserted properly
			$this->sqlBoard = Board::getBoardById($this->mysqli, $this->idboard);
		
			// verify the title and details changed
			$this->assertIdentical($this->sqlBoard->getBoardStatus(), $newStatus);
		}
            
            // teardown
            public function tearDown()
            {
                $this->board->delete($this->mysqli);
            }
        }
?>