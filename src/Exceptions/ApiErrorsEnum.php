<?php

namespace PayeerTrade\Exceptions;

enum ApiErrorsEnum: string
{
    case INVALID_SIGNATURE = 'INVALID_SIGNATURE';
    case INVALID_IP_ADDRESS = 'INVALID_IP_ADDRESS';
    case LIMIT_EXCEEDED = 'LIMIT_EXCEEDED';
    case INVALID_TIMESTAMP = 'INVALID_TIMESTAMP';
    case ACCESS_DENIED = 'ACCESS_DENIED';
    case INVALID_PARAMETER = 'INVALID_PARAMETER';
    case PARAMETER_EMPTY = 'PARAMETER_EMPTY';
    case INVALID_STATUS_FOR_REFUND = 'INVALID_STATUS_FOR_REFUND';
    case REFUND_LIMIT = 'REFUND_LIMIT';
    case UNKNOWN_ERROR = 'UNKNOWN_ERROR';
    case INVALID_DATE_RANGE = 'INVALID_DATE_RANGE';
    case INSUFFICIENT_FUNDS = 'INSUFFICIENT_FUNDS';
    case INSUFFICIENT_VOLUME = 'INSUFFICIENT_VOLUME';
    case INCORRECT_PRICE = 'INCORRECT_PRICE';
    case MIN_AMOUNT = 'MIN_AMOUNT';
    case MIN_VALUE = 'MIN_VALUE';

    public function description()
    {
        return match ($this) {
            self::INVALID_SIGNATURE => "Возможные причины:
            - неверная подпись API-SIGN
            - неверно указан API-ID
            - API-пользователь заблокирован (можно разблокировать в настройках)",

            self::INVALID_IP_ADDRESS => "IP-адрес ip источника запроса не совпадает с тем, который прописан в настройках API",

            self::LIMIT_EXCEEDED => "Превышение установленных лимитов (количество запросов/весов/ордеров), подробнее указано в параметре desc",

            self::INVALID_TIMESTAMP => "параметр ts указан неверно:
            - запрос шел до сервера API более 60 секунд
            - на вашем сервере неверное время, настройте синхронизацию",

            self::ACCESS_DENIED => "Доступ к API/ордеру запрещен. Проверьте order_id",

            self::INVALID_PARAMETER => "параметр parameter указан неверно",

            self::PARAMETER_EMPTY => "параметр parameter обязателен (не должен быть пустым)",

            self::INVALID_STATUS_FOR_REFUND => "статус status ордера не позволяет произвести возврат (ордер уже возвращен или отменен)",

            self::REFUND_LIMIT => "ордер может быть отменен не менее через 1 минуту после создания",

            self::UNKNOWN_ERROR => "Неизвестная ошибка на бирже. Торги приостановлены для проверки. Попробуйте через 15 минут.",

            self::INVALID_DATE_RANGE => "Неверно указан диапазон дат для фильтрации (период не должен превышать 32 дня)",

            self::INSUFFICIENT_FUNDS => "недостаточно средств для создания ордера (max_amount, max_value)",

            self::INSUFFICIENT_VOLUME => "недостаточно объема для создания ордера (max_amount, max_value)",

            self::INCORRECT_PRICE => "цена выходит из допустимого диапазона (min_price, max_price)",

            self::MIN_AMOUNT => "количество меньше минимального min_amount для выбранной пары",

            self::MIN_VALUE => "сумма ордера меньше минимальной min_value для выбранной пары"
        };
    }
}