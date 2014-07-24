<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("/var/www/html/php/classes/printer.php");
	
	class PrinterTest extends UnitTestCase
	{   
		// variable to hold the mysqli objects
		private $sqlPrinter;
		private $printer;
            
	    // constant variables to reuse. set interger or string
	    /* state (member) variables for a printer object
		 * input: (int) new Id
		 * input: (string) new hours open
		 * input: (double) new longitude
		 * input: (double) new latitude
		 * input: (string) areasCovered
		 * input: (int) new contact id
		 *
		 * */
		private $id;  // this is assigned as -1 
		private $hoursOpen = "8am-5pm";
		private $longitude = "-77.037852";
		private $latitude = "38.898556";
		private $areasCovered = "Santa Fe";
		private $contact;
		
		
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
				
				$this->printer = new Printer(-1, $this->hoursOpen, $this->longitude, $this->latitude, $this->areasCovered, $this->contact->getIdcontact());
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "unable to connect to mySQL: ". $exception->getMessage();
			}
		}
			//then assert, this was called ok() in qunit  
		public function testGetPrintersById()
		{
			$this->sqlPrinter = Printer::getPrinterById($this->mysqli, $this->printer->getId());
			$this->assertIdentical($this->printer, $this->sqlPrinter);
		}
			//setup your expectations
		public function testGetPrinterByIdInvalid()
		{
			$this->expectException("Exception");
			@Printer::getPrintersById($this->mysqli, 0);
		}
     
		public function testGetPrintersByContactId()
		{
			$this->sqlPrinter = Printer::getPrinterByContactId($this->mysqli, $this->contact->getIdcontact());
			$this->assertIdentical($this->printer, $this->sqlPrinter);
		}
			//setup your expectations
		public function testGetPrinterByContactIdInvalid()
		{
			$this->expectException("Exception");
			@Printer::getPrintersByContactId($this->mysqli, 0);
		}
		
		public function testValidUpdatePrinter()
		{	
			$newHoursOpen = "JB printers are better";
			$newLongitude = "876584";
			$newLatitude = "-098798768756";
			$newAreasCovered= "Just ask me and I'll tell you.";
			$newContactId= "1";
			
			$this->printer->setHoursOpen($newHoursOpen);
			$this->printer->setLongitude($newLongitude);
			$this->printer->setLatitude($newLatitude);
			$this->printer->setAreasCovered($newAreasCovered);
			$this->printer->setContactId($newContactId);
			$this->printer->update($this->mysqli);
			
			
			//select the user from mySQL and assert it was inserted properly
			$this->sqlPrinter = Printer::getPrinterById($this->mysqli, $this->printer-getId());
		
			// verify the HoursOpen , Longitude, Latitude,AreasCovered ContactId changed
			$this->assertIdentical($this->sqlPrinter->getHoursOpen(), $newHoursOpen);
			$this->assertIdentical($this->sqlPrinter->getLongitude(), $newLongitude);
			$this->assertIdentical($this->sqlPrinter->getLatitude(), $newLatitude);
			$this->assertIdentical($this->sqlPrinter->getAreasCovered(), $newAreasCovered);
			$this->assertIdentical($this->sqlPrinter->getContactId(), $newContactId);
            }
            
            // teardown
            public function tearDown()
            {
                $this->printer->delete($this->mysqli);
            }
        }
?>