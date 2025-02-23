<?php
class Response {
    public function success($data = null) {
        $response = [
            'status' => 'success',
            'data' => $data
        ];
        echo json_encode($response);
        exit;
    }
    
    public function error($message, $code = 400) {
        http_response_code($code);
        $response = [
            'status' => 'error',
            'message' => $message
        ];
        echo json_encode($response);
        exit;
    }
}