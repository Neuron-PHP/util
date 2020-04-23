<?php

class LanguageTest extends PHPUnit\Framework\TestCase
{
	public function testLoad()
	{
		$Language = new \Neuron\Util\Language();

		$this->assertTrue(
			$Language->load(
				"english,title,Awesome program
			french,title,Programme genial"
			)
		);

		$this->assertTrue(
			$Language->getText( '', 'english' ) == null
		);

		$this->assertTrue(
			$Language->getText( 'meh', 'meh' ) == null
		);

		$out = $Language->getText( 'title', 'english' );

		$this->assertEquals(
			'Awesome program',
			$out
		);

		$this->assertEquals(
			'Programme genial',
			$Language->getText( 'title', 'french' )
		);
	}
}
