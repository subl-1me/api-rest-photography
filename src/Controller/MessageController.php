<?php

namespace App\Controller;

use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\Serializer;
use App\Services\JwtAuth;
use App\Services\DataValidator;


class MessageController extends AbstractController
{
    public function create(Request $request, Serializer $serializer){
        // get message data
        $json = $request->get('json', null);
        $params = json_decode($json);

        // default response
        $data = [
            'status' => 'error',
            'code' => 400,
            'message' => 'Error sending message.',
            'params' => $json
        ];

        $validator = new DataValidator();
        $newParams = $validator->paramsValidator($params);

        // validate data
        if($newParams != null){

            // if validate success, create message
            $message = new Message();
            $message->setName($newParams->name);
            $message->setDescription($newParams->description);
            $message->setContact($newParams->contact);
            $message->setCreatedAt(new \DateTime('now'));
            $message->setStatus('pending');

            // save ms
            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();

            $em->persist($message);
            $em->flush();

            $data = [
                'status' => 'success',
                'code' => 200,
                'message_status' => 'Message sent succesfully.',
                'message' => $message
            ];  
        }   

        return $serializer->resjson($data);
    }

    public function remove(Request $request, $id = null, JwtAuth $jwt_auth, Serializer $serializer){
        // get auth
        $token = $request->headers->get('Authorization', null);
        
        // check is token is OK
        $authCheck = $jwt_auth->checkToken($token);

        if($authCheck){ 

            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();

            $message = $doctrine->getRepository(Message::class)->findOneBy([
                'id' => $id
            ]);
    
            if($message && is_object($message)){
                // remove message
                $em->remove($message);
                $em->flush();
    
                $data = [
                    'status' => 'success',
                    'code' => 200,
                    'message_status' => 'Message deleted succesfully.'
                ];
            }else{
                $data = [
                    
                    'status' => 'error',
                    'code' => 400,
                    'message_status' => 'Message doesnt exists.'
                ];
            }
        }else{
            $data = [
                'status' => 'error',
                'code' => 400,
                'message_status' => 'Token required.'
            ];
        }

        return $serializer->resjson($data);
    }

    public function messages(Request $request, Serializer $serializer, JwtAuth $jwt_auth, PaginatorInterface $paginator){
        // get auth
        $token = $request->headers->get('Authorization', null);
        
        // check if token is OK
        $authCheck = $jwt_auth->checkToken($token);

        if($authCheck){

            //start paginate
            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();

            // paginate params
            $page = $request->query->getInt('page', 1);
            $itemsPerPage = 5;

            //consult paginate
            $dql = "SELECT m FROM App\Entity\Message m ORDER BY m.id DESC";
            $query = $em->createQuery($dql);

            //invoke pagination
            $pagination = $paginator->paginate($query, $page, $itemsPerPage);
            $total = $pagination->getTotalItemCount();

            $data = [
                'status' => 'success',
                'code' => 200,
                'totel_items_count' => $total,
                'actual_page' => $page,
                'itemsPerPage' => $itemsPerPage,
                'total_pages' => ceil($total / $itemsPerPage),
                'messages' => $pagination
            ];

           
        }else{
            $data = [
                'status' => 'error',
                'code' => 400,
                'message_status' => 'Token required.'
            ]; 
        }


        return $serializer->resjson($data);
    }

    public function status($id = null, Request $request, JwtAuth $jwt_auth, Serializer $serializer){
        $token = $request->headers->get('Authorization', null);
        $authCheck = $jwt_auth->checkToken($token);

        if($authCheck){
            $em = $this->getDoctrine()->getManager();
            $message = $this->getDoctrine()->getRepository(Message::class)->findOneBy([
                'id' => $id
            ]);

            if($message && is_object($message)){
                $message->setStatus('OK');

                $em->persist($message);
                $em->flush();

                $data = [
                    'status' => 'success',
                    'code' => 200,
                    'message_status' => 'New closed message.',
                    'message' => $message
                ];
            }else{
                $data = [
                    'status' => 'error',
                    'code' => 400,
                    'message_status' => 'Message doesnt exists.'
                ]; 
            }
        }else{
            $data = [
                'status' => 'error',
                'code' => 400,
                'message_status' => 'Token required.'
            ]; 
        }

        return $serializer->resjson($data);
    }
}
