<?php
namespace Files\Controller;

use Laminas\Mvc\Controller\AbstractActionController;

class FilesController extends AbstractActionController
{
    public $form;

    public function uploadAction()
    {
        $user = $this->currentUser();
        $uuid = $user->UUID;
        $this->form->path = './data/files/' . $uuid;
        $this->form->addInputFilter();
        
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $this->form->setData($post);

            if ($this->form->isValid()) {
                $data = $this->form->getData();
            }
        }

        // -- Return to previous screen --//
        $url = $this->getRequest()
            ->getHeader('Referer')
            ->getUri();
        return $this->redirect()->toUrl($url);
    }
}