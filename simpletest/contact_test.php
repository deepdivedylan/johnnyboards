<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("/var/www/html/php/classes/contact.php");
	
	class ContactTest extends UnitTestCase
	{
		// variable to hold the mysqli objects
		private $sqlContact;
		private $contact;
            
		// constant variables to reuse. set interger or string
		 /*
             *input: (integer) idcontact
             *input: (string) company name
             *input: (string) address line 1
             *input: (string) address line 2
             *input: (string) city
             *input: (string) zipcode
             *input: (string) state
             *input: (string) phone
             *input: (string) email */
		 
		//member variables
	    private $idcontact; // this is assigned as -1 
            private $companyName = "Flying Star";
            private $address1="832 Madison NE";
            private $address2= "";
            private $city="Albuquerque";
            private $zipcode="87110";
            private $state="NM";
            private $phoneNumber="505-363-4106";
            private $email="ruben@johnnyboards.com";	 
			
			
		 
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
		public function testGetContactsByIdContact()
		{
			$this->$sqlContact = Contact::getContactsByIdContact($this->mysqli, $this->idcontact);
			$this->assertIdentical($this->contact, $this->$sqlContact[0]);
		}
			//setup your expectations
		public function testGetContactsByIdContactInvalid()
		{
			$this->expectException("Exception");
			@Contact::getContactsByIdContact($this->mysqli, 0);
		}
     
		public function testValidUpdateContact()
		{	
			$newIdContact = "JB contacts are better";
			$newCompanyName = "Just ask me and I'll tell you.";
			$newAddress1 = "Just ask me and I'll tell you.";
			$newAddress2= "Just ask me and I'll tell you.";
			$newCity= "Just ask me and I'll tell you.";
			$newZipCode= "Just ask me and I'll tell you.";
			$newPhoneNumber= "Just ask me and I'll tell you.";
			$newEmail= "Just ask me and I'll tell you.";
			
			
			$this->contact->setIdContact($newIdContact);
			$this->contact->setCompanyName 		($newCompanyName 		);
			$this->contact->setAddress1($newAddress1);
			$this->contact->setAddress2($newAddress2);
			$this->contact->setCity($newCity);
			$this->contact->setZipCode($newZipCode);
			$this->contact->setPhoneNumber($newPhoneNumber);
			$this->contact->setEmail($newEmail);
			$this->contact->update($this->mysqli);
			
											
			//select the user from mySQL and assert it was inserted properly
			$this->sqlContact = Contact::getContactByIdContact($this->mysqli, $this->idcontact);
		
			// verify the IdContact , CompanyName , Address1,Address2, City , zipCode, PhoneNumebr and Email changed
			$this->assertIdentical($this->sqlContact[0]->getIdContact(), $newIdContact);
			$this->assertIdentical($this->sqlContact[0]->getCompanyName (), $newCompanyName);
			$this->assertIdentical($this->sqlContact[0]->getAdress1(), $newAddress1);
			$this->assertIdentical($this->sqlContact[0]->getAddress2(), $newAddress2);
			$this->assertIdentical($this->sqlContact[0]->getCity(), $newCity);
			$this->assertIdentical($this->sqlContact[0]->getZipCode(), $newZipCode);
			$this->assertIdentical($this->sqlContact[0]->getPhoneNumber(), $newPhoneNumber);
			$this->assertIdentical($this->sqlContact[0]->getEmail(), $newEmail);
            }
            
            // teardown
            public function tearDown()
            {
                $this->contact->delete($this->mysqli);
            }
        }
?>