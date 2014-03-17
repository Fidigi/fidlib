<?php

define('FIDLIB_REP',realpath(dirname(__FILE__)));
define('FIDLIB_VER','0.5.0');

/**----- Core -----**/

require("core/core.core.php");

/**----- Autoload -----**/

Core::getInstance();
