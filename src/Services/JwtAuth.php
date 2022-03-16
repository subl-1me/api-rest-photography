<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth{

    public $key;

    public function __construct(){
        $this->key = '0ImXtkDIotA2EmTr1oP9q1uiBQI3Qx66';
    }

    public function signup($username, $password){
         // admin access
         $account = [
            'username' => 'urbina@00_11',
            'password' => hash('sha256', 'urbina_page_@0@')
        ];
        
        // compare params
        $username = ($username == $account->username) ? $username : null;
        $password = ($password == $account->password) ? $password : null;

        if(!empty($username) && !empty($password)){ // Logged
            $token = [
                'username' => $username,
                'password' => $password
            ];  

            $jwt = JWT::encode($token, $this->key, 'HS256');

            // return token
            $data = $jwt;
        }else{
            $data = [
                'status' => 'error',
                'code' => 400,
                'message' => 'Login error.'
            ];
        }

        return $data;
    }

    public function checkToken($jwt){
        $auth = false;

        // Check if token can be decoded
        try{
            $decoded = $this->getIdentity($jwt);
        }catch(\UnexpectedValueException $e){
            $auth = false;
        }catch(\DomainException $e){
            $auth = false;
        }
        
        if(isset($decoded) && !empty($decoded) && is_object($decoded)){
            $auth = true;
        }

        return $auth;
    }

    private function getIdentity($jwt){
        return JWT::decode($jwt, new Key($this->key, 'HS256'));
    }
    
}