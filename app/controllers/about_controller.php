<?php

class AboutController extends AppController {

    public $uses = array();

    public function index() {
        
    }

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }

    public function view_pdf() {
        $this->layout = 'pdf'; //this will use the pdf.ctp layout
        $this->render();
    }

    function about_nma($language = 'en') {
        $this->set('language', $language);
    }

    function history($language = 'en') {
        $this->loadModel('AboutContent');
        $content = $this->AboutContent->find('first', array('conditions' => array('AboutContent.content_type' => 'history')));
        $this->set('content', $content);
        $this->set('language', $language);
    }

    function mission_vision_value($language = 'en') {
        $this->loadModel('AboutContent');
        $content = $this->AboutContent->find('first', array('conditions' => array('AboutContent.content_type' => 'mission_vision_value')));
        $this->set('content', $content);
        $this->set('language', $language);
    }

    function five_year_strategy($language = 'en') {
        $this->loadModel('AboutContent');
        $content = $this->AboutContent->find('first', array('conditions' => array('AboutContent.content_type' => 'five_year_strategy')));
        $this->set('content', $content);
        $this->set('language', $language);
    }

    function duties_and_responsibilities($language = 'en') {
        $this->loadModel('AboutContent');
        $content = $this->AboutContent->find('first', array('conditions' => array('AboutContent.content_type' => 'duties_and_responsibilities')));
        $this->set('content', $content);
        $this->set('language', $language);
    }

    function facilities($language = 'en') {
        $this->loadModel('AboutContent');
        $content = $this->AboutContent->find('first', array('conditions' => array('AboutContent.content_type' => 'facilities')));
        $this->set('content', $content);
        $this->set('language', $language);
    }

    function staff($language = 'en') {
        $this->loadModel('AboutContent');
        $content = $this->AboutContent->find('first', array('conditions' => array('AboutContent.content_type' => 'staff')));
        $this->set('content', $content);
        $this->set('language', $language);
    }

    function organizational_structure($language = 'en') {
        $this->loadModel('AboutContent');
        $content = $this->AboutContent->find('first', array('conditions' => array('AboutContent.content_type' => 'organizational_structure')));
        $this->set('content', $content);
        $this->set('language', $language);
    }

    function contacts($language = 'en') {
        $this->loadModel('AboutContent');
        $content = $this->AboutContent->find('first', array('conditions' => array('AboutContent.content_type' => 'contacts')));
        $this->set('content', $content);
        $this->set('language', $language);
    }

}

?>