<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GreetController extends AbstractController
{
    /**
     * @Route("/greet-me/{name}", name="greet_me")
     * @param Request $request
     * @param string $name
     * @return Response
     */
    public function greetMe(Request $request, string $name): Response
    {
        $response = new Response();
        $response->headers->setCookie(Cookie::create('foo', 'bar'));
        $response->headers->getCookies();
        $response->headers->clearCookie('foo');
        $response->headers->removeCookie('foo');
    }
}
