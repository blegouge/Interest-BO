<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PointOfInterestController extends AbstractController
{
    private $client;
    private $endpoint = 'https://api.foursquare.com/v3/places/search';

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/point/of/interest/{longitude}/{latitude}/{radius}", methods={"GET"}, name="app_point_of_interest")
     * @param Request $request
     * @return Response
     */
    public function index(float $longitude, float $latitude, int $radius): JsonResponse
    {

        $request = $this->client->request(
            'GET',
            $this->endpoint.'?ll='.$longitude.','.$latitude.'&radius='.$radius,
            [
                'headers' => [
                    'Authorization' => 'fsq3LUE8WwKio5dQklMt0eyb9dW/T39x/rwh30gRy0cgsDI='
                ],
                'verify_host' => false,
                'verify_peer' => false,
            ]
        );

        if (200 != $request->getStatusCode()) {
            return new JsonResponse([], 418);
        }

        return new JsonResponse($request->toArray());
    }
}
