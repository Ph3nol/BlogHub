<?php

namespace Sly\BlogHub\Exception;

use Sly\BlogHub\Exception\Exception;

/**
 * ConfigurationException.
 *
 * @uses InvalidArgumentException
 * @author Cédric Dugat <cedric@dugat.me>
 */
class ConfigurationException extends \InvalidArgumentException implements Exception
{
}
