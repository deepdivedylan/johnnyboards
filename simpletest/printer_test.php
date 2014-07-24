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
		private $contactId;
		
		
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
		public function testGetPrintersByHoursOpen()
		{
			$this->$sqlPrinter = Printer::getPrintersByHoursOpen($this->mysqli, $this->hoursOpen);
			$this->assertIdentical($this->printer, $this->$sqlPrinter[0]);
		}
			//setup your expectations
		public function testGetPrintersByHoursOpenInvalid()
		{
			$this->expectException("Exception");
			@Printer::getPrintersByHoursOpen($this->mysqli, 0);
		}
     
		public function testValidUpdatePrinter()
		{	
			$newHoursOpen = "JB printers are better";
			$newLongitude = "Just ask me and I'll tell you.";
			$newLatitude = "Just ask me and I'll tell you.";
			$newAreasCovered= "Just ask me and I'll tell you.";
			$newContactId= "Just ask me and I'll tell you.";
			
			$this->printer->setHoursOpen($newHoursOpen);
			$this->printer->setLongitude($newLongitude);
			$this->printer->setLatitude($newLatitude);
			$this->printer->setAreasCovered($newAreasCovered);
			$this->printer->setContactId($newPrinterId);
			$this->printer->update($this->mysqli);
			
			
			//select the user from mySQL and assert it was inserted properly
			$this->sqlPrinter = Printer::getPrinterByHoursOpen($this->mysqli, $this->hoursOpen);
		
			// verify the HoursOpen , Longitude, Latitude,AreasCovered ContactId changed
			$this->assertIdentical($this->sqlPrinter[0]->getHoursOpen(), $newHoursOpen);
			$this->assertIdentical($this->sqlPrinter[0]->getLongitude(), $newLongitude);
			$this->assertIdentical($this->sqlPrinter[0]->getLatitude(), $newLatitude);
			$this->assertIdentical($this->sqlPrinter[0]->getAreasCovered(), $newAreasCovered);
			$this->assertIdentical($this->sqlPrinter[0]->getContactId(), $newContactId);
            }
            
            // teardown
            public function tearDown()
            {
                $this->printer->delete($this->mysqli);
            }
        }
?>