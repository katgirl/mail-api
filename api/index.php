<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/func.php';
    
    use Symfony\Component\Mailer\Transport;
    use Symfony\Component\Mailer\Mailer;
    use Symfony\Component\Mime\Address;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    
    if(getBearerToken() != $_ENV['API_KEY']) {
        echo "Unauthorized";
        exit;
    }

    $body = file_get_contents('php://input');
    $data = json_decode($body);

    // Create the Transport
    $transport = Transport::fromDsn(($_ENV['SMTP_TLS'] ? 'smtp' : 'smtps').'://'.$_ENV['SMTP_USER'].':'.$_ENV['SMTP_PASS'].'@'.$_ENV['SMTP_HOST'].':'.$_ENV['SMTP_PORT']);
    // Create the Mailer using your created Transport
    $mailer = new Mailer($transport);

    $email = (new TemplatedEmail())
        ->from($_ENV['SMTP_USER'])
        ->to(new Address($data->billing_info->email))
        //->cc('cc@example.com')
        ->bcc($_ENV['SMTP_USER'])
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Thanks for signing up!')
        
        // path of the Twig template to render
        ->htmlTemplate(__DIR__ . '/../templates/signup.html.twig')
        ->textTemplate(__DIR__ . '/../templates//signup.txt.twig')

        // pass variables (name => value) to the template
        ->context([
            'expiration_date' => new \DateTime('+7 days'),
            'username' => 'foo',
        ])
    ;    

    try {
        $mailer->send($email);
        echo json_encode($data); // '{ "success":"true", "message": "confirmation emails sent" }';
    
    } catch (TransportExceptionInterface $e) {
        header('X-PHP-Response-Code: 500', true, '500');
        // some error prevented the email sending; display an
        // error message or try to resend the message
    }