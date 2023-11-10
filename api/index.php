<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/func.php';
    
    use Symfony\Component\Mailer\Transport;
    use Symfony\Component\Mailer\Mailer;
    use Symfony\Component\Mime\Email;   
    
    if(getBearerToken() != $_ENV['API_KEY']) {
        echo "Unauthorized";
        header("Status: 404 Not Found"); 
        exit;
    }


    // Create the Transport
    $transport = Transport::fromDsn(($_ENV['SMTP_TLS'] ? 'smtp' : 'smtps').'://'.$_ENV['SMTP_USER'].':'.$_ENV['SMTP_PASS'].'@'.$_ENV['SMTP_HOST'].':'.$_ENV['SMTP_PORT']);
    // Create the Mailer using your created Transport
    $mailer = new Mailer($transport);

    $email = (new Email())
        ->from($_ENV['SMTP_USER'])
        ->to('info@kirsten-roschanski.de')
        //->cc('cc@example.com')
        ->bcc($_ENV['SMTP_USER'])
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Time for Symfony Mailer2!')
        ->text('Sending emails is fun again!')
        ->html('<p>See Twig integration for better HTML integration!</p>');

    try {
        $mailer->send($email);
        http_response_code(200);
        header('Content-type: application/json');

        return '{ "success":"true", "message": "confirmation emails sent" }';
    
    } catch (TransportExceptionInterface $e) {
        http_response_code(500);
        // some error prevented the email sending; display an
        // error message or try to resend the message
    }