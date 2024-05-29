<?php

namespace App\Controller;

use App\Form\WeatherForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class  WeatherController extends AbstractController
{
    #[Route('/', name: 'home')]
    function index(): Response
    {
        $form = $this->createForm(WeatherForm::class);
        return $this->render('weather/form.html.twig', [
            "controller_name" => "HeavyWeather",
            "city_form" => $form->createView(),
        ]);
    }
}