<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("../php/type.php");
        
	// grab the config file
	require_once("/home/bradg/tutorconnect/config.php");
	
	class TypeTest extends UnitTestCase
	{
		private $mysqli = null;
            
		// variable to hold the mysqli objects
		private $sqlType;
		private $type;
            
		// state (member) variables
		private $id; // this is set to -1 
		private $name;
		
		/* constructor for a printer object
		 * input: (int) new Id
		 * input: (string) name
		 * throws: when invalid input detected */
					
		 
		 // setup() is before *EACH test           
		public function setUp()
		{
			try
			{
				if($this->mysqli === null)
				{
					$this->mysqli = Type::getMysqli();
				}
				$this->type = new Type (-1, $this->name);			
				$this->type->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "Unable to connect to mySQL: " . $exception->getMessage();
			}
		}
			//then assert, this was called ok() in qunit  
		public function testGetTypesByName()
		{
			$this->$sqlType = Type::getTypesByName($this->mysqli, $this->name);
			$this->assertIdentical($this->type, $this->$sqlType[0]);
		}
			//setup your expectations
		public function testGetTypesByNameInvalid()
		{
			$this->expectException("Exception");
			@Type::getTypesByName($this->mysqli, 0);
		}
     
		public function testValidUpdateType()
		{	
			$newName = "JB types are better";
			$newTypeStatus = "Just ask me and I'll tell you.";
			$this->type->setName($newName);
			$this->type->update($this->mysqli);
		
			//select the user from mySQL and assert it was inserted properly
			$this->sqlType = Type::getTypeByName($this->mysqli, $this->name);
		
			// verify the Name changed
			$this->assertIdentical($this->sqlType[0]->getName(), $newName);
			
            }
            
            // teardown
            public function tearDown()
            {
                $this->type->delete($this->mysqli);
            }
        }
?>