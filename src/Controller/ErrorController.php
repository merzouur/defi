<?php

// src/Controller/ErrorController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function pageNotFound(): Response
    {
        return $this->render('error/404.html.twig');
    }
    public function accessDenied(): Response
    {
        return $this->render('error/access_denied.html.twig');
    }
}
