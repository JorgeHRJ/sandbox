<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function exceptions(): Response
    {
        if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException();
        }
        throw new NotFoundHttpException();
    }

    /**
     * @Route("/session", name="test_session")
     * @param Request $request
     * @return Response
     */
    public function session(Request $request): Response
    {
        throw $this->createNotFoundException();
        $this->session->set('foo', 'bar');

        dump($this->session->get('foo'));
        dump($this->session->get('foobar', 'default'));

        dump($request->cookies->get('PHPSESSID'));
        dump($this->session->getId());

        dump($this->session->getName());

        dump($this->session->getMetadataBag());

        $names = [
            'a' => 'Jorge',
            'b' => 'Carlos',
            'c' => 'Samuel'
        ];
        $this->session->set('names', $names);

        $names = $this->session->get('names');
        dump($names);

        $this->session->set('names/d', 'Juan');
        $names = $this->session->get('names');
        dump($names);

        $this->addFlash('message', 'Hello!');
        dump($this->session->getBag('flashes'));

        die();
    }
}
