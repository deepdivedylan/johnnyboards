<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("/var/www/html/php/classes/ad_placement.php");
        
	class AdPlacementTest extends UnitTestCase
	{   
		// variable to hold the mysqli objects
		private $sqlAdPlacement;
		private $ad;
		private $contact;
		private $client;
		private $board;
		
            
		// constant variables to reuse. These must be inserted and deleted from the table every time, since they are objects. The tests for clientId, boardId and contactId shoud all be passing before this test is run. 
		private $clientId; // get from object
		private $boardId; //get from object
		private $contactId; //get from object
            
		// setup() is before *EACH test  
		public function setUp()
		{
			//connect to mySQL database, this is not the most secure method
			mysqli_report(MYSQLI_REPORT_STRICT);
			try
			{
				$this->mysqli = new mysqli("localhost","johnnyboards-dba","achemythratiopaganfacesoap","jb_posting");
				
				//insert the objects
				//contact -- idcontact, company name, address1, address2, city, zip, nm, phone
				$contact = new Contact(-1, "11 Online", "832 Madison NE", "", "Albuquerque", "87110", "NM", "505-363-4106", "josh@joshuatomasgarcia.com");
				$contact->insert($this->mysqli);
				//get contactid
				$contactId = $contact->getIdcontact();
				
				//client -- idclient, contractstartdate, contractenddate, clientype, contactid
				$client = new Client(-1, "12-12-2012", "12-12-2013", 10, $contactId);
				$client->insert($this->mysqli);
				$clientId = $client->getIdclient();
				
				//board -- idboard, boardstatus
				$board = new Board(-1, "Good");
				$board->insert($this->mysqli);
				$boardId = $board->getIdboard();
				
				//create and insert the ad placement object
				$ad = new AdPlacement($this->clientId, $this->contactId, $this->boardId);
				$ad->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "unable to connect to mySQL: ". $exception->getMessage();
			}
		}
		
		//test our static methods, first getAdPlacementByClientId this will also test that we inserted our objects correctly 
		//test for valid
		public function testGetAdsByClientId()
		{
			//first grab our info
			$this->sqlAdPlacement = Ad::getAdPlacementByClientId($this->mysqli, $this->clientId);
			
			//assert that we got what we wanted
			$this->assertIdentical($this->sqlAdPlacement->getClientId(), $this->clientId);
			$this->assertIdentical($this->sqlAdPlacement->getBoardId(), $this->boardId);
			$this->assertIdentical($this->sqlAdPlacement->getContactId(), $this->contactId);
		}
		//also test this method for an invalid entry
		public function testGetAdsByClientIdInvalid()
		{
			$this->expectException("Exception");
			@Ad::getAdPlacementByClientId($this->mysqli, 0);
		}
		
		//next getAdPlacementByBoardId
		//test for valid
		public function testGetAdsByBoardId()
		{
			//first grab our info
			$this->sqlAdPlacement = AdPlacement::getAdPlacementByBoardId($this->mysqli, $this->boardId);
			
			//assert that we got what we wanted
			$this->assertIdentical($this->sqlAdPlacement->getClientId(), $this->clientId);
			$this->assertIdentical($this->sqlAdPlacement->getBoardId(), $this->boardId);
			$this->assertIdentical($this->sqlAdPlacement->getContactId(), $this->contactId);
		}
		//also test this method for an invalid entry
		public function testGetAdsByBoardIdInvalid()
		{
			$this->expectException("Exception");
			@Ad::getAdPlacementByBoardId($this->mysqli, 0);
		}
		
		//next getAdPlacementByContactId
		//test for valid
		public function testGetAdsByContactId()
		{
			//first grab our info
			$this->sqlAdPlacement = AdPlacement::getAdPlacementByContactId($this->mysqli, $this->contactId);
			
			//assert that we got what we wanted
			$this->assertIdentical($this->sqlAdPlacement->getClientId(), $this->clientId);
			$this->assertIdentical($this->sqlAdPlacement->getBoardId(), $this->boardId);
			$this->assertIdentical($this->sqlAdPlacement->getContactId(), $this->contactId);
		}
		//also test this method for an invalid entry
		public function testGetAdsByContactIdInvalid()
		{
			$this->expectException("Exception");
			@Ad::getAdPlacementByContactId($this->mysqli, 0);
		}
		
		//finally getAdPlacementByClientAndBoardId
		//test for valid
		public function testGetAdsByClientAndBoardId()
		{
			//first grab our info
			$this->sqlAdPlacement = AdPlacement::getAdPlacementByClientAndBoardId($this->mysqli, $this->clientId, $boardId);
			
			//assert that we got what we wanted
			$this->assertIdentical($this->sqlAdPlacement->getClientId(), $this->clientId);
			$this->assertIdentical($this->sqlAdPlacement->getBoardId(), $this->boardId);
			$this->assertIdentical($this->sqlAdPlacement->getContactId(), $this->contactId);
		}
		//also test this method for an invalid entry
		public function testGetAdsByClientAndBoardIdInvalid()
		{
			$this->expectException("Exception");
			@Ad::getAdPlacementByClientAndBoardId($this->mysqli, 0);
		}
		
		////WE'RE GONNA SKIP THIS FOR NOW, I AM NOT SURE IF WE WOULD HAVE TO CREATE ANOTHER SET OF OBJECTS HERE AND GRAB THEIR INFO TO TEST
		////test the update function     
		//public function testValidUpdatead()
		//{	
		//	$newClientId;
		//	$newBoardId;
		//	$newContactID;
		//	$this->ad->setClientId($newClientId);
		//	$this->ad->setBoardId($newBoardId);
		//	$this->ad->setContactId($newContactId);
		//	$this->board->update($this->mysqli);
		//
		//	//select the user from mySQL and assert it was inserted properly
		//	$this->AdPlacement = ad::getAdByClientId($this->mysqli, $this->clientid);
		//
		//	// verify the clientId, BoardId and ContactId Status changed
		//	$this->assertIdentical($this->sqlAdPlacement[0]->getClientId(), $newClientId);
		//	$this->assertIdentical($this->sqlAdPlacement[0]->getBoardId(), $newBoardId);
		//	$this->assertIdentical($this->sqlAdPlacement[0]->getContactId(), $newContactId);
		//}
            
		// teardown
		public function tearDown()
		{
			$this->board->delete($this->mysqli);
			$this->client->delete($this->mysqli);
			$this->contact->delete($this->mysqli);
			$this->ad->delete($this->mysqli);
		}
        }
?>