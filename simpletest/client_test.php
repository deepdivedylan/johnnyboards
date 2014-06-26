<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("../php/client.php");
        
	// grab the config file
	require_once("/home/bradg/tutorconnect/config.php");
	
	class ClientTest extends UnitTestCase
	{
		private $mysqli = null;
            
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
		private $id; // this is assigned as -1  
		private $idclient = 7; 
		private $contractStart = "this is a contract";
		private $contractRenew= "This is renew";
		private $clientType = 10;
		private $contactId= "this is an Id";
			
		 
		 // setup() is before *EACH test           
		public function setUp()
		{
			try
			{
				if($this->mysqli === null)
				{
					$this->mysqli = Pointer::getMysqli();
				}
				$this->client = new Client (-1, $this->idclient, $this->contractStart, $this->contractRenew, $this->clientType,$this->contractId);			
				$this->client->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "Unable to connect to mySQL: " . $exception->getMessage();
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