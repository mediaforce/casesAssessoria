<?php

namespace CmsMediaForce\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;

use CmsMediaForce\Form\Login as LoginForm;

class AuthController extends AbstractActionController
{

    public function indexAction()
    {
        $form = new LoginForm;
        $error = false;
        
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $data = $request->getPost()->toArray();
                
                // Criando Storage para gravar sessão da authtenticação
                $sessionStorage = new SessionStorage("CmsUser");
                
                $auth = new AuthenticationService;
                $auth->setStorage($sessionStorage); // Definindo o SessionStorage para a auth
                
                $authAdapter = $this->getServiceLocator()->get("CmsMediaForce\Auth\Adapter");
                $authAdapter->setUsername($data['email']);
                $authAdapter->setPassword($data['password']);
                
                $result = $auth->authenticate($authAdapter);
                
                if($result->isValid())
                {

                    $user = $auth->getIdentity();
                    $user = $user['user'];
                    $sessionStorage->write($user,null);
                    
                    #$sessionStorage->write($auth->getIdentity()['user'],null);
                    return $this->redirect()->toRoute('cms-admin',array('controller'=>'users'));
                }
                else
                    $error = true; 
            }
        }
        
        return new ViewModel(array('form'=>$form,'error'=>$error));
    }
    
    public function logoutAction()
    {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("CmsUser"));
        $auth->clearIdentity();
        
        return $this->redirect()->toRoute('cms-auth');
    }
}
