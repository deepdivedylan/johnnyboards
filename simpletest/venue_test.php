<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("../php/venue.php");
        
	// grab the config file
	require_once("/home/bradg/tutorconnect/config.php");
	
	class VenueTest extends UnitTestCase
	{
		private $mysqli = null;
            
		            
	    // variable to hold the mysqli objects
		private $sqlVenue;
		private $venue;
		
		/*member variables
             *input: (integer) idVenue 
             *input: (double) longitude
             *input: (double) latitude
             *input: (integer) contactId
             */
	    private $id; // this is assigned as -1 
            private $idVenue= 7;
            private $longitude= "-77.037852";
            private $latitude="38.898556";
            private $contactId=5;;
            		
		 // setup() is before *EACH test           
		public function setUp()
		{
			try
			{
				if($this->mysqli === null)
				{
					$this->mysqli = Pointer::getMysqli();
				}
				$this->venue = new Venue (-1, $this->idVenue, $this->longitude,
				$this->latitude,$this->contactId);			
				$this->venue->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "Unable to connect to mySQL: " . $exception->getMessage();
			}
		}
			//then assert, this was called ok() in qunit  
		public function testGetVenuesByIdVenue()
		{
			$this->$sqlVenue = Venue::getVenuesByIdVenue($this->mysqli, $this->idVenue);
			$this->assertIdentical($this->venue, $this->$sqlVenue[0]);
		}
			//setup your expectations
		public function testGetVenuesByIdVenueInvalid()
		{
			$this->expectException("Exception");
			@Venue::getVenuesByIdVenue($this->mysqli, 0);
		}
     
		public function testValidUpdateVenue()
		{	
			$newIdVenue = "JB venues are better";
			$newLongitude = "Just ask me and I'll tell you.";
			$newLatitude = "Just ask me and I'll tell you.";
			$newAreasCovered= "Just ask me and I'll tell you.";
			$newContactId= "Just ask me and I'll tell you.";
			
			$this->venue->setIdVenue($newIdVenue);
			$this->venue->setLongitude($newLongitude);
			$this->venue->setLatitude($newLatitude);
			$this->venue->setContactId($newVenueId);
			$this->venue->update($this->mysqli);
			
			
			//select the user from mySQL and assert it was inserted properly
			$this->sqlVenue = Venue::getVenueByIdVenue($this->mysqli, $this->idVenue);
		
			// verify the IdVenue , Longitude, Latitude,AreasCovered ContactId changed
			$this->assertIdentical($this->sqlVenue[0]->getIdVenue(), $newIdVenue);
			$this->assertIdentical($this->sqlVenue[0]->getLongitude(), $newLongitude);
			$this->assertIdentical($this->sqlVenue[0]->getLatitude(), $newLatitude);
			$this->assertIdentical($this->sqlVenue[0]->getContactId(), $newContactId);
            }
            
            // teardown
            public function tearDown()
            {
                $this->venue->delete($this->mysqli);
            }
        }
?>