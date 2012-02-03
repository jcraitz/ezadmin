<script type="text/javascript">
function updateFormState()
{
<?php if(isset($resourceInfo) && ($resourceInfo['use_custom'] == "T")){?>
    $("#toggleConfig").removeClass('urlOption');
    
<?php } ?>
    updateConfigDisplay();
}

<?php include('scripts/editResource.js');?>
</script>
<?php 

$journal = (isset($resourceInfo) && $resourceInfo['resource_type'] == 'Journal') ? "selected = \"yes\"" : "";
$database = (isset($resourceInfo) && $resourceInfo['resource_type'] == 'Database') ? "selected = \"yes\"" : "";
$platform = (isset($resourceInfo) && $resourceInfo['resource_type'] == 'Platform') ? "selected = \"yes\"" : "";
$aggregator = (isset($resourceInfo) && $resourceInfo['resource_type'] == 'Aggregator') ? "selected = \"yes\"" : "";
$ebook = (isset($resourceInfo) && $resourceInfo['resource_type'] == 'Ebook') ? "selected = \"yes\"" : "";
$resourceid = isset($resourceInfo) && array_key_exists('resource_id',$resourceInfo) ? $resourceInfo['resource_id'] : "";
$resourceTitle = isset($resourceInfo['title']) ? $resourceInfo['title'] : "";
$default = (isset($resourceInfo) && $resourceInfo['custom_config'] == "") ? "checked = \"checked\"": "";
$custom = (isset($resourceInfo) && $resourceInfo['custom_config'] != "") ? "checked = \"checked\"": "";
$toggleText = (isset($resourceInfo) && $resourceInfo['custom_config'] != "" ? 'use standard URLs' : 'use custom configuration');
$customConfig = (isset($resourceInfo) && $resourceInfo['custom_config'] != "") ? $resourceInfo['custom_config']: "";
$restricted = (isset($resourceInfo) && $resourceInfo['restricted'] == "T") ? "checked = \"checked\"": "";

//New problem.  How am I going to dynamically genereate the page to account for the multiple URLs?
?>
<div class="resourceForm">

<form id="resource_form" name="resource_form" method="post" action="index.php?rt=index/save" onsubmit="return testing();" >
<input type="hidden" name="resource_id" value="<?php print($resourceid); ?>" />

<label for="resource_name">Resource Name:</label><input type="text" value="<?php print(htmlentities($resourceTitle)); ?>" size="50" name="resource_name" id="resource_name" />
<label for="resource_type">Resource Type:</label><select name="resource_type" id="resource_type">
  <option <?php print($journal); ?>>Journal</option>
  <option <?php print($database); ?>>Database</option>
  <option <?php print($platform); ?>>Platform</option>
  <option <?php print($aggregator); ?>>Aggregator</option>
  <option <?php print($ebook); ?>>Ebook</option>
</select>

<label for="is_restricted">Restricted: </label><input type="checkbox" name="is_restricted" value="true" id="is_restricted" <?php print($restricted); ?>/>
<div id="configSection">
    <span class="formSectionHeader">Configuration:</span>
    <a href="#" id="addUrl" onClick="return false;" class="addUrlButton">add URL</a>
    <a href="#" id="toggleConfig" onClick="return false;" class="configTypeToggle urlOption"><?php print($toggleText); ?></a>
    <div id="configBody">
      <div id="configUrls">
<?php //I'm going to need a foreach loop that is going to loop through the entire array.
    $x = 0;
    if(isset($configInfo)){
        foreach($configInfo as $config){
            $type = $config['config_type'];
            $value = $config['config_value'];
            $configId = $config['id'];
            $host = ($config['config_type'] == 'H') ? "selected = \"yes\"" : "";
            $domain = ($config['config_type'] == 'D') ? "selected = \"yes\"" : "";
            $domainjs = ($config['config_type'] == 'DJ') ? "selected = \"yes\"" : "";
            $hostjs = ($config['config_type'] == 'HJ') ? "selected = \"yes\"" : "";
?> 
<div class="urlConfigBlock">
<input type="hidden" name="url_id_<?php print($x); ?>" value="<?php print($configId); ?>" />
<a class="deleteUrlButton" href="#" onClick="return false;" id="delete_<?php print($value); ?>" class="del">delete URL</a>
<label for="url_name_<?php print($x); ?>">URL:</label><input class="urlInput" type="text" value="<?php print(htmlentities($value)); ?>" size="50" name="url_name_<?php print($x); ?>" id="url_name_<?php print($x); ?>" />
<label for="url_select_<?php print($x); ?>">URL Type:</label><select name="url_select_<?php print($x); ?>">
<option <?php print($host); ?>>H</option>
<option <?php print($domain); ?>>D</option>
<option <?php print($domainjs); ?>>DJ</option>
<option <?php print($hostjs); ?>>HJ</option>
</select>
</div>

<?php
            $x++;
        }
    } else {
        $x = 1;
?>
<div class="urlConfigBlock">
<a class="deleteUrlButton" href="#" id="delete_0" class="del">delete URL</a>
<label for="url_name_0">URL:</label><input type="text" value="" size="50" name="url_name_0" id="url_name_0" />
<label for="url_select_0">URL Type:</label><select name="url_select_0" id="url_select_0">
<option>H</option>
<option>D</option>
<option>DJ</option>
<option>HJ</option>
</select>

</div>
<?php 
    }
?>         
      <span class="floatClear">&nbsp;</span></div>
      <div id="configCustom">
          <label for="custom_config">Custom Configuration:</label>
          <textarea name="custom_config" rows="8" cols="36" id="custom_config"><?php print(htmlentities($customConfig)); ?></textarea>
      <span class="floatClear">&nbsp;</span></div>
    </div>
</div>
<span class="floatClear">&nbsp;</span>
<input class="rightMargin" type="submit" value="Save Resource" name="save_resource"/>
<?php 
    if($resourceid != ""){
?> 
<input type="button" class="rightMargin" value="Delete Resource" name="delete_resource" id="delete_resource"/>
<?php
    }
?>
<input type="button" value="Cancel" name="cancel" id="cancel"/>
<input type="hidden" name="created_urls" value="<?php print($x); ?>" id="created_urls" />
<input type="hidden" name="use_custom" id="use_custom" value="false" />
<span class="floatClear">&nbsp;</span>
</form>
</div>