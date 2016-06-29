<?php
require __DIR__ . '/libs/nette.min.php';

use Nette\Mail\SmtpMailer;
use Nette\Mail\Message;

//email
$mail = new Message;
//emailový server
$mailer = new SmtpMailer(array(
        'smtp'=> 'true',
        'port'=> '587',
        'host' => 'mail.nov.sk',
        'username' => 'monitor',
        'password' => 'FloHatPop6',
        'secure' => 'tls',
));

$mail->setFrom('Zdenek Srstka <srstka@matador.com>')
    ->addTo('rytmus@piscany.com')
    ->setSubject('Potvrdenie objednavky')
    ->setBody("AhoJ Rytmaus,\nTie matadory na Bavor ti dodám.");

$mailer->send($mail);