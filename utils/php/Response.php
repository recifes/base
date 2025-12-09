<?php
/**
 * Classe Response HSN
 * Utilitário para padronizar respostas de API
 */

namespace HSN\Utils;

class Response
{
    /**
     * Envia resposta JSON de sucesso
     *
     * @param mixed $data
     * @param int $statusCode
     */
    public static function success($data = null, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'data' => $data
        ], JSON_PRETTY_PRINT);

        exit;
    }

    /**
     * Envia resposta JSON de erro
     *
     * @param string $message
     * @param int $statusCode
     * @param array $errors
     */
    public static function error($message, $statusCode = 400, $errors = [])
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');

        $response = [
            'success' => false,
            'message' => $message
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        echo json_encode($response, JSON_PRETTY_PRINT);

        exit;
    }

    /**
     * Resposta de não autenticado
     */
    public static function unauthorized($message = 'Não autenticado')
    {
        self::error($message, 401);
    }

    /**
     * Resposta de acesso negado
     */
    public static function forbidden($message = 'Acesso negado')
    {
        self::error($message, 403);
    }

    /**
     * Resposta de não encontrado
     */
    public static function notFound($message = 'Recurso não encontrado')
    {
        self::error($message, 404);
    }

    /**
     * Resposta de validação falhou
     *
     * @param array $errors
     */
    public static function validationError($errors)
    {
        self::error('Erro de validação', 422, $errors);
    }

    /**
     * Resposta de erro interno
     */
    public static function serverError($message = 'Erro interno do servidor')
    {
        self::error($message, 500);
    }

    /**
     * Resposta customizada
     *
     * @param bool $success
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     */
    public static function custom($success, $data, $message = null, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');

        $response = [
            'success' => $success,
            'data' => $data
        ];

        if ($message) {
            $response['message'] = $message;
        }

        echo json_encode($response, JSON_PRETTY_PRINT);

        exit;
    }

    /**
     * Define headers CORS
     *
     * @param string $allowOrigin
     * @param array $allowMethods
     * @param array $allowHeaders
     */
    public static function setCORS(
        $allowOrigin = '*',
        $allowMethods = ['GET', 'POST', 'PUT', 'DELETE'],
        $allowHeaders = ['Content-Type', 'Authorization']
    ) {
        header("Access-Control-Allow-Origin: $allowOrigin");
        header('Access-Control-Allow-Methods: ' . implode(', ', $allowMethods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $allowHeaders));

        // Responde a requisições OPTIONS
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}
