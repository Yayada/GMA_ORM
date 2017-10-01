<?php 
	/**
	* 
	*/
	class VueAll
	{
		public $content;
		
		public function __construct()
		{
			$this->content = "<html><head><title>test framework</title> <link rel='stylesheet' type='text/css' href='../../src/Vues/bootstrap/bootstrap.min.css'></head>";
			$this->content .= "<body>";
		}

		public function finich(){
			$this->content .= "</body></html>";
		}

		public function afficher(){
			$this->finich();
			echo $this->content;
		}
	}
?>