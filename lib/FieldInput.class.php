<?php

class FieldInput
{
	// VARS -------------------------------------------------------
	
	private $hasError;
	
	public $name;
	public $label;
	public $obliged;
	public $validationType;
	public $fieldType;
	public $minLength;
	public $errorMsg;
	public $labelClass;
	public $inputClass;
	
	// PROTECTED METHODS ------------------------------------------
	
    public function __construct($name, $label, $errorMsg, $obliged = false, $fieldType = 'text', $validationtype = 'string', $minLength = 0, $labelClass = null, $inputClass = null)
    {
    	$this->hasError 		= false;
    	$this->name 			= $name;
    	$this->label 			= $label;
    	$this->obliged			= $obliged;
    	$this->validationType 	= $validationtype; 	// string, numeric, email
    	$this->fieldType		= $fieldType;
    	$this->minLength 		= $minLength; 		// 0 = no minimum;
    	$this->errorMsg			= $errorMsg;
    	$this->labelClass		= $labelClass;
    	$this->inputClass		= $inputClass;
    }
    
    // PUBLIC METHODS ---------------------------------------------
    
    public function validate($val)
    {
    	if($this->obliged)
    	{
    		if(!isset($val) || $val == '') $this->hasError = true;
    		else
    		{
    			if($this->minLength > 0 && strlen($val) < $this->minLength)
    			{
    				$this->hasError = true;
    			}
    			else
    			{
    				switch($this->validationType)
    				{
    					case 'numeric':
    						if(!is_numeric($val)) $this->hasError = true;
    						break;
    					//
    					case 'email':
    						if(!filter_var($val, FILTER_VALIDATE_EMAIL)) $this->hasError = true;
    						break;
    					//
    					default: /* anything else, eg 'string' */ break;
    				}	
    			}
    		}
    	}
    	
    	return $this->hasError;
    }
    
    public function generateHTML($inlineError = false)
    {
    	$html = 	'<label for="'.$this->name.'Field" ';
		$html .=	isset($this->labelClass) ? 'class="'.$this->labelClass : '';
		
		if($this->hasError)
		{
			if(isset($this->labelClass)) $html .= ' error" ';
			else $html .= 'class="error" ';
		}
		else if(isset($this->labelClass)) $html .= '"';
		
		$html .=	'>'.$this->label;
		
		
		$html .=	$this->obliged ? '<span class="obliged">*</span>' : '';
		
		if($inlineError && $this->hasError) $html .=	'<div class="error-msg">'.$this->errorMsg.'</div>';
		
		$html .=	'<input type="'.$this->fieldType.'" name="'.$this->name.'" id="'.$this->name.'Field" ';
		$html .=	isset($this->inputClass) ? 'class="'.$this->inputClass.'" ' : '';
		$html .=	(isset($_SESSION[$this->name]) && $_SESSION[$this->name] != '') ? 'value="'.$_SESSION[$this->name].'" ' : '';
		$html .=	'/></label>';
		
		return $html;
    }
    
    public function getValue()
    {
    	return $_SESSION[$this->name];
    }
    
    public function hasError()
    {
    	return $this->hasError;
    }
    
    public function reset()
    {
    	$this->hasError = false;
    }
    
    protected function __clone()
    {
        //
    }
}

?>