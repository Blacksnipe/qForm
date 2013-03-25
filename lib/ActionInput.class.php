<?php

class ActionInput
{
	// VARS -------------------------------------------------------
	
	public $name;
	public $value;
	public $type;
	public $customHTML;
	
	// PROTECTED METHODS ------------------------------------------
	
	/*
	
	@name 		= name of the input, will be visible in $_POST
	@value 		= text displayed on the input
	@type 		= defaults to 'submit', but can be reset also
	@customHTML = use custom HTML with %submit% as placeholder form the input element
	
	*/
	
    public function __construct($name, $value, $type = 'submit', $customHTML = '')
    {
    	$this->name 			= $name;
    	$this->value 			= $value;
    	$this->type 			= isset($type) ? ($type != '' ? $type : 'submit') : 'submit';
    	$this->customHTML		= $customHTML;
    }
    
    // PUBLIC METHODS ---------------------------------------------
    
    public function generateHTML()
    {
    	$input = '<input type="'.$this->type.'" name="'.$this->name.'" value="'.$this->value.'" />';
    	$html  = '';
    	
    	if(isset($this->customHTML) && $this->customHTML != '')
    	{
    		$html 	.= str_replace('%submit%', $input, $this->customHTML);
    	}
    	else $html .= $input;
		
		return $html;
    }
    
    protected function __clone()
    {
        //
    }
}

?>