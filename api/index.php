<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    
    use Symfony\Component\Mailer\Transport;
    use Symfony\Component\Mailer\Mailer;
    use Symfony\Component\Mime\Email;    

    //require_once('vendor/autoload.php');
echo "Test-" . "a";    
//    print_r($_ENV);
/*
    // Create the Transport
    $transport = Transport::fromDsn('smtp://localhost');
    // Create the Mailer using your created Transport
    $mailer = new Mailer($transport);
    // Set Mail-Config
    return static function (FrameworkConfig $framework) {
    $mailer = $framework->mailer();
    $mailer
        ->envelope()
            ->sender('kirsten@kirsten-roschanski.de')
            ->recipients(['order@kirsten-roschanski.de'])
    ;

    $mailer->header('From')->value('HRZ-Accounts <kirsten@kirsten-roschanski.de>');
    $mailer->header('X-Custom-Header')->value('Online-Shop');
    };


    $email = (new Email())
        ->from('kirsten@kirsten-roschanski.de')
        ->to('kirsten@kirsten-roschanski.de')
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Time for Symfony Mailer!')
        ->text('Sending emails is fun again!')
        ->html('<p>See Twig integration for better HTML integration!</p>');

    $mailer->send($email);

*/