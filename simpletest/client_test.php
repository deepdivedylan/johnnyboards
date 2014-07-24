<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("/var/www/html/php/classes/client.php");
	
	class ClientTest extends UnitTestCase
	{
		// variable to hold the mysqli objects
		private $sqlClient;
		private $client;
		private $contact;
            
		// constant variables to reuse. set interger or string
		 /*member variables
             *input: (integer) idclient -- w/new object will be assigned as -1 
             *input: (string) contract start
             *input: (string) contract renew
             *input: (integer) client type
             *input: (string) contactId*/
		private $contractStart = "12-12-2012";
		private $contractRenew= "12-12-2013";
		private $clientType = 10;
		private $contactId; //object
			
		 
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
				
				
				//client -- idclient, contractstartdate, contractenddate, clientype, contactid
				$this->client = new Client(-1, "12-12-2012", "12-12-2013", 10, $this->contactId);
				$this->client->insert($this->mysqli);
				
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "unable to connect to mySQL: ". $exception->getMessage();
			}
		}
		//test that the objects were inserted correctly & that the the static methods return the correct data
		//
		public function testGetClientByClientId()
		{
			$this->sqlClient = Client::getClientById($this->mysqli, $this->client->getIdclient());
			$this->assertIdentical($this->client, $this->sqlClient);
		}

		public function testGetClientsByIdClientInvalid()
		{
			$this->expectException("Exception");
			@Client::getClientById($this->mysqli, 0);
		}
		
		public function testGetClientByContactId()
		{
			$this->sqlClient = Client::getClientByContactId($this->mysqli, $this->contactId);
			$this->assertIdentical($this->client, $this->sqlClient);
		}
	
		public function testGetClientsByContactIdInvalid()
		{
			$this->expectException("Exception");
			@Client::getClientByContactId($this->mysqli, 0);
		}
     
		public function testValidUpdateClient()
		{	
			$newContractStart = "01-01-1980";
			$newContractRenew = "06-13-1990";
			$newClientType= 5;
			$newContactId= $this->contactId;
			
			$this->client->setContractStart($newContractStart);
			$this->client->setContractRenew($newContractRenew);
			$this->client->setClientType($newClientType);
			$this->client->setContactId($newContactId);
			$this->client->setClientId($clientId);
			$this->client->update($this->mysqli);
			
			
								
			//select the user from mySQL and assert it was inserted properly
			$this->sqlClient = Client::getClientByIdClient($this->mysqli, $this->idclient);
		
			// verify the IdClient , ContractStart, ContractRenew,ClientType ContractId changed
			$this->assertIdentical($this->sqlClient->getIdClient(), $newIdClient);
			$this->assertIdentical($this->sqlClient->getContractStart(), $newContractStart);
			$this->assertIdentical($this->sqlClient->getContractRenew(), $newContractRenew);
			$this->assertIdentical($this->sqlClient->getClientType(), $newClientType);
			$this->assertIdentical($this->sqlClient->getClientId(), $newClientId);
            }
            
            // teardown
            public function tearDown()
            {
                $this->contact->delete($this->mysqli);
		$this->client->delete($this->mysqli);
            }
        }
?>