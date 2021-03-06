<?php

namespace CmsMediaForce\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use CmsBase\Mail\Mail;
use CmsBase\Service\AbstractService;


class User extends AbstractService
{

    protected $transport;
    protected $view;
    
    public function __construct(EntityManager $em, SmtpTransport $transport, $view) 
    {
        parent::__construct($em);
        
        $this->entity = "CmsMediaForce\Entity\User";
        $this->transport = $transport;
        $this->view = $view;
    }
    
    public function insert(array $data) {
        $corretor = $this->em->getReference('CmsMediaForce\Entity\Role',2);

        $data['role'] = $corretor;
        $data['active'] = true;
        $entity = parent::insert($data);

        $repo = $this->em->getRepository("CmsMediaForce\Entity\User");
        
        $user = $repo->findOneByActivationKey($entity->getActivationKey());

        $user->setActive(true);

        (new Hydrator\ClassMethods())->hydrate($data, $user);

        $this->em->persist($user);
        $this->em->flush();

        return $entity;

/*        
        $dataEmail = array('nome'=>$data['nome'],'activationKey'=>$entity->getActivationKey());
        
        if($entity)
        {
            $mail = new Mail($this->transport, $this->view, 'add-user');
            $mail->setSubject('Confirmação de cadastro')
                    ->setTo($data['email'])
                    ->setData($dataEmail)
                    ->prepare()
                    ->send();
            
            return $entity;
        }*/
    }
    
    public function activate($key)
    {
        $repo = $this->em->getRepository("CmsMediaForce\Entity\User");
        
        $user = $repo->findOneByActivationKey($key);
        
        if($user && !$user->getActive())
        {
            $user->setActive(true);
            
            $this->em->persist($user);
            $this->em->flush();
            
            return $user;
        }
    }
    
    public function update(array $data)
    {
        $entity = $this->em->getReference($this->entity, $data['id']);
        
        if(empty($data['password']))
            unset($data['password']);
        
        (new Hydrator\ClassMethods())->hydrate($data, $entity);
        
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
    
}
