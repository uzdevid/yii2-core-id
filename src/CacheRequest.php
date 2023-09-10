<?php

namespace uzdevid\CoreID;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;

class CacheRequest {
    private ClientInterface $client;

    private bool $cacheEnabled = false;
    private int $cacheDuration = 900;
    private string $cacheSchema = 'cache';

    private array $_headers;

    public function __construct(ClientInterface $client) {
        $this->client = $client;
    }

    public static function build(ClientInterface $client): CacheRequest {
        return new self($client);
    }

    public function addHeader(string $name, mixed $value): static {
        $this->_headers[$name] = $value;
        return $this;
    }

    protected function headers(): array {
        return $this->_headers;
    }

    /**
     * @param string $url
     *
     * @return array|null
     * @throws InvalidConfigException
     */
    public function get(string $url): array|null {
        if ($this->cacheEnabled) {
            return Yii::$app->get($this->cacheSchema)->getOrSet([$url, $this->headers()], fn() => $this->send(new Request('GET', $url, $this->headers())), $this->cacheDuration);
        }

        return $this->send(new Request('GET', $url, $this->headers()));
    }

    protected function send(RequestInterface $request) {
        try {
            $response = $this->client->sendAsync($request)->wait();

            return json_decode($response->getBody()->getContents(), true);
        } catch (Throwable $th) {
            return null;
        }
    }

    public function cache(bool $cacheEnabled, int $cacheDuration, string $cacheSchema): static {
        $this->cacheEnabled = $cacheEnabled;
        $this->cacheDuration = $cacheDuration;
        $this->cacheSchema = $cacheSchema;
        return $this;
    }
}