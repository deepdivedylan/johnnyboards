<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("/var/www/html/php/classes/board_location.php");
	
	class BoardLocationTest extends UnitTestCase
	{
		// variable to hold the mysqli objects
		private $sqlLocation;
		private $location;
		private $contact;
		private $venue;
            
		// constant variables to reuse. set intergers($id, $VenueId) and string (boardLocations)
		// state (member) variables
		private $boardLocation = "the board is valid";
		private $venueId;
		private $contactId; 
		
		 
		 // setup() is before *EACH test           
		public function setUp()
		{
			mysqli_report(MYSQLI_REPORT_STRICT);
			try
			{
				$this->mysqli = new mysqli("localhost","johnnyboards-dba","achemythratiopaganfacesoap","jb_posting");
				//insert the objects
				//contact -- idcontact, company name, address1, address2, city, zip, nm, phone
				$this->contact = new Contact(-1, "11 Online", "832 Madison NE", "", "Albuquerque", "87110", "NM", "505-363-4106", "josh@joshuatomasgarcia.com");
				$this->contact->insert($this->mysqli);
				//get contactid
				$this->contactId = $this->contact->getIdcontact();

				//venue -- idVenue, longitude, longitude, contactId
				$this->venue = new Venue(-1, 35, 100, $this->contactId);
				$this->venue->insert($this->mysqli);
				//get contactid
				$this->venueId = $this->venue->getIdVenue();
				
				//boardLocation -- id, boardLocation, venueId
				$this->location = new BoardLocation(-1, "Satellite", $this->venueId);
				$this->location->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "unable to connect to mySQL: ". $exception->getMessage();
			}
		}
		//test that the objects were inserted correctly & that the the static methods return the correct data 
		public function testGetBoardLocationById()
		{
			$this->sqlLocation = BoardLocation::getBoardLocationById($this->mysqli, $this->location->getId());
			$this->assertIdentical($this->location, $this->sqlLocation);
		}
		
		public function testGetBoardLocationByIdInvalid()
		{
			$this->expectException("Exception");
			@Board::getBoardLocationById($this->mysqli, 0);
		}
		
		public function testGetBoardLocationByVenueId()
		{
			$this->sqlLocation = BoardLocation::getBoardLocationByVenueId($this->mysqli, $this->venueId);
			$this->assertIdentical($this->location, $this->sqlLocation);
		}
		
		public function testGetBoardLocationByVenueIdInvalid()
		{
			$this->expectException("Exception");
			@Board::getBoardLocationByVenueId($this->mysqli, 0);
		}
     
		public function testValidUpdateLocation()
		{	
			
			$newBoardLocation = "Flying Star";
			$newVenueId = $this->venueId; //-- not testing this w/ new info for now b/c we would have to insert new Venue object
			$this->location->setBoardLocation($newBoardLocation);
			$this->location->setVenueId($newVenueId);
			$this->location->update($this->mysqli);
			 
		
			//select the user from mySQL and assert it was inserted properly
			$this->sqlLocation = BoardLocation::getBoardLocationById($this->mysqli, $this->location->getId());
		
			// verify the BoardLocation and VenueId changed
			$this->assertIdentical($this->sqlLocation->getBoardLocation(), $newBoardLocation);
			$this->assertIdentical($this->sqlLocation->getVenueId(), $newVenuId);
            }
            
            // teardown
            public function tearDown()
            {
                $this->location->delete($this->mysqli);
		$this->venue->delete($this->mysqli);
		$this->contact->delete($this->mysqli);
            }
        }
?>