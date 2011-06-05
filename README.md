
Object DataMapper
===============
The Object DataMapper allowed for the simple mapping of properties from one object to another.
Some of the handy features it gives you include the expansion of object property strings and
the aliasing of property values.

```php
<?php
include_once 'lib/DataMapper.php';
include_once 'lib/ExpandObject.php';
include_once 'lib/MapItem.php';

$classOne = new stdClass();
$classOne->foo = 'test';

$dm = new DataMapper();
$dm->configure(array(
	'first_map' => new MapItem(array(
		'map:input' => array($classOne,'foo')
	))
));
$dm->execute();

echo 'Should be "test": '.$dm->first_map;
?>
```
