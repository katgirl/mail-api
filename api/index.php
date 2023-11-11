<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/func.php';
    
    use Symfony\Component\Mailer\Transport;
    use Symfony\Component\Mailer\Mailer;
    use Symfony\Component\Mime\Address;
    use Symfony\Bridge\Twig\Mime\BodyRenderer;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Twig\Environment as TwigEnvironment;
    use Twig\Loader\FilesystemLoader as TwigFilesystemLoader;
    
   // if(getBearerToken() != $_ENV['API_KEY']) {
   //     echo "Unauthorized";
   //     exit;
   // }

   // $body = file_get_contents('php://input');
    $body    = '{"checkout_type":"bank-transfer","billing_info":{"email":"kirsten@kirsten-roschanski.de","address":{"billing_firstname":"Kirsten","billing_lastname":"Roschanski","billing_street":"Schwalbenweg, 11, 11","billing_street_additional":"11","billing_zip":"35043","billing_city":"Marburg","billing_country":"DE","billing_phone":"+491799946813","billing_email":"kirsten@kirsten-roschanski.de","billing_notes":"test"},"total":1200,"currency":"EUR","payment_method":"Banküberweisung","cart":[{"quantity":1,"article":"Françoise Dugourd-Caput - Lascive - 80 x 60 cm","amount":1200,"currency":"EUR","imageUrl":"https://cdn.sanity.io/images/ovj42q9c/galleristic/d8d760fc438ca4aabee6b473e2524abbbab24f38-1808x2438.png"}]}}';
    $data = json_decode($body);
    $loader = new TwigFilesystemLoader( __DIR__ . '/../templates/' );
    $twig = new TwigEnvironment($loader, ['debug' => true]);


    $filename = __DIR__ . '/../templates/signup.html.twig';

    if (file_exists($filename)) {
        echo "Die Datei $filename existiert";
    }

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
        ->htmlTemplate($filename)

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