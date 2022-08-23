<?php

namespace Ilimic\Classnames;

class Classnames {

	/**
	 * @var non-empty-string[] $classes
	 */
	private array $classes = [];

	/**
	 * @param mixed $args
	 */
	public function __construct( ...$args )
	{
		$this->classes = self::buildClasses( $args );
	}

	public function addClass( string $class ) : void
	{
		if ( ! $this->hasClass( $class ) ) {
			$this->classes = \array_merge( $this->classes, self::normalizeClass( $class ) );
		}
	}

	/**
	 * @param string[] $classes
	 */
	public function addClasses( array $classes ) : void
	{
		foreach ( $classes as $class ) {
			$this->addClass( $class );
		}
	}

	public function removeClass( string $class ) : void
	{
		// $key = \array_search( $class, $this->classes, true );
		// if ( $key !== false ) {
		// 	unset( $this->classes[ $key ] );
		// }

		// doing things the same way as jQuery, removeClass should
		// remove all classes if there are duplicates
		foreach ( $this->classes as $i => $_class ) {
			if ( $_class === $class ) {
				unset( $this->classes[$i] );
			}
		}
	}

	/**
	 * @param string[] $classes
	 */
	public function removeClasses( array $classes ) : void
	{
		foreach ( $classes as $class ) {
			$this->removeClass( $class );
		}
	}

	public function hasClass( string $class ) : bool
	{
		return \in_array( $class, $this->classes, true );
	}

	/**
	 * @param string[] $classes
	 */
	public function hasAnyClass( array $classes ) : bool
	{
		foreach ( $classes as $class ) {
			if ( $this->hasClass( $class ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param non-empty-array<string> $classes
	 */
	public function hasAllClasses( array $classes ) : bool
	{
		if ( ! $classes ) {
			throw new \ValueError( 'hasAllClasses(): Argument #1 ($classes) must not be empty' );
		}

		foreach ( $classes as $class ) {
			if ( ! $this->hasClass( $class ) ) {
				return false;
			}
		}
		return true;
	}

	public function getClassnames() : string
	{
		return \implode( ' ', $this->classes );
	}

	public function __toString() : string
	{
		return $this->getClassnames();
	}

	// /**
	//  * Polyfill for str_contains() on PHP 7.4
	//  */
	// private static function str_contains( string $haystack, string $needle ) : bool {
	// 	return \strpos( $haystack, $needle ) !== false;
	// }

	/**
	 * @return non-empty-string[]
	 */
	private static function normalizeClass( string $class ) : array
	{
		if ( $class === '' ) {
			return [];
		}

		$classes = \explode( ' ', $class );
		$classes = \array_filter( $classes, fn ( $class ) => $class !== '' );
		$classes = \array_unique( $classes );

		return $classes;
	}

	/**
	 * @param mixed[] $args
	 * @return non-empty-string[]
	 */
	private static function buildClasses( array $args ) : array
	{
		$classes = [];

		foreach ( $args as $key => $val ) {
			if ( \is_string( $key ) ) {
				if ( $val ) {
					$classes = \array_merge( $classes, self::normalizeClass( $key ) );
				}
			} elseif ( \is_string( $val ) ) {
				$classes = \array_merge( $classes, self::normalizeClass( $val ) );
			} elseif ( \is_array( $val ) ) {
				$classes = \array_merge( $classes, self::buildClasses( $val ) );
			}
		}

		return $classes;
	}

	/**
	 * @param mixed[] $args
	 */
	public static function classnames( ...$args ) : string
	{
		return (string) new Classnames( ...$args );
	}
}
