<?php

class LanguageTest extends PHPUnit\Framework\TestCase
{
	public function testLoad()
	{
		$language = new \Neuron\Util\Language();

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
}
