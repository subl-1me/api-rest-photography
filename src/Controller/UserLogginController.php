<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Serializer;
use App\Services\JwtAuth;




class UserLogginController extends AbstractController
{
    /**
     * @Route("/user/loggin", name="user_loggin")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserLogginController.php',
        ]);
    }

    public function login(Request $request, Serializer $serializer, JwtAuth $jwt_auth){

        // default response
        $data = [
            'status' => 'error',
            'code' => 400,
            'message' => 'Login error.'
        ];

        $json = $request->get('json', null);
        $params = json_decode($json);

        if($json != null){
            $params->username = strtolower($params->username);
            $params->password = strtolower($params->password);

            // compare data & account
            $username = (!empty($params->username)) ? $params->username : null;
            $password = (!empty($params->password)) ? $params->password : null;

            if(!empty($username) && !empty($password)){
                $pwd = hash('sha256', $password);

                // get token
                $signup = $jwt_auth->signup($username, $pwd);

                return $serializer->resjson($signup);
            }
        }
        return $serializer->resjson($data);
    }


}
