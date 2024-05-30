<?php

namespace App\Controller;


use App\Entity\SearchEntry;
use App\Form\ClearSessionType;
use App\Form\WeatherForm;
use App\Services\WeatherApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class  WeatherController extends AbstractController
{
    #[Route('/', name: 'home')]
    function index(Request $request, HttpClientInterface $client, EntityManagerInterface $entityManager): Response
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

                $request->getSession()->set("last_searched_city", $data['cityName']);

                $searchCount = $request->cookies->get("search_count", 0);
                $cookie = new Cookie("search_count", ++$searchCount, time() + (2 * 365 * 24 * 60 * 60));

                $this->saveCitySearch($data["cityName"], $entityManager);


            } elseif ($sessionForm->isSubmitted() && $sessionForm->isValid()) {
                $request->getSession()->clear();
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
        $topSearches = $entityManager->getRepository(SearchEntry::class)->getTopSearches();


        $response = $this->render('weather/form.html.twig', [
            "controller_name" => "HeavyWeather",
            "city_form" => $weatherForm->createView(),
            "session_form" => $sessionForm->createView(),
            "weather_data" => $weatherData,
            "error" => $error,
            "history" => $topSearches
        ]);
        if (isset($cookie)) {
            $response->headers->setCookie($cookie);
        }
        return $response;
    }

    public function saveCitySearch(string $cityName, EntityManagerInterface $entityManager): void
    {
        $searchEntry = new SearchEntry();
        $searchEntry->setCityName($cityName);
        $searchEntry->setSearchTime(new \DateTime());

        $entityManager->persist($searchEntry);
        $entityManager->flush();
    }
}