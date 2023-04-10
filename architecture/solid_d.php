<?php

interface HttpServiceInterface {
	public function request(string $url, string $method, array $options);
}

class XMLHttpService extends XMLHTTPRequestService implements HttpServiceInterface {}

class Http {
    private $service;

    public function __construct(HttpServiceInterface $httpService) { }

    public function get(string $url, array $options) {
        $this->service->request($url, 'GET', $options);
    }

    public function post(string $url) {
        $this->service->request($url, 'GET');
    }
}
