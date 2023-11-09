<?php
require_once('./handlers/db_handler.php');

class token_handler {
    private string $secret = '';

    function __constuct () {
        // Generate secure secret
        $env = parse_ini_file('/.env');
        $secret = $env['SECRET_KEY'].$_SERVER['SERVER_NAME'];
        $this -> secret = $secret;
    }

    function create_token (array $data, string $type, int $user, string $time, bool $renew ) {
        // Set variables
        $expiredate = date("Y-m-d H:i:s", strtotime('+ ' . $time));
        $data = json_encode($data);

        // Connect to database
        $conn = new db_handler();

        // Create entry in database & get id
        $conn -> execute("INSERT INTO tokens (user, type, data, expires, renew, renewtime) VALUES (?, ?, ?, ?, ?, ?)", 'isssis', [$user, $type, $data, $expiredate, $renew, $time]);
        $result = $conn -> query2("SELECT id FROM tokens WHERE id = LAST_INSERT_ID()");
        $id = $result [0]['id'];

        // Generate token and store it in database
        $token = hash('sha256', $this -> secret . $id);
        $conn -> execute("UPDATE tokens SET token = ? WHERE id = ?", 'si', [$token, $id]);

        return $token;
    }

    function verify_token (string $token, string $type) {
        // Connect to database
        $conn = new db_handler();

        // Get token data if token is valid, else return false.
        $token_data = $conn -> query("SELECT * FROM tokens WHERE token = ? AND type = ? AND used = 0 AND expires > CURRENT_TIMESTAMP()", 'ss', [$token, $type]);
        if (empty($token_data)) return false;
        $token_data = $token_data[0];

        // Set token as used
        $conn -> execute("UPDATE tokens SET used = 1, usedtime = CURRENT_TIMESTAMP() WHERE id = ?", 'i', [$token_data['id']]);
        
        // If token doesn't has to be renewed, return data
        if (!$token_data['renew']) {
            return array(
                'data' => json_decode($token_data['data'], true)
            );
        }

        // Generate a new token and return it with the data
        $new_token = $this -> create_token(json_decode($token_data['data'], true), $token_data['type'], $token_data['user'], $token_data['renewtime'], true);
        return array(
            'token' => $new_token,
            'data' => json_decode($token_data['data'], true)
        );
    }

    function cancel_user_tokens (int $user, string $type) {
        $conn = new db_handler();
        $conn -> execute("UPDATE tokens SET used = 1 WHERE user = ? AND type = ?", 'is', [$user, $type]);
    }
}

?>