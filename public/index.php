<?php

// $file = "ação-íaaa.jpg";
// echo utf8_decode($file);
// die;
// $file = iconv('UTF-8', 'ASCII//TRANSLIT', $file);
// echo "{$file} <br>"; die;

// $filename    = html_entity_decode( $file, ENT_QUOTES, 'utf-8' );

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */

/*echo "Teste Email Function:<br>"; 

$email = 'dnaloco@gmail.com'; // email para onde a mensagem deve ir
$resultado = mail($email, 'Testando nossa configuração', 'Olá, nossa configuração funcionou.');
if($resultado)
{
    echo 'Seu email foi enviado com sucesso.';
}
else
{
    echo 'Não foi possível enviar seu email.';
}

die;*/
// $datetime1 = new DateTime('2014-12-02'); // data hoje
// $datetime2 = new DateTime('2014-12-01'); // data post
// $interval = $datetime1->diff($datetime2);
// $diffDays = $interval->format('%R%a');

// if ($diffDays < 0) {
// 	echo "Post expirou há " . $diffDays;
// }

// die;

date_default_timezone_set('America/Sao_Paulo');

chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();


mail ('arthur_scosta@yahoo.com.br', "Test Postfix", "Test mail from postfix");