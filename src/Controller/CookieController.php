<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CookieController extends AbstractController
{
    /**
     * @Route("/cookie", name="cookie-warning")
     */
    public function cookieWarning(): Response

    {
        if (empty($_COOKIE['visited'])) {

                $response = new Response();
                $response->headers->setCookie(new Cookie('visited', 'hide', time() + (365 * 24 * 60 * 60)));
                $response->sendHeaders();            

            return $this->redirectToRoute("accueil");
        }
    }
}
