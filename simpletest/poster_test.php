<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("../php/poster.php");
        
	// grab the config file
	require_once("/home/bradg/tutorconnect/config.php");
	
	class PosterTest extends UnitTestCase
	{
		private $mysqli = null;
            
		// variable to hold the mysqli objects
		private $sqlPoster;
		private $poster;
            
		// constant variables to reuse. set interger or string
		/* state (member) variables
		 * input: (int) new Id
		 * input: (int) posterType
		 * input: (int) new contact id */
		
		private $id; // this is assigned as -1 
		private $posterType= 10;
		private $contactId= 20;
		
		/* state (member) variables
		 * input: (int) new Id
		 * input: (int) posterType
		 * input: (int) new contact id */
		
					 
		 // setup() is before *EACH test           
		public function setUp()
		{
			try
			{
				if($this->mysqli === null)
				{
					$this->mysqli = Pointer::getMysqli();
				}
				$this->poster = new Poster (-1, $this->posterType, $this->contactId);			
				$this->poster->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "Unable to connect to mySQL: " . $exception->getMessage();
			}
		}
			//then assert, this was called ok() in qunit  
		public function testGetPosterByPosterType()
		{
			$this->$sqlPoster = Poster::getPostersByPosterType($this->mysqli, $this->posterType);
			$this->assertIdentical($this->poster, $this->$sqlPoster[0]);
		}
			//setup your expectations
		public function testGetPosterByPosterTypeInvalid()
		{
			$this->expectException("Exception");
			@Poster::getPostersByPosterType($this->mysqli, 0);
		}
     
		public function testValidUpdatePoster()
		{	
			$newPosterType = "JB posters are better";
			$newContactId = "Just ask me and I'll tell you.";
			$this->poster->setPosterType($newPosterType);
			$this->poster->setContactId($newContactId);
			$this->poster->update($this->mysqli);
		
			//select the user from mySQL and assert it was inserted properly
			$this->sqlPoster = Poster::getPosterByPosterType($this->mysqli, $this->posterType);
		
			// verify the PosterType and ContactId changed
			$this->assertIdentical($this->sqlPoster[0]->getPosterType(), $newPosterType);
			$this->assertIdentical($this->sqlPoster[0]->getContactId(), $newContactId);
            }
            
            // teardown
            public function tearDown()
            {
                $this->poster->delete($this->mysqli);
            }
        }
?>