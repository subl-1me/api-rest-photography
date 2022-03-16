<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Photo;

use App\Services\DataValidator;
use App\Services\JwtAuth;
use App\Services\Serializer;
use App\Services\FileUploader;
use App\Services\ResponseHandler;




class ImageController extends AbstractController
{

     public function upload(Request $request, JwtAuth $jwt_auth, Serializer $serializer, FileUploader $fileUploader)
     {

        $response = new ResponseHandler();
        $error = false;

        $token = $request->headers->get('Authorization', null);
        $authCheck = $jwt_auth->checkToken($token);

        if(!$authCheck){
            $data = $response->errorResponse(401, 'Authenticating failed.');
            $error = true;
        }

        $json = $request->get('json', null);
        $params = json_decode($json);

        if($params == null){
            $data = $response->errorResponse(400, 'Parameters are required.');
            $error = true;
        }

        if(!$error){
            $validator = new DataValidator();
            $newParams = $validator->paramsValidator($params);

            // If url is not provided, then there is a file
            if (!isset($newParams->url)) {
                $file = $request->files->get('file0', null);
                if ($file && $validator->isImage($file)) {
                    $fileName = $fileUploader->upload($file);
                }else{
                    $data = $response->errorResponse(400, 'A valid file is required.');
                    $fileName = null;
                }
            } else {
                $fileName = 'from-web';
            }

            if ($fileName != null) {
    
                $photo = new Photo();
                $photo->setName($fileName);
                $photo->setTitle($newParams->title);
                $photo->setDescription($newParams->description);
                $photo->setCreatedAt(new \DateTime('now'));
    
                if (isset($newParams->url)) {
                    $photo->setUrl($newParams->url);
                }
    
                // save photo
                $doctrine = $this->getDoctrine();
                $em = $doctrine->getManager();
                $em->persist($photo);
                $em->flush();

                $data = $response->successResponse(200, $photo);
            }   
        }
        
        return $serializer->resjson($data);
    }

    public function edit(Request $request, JwtAuth $jwt_auth, Serializer $serializer, FileUploader $fileUploader)
    {
        $response = new ResponseHandler();
        $error = false;

        $token = $request->headers->get('Authorization', null);
        $authCheck = $jwt_auth->checkToken($token);

        if(!$authCheck){
            $data = $response->errorResponse(401, 'Authenticating failed.');
            $error = true;
        }

        $json = $request->get('json', null);
        $params = json_decode($json);

        if($params == null){
            $data = $response->errorResponse(400, 'Parameters are required.');
            $error = true;
        }

        if(!$error){
            // get image id
            $photoId = $request->get('id', null);
            if($photoId){
                $photo = $this->getDoctrine()->getRepository(Photo::class)->findOneBy([
                    'id' => $photoId
                ]);
                if($photo){

                    $validator = new DataValidator();
                    $newParams = $validator->paramsValidator($params);
                    $photo->setUrl(null);
                    ///dd($photo);

                    if(!isset($newParams->url)){ // Gonna change image for url
                        $file = $request->files->get('file0', null);
                        if($file){
                            $fileName = $fileUploader->upload($file);
                        }
                    }else{
                        $fileName = 'from-web';
                        $photo->setUrl($newParams->url);
                    } 

                    if($newParams != null && $fileName != null){
                        $photo->setTitle($newParams->title);
                        $photo->setDescription($newParams->description);
                        $photo->setName($fileName);

                        
                        // save photo
                        $doctrine = $this->getDoctrine();
                        $em = $doctrine->getManager();

                        $em->persist($photo);
                        $em->flush();

                        $data = [
                            'status' => 'success',
                            'code' => 200,
                            'photo' => $photo
                        ];
                    }
                }
            }
        }

        return $serializer->resjson($data);
    }

    public function list(Serializer $serializer, PaginatorInterface $paginator, Request $request){

        $em = $this->getDoctrine()->getManager();

        // consult to paginate
        $dql = "SELECT p FROM App\Entity\Photo p ORDER BY p.id DESC";
        $query = $em->createQuery($dql);

        // page param
        $page = $request->query->getInt('page', 1);
        $itemsPerPage = 6;



        // invoke pagination
        $pagination = $paginator->paginate($query, $page, $itemsPerPage);
        $total = $pagination->getTotalItemCount();

        $paginator = [
            'totalItemsCount' => $total,
            'actualPage' => $page,
            'itemsPerPage' => $itemsPerPage,
            'totalPages' => ceil($total / $itemsPerPage)
        ];

        $data = [
            'paginator' => $paginator,
            'photos' => $pagination
        ];

        return $serializer->resjson($data);
    }

    public function detail(Request $request, Serializer $serializer)
    {
        $response = new ResponseHandler();
        $photoId = $request->get('id', null);

        if($photoId){
            $photo = $this->getDoctrine()->getRepository(Photo::class)->findOneBy([
                'id' => $photoId
            ]);

            if($photo){
                $data = $response->successResponse(200, $photo);
            }else{
                $data = $response->errorResponse(400, 'Photo doesnt exists');
            }
        }

        return $serializer->resjson($data);
    }

    public function remove(Request $request, Serializer $serializer, JwtAuth $jwt_auth){

        $token = $request->headers->get('Authorization', null);
        $authCheck = $jwt_auth->checkToken($token);

        $photoId = $request->get('id', null);

        if($authCheck){
            if($photoId){
                $photo = $this->getDoctrine()->getRepository(Photo::class)->findOneBy([
                    'id' => $photoId
                ]);

                if($photo){
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($photo);
                    $em->flush();

                    $data = [
                        'status' => 'success',
                        'code' => 200,
                        'message_status' => 'Photo was deleted successfully.'
                    ];
                }else{
                    $data = [
                        'status' => 'error',
                        'code' => 400,
                        'message_status' => 'Photo doesnt exists.'
                    ];
                }
            }
           
        }else{
            $data = [
                'status' => 'error',
                'code' => 400,
                'message_status' => 'User token required.'
            ];
        }
        
        return $serializer->resjson($data);
    }
}
