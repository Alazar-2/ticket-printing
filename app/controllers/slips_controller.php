<?php

class SlipsController extends AppController {

    var $name = 'Slips';

    function index() {
        $tickets = $this->Slip->Ticket->find('all');
        $this->set(compact('tickets'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $ticket_id = (isset($_REQUEST['ticket_id'])) ? $_REQUEST['ticket_id'] : -1;
        if ($id)
            $ticket_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($ticket_id != -1) {
            $conditions['Slip.ticket_id'] = $ticket_id;
        }

        $this->set('slips', $this->Slip->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->Slip->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid slip', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Slip->recursive = 2;
        $this->set('slip', $this->Slip->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {


            $this->Slip->create();
            $this->autoRender = false;
            $this->layout = 'message_layout';
            if ($this->Slip->save($this->data)) {

                $photox = $this->data['Slip']['image'];
                if (isset($photox)) {
                    $allowedExts = array("jpg", "jpeg", "gif", "png");
                    $extension = end(explode(".", $photox["name"]));
                    if ((($photox["type"] == "image/gif")
                            || ($photox["type"] == "image/jpeg")
                            || ($photox["type"] == "image/png")
                            || ($photox["type"] == "image/pjpeg"))
                            && in_array($extension, $allowedExts)) {
                        if ($photox["error"] > 0) {
                            
                        } else {
                            if (!is_dir(IMAGES . "slip_images"))
                                mkdir(IMAGES . "slip_images", 0777);
                            $this->data['Slip']['id'] = $this->Slip->getLastInsertId();
                            $photox["name"] = $this->data['Slip']['id'] . "." . $extension;
                            move_uploaded_file($photox["tmp_name"], IMAGES . "slip_images" . DS . $photox["name"]);
                            $this->data['Slip']['url'] = $photox["name"];
                            $file_name = $photox["name"];
                            $ext = substr($file_name, strripos($file_name, '.') + 1);

                            if (in_array($ext, array('png', 'jpg', 'jpeg', 'gif'))) {
                                list($w1, $h1) = getimagesize(IMAGES . 'slip_images' . DS . $file_name);
                                /* if ($w1 > 150) {
                                  // Load
                                  $w2 = 150;
                                  $h2 = ($w2 * $h1) / $w1;
                                  if ($h2 > 190)
                                  $h2 = 190;
                                 * 
                                 */

                                $thumb = imagecreatetruecolor($w1, $h1);
                                $source = null;
                                if (in_array($ext, array('jpg', 'jpeg')))
                                    $source = imagecreatefromjpeg(IMAGES . 'slip_images' . DS . $file_name);
                                elseif (in_array($ext, array('png')))
                                    $source = imagecreatefrompng(IMAGES . 'slip_images' . DS . $file_name);
                                else
                                    $source = imagecreatefromgif(IMAGES . 'slip_images' . DS . $file_name);
                                // Resize
                                imagecopyresized($thumb, $source, 0, 0, 0, 0, $w1, $h1, $w1, $h1);
                                //$new_image = 'acuity_' . date('YmdHi') . '.png';
                                if (in_array($ext, array('jpg', 'jpeg')))
                                    imagejpeg($thumb, IMAGES . 'slip_images' . DS . $file_name);
                                elseif (in_array($ext, array('png')))
                                    imagepng($thumb, IMAGES . 'slip_images' . DS . $file_name);
                                else
                                    imagegif($thumb, IMAGES . 'slip_images' . DS . $file_name);

                                //unlink(IMAGES . 'employee_photos' . DS . $file_name);
                            }
                        }
                    }
                }
                $this->Slip->save($this->data);
                $this->Session->setFlash(__('The slip has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The slip could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $tickets = $this->Slip->Ticket->find('list');
        $this->set(compact('tickets'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid slip', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->Slip->save($this->data)) {
                $this->Session->setFlash(__('The slip has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The slip could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('slip', $this->Slip->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $tickets = $this->Slip->Ticket->find('list');
        $this->set(compact('tickets'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for slip', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->Slip->delete($i);
                }
                $this->Session->setFlash(__('Slip deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Slip was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Slip->delete($id)) {
                $this->Session->setFlash(__('Slip deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Slip was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>