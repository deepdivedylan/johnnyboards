<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("/var/www/html/php/classes/poster.php");
	
	class PosterTest extends UnitTestCase
	{
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
		private $contact; //object
		
					 
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
				
				$this->poster = new Poster(-1, $this->posterType, $this->contact->getIdcontact());
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "unable to connect to mySQL: ". $exception->getMessage();
			}
		}
			//then assert, this was called ok() in qunit  
		public function testGetPosterByPosterType()
		{
			$this->sqlPoster = Poster::getPostersByPosterType($this->mysqli, $this->posterType);
			$this->assertIdentical($this->poster, $this->sqlPoster[0]);
		}
			//setup your expectations
		public function testGetPosterByPosterTypeInvalid()
		{
			$this->expectException("Exception");
			@Poster::getPostersByPosterType($this->mysqli, 0);
		}
     
		public function testGetPosterByContactId()
		{
			$this->sqlPoster = Poster::getPostersByContactId($this->mysqli, $this->contact->getIdcontact());
			$this->assertIdentical($this->poster, $this->sqlPoster[0]);
		}
			//setup your expectations
		public function testGetPosterByContactIdInvalid()
		{
			$this->expectException("Exception");
			@Poster::getPostersByContactId($this->mysqli, 0);
		}
		
		public function testGetPosterById()
		{
			$this->sqlPoster = Poster::getPosterById($this->mysqli, $this->poster->getId());
			$this->assertIdentical($this->poster, $this->sqlPoster);
		}
			//setup your expectations
		public function testGetPosterByIdInvalid()
		{
			$this->expectException("Exception");
			@Poster::getPosterById($this->mysqli, 0);
		}
		
		public function testValidUpdatePoster()
		{	
			$newPosterType = "JB posters are better";
			$newContactId = "Just ask me and I'll tell you.";
			$this->poster->setPosterType($newPosterType);
			$this->poster->setContactId($newContactId);
			$this->poster->update($this->mysqli);
		
			//select the user from mySQL and assert it was inserted properly
			$this->sqlPoster = Poster::getPosterById($this->mysqli, $this->poster->getId());
		
			// verify the PosterType and ContactId changed
			$this->assertIdentical($this->sqlPoster->getPosterType(), $newPosterType);
			$this->assertIdentical($this->sqlPoster->getContactId(), $newContactId);
            }
            
            // teardown
            public function tearDown()
            {
                $this->poster->delete($this->mysqli);
            }
        }
?>