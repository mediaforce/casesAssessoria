<?php

namespace CmsMediaForce\Form;

use Zend\Form\Form;

class Post  extends Form
{

    public function __construct($name = 'user', array $categorias = null, $options = array()) {
        parent::__construct($name, $options);
        
        $this->setInputFilter(new PostFilter());
        $this->setAttribute('method', 'post');
        $this->setAttribute('novalidate', 'true');
        
        $id = new \Zend\Form\Element\Hidden('id');
        $this->add($id);
        
        $nome = new \Zend\Form\Element\Text("titulo");
        $nome->setLabel("Título: ")
                ->setAttribute('placeholder','Entre com o título da página')
                ->setAttribute('class', 'form-input')
                ->setAttribute('data-ng-model', 'inputTitulo');
        $this->add($nome);

        $checkbox = new \Zend\Form\Element\Checkbox('expirar');
        $checkbox->setLabel('Não Expirar Post: ')
                    ->setUseHiddenElement(true)
                    ->setCheckedValue("nao")
                    ->setUncheckedValue("sim")
                    ->setAttribute('data-ng-model', 'checked_expirar');
        $this->add($checkbox);
    
        $expirar = new \Zend\Form\Element\Date('expiresAt');
        $expirar
            ->setLabel('Expira Em:')
            ->setAttributes(array(
                'min'  => date("Y-m-d", mktime(0, 0, 0, date("m"),  date("d")+1,  date("Y"))),
                'max'  => date("Y-m-d", mktime(0, 0, 0, date("m"),  date("d"),  date("Y")+1)),
                'step' => '1', // days; default step interval is 1 day
                'data-ng-disabled' => 'checked_expirar',
                'data-ng-model' => 'expiresAt'
            ))
            ->setOptions(array(
                'format' => 'Y-m-d'
            ));
        $this->add($expirar);

        $cats = new \Zend\Form\Element\Select();
        $cats->setLabel("Categoria: ")
                ->setName("categoria")
                ->setAttribute('data-ng-model', 'categoria')
                ->setOptions(array('value_options' => $categorias));
        $this->add($cats);

        $textarea = new \Zend\Form\Element\Textarea('conteudo');
        $textarea->setLabel('Conteudo: ')
                    ->setAttribute('id', 'textarea-content')
                    ->setAttribute('data-ng-model', 'conteudo');

        $this->add($textarea);
        
        $csrf = new \Zend\Form\Element\Csrf("security");
        $this->add($csrf);
        
        $this->add(array(
            'name' => 'submit',
            'type'=>'Zend\Form\Element\Submit',
            'attributes' => array(
                'value'=>'Publicar',
                'class' => 'btn-success right',
                'data-ng-hide' => 'corretor.id',
                'data-ng-disabled' => 'formIsInvalid'
            )
        ));
                
       
    }
    
}
