<?php
/*******************************************************************************
localize.php

Description: Rename this file to localize.php and set the values below, 
sans comments, as needed.

Created by Troy Hurteau
NCSU Libraries, NC State University (libraries.opensource@ncsu.edu).

Copyright (c) 2011 North Carolina State University, Raleigh, NC.

EZadmin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

EZadmin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

EZadmin as distributed by NCSU Libraries is located at:
http://code.google.com/p/ezadmin/

*******************************************************************************/

/*
 * This variable determines some behavior of the application:
 * 'development' : 
 *     Error messages and warnings turned on, debuging enabled.
 * 'production' (default value) or any other value : 
 *     Error messages and warnings turned off, no debugging.
 */
//define ('APPLICATION_ENV', 'production');

/*
 * The absolute or relative (to index.php) path to the application code.
 * It is recommended you move the /application folder or its contents
 * outside of httpd's document root for security purposes. 
 * If you do so, this value should reflect the _path to_ /application's
 * contents, with trailing slash.
 * Alternatively, you can configure httpd (apache) to disallow browser
 * access to /application. 
 * 
 * httpd must have read access to the folder and all contents.
 * default value: the absolute path to index.php
 */
define ('APPLICATION_PATH', realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . '/application/');

/*
 * The absolute or relative (to index.php) path to where you want
 * EZproxy configuration files to be stored on export.
 * 
 * httpd must have write access here.
 */
define ('OUTPUT_PATH', APPLICATION_PATH . 'data/output/');

/*
 * The absolute or relative (to index.php) path to where the XML
 * configuration file for EZadmin is stored.
 * 
 * httpd must have read access here.
 */
define ('APPLICATION_CONF', APPLICATION_PATH . 'config/config.xml');
