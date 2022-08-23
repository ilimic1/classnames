<?php

namespace Ilimic\Classnames;

class Classnames {

	/**
	 * @var string[] $classes
	 * @phpstan-var non-empty-string[] $classes
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
		if ( $class === '' ) {
			return;
		}

		if ( ! $this->hasClass( $class ) ) {
			$this->classes = \array_merge( $this->classes, self::normalizeClass( $class ) );
		}
	}

	public function removeClass( string $class ) : void
	{
		if ( $class === '' ) {
			return;
		}

		// doing things the same way as jQuery, removeClass should
		// remove all classes if there are duplicates
		foreach ( $this->classes as $i => $_class ) {
			if ( $_class === $class ) {
				unset( $this->classes[$i] );
			}
		}
	}

	public function hasClass( string $class ) : bool
	{
		if ( $class === '' ) {
			return false;
		}

		return \in_array( $class, $this->classes, true );
	}

	public function getClassnames() : string
	{
		return \implode( ' ', $this->classes );
	}

	public function __toString() : string
	{
		return $this->getClassnames();
	}

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
	 * @return string[]
	 * @phpstan-return non-empty-string[]
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
