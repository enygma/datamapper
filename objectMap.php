<?php

include_once 'lib/DataMapper.php';
include_once 'lib/ExpandObject.php';
include_once 'lib/MapItem.php';


abstract class MapDecorator
{
    public function __construct()
    {
        
    }
    abstract public function decorate($data);
}

//--------------
//$ticket = new stdClass();
//$ticket->foo->bar = 'baz';


class Sample
{
    public function output()
    {
        return 'Sample::output';
    }
}
class Ticket
{
    public $foo     		= null;
    public $sample  		= null;
	private $_objectMap 	= null;

    public function __construct()
    {
		// set up the ticket object's data mapper
		$this->_objectMap = new DataMapper();
		$this->_objectMap->configure(array(
			'title'	=> new MapItem(array(
				'map:input' 	=> 'tickets->name',
				'map:output'	=> 'mytitle',
				'alias'		=> 'mTitle'
			))
		));
				
		//-------------------
        /*$this->foo = new stdClass();
        $this->foo->bar = 'baz';
        $this->foo->myarr = array('blah');

        $this->sample = new Sample();*/
    }

	public function returnMapped($sample)
	{
		// return the data from our $responseObject mapped using our map
		if($this->_objectMap != null){
			
			$this->_objectMap->setSource($sample);
			$this->_objectMap->execute();
			
			//var_dump($this->_objectMap);
			return $this->_objectMap;
			
		}else{
			return $responseObj;
		}
	}
    
    public function foo()
    {
        echo 'Ticket::foo';
    }
}
$sample = new Sample();
$sample->tickets = new stdClass();
$sample->tickets->name = "i'll be mytitle";

// remap tickets->name to $return->mTitle
$ticket = new Ticket();
$return = $ticket->returnMapped($sample);
echo 'end: '.$return->mTitle."\n";

//--------------
$dm = new DataMapper();

$dm->configure(array(
	'first_name' => new MapItem(array(
		'inputMap' 		=> array($ticket,'foo->bar'),
		'alias' 		=> 'firstName'
	)),
    'testing' => new MapItem(array(
        'inputMap'      => array($ticket,'foo->myarr'),
    )),
	'last_name' => new MapItem(array(
		'inputMap'      => array($ticket,'sample','output'), //'testing',
        'outputMap'     => 'map->to->lname',
        'alias'         => 'lastName'
	)),
    'middle_name' => new MapItem(array(
        'inputMap'          => 'testing middle name',
        'outputMap'         => 'property'
    ))
));
//$dm->execute();
/*
echo 'fname:'.$dm->firstName."\n";

//echo 'out: '.$dm->lastName->map->to->lname."\n\n";
echo 'lname: '; var_dump($dm->lastName);
echo 'myarr: '; var_dump($dm->testing->getValue());

echo 'fullpath: '; var_dump($dm->last_name->map->to->lname);
*/
//echo 'fullpath: '; var_dump($dm->last_name->map->to->lname);

echo "//--------------------------\n";
echo "\n\n";
//var_dump($dm);

?>