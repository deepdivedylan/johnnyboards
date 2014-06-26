<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("../php/board_location.php");
        
	// grab the config file
	require_once("/home/bradg/tutorconnect/config.php");
	
	class BoardLocationTest extends UnitTestCase
	{
		private $mysqli = null;
            
		// variable to hold the mysqli objects
		private $sqlLocation;
		private $location;
            
		// constant variables to reuse. set intergers($id, $VenueId) and string (boardLocations)
		// state (member) variables
		private $id = 7; // this is assigned as -1 
		private $boardLocation = "the board is valid";
		private $venueId = 10;
		
		 
		 // setup() is before *EACH test           
		public function setUp()
		{
			try
			{
				if($this->mysqli === null)
				{
					$this->mysqli = Pointer::getMysqli();
				}
				$this->location = new Location (-1, $this->boardLocation, $this->venueId);			
				$this->location->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "Unable to connect to mySQL: " . $exception->getMessage();
			}
		}
			//then assert, this was called ok() in qunit  
		public function testGetLocationByBoardLocation()
		{
			$this->$sqlLocation = Location::getLocationByBoardLocation($this->mysqli, $this->boardLocation);
			$this->assertIdentical($this->location, $this->$sqlLocation[0]);
		}
			//setup your expectations
		public function testGetLocationByBoardLocationInvalid()
		{
			$this->expectException("Exception");
			@Board::getLocationByBoardLocation($this->mysqli, 0);
		}
     
		public function testValidUpdateLocation()
		{	
			$newBoardLocation = "JB boards are better";
			$newVenueId = "Just ask me and I'll tell you.";
			$this->location->setBoardLocation($newBoardLocation);
			$this->location->setVenueId($newVenueId);
			 
		
			//select the user from mySQL and assert it was inserted properly
			$this->sqlLocation = Location::getLocationByBoardLocation($this->mysqli, $this->boardLocation);
		
			// verify the BoardLocation and VenueId changed
			$this->assertIdentical($this->sqlLocation[0]->getBoardLocation(), $newBoardLocation);
			$this->assertIdentical($this->sqlLocation[0]->getVenueId(), $newVenuId);
            }
            
            // teardown
            public function tearDown()
            {
                $this->location->delete($this->mysqli);
            }
        }
?>