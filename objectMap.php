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
    public $foo     = null;
    public $sample  = null;

    public function __construct()
    {
        $this->foo = new stdClass();
        $this->foo->bar = 'baz';

        $this->foo->myarr = array('blah');

        $this->sample = new Sample();
    }
    
    public function foo()
    {
        echo 'Ticket::foo';
    }
}
$ticket = new Ticket();

class mnDecorate extends MapDecorator
{
    public function decorate($data)
    {
        
    }
}
//--------------

$decoratorOne = new mnDecorate();

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
        'outputMap'         => 'property',
        //'outputDecorator'   => $decoratorOne
    ))
));
$dm->execute();
/*
echo 'fname:'.$dm->firstName."\n";

//echo 'out: '.$dm->lastName->map->to->lname."\n\n";
echo 'lname: '; var_dump($dm->lastName);
echo 'myarr: '; var_dump($dm->testing->getValue());

echo 'fullpath: '; var_dump($dm->last_name->map->to->lname);
*/
echo 'fullpath: '; var_dump($dm->last_name->map->to->lname);

echo "//--------------------------\n";
echo "\n\n";
//var_dump($dm);

?>