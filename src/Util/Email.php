<?php

namespace Neuron\Util;

/**
 * Email utility class for sending HTML and text emails with attachments.
 * 
 * Provides a simple interface for composing and sending emails using PHP's mail() function.
 * Supports multiple recipients (To, CC, BCC), file attachments, and both HTML and plain text content.
 * 
 * @package Neuron\Util
 */
class Email
{
	const EMAIL_TEXT = 0;
	const EMAIL_HTML = 1;

	private $_toList		= array();
	private $_ccList		= array();
	private $_bccList	= array();
	private $_attachList= array();

	private $_headers;
	private $_mimeBoundry;
	private $_from;
	private $_subject;
	private $_body;

	private $_type = Email::EMAIL_HTML;

	/**
	 * @param $type
	 */

	public function setType( $type )
	{
		$this->_type = $type;
	}

	/**
	 * @return int
	 */

	public function getType()
	{ return $this->_type; }

	/**
	 * @param $addr
	 */

	public function addTo( $addr )
	{
		array_push( $this->_toList, $addr );
	}

	/**
	 * @return array
	 */

	public function getToList()
	{
		return $this->_toList;
	}

	/**
	 * @param $addr
	 */

	public function addCC( $addr )
	{
		array_push( $this->_ccList, $addr );
	}

	/**
	 * @return array
	 */

	public function getCCList()
	{
		return $this->_ccList;
	}

	/**
	 * @param $addr
	 */

	public function addBCC( $addr )
	{
		array_push( $this->_bccList, $addr );
	}

	/**
	 * @return array
	 */

	public function getBCCList()
	{
		return $this->_bccList;
	}

	/**
	 * @param $file
	 */

	public function attachFile( $file )
	{
		array_push( $this->_attachList, $file );
	}

	/**
	 * @return array
	 */

	public function getAttachList()
	{
		return $this->_attachList;
	}

	/**
	 * @param $from
	 */

	public function setFrom( $from )
	{
		$this->_from = $from;
	}

	public function getFrom()
	{
		return $this->_from;
	}

	/**
	 * @param $subject
	 */

	public function setSubject( $subject )
	{
		$this->_subject = $subject;
	}

	/**
	 * @return mixed
	 */

	public function getSubject()
	{
		return $this->_subject;
	}

	/**
	 * @param $body
	 */

	public function setBody( $body )
	{
		$this->_body = $body;
	}

	/**
	 * @return mixed
	 */

	public function getBody()
	{
		return $this->_body;
	}

	/**
	 * @param $arr
	 * @return string
	 */

	protected function getArrayList( $arr )
	{
		$list = "";

		foreach( $arr as $s )
		{
			if( strlen( $list ) )
				$list .= ',';
			$list .= $s;
		}
		return $list;
	}

	/**
	 * @param $name
	 * @return string
	 */

	protected function getAttachmentCode( $name )
	{
		$file = fopen( $name, 'rb' );
		$data = fread( $file, filesize( $name ) );
		fclose( $file );

		$message = "This is a multi-part message in MIME format.\n\n" .
						 "--{$this->_mimeBoundry}\n";

		if( $this->_type == Email::EMAIL_TEXT )
		{
			$message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
		}
		else if( $this->_type == Email::EMAIL_HTML )
		{
			$message .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
		}

		$message .= "Content-Transfer-Encoding: 7bit\n\n" .
						$this->_body . "\n\n";

		// Base64 encode the file data
		$data = chunk_split( base64_encode( $data ) );

		// Add file attachment to the message

		$fileattType = filetype( $name );
		$fileattName = basename( $name );

		$message .= "--{$this->_mimeBoundry}\n" .
						"Content-Type: {$fileattType};\n" .
						" name=\"{$fileattName}\"\n" .
						"Content-Disposition: attachment;\n" .
						" filename=\"{$fileattName}\"\n" .
						"Content-Transfer-Encoding: base64\n\n" .
						$data . "\n\n" .
						"--{$this->_mimeBoundry}--\n";

		return $message;
	}

	/**
	 * @return bool
	 */

	public function send()
	{
		$message = '';
		$headers = '';

		if( count( $this->_attachList ) )
		{
			$semiRand = md5(time());
			$this->_mimeBoundry = "==Multipart_Boundary_x{$semiRand}x";

			$this->_headers .= "Content-Type: multipart/mixed;\n" .
									" boundary=\"{$this->_mimeBoundry}\"";

			foreach( $this->getAttachList() as $name )
				$message .= $this->getAttachmentCode( $name );
		}
		else
		{
			if( $this->getType() == Email::EMAIL_TEXT )
				$headers .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
			else
				if ( $this->getType() == Email::EMAIL_HTML )
					$headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";

			$message = $this->getBody();
		}

		// Send the message

		$headers .= "From: {$this->getFrom()}\nMIME-Version: 1.0\n";
		$headers .= "CC: ".$this->getArrayList( $this->getCCList() )."\n";
		$headers .= "BCC: ".$this->getArrayList( $this->getBCCList() )."\n";

		$ret = @mail(	$this->getArrayList( $this->getToList() ),
							$this->getSubject(),
							$message,
							$headers );

		return $ret;
	}
}

?>
