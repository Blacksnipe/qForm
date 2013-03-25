<?php

require_once 'Form.class.php';

class PagedForm
{
	// VARS -------------------------------------------------------
	
	private $hasError;
	private $forms;
	private $formIndex;
	private $actionInputs;
	
	public $prevBtn;
	public $nextBtn;
	public $submitBtn;
	
	// CONSTRUCTOR ------------------------------------------------
	
    public function __construct($prevBtn, $nextBtn, $submitBtn)
    {
    	$this->hasError 		= false;
    	$this->forms 			= array();
    	$this->actionInputs		= array();
    	
    	if(isset($prevBtn) && $prevBtn->type == 'submit' && !isset($this->prevBtn))
    	{
    		$this->prevBtn = $prevBtn;
    		$this->actionInputs[] = $this->prevBtn;
    	}
    	else die('A submit button to go to a previous step is obligatory.');
    	
    	if(isset($nextBtn) && $nextBtn->type == 'submit' && !isset($this->nextBtn))
    	{
    		$this->nextBtn = $nextBtn;
    		$this->actionInputs[] = $this->nextBtn;
    	}
    	else die('A submit button to go to a next step is obligatory.');
    	
    	if(isset($submitBtn) && $submitBtn->type == 'submit' && !isset($this->submitBtn))
    	{
    		$this->submitBtn = $submitBtn;
    		$this->actionInputs[] = $this->submitBtn;
    	}
    	else die('A submit button to go to submit the final form is obligatory.');
    	
    	if(session_id() == '')
    	{
    		die('Session must be started before the paged form works');
    	}
    	elseif(isset($_SESSION['formIndex']))
    	{
    		$this->formIndex = $_SESSION['formIndex'];
    	}
    	else
    	{
    		$this->formIndex = $_SESSION['formIndex'] = 0;
    	}
    }
    
    // PUBLIC METHODS ---------------------------------------------
    
    public function addForm($form)
    {
    	$this->forms[] = $form;
    }
    
    public function validate($data)
    {
    	$this->buildForms();
    	
    	if(isset($data[$this->nextBtn->name]) || isset($data[$this->submitBtn->name]))
    	{
    		$this->hasError = $this->forms[$this->formIndex]->validate($data);
    		
    		if(!$this->hasError && $this->formIndex < count($this->forms) - 1)
    		{
    			$this->formIndex++;
    			$_SESSION['formIndex'] = $this->formIndex;
    		}
    	}
    	elseif(isset($data[$this->prevBtn->name]) && $this->formIndex > 0)
    	{
    		$this->formIndex--;
    		$_SESSION['formIndex'] = $this->formIndex;
    	}
    	
    	$this->buildForms();
    	
    	return $this->hasError;
    }
    
    public function getFormByPageNr($page)
    {
    	if(isset($this->forms[$page])) return $this->forms[$page];
    	
    	return null;
    }
    
    public function buildForms()
    {
    	if($this->formIndex == 0) $this->forms[$this->formIndex]->addSubmitBtn($this->nextBtn);
    	else
    	{
    		$this->forms[$this->formIndex]->addActionInput($this->prevBtn);
    		if($this->formIndex < count($this->forms) - 1) $this->forms[$this->formIndex]->addSubmitBtn($this->nextBtn);
    		else $this->forms[$this->formIndex]->addSubmitBtn($this->submitBtn);
    	}
    }
    
    public function generateHTML()
    {
    	return $this->forms[$this->formIndex]->generateHTML();
    }
    
    public function hasError()
    {
    	return $this->hasError;
    }
    
    public function isValid()
    {
    	return !$this->forms[count($this->forms)-1]->hasError();
    }
    
    public function reset()
    {
    	foreach($this->forms as $form)
    	{
    		$form->reset();
    	}
    }
    
    public function __clone()
    {
        //
    }
}

?>