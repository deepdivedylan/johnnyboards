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
            
		// constant variables to reuse. set interger or string
		 /*member variables
             *input: (integer) idclient
             *input: (string) contract start
             *input: (string) contract renew
             *input: (integer) client type
             *input: (string) contactId*/
		private $idclient; // this is assigned as -1
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
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "unable to connect to mySQL: ". $exception->getMessage();
			}
		}
			//then assert, this was called ok() in qunit  
		public function testGetClientsByIdClient()
		{
			$this->$sqlClient = Client::getClientsByIdClient($this->mysqli, $this->idclient);
			$this->assertIdentical($this->client, $this->$sqlClient[0]);
		}
			//setup your expectations
		public function testGetClientsByIdClientInvalid()
		{
			$this->expectException("Exception");
			@Client::getClientsByIdClient($this->mysqli, 0);
		}
     
		public function testValidUpdateClient()
		{	
			$newIdClient = "JB clients are better";
			$newContractStart = "Just ask me and I'll tell you.";
			$newContractRenew = "Just ask me and I'll tell you.";
			$newClientType= "Just ask me and I'll tell you.";
			$newContractId= "Just ask me and I'll tell you.";
			
			$this->client->setIdClient($newIdClient);
			$this->client->setContractStart($newContractStart);
			$this->client->setContractRenew($newContractRenew);
			$this->client->setClientType($newClientType);
			$this->client->setClientId($newClientId);
			$this->client->update($this->mysqli);
			
			
								
			//select the user from mySQL and assert it was inserted properly
			$this->sqlClient = Client::getClientByIdClient($this->mysqli, $this->idclient);
		
			// verify the IdClient , ContractStart, ContractRenew,ClientType ContractId changed
			$this->assertIdentical($this->sqlClient[0]->getIdClient(), $newIdClient);
			$this->assertIdentical($this->sqlClient[0]->getContractStart(), $newContractStart);
			$this->assertIdentical($this->sqlClient[0]->getContractRenew(), $newContractRenew);
			$this->assertIdentical($this->sqlClient[0]->getClientType(), $newClientType);
			$this->assertIdentical($this->sqlClient[0]->getClientId(), $newClientId);
            }
            
            // teardown
            public function tearDown()
            {
                $this->client->delete($this->mysqli);
            }
        }
?>