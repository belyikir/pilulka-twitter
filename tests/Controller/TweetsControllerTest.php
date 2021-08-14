<?php

namespace App\Tests\Controller;

use App\Service\TwitterApiService;
use http\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TweetsControllerTest extends WebTestCase
{
    //Test if page load correctly and successful
    public function testLoadingPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'http://127.0.0.1:8001/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label.required', 'Write down your query');
    }
    // Test if application return same data as TwitterApi(same count of tweets)
    public function testGetTweets(): void
    {
        $client= static::createClient();
        $crawler = $client->request("GET","http://127.0.0.1:8001/");

        $buttonCrawlerNode = $crawler->selectButton('tweet[submit]');
        $form = $buttonCrawlerNode->form();

        $query = "pilulkacz";
        $form['tweet[query]'] = $query;
        $crawler = $client->submit($form);


        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label.required', 'Write down your query');

        $twitterApiService = new TwitterApiService();
        $result = $twitterApiService->searchTweets($query);

        $this->assertCount(count($result),$crawler->filter('table'));

    }
    //Test if REST Api works correctly and return same data as TwitterApi(same count of tweets)
    public function  testRestApiRequest()
    {
        $client = static::createClient();
        $query = "pilulkacz";
        $client->request("GET","http://127.0.0.1:8001");

        $client->xmlHttpRequest('GET', '/api/tweet/'.$query);
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $twitterApiService = new TwitterApiService();
        $result = $twitterApiService->searchTweets($query);

        $this->assertSameSize($result,json_decode($response,true),true);
    }
}
