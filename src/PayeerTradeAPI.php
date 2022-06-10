<?php

namespace PayeerTrade;

use PayeerTrade\Exceptions\ApiException;
use PayeerTrade\Exceptions\RuntimeException;
use PayeerTrade\Exceptions\UnknownFunctionException;
use PayeerTrade\Exceptions\UnknownMethodException;

/**
 * Ваш электронный
 * PAYEER® кошелек!
 *
 * API
 */
class PayeerTradeAPI
{
    /**
     * API URL
     *
     * @var string
     */
    private string $apiUrl = 'https://payeer.com/api/trade/';

    /**
     * Агент для запроса
     *
     * @var string
     */
    private string $agent = 'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20100101 Firefox/12.0';

    /**
     * Экземпляр
     *
     * @var PayeerTradeAPI|null
     */
    static ?PayeerTradeAPI $instance = null;

    /**
     * Время на сервере API
     *
     * @var float|null
     */
    private ?float $timeOnApiServer = null;

    /**
     * Последняя синхронизация времени с API сервером
     *
     * @var int|null
     */
    private ?int $lastUpdate = null;

    /**
     * @param string $apiId id приложения
     * @param string $apiSecretKey API ключ
     */
    public function __construct(
        private readonly string $apiId,
        private readonly string $apiSecretKey,
    )
    {
        self::$instance = $this;
    }

    /**
     * Обработка несуществующего статичного метода
     *
     * @param string $name
     * @param array $arguments
     */
    public static function __callStatic(string $name, array $arguments)
    {
        throw new UnknownMethodException($name);
    }

    /**
     * Отправка запроса на сервер API
     *
     * @param string $method
     * @param false $isPost
     * @param array $data
     * @return mixed
     * @throws ApiException
     */
    private function request(string $method, bool $isPost = false, array $data = []): mixed
    {
        if (!function_exists('curl_init')) {
            throw new UnknownFunctionException('curl_init');
        }

        $headers = [
            "Content-Type: application/json",
        ];

        if (!is_null($this->timeOnApiServer)) {

            if (is_null($this->apiId)) {
                throw new RuntimeException('Забыл указать apiId');
            }

            $headers[] = 'API-ID: ' . $this->apiId;

            $data['ts'] = $this->timeOnApiServer;
            $data = json_encode($data);

            $headers[] = 'API-SIGN: ' . $this->makeSign($method, $data);
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . $method, // Полный адрес метода
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true, // Возвращать ответ
            CURLOPT_POST => $isPost, // Метод POST
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $data, // Данные в запросе
            CURLOPT_USERAGENT => $this->agent,
        ]);

        $server_output = curl_exec($curl);

        $result = json_decode($server_output);

        $this->checkError($result);

        curl_close($curl);

        return $result;
    }

    /**
     * Подготовка к отправке запроса
     *
     * @param string $method
     * @param bool $isPost
     * @param array $data
     * @return mixed
     * @throws ApiException
     */
    private static function send(string $method, bool $isPost = false, array $data = []): mixed
    {
        self::getInstance()->timeSynchronization();

        return self::getInstance()->request($method, $isPost, $data);
    }

    /**
     * Синхронизация времени с сервером API
     * @throws ApiException
     */
    private function timeSynchronization(): void
    {
        if (is_null(self::getInstance()->lastUpdate) || self::getInstance()->lastUpdate + 30 < time()) {

            $response = self::getInstance()->request(
                method: 'time'
            );

            if ($response->success === true) {
                self::getInstance()->lastUpdate = time();
                self::getInstance()->timeOnApiServer = $response->time;
            } else {
                throw new RuntimeException();
            }

        }
    }

    /**
     * Генерация подписи
     *
     * @param string $method
     * @param string $data
     * @return string
     */
    private function makeSign(string $method, string $data): string
    {
        if (is_null($this->apiSecretKey)) {
            throw new RuntimeException('Забыл указать apiSecretKey');
        }

        return hash_hmac('sha256', $method.$data, $this->apiSecretKey);
    }

    /**
     * Обработка ошибок из API
     *
     * @param mixed $result
     * @throws ApiException
     */
    private function checkError(mixed $result): void
    {
        if (isset($result->error)) {
            throw new ApiException($result->error->code);
        }
    }

    /**
     * Настройка API URL
     *
     * @param string $apiUrl
     */
    public static function setApiUrl(string $apiUrl): void
    {
        self::getInstance()->apiUrl = $apiUrl;
    }

    /**
     * Настройка агента
     *
     * @param string $agent
     */
    public static function setAgent(string $agent): void
    {
        self::getInstance()->agent = $agent;
    }

    /**
     * Получение класса
     *
     * @return PayeerTradeAPI
     */
    public static function getInstance(): PayeerTradeAPI
    {
        if (!self::$instance) {
            throw new RuntimeException('Забыл инициализировать! ' . self::class);
        }

        return self::$instance;
    }

    /**
     * Проверка подключения / Получение времени на сервере API
     *
     * @return mixed
     * @throws ApiException
     */
    public static function checkConnection(): mixed
    {
        return self::send(
            method: 'time'
        );
    }

    /**
     * Получение баланса пользователя
     *
     * @return mixed
     * @throws ApiException
     */
    public static function account(): mixed
    {
        return self::send(
            method: 'account',
            isPost: true
        );
    }

    /**
     * Получение статистики цен и их колебания за последние 24 часа
     *
     * @param string ...$pair
     * @return mixed
     * @throws ApiException
     */
    public static function ticker(...$pair): mixed
    {
        try {
            $data = ['pair' => implode(',', $pair)];
        } catch (\Error | \Exception) {
            throw new RuntimeException();
        }

        return self::send(
            method: 'ticker',
            data: $data
        );
    }
}
