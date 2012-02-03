<?php
/*******************************************************************************
indexController.php

Description: Primary controller for main interface page.

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


class indexController extends baseController
{

	public $resourceArray = array();
	private $configArray = array();
	
	private function populateResourceArray()
	{
		$resourceQuery = "SELECT id, title, custom_config, resource_type, use_custom, restricted FROM resource ORDER BY title";
		$db = $this->registry->db;
		$resourceQuery = $db->real_escape_string($resourceQuery);
		$resourceResult = $db->query($resourceQuery);
		$x = 0;
		while($row = $resourceResult->fetch_array()){
			$this->resourceArray[$x] = array();
			$this->resourceArray[$x]['id'] = $row[0];
			$this->resourceArray[$x]['title'] = $row[1];
			$this->resourceArray[$x]['type'] = $row[3];
			$this->resourceArray[$x]['custom_config'] = $row[2];
			$this->resourceArray[$x]['use_custom'] = $row[4];
			$this->resourceArray[$x]['restricted'] = $row[5];
			if(!empty($this->configArray[$row[0]])){
				$this->resourceArray[$x]['config'] = $this->configArray[$row[0]];
			}
			else{
				$this->resourceArray[$x]['config'] = "";
			}
			$x++;
		}
	}
	
	private function populateConfigList()
	{
		$db = $this->registry->db;
		$configQuery = "SELECT id, resource, config_type, config_value FROM config ORDER BY resource, config_type";
		$configQuery = $db->real_escape_string($configQuery);
		$configResult = $db->query($configQuery);
		$resource = 0;
		while($row = $configResult->fetch_array()){
			if($resource != $row[1]){
				$resource = $row[1];
				$this->configArray[$resource] = array();
			}
			$id = $row[0];
			$this->configArray[$resource][$id] = array();
			$this->configArray[$resource][$id]['config_type'] = $row[2];
			$this->configArray[$resource][$id]['config_value'] = $row[3];
		}
		
	}
	
	private function getConfigListFor($id)
	{
		$db = $this->registry->db;
		$configQuery = "SELECT id, resource, config_type, config_value FROM config WHERE resource = {$id} ORDER BY resource";
		$configQuery = $db->real_escape_string($configQuery);
		$configResult = $db->query($configQuery);
		$resource = 0;
		$returnArray = array();
		while($row = $configResult->fetch_array()){
			$returnArray[] = array(
				'config_type' => $row[2],
				'config_value' => $row[3]
			);
		}
	}
	
	private function getResource($id)
	{
		$resourceQuery = "SELECT id, title, custom_config, resource_type, use_custom, restricted FROM resource WHERE id = {$id}";
		$db = $this->registry->db;
		$resourceQuery = $db->real_escape_string($resourceQuery);
		$resourceResult = $db->query($resourceQuery);
		$row = $resourceResult->fetch_array();
		return array(
			'title' => $row[1],
			'custom' => $row[2],
			'type' => $row[3],
			'use_custom' => $row[4]
		);
	}
	
	public function index()
	{
		$this->populateConfigList();
		$this->populateResourceArray();
		$this->registry->template->resourceList = $this->resourceArray;
		$this->registry->template->title = 'EZ Admin - Resource Controller';
		$this->registry->template->show('index');
	}

	public function deleteConfirm()
	{
		$resourceid = $_REQUEST['resource_id'];
		$config = $this->getConfigListFor($resourceid);
		$resource = $this->getResource($resourceid);
		$this->registry->template->resource = '';
		$this->registry->template->title = 'EZ Admin - Confirm Delete';
		$this->registry->template->status = "<p>Are you sure you want to delete {$resource['title']} ?</p>"
		  . "<p>
		  <a class='noButton' href='index.php?rt=index'>Cancel</a>
		  <a class='yesButton' href='index.php?rt=index/save&resource_id={$resourceid}&delete_resource=true'>Yes</a>
		  </p>";
		$this->registry->template->show('message');
		
	}
	
	public function save()
	{
		    $this->correctMagicQuotes();
		    $db = $this->registry->db;
			$title = array_key_exists('resource_name', $_REQUEST) ? $_REQUEST['resource_name'] : '';
			$custom_config = array_key_exists('custom_config', $_REQUEST) ? $_REQUEST['custom_config'] : '';
			$resource_type = array_key_exists('resource_type', $_REQUEST) ? $_REQUEST['resource_type'] : '';
			$use_custom = isset($_REQUEST['use_custom']) && 'true' == $_REQUEST['use_custom'] ? "T" : "F";
			$restricted = isset($_REQUEST['is_restricted']) ? "T" : "F";
			$resourceid = "";
			$updateStatement = "";
			if(isset($_REQUEST['delete_resource'])){
				$this->registry->template->title = 'EZ Admin - Deleted Resource';
				//echo "Deleteing!";
				//print_r($_REQUEST);
				//die;
				$oldData = $this->getResource($_REQUEST['resource_id']);
				$old_title = $oldData['title'];
				
				$resourceid = $_REQUEST['resource_id'];
				$deleteConfigQuery = "DELETE FROM config WHERE resource = ?";
				$deleteResourceQuery = "DELETE FROM resource WHERE id = ?";
				$deleteConfigStmt = $db->prepare($deleteConfigQuery);
				$deleteConfigStmt->bind_param('i', $resourceid);
				$deleteConfigStmt->execute();
				$deleteConfigStmt->close();
				
				$deleteResourceStmt = $db->prepare($deleteResourceQuery);
				$deleteResourceStmt->bind_param('i', $resourceid);
				$deleteResourceStmt->execute();
				if($deleteResourceStmt->affected_rows > 0){
					$updateStatement = "Successfully deleted the {$old_title} resource.<br />";
				}
				$deleteResourceStmt->close();
				
				
				
				
			}
			
			elseif(isset($_REQUEST['save_resource']) && $_REQUEST['resource_id'] != ""){
				$this->registry->template->title = 'EZ Admin - Edited Resource';
				//echo "Updating!";
				//print_r($_REQUEST);
				//die;
				$resourceid = $_REQUEST['resource_id'];
				$current_title = "";
				$current_custom_config = "";
				$current_resource_type = "";
				$selectResource = "SELECT title, custom_config, resource_type FROM resource WHERE id = ?";
				$selectStmt = $db->prepare($selectResource);
				$selectStmt->bind_param('i', $resourceid);
				$selectStmt->execute();
				$selectStmt->bind_result($current_title, $current_custom_config, $current_resource_type);
				$selectStmt->fetch();
				$selectStmt->close();
				//if($current_title != "" && $current_resource_type != ""){
				if($current_title != ""){	
					$resourceUpdateQuery = "UPDATE resource SET title=?, custom_config=?, resource_type=?, use_custom=?, restricted=? WHERE id = ?";
					$updateStmt = $db->prepare($resourceUpdateQuery);
					$updateStmt->bind_param('sssssi', $title, $custom_config, $resource_type, $use_custom, $restricted, $resourceid);
					$updateStmt->execute();
					if($updateStmt->affected_rows > 0){
						$updateStatement = "Successfully updated the <a href='#rid_" . $resourceid . "'>" . $title . "</a> resource.<br />";
					}
					$updateStmt->close();
					$configs = array();
					foreach ($_REQUEST as $key=>$value){
						$temp = explode("_", $key);
						if($temp[0] == "url"){
							$configs[$temp[2]][$temp[1]] = $value;
						}
					}
					$urlArray = array();
					foreach($configs as $config){
						$urlname = $config['name'];
						$urltype = $config['select'];
						$urlid = $config['id'];
						array_push($urlArray, $urlid);
						if($urlid != "" && ($urlname != "" && $urlname != null)){
							$configUpdate= "UPDATE config SET resource=?, config_type=?, config_value=? WHERE id=?";
							$configUpdateStmt = $db->prepare($configUpdate);
							$configUpdateStmt->bind_param('issi', $resourceid, $urltype, $urlname, $urlid);
							$configUpdateStmt->execute();
							if($configUpdateStmt->affected_rows > 0){
								$updateStatement .= "Successfully updated the $urlname configuration value for <a href='#rid_" . $resourceid . "'>" . $title . "</a>.<br />";
							}
							$configUpdateStmt->close();
						}
						else{
							$configInsert = "INSERT INTO config (resource, config_type, config_value) VALUES (?, ?, ?)";
							$configInsertStmt = $db->prepare($configInsert);
							$configInsertStmt->bind_param('iss', $resourceid, $urltype, $urlname);
							$configInsertStmt->execute();
							array_push($urlArray, $db->insert_id);
							if($configInsertStmt->affected_rows > 0){
								$updateStatement .= "Successfully added the $urlname configuration value for <a href='#rid_" . $resourceid . "'>" . $title . "</a>.<br /> ";								
							}
							$configInsertStmt->close();
						}
					}
					$current_configId = "";
					$current_configType = "";
					$current_configValue = "";
					$currentIdArray = array();
					$existingTitlesArray = array();
					$configSelectQuery = "SELECT id, config_type, config_value FROM config WHERE resource = ?";
					$configStmt = $db->prepare($configSelectQuery);
					$configStmt->bind_param('i', $resourceid);
					$configStmt->execute();
					$configStmt->bind_result($current_configId, $current_configType, $current_configValue);
					while($configStmt->fetch()){
						array_push($currentIdArray, $current_configId);
						$existingTitlesArray[$current_configId] = $current_configValue;
					}
					$configStmt->close();
					
					$idDiff = array_diff($currentIdArray, $urlArray);
					foreach ($idDiff as $id){
						$deleteUrl = "DELETE FROM config WHERE id = ?";
						$deleteUrlStmt = $db->prepare($deleteUrl);
						$deleteUrlStmt->bind_param('i', $id);
						$deleteUrlStmt->execute();
						if($deleteUrlStmt->affected_rows > 0){
							$updateStatement .= "Successfully deleted the " .  $existingTitlesArray[$id] . " configuration value for <a href='#rid_" . $resourceid . "'>" . $title . "</a>. <br />";
						}
						$deleteUrlStmt->close();
					}
				}
				else{
					print_r("404ed!!one!!!111");
					die;
				}
			}
			else{
				//echo "Adding!";
				//print_r($_REQUEST);
				//die;
				$insert = "INSERT INTO resource (title, custom_config, resource_type, use_custom, restricted) VALUES (?, ?, ?, ?, ?)";
				$stmt = $db->prepare($insert);
				$stmt->bind_param('sssss', $title, $custom_config, $resource_type, $use_custom, $restricted);
				$stmt->execute();
				$resourceid = $db->insert_id;
				if($stmt->affected_rows > 0){
					$updateStatement ="Successfully added <a href='#rid_" . $resourceid . "'>" . $title . "</a> resource.<br />";
				}
				$stmt->close();
				
				$configs = array();
				foreach ($_REQUEST as $key=>$value){
					$temp = explode("_", $key);
					if($temp[0] == "url"){
						$configs[$temp[2]][$temp[1]] = $value;
					}
				}
				foreach($configs as $config){
					$urlname = $config['name'];
					$urltype = $config['select'];
					if($urlname != "" || $urlname != null){
						$configInsert = "INSERT INTO config (resource, config_type, config_value) VALUES (?, ?, ?)";
						$configStmt = $db->prepare($configInsert);
						$configStmt->bind_param('iss', $resourceid, $urltype, $urlname);
						$configStmt->execute();
						if($configStmt->affected_rows > 0){
							$updateStatement .="<br /> Successfully added the $urlname configuration value.";
						}
						$configStmt->close();
					}
				
				}
			}
			//When deleteing resources, we need a confirmation box.
			
			if ('' != trim($updateStatement)) {
				$this->registry->template->status = $updateStatement;
			}
			$this->index();
			
		}
		
	public function export()
	{
		if(!array_key_exists('export_confirm',$_REQUEST) || 'Export' != $_REQUEST['export_confirm']){
			$this->registry->template->title = 'Please confirm export';
			$this->registry->template->show('exportConfirm');
		} else {
				//3) Does someone have to sign in?  Will only certain people see it
			
			$this->populateConfigList();
			$this->populateResourceArray();
	        $path = $this->registry->outputPath;
			$error = '';
			$label = "########";
				
			$restrictedTypes = array('T', 'F');
			$resourceTypes = array('Journal', 'Database','Platform','Aggregator','Ebook');
			
			$restrictedStart = "####### This is the list of restricted resrources  #########\nGroup restricted\n\n";
			$unrestrictedStart = "####### This is the list of unrestricted resrources  #########\nGroup unrestricted\n\nIncludeFile sage.txt\nIncludeFile oxford.txt\n\n";
			
			$restrictedUploadConf = array();
			$unrestrictedUploadConf = array();
			
			$writeSuccess = 1;
			
			foreach ($restrictedTypes as $restricted){
				$file = ('T' == $restricted ? $path .'restrictedoutput.txt' : $path. 'unrestrictedoutput.txt');
				$writeStart = 'T' == $restricted ? $restrictedStart : $unrestrictedStart;
				$fh = fopen($file, 'w');
				if($fh){						
					$writeSuccess = $writeSuccess && fwrite($fh, $writeStart);
					foreach ($resourceTypes as $resourceType){
						$writeLabel = $label . " " . $resourceType . " " . $label . "\n\n";
						$writeSuccess = $writeSuccess && fwrite($fh, $writeLabel);
						foreach($this->resourceArray as $resource){
							if($resource['type'] == $resourceType){
								if($resource['restricted'] == $restricted){
									$resourceString = "T " . $resource['title'] . "\n";
									$writeSuccess = $writeSuccess && fwrite($fh, $resourceString);
									if (is_array($resource['config']) && $resource['use_custom'] == 'F'){
										foreach ($resource['config'] as $config){
											$configString = $config['config_type'] . " " . $config['config_value'] . "\n";
											$writeSuccess = $writeSuccess && fwrite($fh, $configString);
										}
									}
									else{
										$configString = $resource['custom_config'] . "\n";
										$writeSuccess = $writeSuccess && fwrite($fh, $configString);
									}
									$writeSuccess = $writeSuccess && fwrite($fh, "\n");
								}
							}
						}
					}
					fclose($fh);
				} else {
					$error .= "<p>An error occured while writing to the " . ($restricted == 'T' ? '' : 'un') . "restricted configuration file.<p>";
				}
				if(!$writeSuccess) {
					$error .= "<p>An error occured while writing to the " . ($restricted == 'T' ? '' : 'un') . "restricted configuration file.<p>";
				}
			}
			
			if('' == trim($error)){
				if (!$this->upload($path .'restrictedoutput.txt',$restrictedUploadConf)){
					$error .= "<p>An error occured while uploading the unrestricted configuration file.<p>";
				}
				if (!$this->upload($path. 'unrestrictedoutput.txt',$unrestrictedUploadConf)){
					$error .= "<p>An error occured while uploading the restricted configuration file.<p>";
				}
			}
			
			$this->registry->template->status = (
				'' == trim($error) 
				? 'Configuration Files Written.' 
				: $error
			);
			$this->index();
		}
	}
	
	// TODO: verify success of upload
	private function upload($localFilename, $uploadConfiguaration)
	{
		return true;		
	}
	
    private function correctMagicQuotes()
    {
        if(ini_get('magic_quotes_gpc')){
            foreach($_REQUEST as $requestIndex=>$requestValue){
                $_REQUEST[$requestIndex] = $this->stripSlashesHandleArrays($requestValue);
            }
            foreach($_GET as $getIndex=>$getValue){
                $_GETT[$getIndex] = $this->stripSlashesHandleArrays($getValue);
            }
            foreach($_POST as $postIndex=>$postValue){
                $_POST[$postIndex] = $this->stripSlashesHandleArrays($postValue);
            }
            foreach($_COOKIE as $cookieIndex=>$cookieValue){
                $_COOKIE[$cookieIndex] = $this->stripSlashesHandleArrays($cookieValue);
            }
        }
    }

    private function stripSlashesHandleArrays($value)
    {
        if (is_array($value)){
            $newValue = array();
            foreach($value as $valueIndex=>$valueValue){
                $newValue[$valueIndex] = $this->stripSlashesHandleArrays($valueValue);
            }
            return $newValue;
        } else {
            return stripslashes($value);
        }
    }   	
	
}