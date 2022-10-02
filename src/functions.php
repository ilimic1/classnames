<?php

namespace Ilimic\Classnames;

/**
 * Classnames helper function.
 * 
 * @param mixed[] $args
 */
function classnames( ...$args ) : string
{
	return Classnames::classnames( ...$args );
}

/**
 * Classnames helper function.
 * 
 * @param mixed[] $args
 */
function cn( ...$args ) : string
{
	return Classnames::classnames( ...$args );
}
