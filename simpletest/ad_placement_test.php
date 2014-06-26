<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("../php/ad_placement.php");
        
	// grab the config file
	require_once("/home/bradg/tutorconnect/config.php");
	
	class AdPlacementTest extends UnitTestCase
	{
		private $mysqli = null;
            
		// variable to hold the mysqli objects
		private $sqlAdPlacement;
		private $ad;
            
		// constant variables to reuse. set intergers or string, these are all intergers
		private $id; // this is assigned as -1 
		private $clientId = 7;
		private $boardId = 10;
		private $contactId =5;
            
		
		 // setup() is before *EACH test           
		public function setUp()
		{
			try
			{
				if($this->mysqli === null)
				{
					$this->mysqli = Pointer::getMysqli();
				}
				$this->ad = new Ad (-1, $this->clientId, $this->boardId, $this->contactId);			
				$this->ad->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "Unable to connect to mySQL: " . $exception->getMessage();
			}
		}
			//then assert, this was called ok() in qunit  
		public function testGetAdsByClientId()
		{
			$this->$sqlAdPlacement = Ad::getGetAdsByClientId($this->mysqli, $this->clientId);
			$this->assertIdentical($this->ad, $this->$sqlAdPlacement[0]);
		}
			//setup your expectations
		public function testGetAdsByClientIdInvalid()
		{
			$this->expectException("Exception");
			@Ad::GetAdsByClientId($this->mysqli, 0);
		}
     
		public function testValidUpdatead()
		{	
			$newClientId = "JB boards are better";
			$newBoardId = "Just ask me and I'll tell you.";
			$newContactID = "Just ask me and I'll tell you.";
			$this->ad->setClientId($newClientId);
			$this->ad->setBoardId($newBoardId);
			$this->ad->setContactId($newContactId);
			$this->board->update($this->mysqli);
		
			//select the user from mySQL and assert it was inserted properly
			$this->AdPlacement = ad::getAdByClientId($this->mysqli, $this->clientid);
		
			// verify the clientId, BoardId and ContactId Status changed
			$this->assertIdentical($this->sqlAdPlacement[0]->getClientId(), $newClientId);
			$this->assertIdentical($this->sqlAdPlacement[0]->getBoardId(), $newBoardId);
			$this->assertIdentical($this->sqlAdPlacement[0]->getContactId(), $newContactId);
            }
            
            // teardown
            public function tearDown()
            {
                $this->ad->delete($this->mysqli);
            }
        }
?>