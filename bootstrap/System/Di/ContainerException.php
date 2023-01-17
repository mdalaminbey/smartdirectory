<?php
/**
 * SmartDirectory DI ContainerException
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

namespace SmartDirectory\Bootstrap\System\Di;

use Psr\Container\ContainerExceptionInterface;

class ContainerException extends \Exception implements ContainerExceptionInterface {}
