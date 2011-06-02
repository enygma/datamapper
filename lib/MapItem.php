<?php

class MapItem
{
	public $_settings 	= null;
	public $_value		= null;
	
	// nothing here yet
	public function __construct($settings)
	{
		$this->_settings = $settings;
	}
    public function __get($name)
    {
        return 'MapItem::get';
    }
    public function getValue()
    {
        return $this->_value;
    }
    public function __toString(){
        return $this->_value;
    }
}

?>