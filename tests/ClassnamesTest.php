<?php

declare(strict_types=1);

use Ilimic\Classnames\Classnames;
use PHPUnit\Framework\TestCase;

class MainTest extends TestCase {

	public function testArgsAreFilteredOutCorrectly() : void
	{
		$this->assertSame( '', Classnames::classnames( null, true, false, 1, 1.1, [], new stdClass ) );
		$this->assertSame( 'foo', Classnames::classnames( null, true, false, 1, 1.1, 'foo', [], new stdClass ) );
		$this->assertSame( 'foo bar', Classnames::classnames( null, true, false, 1, 1.1, 'foo', [], new stdClass, 'bar' ) );
		$this->assertSame( 'foo bar', Classnames::classnames( 'foo', [ 'bar' => true ] ) );
		$this->assertSame( 'foo', Classnames::classnames( 'foo', [ 'bar' => false ] ) );
	}

	public function testArgsAreRecursive() : void
	{
		$this->assertSame( 'foo bar baz', Classnames::classnames( [ 'foo', [ 'bar', [ 'baz' ] ] ] ) );
		$this->assertSame( 'foo bar baz', Classnames::classnames( [ 'foo' => true, [ 'bar' => true, [ 'baz' => true ] ] ] ) );
		$this->assertSame( 'foo bar baz', Classnames::classnames( [ [ [ 'foo', 'bar', 'baz' ] ] ] ) );
		$this->assertSame( 'foo bar baz', Classnames::classnames( 'foo', [ [ [ 'bar', 'baz' ] ] ] ) );
	}

	public function testFalsyValuesExcludeClass() : void
	{
		$this->assertSame( '', Classnames::classnames( [ 'bar' => null ] ) );
		$this->assertSame( '', Classnames::classnames( [ 'bar' => false ] ) );
		$this->assertSame( '', Classnames::classnames( [ 'bar' => 0 ] ) );
		$this->assertSame( '', Classnames::classnames( [ 'bar' => '' ] ) );
		$this->assertSame( '', Classnames::classnames( [ 'bar' => '0' ] ) );
		$this->assertSame( '', Classnames::classnames( [ 'bar' => [] ] ) );
		$this->assertSame( 'foo', Classnames::classnames( 'foo', [ 'bar' => null ] ) );
		$this->assertSame( 'foo', Classnames::classnames( 'foo', [ 'bar' => false ] ) );
		$this->assertSame( 'foo', Classnames::classnames( 'foo', [ 'bar' => 0 ] ) );
		$this->assertSame( 'foo', Classnames::classnames( 'foo', [ 'bar' => '' ] ) );
		$this->assertSame( 'foo', Classnames::classnames( 'foo', [ 'bar' => '0' ] ) );
		$this->assertSame( 'foo', Classnames::classnames( 'foo', [ 'bar' => [] ] ) );
	}

	public function testTruthyValuesIncludeClass() : void
	{
		$this->assertSame( 'bar', Classnames::classnames( [ 'bar' => true ] ) );
		$this->assertSame( 'bar', Classnames::classnames( [ 'bar' => 1 ] ) );
		$this->assertSame( 'bar', Classnames::classnames( [ 'bar' => ' ' ] ) );
		$this->assertSame( 'bar', Classnames::classnames( [ 'bar' => '123' ] ) );
		$this->assertSame( 'bar', Classnames::classnames( [ 'bar' => [ 1 ] ] ) );
		$this->assertSame( 'bar', Classnames::classnames( [ 'bar' => new stdClass ] ) );
		$this->assertSame( 'foo bar', Classnames::classnames( 'foo', [ 'bar' => true ] ) );
		$this->assertSame( 'foo bar', Classnames::classnames( 'foo', [ 'bar' => 1 ] ) );
		$this->assertSame( 'foo bar', Classnames::classnames( 'foo', [ 'bar' => ' ' ] ) );
		$this->assertSame( 'foo bar', Classnames::classnames( 'foo', [ 'bar' => '123' ] ) );
		$this->assertSame( 'foo bar', Classnames::classnames( 'foo', [ 'bar' => [ 1 ] ] ) );
		$this->assertSame( 'foo bar', Classnames::classnames( 'foo', [ 'bar' => new stdClass ] ) );
	}

