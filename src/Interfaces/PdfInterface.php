<?php


namespace App\Interfaces;


use Symfony\Component\HttpFoundation\Response;

interface PdfInterface
{
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
    ): Response;
}