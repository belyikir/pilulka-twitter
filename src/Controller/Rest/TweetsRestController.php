<?php

namespace App\Controller\Rest;

use App\Service\TwitterApiService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;

/**
 * Class TweetsRestController
 * @package App\Controller\Rest
 */
class TweetsRestController extends AbstractFOSRestController
{
    private TwitterApiService $twitterApiService;

    /**
     * @param TwitterApiService $twitterApiService
     */
    public function __construct(TwitterApiService $twitterApiService)
    {
        $this->twitterApiService = $twitterApiService;
    }

    /**
     * @Rest\Get("/tweet/{query}", name="api_get_tweets")
     * @param $query
     * @return Response
     */
    public function getTweets($query): Response
    {
        $result_tweets = $this->twitterApiService->searchTweets($query);

        $view = $this->view($result_tweets, 200);
        return $this->handleView($view);
    }
}