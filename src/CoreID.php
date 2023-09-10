<?php

namespace uzdevid\CoreID;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class CoreID
 *
 * @package uzdevid\CoreID
 *
 * @property-read ClientInterface $client
 */
class CoreID extends Component {
    public string $baseUri;
    public string $clientSecret;
    public bool $cacheEnabled = true;
    public int $cacheDuration = 900;
    public string $cacheSchema = 'cache';

    private ClientInterface $_client;

    public function getClient(): ClientInterface {
        if (!isset($this->_client)) {
            $this->_client = new Client(['base_uri' => $this->baseUri]);
        }

        return $this->_client;
    }

    /**
     * @throws InvalidConfigException
     */
    public function getProfileByToken(string $token): array|null {
        $request = CacheRequest::build($this->client)
            ->cache($this->cacheEnabled, $this->cacheDuration, $this->cacheSchema)
            ->addHeader('Authorization', $token)
            ->addHeader('Client-Secret', $this->clientSecret);

        $response = $request->get('/profile/profile/info');

        return $response['body'] ?? null;
    }
}