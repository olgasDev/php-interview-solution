
<?php

interface SecretStorageInterface {
	public function getSecretValue(string $id) : ?string;
}

class FileSecretStorage implements SecretStorageInterface {}
class DBSecretStorage implements SecretStorageInterface {}
class RedisSecretStorage implements SecretStorageInterface {}
class CloudSecretStorage implements SecretStorageInterface {}

class SecretStorageProvider {

	private static array $storages = [
		'file' => FileSecretStorage::class,
		'db' => DBSecretStorage::class,
		'redis' => RedisSecretStorage::class,
		'cloud' => CloudSecretStorage::class,
	];

	public static function getStorage(): ?SecretStorageInterface {
		//получаем из конфига какой secret storage использовать
		$storageType = env('secret_storage');

		$storageClassName = self::$storages[$storageType];

		return $storageClassName ? new $storageClassName() : null;
	}

}


class Concept {
    private $client;

    public function __construct() {
        $this->client = new \GuzzleHttp\Client();
    }

    public function getUserData() {
        $params = [
            'auth' => ['user', 'pass'],
            'token' => $this->getSecretKey()
        ];

        $request = new \Request('GET', 'https://api.method', $params);
        $promise = $this->client->sendAsync($request)->then(function ($response) {
            $result = $response->getBody();
        });

        $promise->wait();
    }

    private function getSecretKey() {
		$secretStorage = SecretStorageProvider::getStorage();
		if (!$secretStorage) {
			throw new ApiAuthException();
		}

    	return $secretStorage->getSecretValue('api_secret_key');
	}
}