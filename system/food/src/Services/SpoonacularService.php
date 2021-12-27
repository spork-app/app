<?php
namespace Spork\Food\Services;

use App\Models\FeatureList;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class SpoonacularService
{
    protected const API_URL = 'https://api.spoonacular.com/';

    public function __construct(
        protected ?Client $client =  null,
        protected ?User $user = null
    ) {
        $this->client = $client ?? new Client();
        $this->user = $user ?? auth()->user();
    }


    protected function getUser()
    {
        $user = FeatureList::forFeature('food')->where('user_id', $this->user->id)->first();

        if (empty ($user)) {
            $user = new FeatureList();
            $user->feature = 'food';
            $user->settings = [
                'auth' => $this->connectUser($this->user)
            ];
            $user->save();
        }

        return $user;
    }

    protected function connectUser(User $user)
    {
        $response = $this->client->post(static::API_URL.'/users/connect', [
            'json' => [
                'username' => strtolower(Str::snake($user->name, '')),
                'email' => $user->email,
            ]
        ]);

        $response = json_decode($response->getBody()->getContents());

        return $response;
    }

    // Use guzzle to query the spoonacular API
    public function request(string $url, )
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $query);
        $response = $response->getBody();
        $response = json_decode($response, true);
        return $response;
    }
}