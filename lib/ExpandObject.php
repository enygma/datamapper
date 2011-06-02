<?php

class ExpandObject
{
	public function expand($object,$path)
	{
		$pathParts = explode('->',$path);
		foreach($pathParts as $path){
			if(isset($object->$path)){
				$object = $object->$path;
			}
		}
		return $object;
	}
    public function apply($object,$path,$value)
    {
        $pathParts  = explode('->',$path);
        $ref        = $object;
        $pathCount  = count($pathParts);
        $ct         = 1;
        foreach($pathParts as $path){
            if($pathCount==$ct){
                $ref->{$path} = $value;
            }else{
                $ref->{$path} = new stdClass();
                $ref = $ref->{$path};
            }
            $ct++;
        }
    }
}

?>