<?php
/**
 * FidLib V1.0.0
 * 	
 * 	LICENCE :
  	Copyright 2011, 2013, 2014, 2018 Arnaud LAURENT 
  	This file is part of FidLib.

    FidLib is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, either version 2.1 of the License, or
    (at your option) any later version.

    FidLib is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with FidLib.  If not, see <http://www.gnu.org/licenses/>.
 */

define('FIDLIB_REP',realpath(dirname(__FILE__)));
define('FIDLIB_VER','1.0.0');

/**----- Require -----**/

require("Fidlib/Tools/StringToolsCore.php");
require("Fidlib/Tools/FileToolsCore.php");
require("Fidlib/Tools/ArrayToolsCore.php");
require("Fidlib/ObjectType/FidBase.php");
require("Fidlib/ObjectType/FidObject.php");
require("Fidlib/ObjectType/CollectionFidObjectManager.php");
require("Fidlib/Core.php");
require("Fidlib/AutoloadCore.php");

/**----- Autoload -----**/

use Fidlib\Core;

Core::getInstance();