	public function testWhitespaceDoesntAffectReturnedClasses() : void
	{
		$this->assertSame( '', Classnames::classnames( ' ' ) );
		$this->assertSame( '', Classnames::classnames( '  ' ) );

		$this->assertSame( 'foo', Classnames::classnames( 'foo ' ) );
		$this->assertSame( 'foo', Classnames::classnames( ' foo' ) );
		$this->assertSame( 'foo', Classnames::classnames( ' foo ' ) );
		$this->assertSame( 'foo', Classnames::classnames( 'foo  ' ) );
		$this->assertSame( 'foo', Classnames::classnames( '  foo' ) );
		$this->assertSame( 'foo', Classnames::classnames( '  foo  ' ) );

		$this->assertSame( 'foo bar', Classnames::classnames( 'foo  bar' ) );
		$this->assertSame( 'foo bar baz', Classnames::classnames( 'foo  bar  baz' ) );

		$this->assertSame( 'foo bar', Classnames::classnames( 'foo bar ' ) );
		$this->assertSame( 'foo bar', Classnames::classnames( ' foo bar' ) );
		$this->assertSame( 'foo bar', Classnames::classnames( 'foo bar  ' ) );
		$this->assertSame( 'foo bar', Classnames::classnames( '  foo bar' ) );

		$this->assertSame( 'foo bar', Classnames::classnames( 'foo  bar  ' ) );
		$this->assertSame( 'foo bar', Classnames::classnames( '  foo  bar' ) );
		$this->assertSame( 'foo bar', Classnames::classnames( '  foo  bar  ' ) );
	}

	public function testClassDeduplication() : void
	{
		$this->assertSame( 'foo', Classnames::classnames( 'foo foo foo' ) );
		$this->assertSame( 'foo bar', Classnames::classnames( 'foo foo bar bar' ) );
		$this->assertSame( 'foo bar', Classnames::classnames( 'foo bar foo bar' ) );
	}

	public function testVariousCharacters() : void
	{
		$this->assertSame( '-foo', Classnames::classnames( '-foo' ) );
		$this->assertSame( '-foo-', Classnames::classnames( '-foo-' ) );
		$this->assertSame( 'foo-', Classnames::classnames( 'foo-' ) );
		$this->assertSame( 'c-foo', Classnames::classnames( 'c-foo' ) );
		$this->assertSame( 'c-foo--large', Classnames::classnames( 'c-foo--large' ) );
		$this->assertSame( 'C-fOo--LaRgE', Classnames::classnames( 'C-fOo--LaRgE' ) );
		$this->assertSame( 'c-foo--large19', Classnames::classnames( 'c-foo--large19' ) );
	}

	public function testAddClass() : void
	{
		$cn = new Classnames( 'foo' );
		$cn->addClass( 'bar' );
		$this->assertEquals( 'foo bar', $cn->getClassnames() );
		$cn->addClass( 'bar' );
		$this->assertEquals( 'foo bar', $cn->getClassnames() );
		$cn->addClass( '' );
		$this->assertEquals( 'foo bar', $cn->getClassnames() );
		$cn->addClass( ' ' );
		$this->assertEquals( 'foo bar', $cn->getClassnames() );
	}

	public function testRemoveClass() : void
	{
		$cn = new Classnames( 'foo', 'bar', 'baz' );
		$cn->removeClass( 'baz' );
		$this->assertEquals( 'foo bar', $cn->getClassnames() );
		$cn->removeClass( 'bar' );
		$this->assertEquals( 'foo', $cn->getClassnames() );
		$cn->removeClass( 'foo' );
		$this->assertEquals( '', $cn->getClassnames() );
	}

	public function testHasClass() : void
	{
		$cn = new Classnames( 'foo bar' );
		$this->assertTrue( $cn->hasClass( 'foo' ) );
		$this->assertFalse( $cn->hasClass( '' ) );
		$this->assertTrue( $cn->hasClass( 'bar' ) );
		$this->assertFalse( $cn->hasClass( 'baz' ) );
		$this->assertFalse( $cn->hasClass( 'foo ' ) );
	}

	public function testGetClassnames() : void
	{
		$cn = new Classnames();
		$this->assertEquals( '', $cn->getClassnames() );

		$cn2 = new Classnames( '' );
		$this->assertEquals( '', $cn2->getClassnames() );

		$cn3 = new Classnames( 'foo bar baz' );
		$this->assertEquals( 'foo bar baz', $cn3->getClassnames() );
	}

	public function testToString() : void
	{
		$cn = new Classnames();
		$this->assertEquals( '', (string) $cn );

		$cn2 = new Classnames( '' );
		$this->assertEquals( '', (string) $cn2 );

		$cn3 = new Classnames( 'foo bar baz' );
		$this->assertEquals( 'foo bar baz', (string) $cn3 );
	}

	public function testHelperFunctionsExist() : void
	{
		$this->assertTrue( function_exists( 'Ilimic\Classnames\classnames' ) );
		$this->assertTrue( function_exists( 'Ilimic\Classnames\cn' ) );
	}
}
