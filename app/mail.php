<?php

require_once __DIR__ . '/../vendor/autoload.php';

function bootstrapMailer()
{
    $vars = require __DIR__ . '/config/mail.php';

    // Create the Transport
    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
        ->setUsername($vars['mail_user'])
        ->setPassword($vars['mail_pass']);

// Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

    return $mailer;
}

function send_confirm_mail($destination, $emailhash)
{
    $mailer = bootstrapMailer();

    // Create a message
    $message = (new Swift_Message('[Confirmation email] Have I Been Sold?'))
        ->setFrom(['email@bitfalls.com' => 'Bitfalls Team'])
        ->setTo([$destination])
        ->setBody('You recently indicated that you want to be notified when we obtain a list on which your email was sold. Please click this link to confirm: ' . generateLink('confirm',
                $destination, $emailhash), 'text/html');

    return $mailer->send($message);
}

function send_remove_mail($destination, $emailhash)
{
    $mailer = bootstrapMailer();

    // Create a message
    $message = (new Swift_Message('[Removal confirmation email] Have I Been Sold?'))
        ->setFrom(['email@bitfalls.com' => 'Bitfalls Team'])
        ->setTo([$destination])
        ->setBody('You recently indicated that you want to be removed from our notification database. Please confirm this action by clicking: ' . generateLink('remove',
                $destination, $emailhash), 'text/html');

    return $mailer->send($message);
}

function generateLink($action, $destination, $emailhash)
{
    $vars = require __DIR__ . '/config/mail.php';

    $actionhash = generateactionhash($action, $destination);

    $string = '<a href="' . $vars['domain'] . '/admin.php?a=' . $actionhash . '&h=' . $emailhash . '">' . $vars['domain'] . '/admin.php?a=' . $actionhash . '&h=' . $emailhash . '</a>';

    return $string;
}

function generateactionhash($action, $destination) {
    $vars = require __DIR__ . '/config/mail.php';

    return hash('sha256', $vars['seed'] . $action . $destination);

}

function generateemailhash($destination)
{
    $vars = require __DIR__ . '/config/mail.php';

    return hash('sha256', $vars['seed'] . $destination . time());
}