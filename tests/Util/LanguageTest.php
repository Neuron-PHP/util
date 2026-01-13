<?php

namespace Tests\Util;

use Neuron\Util\Language;
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
	public function testLoad()
	{
		$language = new Language();

		$this->assertTrue(
			$language->load(
				"english,title,Awesome program
			french,title,Programme genial"
			)
		);

		$this->assertTrue(
			$language->getText( '', 'english' ) == null
		);

		$this->assertTrue(
			$language->getText( 'meh', 'meh' ) == null
		);

		$out = $language->getText( 'title', 'english' );

		$this->assertEquals(
			'Awesome program',
			$out
		);

		$this->assertEquals(
			'Programme genial',
			$language->getText( 'title', 'french' )
		);
	}

	public function testLoadWithEmptyText()
	{
		$language = new Language();

		// Test with null/empty text
		$this->assertFalse(
			$language->load( null )
		);

		$this->assertFalse(
			$language->load( '' )
		);

		$this->assertFalse(
			$language->load( false )
		);
	}

	public function testSetLanguage()
	{
		$language = new Language();

		// Load some test data
		$language->load(
			"spanish,greeting,Hola
			german,greeting,Guten Tag"
		);

		// Set language in session
		$language->setLanguage( 'spanish' );

		$this->assertEquals(
			'spanish',
			$_SESSION[ 'language' ]
		);

		// Test getText without language parameter (uses session)
		$this->assertEquals(
			'Hola',
			$language->getText( 'greeting' )
		);
	}

	public function testGetTextWithSessionLanguage()
	{
		$language = new Language();

		// Load test data
		$language->load(
			"italian,welcome,Benvenuto
			portuguese,welcome,Bem-vindo"
		);

		// Set session language
		$_SESSION[ 'language' ] = 'italian';

		// getText should use session language when no language param provided
		$this->assertEquals(
			'Benvenuto',
			$language->getText( 'welcome' )
		);

		// Change session language
		$_SESSION[ 'language' ] = 'portuguese';

		$this->assertEquals(
			'Bem-vindo',
			$language->getText( 'welcome' )
		);
	}
}
