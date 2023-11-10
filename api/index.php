<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    
    use Symfony\Component\Mailer\Transport;
    use Symfony\Component\Mailer\Mailer;
    use Symfony\Component\Mime\Email;    

    echo ($_ENV['SMTP_TLS'] ? 'smtps' : 'smtp').'://'.$_ENV['SMTP_USER'].':'.$_ENV['SMTP_USER'].'@'.$_ENV['SMTP_HOST'].':'.$_ENV['SMTP_PORT'];

    // Create the Transport
    $transport = Transport::fromDsn(($_ENV['SMTP_TLS'] ? 'smtps' : 'smtp').'://\''.$_ENV['SMTP_USER'].'\':'.$_ENV['SMTP_PASS'].'@'.$_ENV['SMTP_HOST'].':'.$_ENV['SMTP_PORT']);
    // Create the Mailer using your created Transport
    $mailer = new Mailer($transport);
/*    
    // Set Mail-Config
    return static function (FrameworkConfig $framework) {
        $mailer = $framework->mailer();
        $mailer
            ->envelope()
                ->sender($_ENV['SMTP_USER'])
                ->recipients($_ENV['SMTP_USER'])
        ;

        $mailer->header('From')->value($_ENV['SMTP_USER']);
        $mailer->header('X-Custom-Header')->value('Online-Shop');
    };
*/

    $email = (new Email())
        ->from($_ENV['SMTP_USER'])
        ->to('info@kirsten-roschanski.de')
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Time for Symfony Mailer!')
        ->text('Sending emails is fun again!')
        ->html('<p>See Twig integration for better HTML integration!</p>');

    try {
        $mailer->send($email);
    } catch (TransportExceptionInterface $e) {
        var_dump($e);
        // some error prevented the email sending; display an
        // error message or try to resend the message
    }

