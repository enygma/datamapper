<?php

class DataMapper 
{
	private $_mappings 	= null;

    /**
     * Set up object with configuration settings
     * @param  $settings
     * @return void
     */
	public function configure($settings)
	{
		$this->_mappings = $settings;
	}

    /**
     * Parse current mappings and run any custom actions
     * @return void
     */
	public function execute()
	{
		// loop through the mappings and execute the actions
		if($this->_mappings != null){
			foreach($this->_mappings as $property => $map){
                if(get_class($map) != 'MapItem'){
                    continue;
                }

				foreach($map->_settings as $name => $value){
					$methodName = '_'.$name;
					if(method_exists($this,$methodName)){
						$this->$methodName($this->_mappings[$property],$value);
					}
				}
			}
		}
	}
    public function __get($name)
    {
        foreach($this->_mappings as $property => $map){
            //look through our object set and see if any of them have an alias to match
            if(isset($map->_settings['alias']) && $map->_settings['alias']==$name){
                return $map;
            }

            // no alias, look for the actual name
            if($property == $name){
                return $map;
            }
        }
    }

    /**
     * Callback: handles custom input path
     * Assigns value by reference
     * 
     * @param object $mapObject Current MapItem object
     * @param string $mapValue Custom mapping path
     * @return void
     */
	public function _inputMap(&$mapObject,$mapValue)
	{
		$expand = new ExpandObject();
		
		$returnValue = (is_array($mapValue)) ? $expand->expand($mapValue[0],$mapValue[1]) : $mapValue;
		if(is_array($mapValue) && isset($mapValue[2])){
			$returnValue = $returnValue->$mapValue[2]();
		}
		
		$mapObject->_value = $returnValue;
	}
    public function _outputMap(&$mapObject,$mapValue)
    {
        $expand = new ExpandObject();
        $expand->apply($mapObject,$mapValue,$mapObject->_value);
    }
    public function _outputDecorator(&$mapObject,$mapValue)
    {
		//var_dump($mapValue);
    }
}

?>