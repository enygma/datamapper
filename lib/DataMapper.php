<?php

class DataMapper 
{
	private $_mappings 	= null;
	private $_sourceObj	= null;

    /**
     * Set up object with configuration settings
     * @param array $settings Settings array
	 * @param object $sourceObj[optional] If object is defined, used as source for mapping
     * @return void
     */
	public function configure($settings,$sourceObj=null)
	{
		$this->_mappings = $settings;
		if($sourceObj != null){
			$this->_sourceObj = $sourceObj;
		}
	}
	
	/**
	 * Sets the source object to pull from
	 *
	 * @param object $object Source object (any type)
	 */
	public function setSource($object)
	{
		$this->_sourceObj = $object;
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
					$nameParts 	= explode(':',$name);
					$isInput 	= false;
					$methodName = '';
					
					if(count($nameParts)>1){
						// append them
						if($nameParts[1]=='input'){ $isInput = true; }
						$methodName = '_'.array_pop($nameParts);
						foreach($nameParts as $part){
							$methodName .= ucwords(strtolower($part));
						}
					}else{
						$methodName = '_'.$name;
					}

					if(method_exists($this,$methodName)){
						if(count($value)==1 && $this->_sourceObj!=null && $isInput){
							$value = array($this->_sourceObj,$value);
						}
						
						$this->$methodName($this->_mappings[$property],$value);
					}
				}
			}
		}
	}
	
	/**
	 * Given a name, tries to match against actual properties and aliases in the map
	 *
	 * @param string $name Value to search for
	 */
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
	
	/**
	 * Callback: handle the output mapping given the current value
	 *
	 * @param object $mapObject Current map's object
	 * @param mixed $mapValue Mapping to apply value to
	 */
    public function _outputMap(&$mapObject,$mapValue)
    {
        $expand = new ExpandObject();
        $expand->apply($mapObject,$mapValue,$mapObject->_value);
    }

	/**
	 * Callback: handle the output and "decorate" it
	 *
	 * @param object $mapObject Current map's object
	 * @param mixed $mapValue Value for the current mapping
	 */
    public function _outputDecorator(&$mapObject,$mapValue)
    {
		//TODO: figure out decorators
    }
}

?>