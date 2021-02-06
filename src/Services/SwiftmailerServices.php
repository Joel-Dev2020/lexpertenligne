<?php

namespace App\Services;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SwiftmailerServices
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * LogsServices constructor.
     * @param Environment $twig
     * @param \Swift_Mailer $mailer
     */
    public function __construct(Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * @param string $subject
     * @param array $mailFrom
     * @param array $mailTo
     * @param string $template
     * @param array|null $parameters
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function send(string $subject, array $mailFrom, array $mailTo, string $template, ?array $parameters) {
        $to = $mailTo ?? [];
        $from = $mailFrom ?? [];

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($this->twig->render($template, $parameters), 'text/html');
        $this->mailer->send($message);
    }

    /**
     * @param string $subject
     * @param string $mailFrom
     * @param string $mailTo
     * @param string $template
     * @param array|null $parameters
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function mail(string $subject, string $mailFrom, string $mailTo, string $template, ?array $parameters) {
        $to = $mailTo;
        $from = $mailFrom;

        $message = $this->twig->render($template, $parameters);

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: '.$from.' ' . "\r\n" .
            'Reply-To: '.$from.'' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }
}