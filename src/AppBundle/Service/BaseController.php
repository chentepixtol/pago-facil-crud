<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Response;

trait BaseController {

    /**
     * @param array $response
     * @param int $status
     * @return Response
     */
    public function jsonResponse(array $response, $status = 200)
    {
        return new Response(json_encode($response), $status, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @param string $message
     * @param int $status
     * @return Response
     */
    public function errorResponse($message, $status = 500)
    {
        return new Response(json_encode([
            'status' => 'error',
            'message' => $message,
        ]), $status, [
            'Content-Type' => 'application/json',
        ]);
    }
}
