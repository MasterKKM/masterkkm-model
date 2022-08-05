<?php

/* @var $generator masterkkm\generator\Generator */
/* @var $className string class name */

echo "<?php\n";
?>

namespace <?= $generator->businessNs ?>;

/**
* This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
*
*/
class <?= $className ?> extends <?= '\\' . ltrim($generator->ns, '\\') . "\\$className\n" ?>
{

}
