<?php

namespace Tests\Util;

use Neuron\Util\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
	public function testConstructorAndDefaults()
	{
		$email = new Email();

		// Default type should be EMAIL_HTML
		$this->assertEquals( Email::EMAIL_HTML, $email->getType() );

		// Lists should be empty by default
		$this->assertEmpty( $email->getToList() );
		$this->assertEmpty( $email->getCCList() );
		$this->assertEmpty( $email->getBCCList() );
		$this->assertEmpty( $email->getAttachList() );
	}

	public function testSetAndGetType()
	{
		$email = new Email();

		// Test setting to TEXT
		$email->setType( Email::EMAIL_TEXT );
		$this->assertEquals( Email::EMAIL_TEXT, $email->getType() );

		// Test setting to HTML
		$email->setType( Email::EMAIL_HTML );
		$this->assertEquals( Email::EMAIL_HTML, $email->getType() );
	}

	public function testAddToAndGetToList()
	{
		$email = new Email();

		$email->addTo( 'user1@example.com' );
		$this->assertCount( 1, $email->getToList() );
		$this->assertContains( 'user1@example.com', $email->getToList() );

		$email->addTo( 'user2@example.com' );
		$this->assertCount( 2, $email->getToList() );
		$this->assertContains( 'user2@example.com', $email->getToList() );
	}

	public function testAddCCAndGetCCList()
	{
		$email = new Email();

		$email->addCC( 'cc1@example.com' );
		$this->assertCount( 1, $email->getCCList() );
		$this->assertContains( 'cc1@example.com', $email->getCCList() );

		$email->addCC( 'cc2@example.com' );
		$this->assertCount( 2, $email->getCCList() );
		$this->assertContains( 'cc2@example.com', $email->getCCList() );
	}

	public function testAddBCCAndGetBCCList()
	{
		$email = new Email();

		$email->addBCC( 'bcc1@example.com' );
		$this->assertCount( 1, $email->getBCCList() );
		$this->assertContains( 'bcc1@example.com', $email->getBCCList() );

		$email->addBCC( 'bcc2@example.com' );
		$this->assertCount( 2, $email->getBCCList() );
		$this->assertContains( 'bcc2@example.com', $email->getBCCList() );
	}

	public function testAttachFileAndGetAttachList()
	{
		$email = new Email();

		$email->attachFile( '/path/to/file1.pdf' );
		$this->assertCount( 1, $email->getAttachList() );
		$this->assertContains( '/path/to/file1.pdf', $email->getAttachList() );

		$email->attachFile( '/path/to/file2.pdf' );
		$this->assertCount( 2, $email->getAttachList() );
		$this->assertContains( '/path/to/file2.pdf', $email->getAttachList() );
	}

	public function testSetAndGetFrom()
	{
		$email = new Email();

		$email->setFrom( 'sender@example.com' );
		$this->assertEquals( 'sender@example.com', $email->getFrom() );
	}

	public function testSetAndGetSubject()
	{
		$email = new Email();

		$email->setSubject( 'Test Subject' );
		$this->assertEquals( 'Test Subject', $email->getSubject() );
	}

	public function testSetAndGetBody()
	{
		$email = new Email();

		$body = '<html><body>Test email body</body></html>';
		$email->setBody( $body );
		$this->assertEquals( $body, $email->getBody() );
	}

	public function testGetArrayListWithEmptyArray()
	{
		$email = new Email();

		$reflection = new \ReflectionClass( $email );
		$method = $reflection->getMethod( 'getArrayList' );
		$method->setAccessible( true );

		$result = $method->invoke( $email, [] );
		$this->assertEquals( '', $result );
	}

	public function testGetArrayListWithSingleItem()
	{
		$email = new Email();

		$reflection = new \ReflectionClass( $email );
		$method = $reflection->getMethod( 'getArrayList' );
		$method->setAccessible( true );

		$result = $method->invoke( $email, ['user@example.com'] );
		$this->assertEquals( 'user@example.com', $result );
	}

	public function testGetArrayListWithMultipleItems()
	{
		$email = new Email();

		$reflection = new \ReflectionClass( $email );
		$method = $reflection->getMethod( 'getArrayList' );
		$method->setAccessible( true );

		$result = $method->invoke( $email, ['user1@example.com', 'user2@example.com', 'user3@example.com'] );
		$this->assertEquals( 'user1@example.com,user2@example.com,user3@example.com', $result );
	}

	public function testGetAttachmentCodeWithTextEmail()
	{
		$email = new Email();
		$email->setType( Email::EMAIL_TEXT );
		$email->setBody( 'Plain text email body' );

		// Create a temporary test file
		$tmpFile = sys_get_temp_dir() . '/test_attachment_' . uniqid() . '.txt';
		file_put_contents( $tmpFile, 'Test file content' );

		$reflection = new \ReflectionClass( $email );
		$method = $reflection->getMethod( 'getAttachmentCode' );
		$method->setAccessible( true );

		$result = $method->invoke( $email, $tmpFile );

		// Verify the result contains expected MIME parts
		$this->assertStringContainsString( 'This is a multi-part message in MIME format', $result );
		$this->assertStringContainsString( 'Content-Type: text/plain', $result );
		$this->assertStringContainsString( 'Plain text email body', $result );
		$this->assertStringContainsString( 'Content-Transfer-Encoding: base64', $result );

		// Clean up
		unlink( $tmpFile );
	}

	public function testGetAttachmentCodeWithHtmlEmail()
	{
		$email = new Email();
		$email->setType( Email::EMAIL_HTML );
		$email->setBody( '<html><body>HTML email body</body></html>' );

		// Create a temporary test file
		$tmpFile = sys_get_temp_dir() . '/test_attachment_' . uniqid() . '.pdf';
		file_put_contents( $tmpFile, 'PDF file content' );

		$reflection = new \ReflectionClass( $email );
		$method = $reflection->getMethod( 'getAttachmentCode' );
		$method->setAccessible( true );

		$result = $method->invoke( $email, $tmpFile );

		// Verify the result contains expected MIME parts
		$this->assertStringContainsString( 'This is a multi-part message in MIME format', $result );
		$this->assertStringContainsString( 'Content-Type: text/html', $result );
		$this->assertStringContainsString( 'HTML email body', $result );
		$this->assertStringContainsString( 'Content-Transfer-Encoding: base64', $result );

		// Clean up
		unlink( $tmpFile );
	}

	public function testCompleteEmailConfiguration()
	{
		$email = new Email();

		// Configure a complete email
		$email->setType( Email::EMAIL_HTML );
		$email->setFrom( 'sender@example.com' );
		$email->setSubject( 'Test Email Subject' );
		$email->setBody( '<html><body><h1>Test Email</h1></body></html>' );
		$email->addTo( 'recipient1@example.com' );
		$email->addTo( 'recipient2@example.com' );
		$email->addCC( 'cc@example.com' );
		$email->addBCC( 'bcc@example.com' );

		// Verify all properties are set correctly
		$this->assertEquals( Email::EMAIL_HTML, $email->getType() );
		$this->assertEquals( 'sender@example.com', $email->getFrom() );
		$this->assertEquals( 'Test Email Subject', $email->getSubject() );
		$this->assertStringContainsString( '<h1>Test Email</h1>', $email->getBody() );
		$this->assertCount( 2, $email->getToList() );
		$this->assertCount( 1, $email->getCCList() );
		$this->assertCount( 1, $email->getBCCList() );
	}

	public function testEmailConstants()
	{
		// Verify constants are defined with expected values
		$this->assertEquals( 0, Email::EMAIL_TEXT );
		$this->assertEquals( 1, Email::EMAIL_HTML );
	}

	public function testMultipleRecipients()
	{
		$email = new Email();

		// Add multiple recipients of each type
		for( $i = 1; $i <= 5; $i++ )
		{
			$email->addTo( "user{$i}@example.com" );
			$email->addCC( "cc{$i}@example.com" );
			$email->addBCC( "bcc{$i}@example.com" );
		}

		$this->assertCount( 5, $email->getToList() );
		$this->assertCount( 5, $email->getCCList() );
		$this->assertCount( 5, $email->getBCCList() );
	}

	public function testMultipleAttachments()
	{
		$email = new Email();

		// Add multiple attachments
		$email->attachFile( '/path/to/document.pdf' );
		$email->attachFile( '/path/to/image.jpg' );
		$email->attachFile( '/path/to/spreadsheet.xlsx' );

		$this->assertCount( 3, $email->getAttachList() );
		$this->assertContains( '/path/to/document.pdf', $email->getAttachList() );
		$this->assertContains( '/path/to/image.jpg', $email->getAttachList() );
		$this->assertContains( '/path/to/spreadsheet.xlsx', $email->getAttachList() );
	}

	public function testSendTextEmailWithoutAttachments()
	{
		$email = new Email();
		$email->setType( Email::EMAIL_TEXT );
		$email->setFrom( 'sender@example.com' );
		$email->setSubject( 'Test Subject' );
		$email->setBody( 'Plain text email body' );
		$email->addTo( 'recipient@example.com' );

		// The send() method calls mail() which we can't easily mock
		// We'll test that it returns a boolean and doesn't throw exceptions
		$result = @$email->send();

		// mail() returns false if it fails to accept the email for delivery
		// In testing environment, this is expected behavior
		$this->assertIsBool( $result );
	}

	public function testSendHtmlEmailWithoutAttachments()
	{
		$email = new Email();
		$email->setType( Email::EMAIL_HTML );
		$email->setFrom( 'sender@example.com' );
		$email->setSubject( 'HTML Test Subject' );
		$email->setBody( '<html><body><h1>HTML Email</h1></body></html>' );
		$email->addTo( 'recipient@example.com' );
		$email->addCC( 'cc@example.com' );
		$email->addBCC( 'bcc@example.com' );

		// Test that send executes without throwing exceptions
		$result = @$email->send();

		$this->assertIsBool( $result );
	}

	public function testSendEmailWithAttachments()
	{
		$email = new Email();
		$email->setType( Email::EMAIL_HTML );
		$email->setFrom( 'sender@example.com' );
		$email->setSubject( 'Email with Attachment' );
		$email->setBody( '<html><body>Email with attachment</body></html>' );
		$email->addTo( 'recipient@example.com' );

		// Create a temporary test file
		$tmpFile = sys_get_temp_dir() . '/test_email_attachment_' . uniqid() . '.txt';
		file_put_contents( $tmpFile, 'Test attachment content' );

		$email->attachFile( $tmpFile );

		// Test that send executes without throwing exceptions
		$result = @$email->send();

		$this->assertIsBool( $result );

		// Clean up
		unlink( $tmpFile );
	}

	public function testSendWithMultipleRecipientsAndAttachments()
	{
		$email = new Email();
		$email->setType( Email::EMAIL_TEXT );
		$email->setFrom( 'sender@example.com' );
		$email->setSubject( 'Multiple Recipients Test' );
		$email->setBody( 'Test email with multiple recipients and attachments' );
		$email->addTo( 'recipient1@example.com' );
		$email->addTo( 'recipient2@example.com' );
		$email->addCC( 'cc1@example.com' );
		$email->addCC( 'cc2@example.com' );
		$email->addBCC( 'bcc@example.com' );

		// Create temporary test files
		$tmpFile1 = sys_get_temp_dir() . '/test_attach1_' . uniqid() . '.txt';
		$tmpFile2 = sys_get_temp_dir() . '/test_attach2_' . uniqid() . '.pdf';
		file_put_contents( $tmpFile1, 'First attachment' );
		file_put_contents( $tmpFile2, 'Second attachment' );

		$email->attachFile( $tmpFile1 );
		$email->attachFile( $tmpFile2 );

		// Test that send executes without throwing exceptions
		$result = @$email->send();

		$this->assertIsBool( $result );

		// Clean up
		unlink( $tmpFile1 );
		unlink( $tmpFile2 );
	}
}
