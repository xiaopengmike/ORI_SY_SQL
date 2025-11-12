<?php

/**
 * 返回码构造类
 */
class ResultCodeModel
{
    public static $commonStatusCode = array(
        '200' =>'OK请求成功。',
        '400' =>'Bad Request客户端请求错误',
        '401' =>'Unauthorized请求要求用户的身份认证',
        '403' =>'Forbidden服务器拒绝执行客户端请求',
        '404' =>'Not Found您所请求的资源无法找到',
        '500' =>'Internal Server Error服务器内部错误，无法完成请求',
        '503' =>'Service Unavailable服务器超载或系统维护'
    );

    public function setHttpHeaders($statusCode)
    {
        $statusMessage = $this->getHttpStatusMessage($statusCode);
        return $statusMessage;
    }

    /**
     * HTTP返回码
     */
    public function getHttpStatusMessage($statusCode)
    {
        $httpStatus = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );
        return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $httpStatus[500];
    }
}
