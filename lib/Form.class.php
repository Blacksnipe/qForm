<?php

require_once 'FieldInput.class.php';
require_once 'ActionInput.class.php';
require_once 'MultiInput.class.php';

class Form
{
	// VARS -------------------------------------------------------
	
	private $hasError;
	private $fields;
	private $actionInputs;
	
	public $action;
	public $method;
	public $id;
	public $class;
	public $inlineErrors;
	public $errorsAbove;
	public $submitBtn;
	
	// CONSTRUCTOR ------------------------------------------------
	
    public function __construct()
    {
    	$this->hasError 		= true;
    	$this->handled			= false;
    	$this->fields 			= array();
    	$this->actionInputs		= array();
    	$this->action 			= 'index.php';
    	$this->method			= 'post';
    	$this->inlineErrors		= false;
    	$this->errorsAbove		= true;
    	
    	if(session_id() == '')
    	{
    		die('Session must be started before the paged form works.');
    	}
    	else
    	{
    		$_SESSION['currentFormPage'] = 1;
    	}
    }
    
    // PUBLIC METHODS ---------------------------------------------
    
    public function addField($field)
    {
    	$this->fields[] = $field;
    }
    
    public function addActionInput($btn)
    {
    	// check if the button is already present
    	foreach($this->actionInputs as $key => $input)
    	{
    		// yes, remove from array and escape function
    		if($input->name == $btn->name)
    		{
    			unset($this->actionInputs[$key]);
    			break;
    		}
    	}
    	
    	$this->actionInputs[] = $btn;
    }
    
    public function addSubmitBtn($btn)
    {
    	$this->submitBtn = $btn;
    	$this->addActionInput($this->submitBtn);
    }
    
    public function validate($data)
    {
    	if(isset($data[$this->submitBtn->name]))
    	{
    		foreach($this->fields as $field)
    		{
    			$val = $_SESSION[$field->name] = isset($data[$field->name]) ? $data[$field->name] : null;
    			$field->validate($val);
    			
    			$errors[] = $field->hasError();
    		}
    		
    		$this->hasError = in_array(true, $errors);
    	}
    	
    	return $this->hasError;
    }
    
    public function getFieldByName($name)
    {
    	foreach($this->fields as $field)
    	{
    		if($field->name == $name) return $field;
    	}
    	
    	return null;
    }
    
    public function generateHTML()
    {
    	$html =		'';
    	
    	if($this->hasError && !$this->inlineErrors && $this->errorsAbove)
    	{
    		$html .=	'<div class="formerrors">
    						<ul>';
    		
    		foreach($this->fields as $field)
    		{
    			if($field->hasError())
    				$html .=	'<li>' . $field->errorMsg . '</li>';
    		}
    		
    		$html .=		'</ul>
    					</div>';
    	}
    	
    	$html .=	'<form action="'.$this->action.'" method="'.$this->method.'" ';
    	$html .=	isset($this->id) ? 'id="'.$this->id.'" ' : '';
    	$html .=	isset($this->class) ? 'class="'.$this->class.'" ' : '';
    	$html .=	'><fieldset>';
    	
    	foreach($this->fields as $field)
    	{
    		$html .= $field->generateHTML($this->inlineErrors);
    	}
    	
    	$html .=	'</fieldset>';
    	
    	foreach($this->actionInputs as $btn)
    	{
    		$html .= $btn->generateHTML();
    	}
    	
    	$html .=	'</form>';
    	
    	if($this->hasError && !$this->inlineErrors && !$this->errorsAbove)
    	{
    		$html .=	'<div class="formerrors">
    						<ul>';
    		
    		foreach($this->fields as $field)
    		{
    			if($field->hasError)
    				$html .=	'<li>' . $field->errorMsg . '</li>';
    		}
    		
    		$html .=		'</ul>
    					</div>';
    	}
    	
    	return $html;
    }
    
    public function getValues()
    {
    	$values = array();
    	
    	foreach($this->fields as $field)
    	{
    		$values[$field->name] = $field->getValue();
    	}
    	
    	return $values;
    }
    
    public function hasError()
    {
    	return $this->hasError;
    }
    
    public function reset()
    {
    	foreach($this->fields as $field)
    	{
    		$field->reset();
    	}
    }
    
    public function __clone()
    {
        //
    }
}

?>