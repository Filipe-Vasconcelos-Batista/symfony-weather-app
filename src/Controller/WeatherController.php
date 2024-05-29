<?php

namespace App\Controller;


use App\Form\ClearSessionType;
use App\Form\WeatherForm;
use App\Services\WeatherApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class  WeatherController extends AbstractController
{
    #[Route('/', name: 'home')]
    function index(Request $request, HttpClientInterface $client): Response
    {
        $weatherData = null;
        $error = null;

        $weatherForm = $this->createForm(WeatherForm::class);
        $sessionForm = $this->createForm(ClearSessionType::class);

        try {
            $weatherForm->handleRequest($request);
            if ($weatherForm->isSubmitted() && $weatherForm->isValid()) {
                $data = $weatherForm->getData();

                $weatherApi = new WeatherApi($_ENV["API_KEY"], $client);
                $weatherData = $weatherApi->getWeather($data['cityName']);

                //todo
            } elseif ($sessionForm->isSubmitted() && $sessionForm->isValid()) {
                $request->getSession()->clear();
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return $this->render('weather/form.html.twig', [
            "controller_name" => "HeavyWeather",
            "city_form" => $weatherForm->createView(),
            "session_form" => $sessionForm->createView(),
            "weather_data" => $weatherData,
            "error" => $error
        ]);
    }
}