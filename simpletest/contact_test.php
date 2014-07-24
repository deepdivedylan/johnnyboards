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
				$this->contact = new Contact(-1, $this->companyName, $this->address1, $this->address2, $this->city, $this->zipcode, $this->state, $this->phoneNumber, $this->email);
				$this->contact->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "unable to connect to mySQL: ". $exception->getMessage();
			}
		}
			//then assert, this was called ok() in qunit  
		public function testGetContactsById()
		{
			$this->sqlContact = Contact::getContactById($this->mysqli, $this->contact->getIdcontact());
			$this->assertIdentical($this->contact, $this->sqlContact);
		}
			//setup your expectations
		public function testGetContactsByIdInvalid()
		{
			$this->expectException("Exception");
			@Contact::getContactsById($this->mysqli, 0);
		}
     
		public function testGetContactsByEmail()
		{
			$this->sqlContact = Contact::getContactByEmail($this->mysqli, $this->email);
			$this->assertIdentical($this->contact, $this->sqlContact);
		}
			//setup your expectations
		public function testGetContactsByEmailInvalid()
		{
			$this->expectException("Exception");
			@Contact::getContactsByEmail($this->mysqli, "josh@joshy.com");
		}
		
		public function testValidUpdateContact()
		{	
			$newIdContact = "JB contacts are better";
			$newCompanyName = "Just ask me and I'll tell you.";
			$newAddress1 = "Just ask me and I'll tell you.";
			$newAddress2= "Just ask me and I'll tell you.";
			$newCity= "Just ask me and I'll tell you.";
			$newZipCode= "98888";
			$newPhoneNumber= "911-911-9111";
			$newEmail= "Just ask me and I'll tell you.";
			
			
			$this->contact->setIdContact($newIdContact);
			$this->contact->setCompanyName($newCompanyName);
			$this->contact->setAddress1($newAddress1);
			$this->contact->setAddress2($newAddress2);
			$this->contact->setCity($newCity);
			$this->contact->setZipCode($newZipCode);
			$this->contact->setPhoneNumber($newPhoneNumber);
			$this->contact->setEmail($newEmail);
			$this->contact->update($this->mysqli);
			
											
			//select the user from mySQL and assert it was inserted properly
			$this->sqlContact = Contact::getContactById($this->mysqli, $this->contact->getIdcontact());
		
			// verify the IdContact , CompanyName , Address1,Address2, City , zipCode, PhoneNumebr and Email changed
			$this->assertIdentical($this->sqlContact->getIdContact(), $newIdContact);
			$this->assertIdentical($this->sqlContact->getCompanyName (), $newCompanyName);
			$this->assertIdentical($this->sqlContact->getAdress1(), $newAddress1);
			$this->assertIdentical($this->sqlContact->getAddress2(), $newAddress2);
			$this->assertIdentical($this->sqlContact->getCity(), $newCity);
			$this->assertIdentical($this->sqlContact->getPhoneNumber(), $newPhoneNumber);
			$this->assertIdentical($this->sqlContact->getEmail(), $newEmail);
            }
            
            // teardown
            public function tearDown()
            {
                $this->contact->delete($this->mysqli);
            }
        }
?>