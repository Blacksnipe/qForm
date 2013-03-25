<?php

class MultiInput
{
	// VARS -------------------------------------------------------
	
	private $values;
	private $hasError;
	private $firstValError;
	
	public $name;
	public $label;
	public $errorMsg;
	public $obliged;
	public $type;
	public $labelClass;
	public $inputClass;
	
	// PROTECTED METHODS ------------------------------------------
	
    public function __construct($name, $label, $errorMsg, $values, $type, $obliged = false, $firstValError = false, $labelClass = null, $inputClass = null)
    {
    	$this->hasError 		= false;
    	$this->firstValError	= $firstValError;
    	$this->name 			= $name;
    	$this->label 			= $label;
    	$this->obliged			= $obliged;
    	$this->values			= $values;
    	$this->errorMsg			= $errorMsg;
    	$this->type				= $type;
    	$this->labelClass		= $labelClass;
    	$this->inputClass		= $inputClass;
    }
    
    // PUBLIC METHODS ---------------------------------------------
    
    public function validate($val)
    {
    	if($this->obliged)
    	{
    		if(!isset($val)) $this->hasError = true;
    		if($this->firstValError && $val == $this->values[0]) $this->hasError = true;
    	}
    	
    	return $this->hasError;
    }
    
    public function generateHTML($inlineError = false)
    {
    	$html = 	'<label for="'.$this->name.'" ';
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
    	
    	$tpl = '';
    	switch($this->type)
    	{
    		case 'select':
    			$html .= '<select name="'.$this->name.'">';
    			$tpl = '<option value="%val%"%selected%>%val%</option>';
	    		break;
	    	//
    		case 'radio':
    			$tpl = '<input type="radio" name="'.$this->name.'" value="%val%"%selected% />%val%';
	    		break;
	    	//
    		case 'checkbox':
    			$tpl = '<input type="checkbox" name="'.$this->name.'[]" value="%val%"%selected% />%val%';
    			break;
    	}
    	
    	foreach($this->values as $key => $value)
    	{
    		$item = $tpl;
    		$item = str_replace('%val%', $value, $item);
    		if(isset($_SESSION[$this->name]))
    		{
    			$inArr = false;
    			if(is_array($_SESSION[$this->name]))
    			{
    				$inArr = in_array($value, $_SESSION[$this->name]);
    			}
    			
    			if($_SESSION[$this->name] == $value || $inArr)
    			{
	    			$checked_str = $this->type == 'select' ? ' selected' : ' checked="checked"';
	    			$item = str_replace('%selected%', $checked_str, $item);
	    		}
    		}
    		$item = str_replace('%selected%', '', $item);
    		
    		$html .= $item;
    	}
    	
    	if($this->type == 'select') $html .= '</select>';
		
		$html .=	'</label>';
		
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