<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', E_ALL);

require_once 'lib/PagedForm.class.php';

$formA 					= new Form();
$formA->id 				= 'regform';
$formA->class 			= 'clearfix';
$formA->inlineErrors		= false;

$formA->addField(new FieldInput('naam', 'Naam', 'Gelieve je achternaam in te vullen', true, 'text', 'string'))
      ->addField(new FieldInput('voornaam', 'Voornaam', 'Gelieve je voornaam in te vullen', true, 'text', 'string'))
      ->addField(new MultiInput('oogballen', 'Aantal oogballen', 'Gelieve je aantal oogballen te selecteren', array('---','Geen','Twee','Drie'), 'select', true, true))
      ->addField(new MultiInput('rugbyballen', 'Aantal rugbyballen', 'Gelieve je aantal rugbyballen te selecteren', array('Geen','Twee','Drie'), 'checkbox', true))
      ->addField(new MultiInput('teelballen', 'Aantal teelballen', 'Gelieve je aantal teelballen te selecteren', array('Geen','Twee','Drie'), 'radio', true))
      ->addSubmitBtn(new ActionInput('submitbtn', 'Verstuur!'))
      ->validate($_POST);

/*$formB 					= new Form();
$formB->id 				= 'regform';
$formB->class 			= 'clearfix';
$formB->inlineErrors		= false;
$formB->addField(new FieldInput('buikomvang', 'Buikomvang', 'Gelieve je buikomvang in te vullen', true, 'text', 'string'));
$formB->addField(new FieldInput('neuslengte', 'Lengte van je neus', 'Gelieve de lengte van je neus in te vullen', true, 'text', 'string'));

$multiform = new PagedForm(new ActionInput('prevbtn', 'Vorige stap'), new ActionInput('nextbtn', 'Volgende stap'), new ActionInput('submitbtn', 'Verstuur!'));
$multiform->addForm($formA);
$multiform->addForm($formB);

$multiform->validate($_POST);*/

?>

<html>
<head></head>
<body>
	<?php
		/*if(!$multiform->isValid())
		{
			echo $multiform->generateHTML();
		}
		else 
		{
			echo '<p>This was handled properly.</p>';
			session_destroy();
		}*/
		
		if($formA->hasError())
		{
			echo $formA->generateHTML();
		}
		else 
		{
			echo '<p>This was handled properly.</p>';
			session_destroy();
		}
	?>
</body>
</html>