<?php
/*******************************************************************************
ezproxydb.class.php

Description: Connection parameters to database.

Created by Karl Doerr, 
Modified by Troy Hurteau, Eric McEachern, Emily Lynema, 
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

class ezproxydb extends mysqli{

	private static $instance = NULL;
		
	// production and development values are the same
	private $dbHost = 'localhost';
	private $dbUser = 'DBUSER';
	private $dbPwd = 'PASSWORD';
	private $dbName = 'SCHEMA';
	
	private function __construct()
	{
        $this->init();
        $this->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
        $registry = Registry::getInstance();
        $this->dbHost = $registry->config->get('db:host');
        $this->dbUser = $registry->config->get('db:user');
        $this->dbPwd = $registry->config->get('db:password');
        $this->dbName = $registry->config->get('db:schema');
        $success = $this->real_connect(
            $this->dbHost, $this->dbUser, $this->dbPwd, $this->dbName
        );
        if(!$success || $this->connect_errno){
			throw new Exception(
				$this->connect_error,
				$this->connect_errno
			);
		}
	}
	
	public static function getInstance() 
	{
		
		if(!self::$instance)
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
	
	private function __clone()
	{
		
	}	
	
}