<?php


namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutSuccessHandler
{
    protected $httpUtils;
    protected $targetUrl;

    /**
     * @param HttpUtils $httpUtils
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(HttpUtils $httpUtils, UrlGeneratorInterface $urlGenerator)
    {
        $this->httpUtils = $httpUtils;

        $this->targetUrl = $urlGenerator->generate('security_login', ['logout' => 'success']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        return $this->httpUtils->createRedirectResponse($request, $this->targetUrl);
    }
}