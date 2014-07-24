<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("/var/www/html/php/classes/type.php");
	
	class TypeTest extends UnitTestCase
	{
		// variable to hold the mysqli objects
		private $sqlType;
		private $type;
            
		/* state (member) variables
		 * input: (int) new Id -- no variable b/c is it never called in test
		 * input: (string) name
		 * throws: when invalid input detected */
		private $name = "Valid Name";
		 
		 // setup() is before *EACH test           
		public function setUp()
		{
			mysqli_report(MYSQLI_REPORT_STRICT);
			try
			{
				$this->mysqli = new mysqli("localhost","johnnyboards-dba","achemythratiopaganfacesoap","jb_posting");
				
				//insert the object
				//type -- idtype, name
				$this->type = new Type(-1,$this->name);
				$this->type->insert($this->mysqli);
				
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "unable to connect to mySQL: ". $exception->getMessage();
			}
		}
		//first we are going to test that the object is in the db and that the static methods are working with assert identical
		//Test valid and invalud getPosterTypeById
		public function testGetPosterTypeById()
		{
			$this->$sqlType = Type::getPosterTypeById($this->mysqli, $this->type->getIdtype());
			$this->assertIdentical($this->type, $this->sqlType);
		}

		public function testGetPosterTypeByIdInvalid()
		{
			$this->expectException("Exception");
			@Type::getPosterTypeById($this->mysqli, 0);
		}
		
		//Test valid and invalud getPosterTypeByName
		public function testGetPosterTypeByName()
		{
			$this->$sqlType = Type::getPosterTypeByName($this->mysqli, $this->name);
			$this->assertIdentical($this->type, $this->sqlType);
		}

		public function testGetPosterTypeByNameInvalid()
		{
			$this->expectException("Exception");
			@Type::getPosterTypeByName($this->mysqli, "Invalid Name");
		}
     
		public function testValidUpdateType()
		{	
			$newName = "New Name";
			$this->type->setName($newName);
			$this->type->update($this->mysqli);
		
			//select the user from mySQL and assert it was inserted properly
			$this->sqlType = Type::getPosterTypeByName($this->mysqli, $this->name);
		
			// verify the Name changed
			$this->assertIdentical($this->sqlType->getName(), $newName);
			
            }
            
            // teardown
            public function tearDown()
            {
                $this->type->delete($this->mysqli);
            }
        }
?>