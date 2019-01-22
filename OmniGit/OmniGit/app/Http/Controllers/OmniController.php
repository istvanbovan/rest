<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\View\View;
use Exception;

class OmniController extends Controller
{
    /** @var array */
    private $providers = [
        'GitHub',
    ];
    /** @var Client */
    private $client;

    /**
     * OmniController constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param string $values
     * @return View
     */
    public function authenticate(string $values) : View
    {
        $providers = $this->validateProviders($values);
        $this->checkProvidersAccess($providers);
        $repos = $this->getRepos($providers['source']);

        return view(
            'omni',
            [
                'repos' => $repos,
                'source' => $providers['source'],
                'target' => $providers['target'],
            ]
        );
    }

    public function migrate(string $values) : void
    {
        $repos = $this->validateRepoNames($values);
        $source = $repos['source'];
        $target = $repos['target'];

        $url = 'https://api.github.com/user/repos';
        $headers = [
            'Authorization' => 'Bearer ' . env('GIT_ACCESS_TOKEN'),
        ];
        $payload = [
            'name' => $target['repo'],
        ];
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $payload,
        ]);

        if ($response->getStatusCode() !== 201) {
            throw new Exception('Failed to create new repository.');
        }


    }

    /**
     * @param string $values
     * @return array
     * @throws Exception
     */
    private function validateRepoNames(string $values) : array
    {
        $repos = explode('_', $values);
        $source = [
            'provider' => explode('-', $repos[0])[0],
            'repo' => explode('-', $repos[0])[1],
        ];
        $target = [
            'provider' => explode('-', $repos[1])[0],
            'repo' => explode('-', $repos[1])[1],
        ];

        if ($source['provider'] === $target['provider'] &&
            $source['repo'] === $target['repo']) {
            throw new Exception('You have defined the same target as the source. Please specify a different one.');
        }

        $existing = $this->getRepos($target['provider']);

        if (in_array($target['repo'], $existing)) {
            throw new Exception('You already have a repository with the same name. Please specify a different one.');
        }

        return [
            'source' => $source,
            'target' => $target,
        ];
    }

    /**
     * @param string $provider
     * @return array
     */
    private function getRepos(string $provider) : array
    {
        $repos = [];

        if ($provider === 'GitHub') {
            $url = 'https://api.github.com/user/repos';
            $headers = [
                'Authorization' => 'Bearer ' . env('GIT_ACCESS_TOKEN'),
            ];
            $response = $this->client->get($url, [
                'headers' => $headers,
            ]);
            $ret = json_decode($response->getBody()->getContents(), true);

            foreach ($ret as $repo) {
                $repos[] = [
                    'id' => $repo['id'],
                    'name' => $repo['name'],
                ];
            }

            return $repos;
        }

        return [];
    }

    /**
     * @param array $providers
     * @return void
     * @throws Exception
     */
    private function checkProvidersAccess(array $providers) : void
    {
        if ($providers['source'] === 'GitHub') {
            $this->checkGitHubAccess();
        }

        $same = $this->checkSourceAndTarget($providers);

        if ($same) {
            return;
        }

        if ($providers['target'] === 'Some Other Provider') {
            //placeholder for other provider's access check
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    private function checkGitHubAccess() : void
    {
        $url = sprintf('https://api.github.com/?access_token=%s', env('GIT_ACCESS_TOKEN'));
        $response = $this->client->get($url);

        if ($response->getStatusCode() !== 200) {
            throw new Exception('Could not authenticate with GitHub.');
        }
    }

    /**
     * @param array $providers
     * @return bool
     */
    private function checkSourceAndTarget(array $providers) : bool
    {
        return $providers['source'] === $providers['target'] ? true : false;
    }

    /**
     * @param string $values
     * @return array
     * @throws Exception
     */
    private function validateProviders(string $values) : array
    {
        $array = explode('_', $values);

        foreach ($array as $value) {
            if (!in_array($value, $this->providers)) {
                throw new Exception(sprintf(
                    'Unknown provider "%s"! Supported providers are %s.',
                    $value,
                    json_encode($this->providers)
                ));
            }
        }

        return [
            'source' => $array[0],
            'target' => $array[1],
        ];
    }
}
