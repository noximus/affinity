<?php
class smartyConf extends Smarty {
	function smartyConf() {
		$this->template_dir = 'template';
		$this->compile_dir = 'compile';
	}
}
?>