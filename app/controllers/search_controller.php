<?php
class SearchController extends AppController {

	var $name = 'Search';
	var $uses = array();
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('*');
	}
	
	function index() {
		$this->layout = 'search_layout';
		$this->loadModel('Bulletin');
		$result = array();
		
		$search_string = (isset($_REQUEST['q'])) ? $_REQUEST['q'] : '';
		$from_bulletins = $this->Bulletin->find('all', array(
			'conditions' => array('Bulletin.content LIKE' => '%'.$search_string.'%')));
		
		foreach($from_bulletins as $blt){
			$result[] = array(
				'title' => $blt['Bulletin']['title'],
				'excerpt' => substr($blt['Bulletin']['content'],0,200),
				'controller' => 'bulletins',
				'action' => 'bulletin_viewer',
				'parameter' => 'nowhere/' . $blt['Bulletin']['id']);
		}
		
		$this->set('result', $result);
	}


}
?>