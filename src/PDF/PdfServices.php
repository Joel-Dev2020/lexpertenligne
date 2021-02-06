<?php

namespace App\PDF;

use App\Interfaces\PdfInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class PdfServices implements PdfInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param object $entity
     * @param string $filename
     * @param string $orientation
     * @param string $template
     * @param array $options
     * @return Response
     */
    public function generate(
        object $entity,
        string $filename,
        string $orientation = 'A4',
        string $template,
        array $options = []
    ): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsHtml5ParserEnabled(true);
        $filename = str_replace(' ', '_', $filename);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $dompdf->setBasePath("pdf");
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);

        $dompdf->setHttpContext($contxt);

        $html = $this->container->get('twig')->render($template, $options);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($filename.".pdf", [
            "Attachment" => true
        ]);

        $response = new Response();

        $response->headers->set('Content-type', 'application/pdf');

        return $response;
    }

    /**
     * @param array $entity
     * @param string $filename
     * @param string $orientation
     * @param string $template
     * @param array $options
     * @return Response
     */
    public function arraygenerate(array $entity, string $filename, string $orientation = 'A4', string $template, array $options = []): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsHtml5ParserEnabled(true);
        $filename = str_replace(' ', '_', $filename);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $dompdf->setBasePath("pdf");
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);

        $dompdf->setHttpContext($contxt);

        $html = $this->container->get('twig')->render($template, $options);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($filename.".pdf", [
            "Attachment" => true
        ]);

        $response = new Response();

        $response->headers->set('Content-type', 'application/pdf');

        return $response;
    }
}