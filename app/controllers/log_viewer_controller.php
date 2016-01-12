<?php
class LogViewerController extends AppController {

	var $name = 'LogViewer';
	var $uses = array();
	
	function index() {
	}
	
	function list_data() {
		$lines = file(APP . 'tmp' . DS . 'logs' . DS . 'error.log');
		
		$this->set('lines', $lines);
	}
	
}
?>