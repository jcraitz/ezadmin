<?php
/*******************************************************************************
editresourceController.php

Created by Karl Doerr, 
Modified by Troy Hurteau, Eric McEachern,
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
class editresourceController extends baseController {
		
	public $resourceInfo = array();
	public $configInfo = array();
		
	public function index()
	{
		if (
		    array_key_exists('resource_id', $_REQUEST) 
		    && $_REQUEST['resource_id'] != ""
		){
			$this->populateResourceInfo($_REQUEST['resource_id']);
			$this->populateConfigInfo($_REQUEST['resource_id']);
            $this->registry->template->title = 'EZ Admin - Edit Resource';
		} else {
			$this->registry->template->title = 'EZ Admin - Add Resource';
			$this->registry->template->status = 'Please enter information for the new resource below.';
		}
		$this->registry->template->show('editresource');
	}
	
	public function populateResourceInfo($resourceid)
	{
		$db = $this->registry->db;
		$title = "";
		$custom_config = "";
		$resource_type = "";
		$use_custom = "";
		$restricted = "";
		$selectResourceQuery = "SELECT id, title, custom_config, resource_type, use_custom, restricted FROM resource WHERE id = ?";
		$stmt = $db->prepare($selectResourceQuery);
		$stmt->bind_param('i', $resourceid);
		$stmt->execute();
		$stmt->bind_result($id, $title, $custom_config, $resource_type, $use_custom, $restricted);
		while($stmt->fetch()){
			$this->resourceInfo['resource_id'] = $id;
			$this->resourceInfo['title'] = $title;
			$this->resourceInfo['custom_config'] = $custom_config;
			$this->resourceInfo['resource_type'] = $resource_type;
			$this->resourceInfo['use_custom'] = $use_custom;
			$this->resourceInfo['restricted'] = $restricted;
		}
		$stmt->close();
		$this->registry->template->resourceInfo = $this->resourceInfo;
	}
		
	public function populateConfigInfo($resourceid)
	{
		$db = $this->registry->db;
		$type = "";
		$value = "";
		$id = "";
		$selectConfigQuery = "SELECT id, config_type, config_value FROM config WHERE resource = ?";
		$stmt = $db->prepare($selectConfigQuery);
		$stmt->bind_param('i', $resourceid);
		$stmt->execute();
		$stmt->bind_result($id, $type, $value);
		$x = 0;
		while($stmt->fetch()){
			$this->configInfo[$x]['config_type'] = $type;
			$this->configInfo[$x]['config_value'] = $value;
			$this->configInfo[$x]['id'] = $id;
			$x++;
		}
		$stmt->close();
		$this->registry->template->configInfo = $this->configInfo;
	}
}