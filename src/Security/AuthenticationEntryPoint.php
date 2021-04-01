<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    private $urlGenerator;
    private $session;
    private $translator;

    public function __construct(UrlGeneratorInterface $urlGenerator, SessionInterface $session,TranslatorInterface $translator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
        $this->translator = $translator;
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        // add a custom flash message and redirect to the login page
        $this->session->getFlashBag()->add(
            'danger',
            $this->translator->trans('backend.login.start')
        );
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}