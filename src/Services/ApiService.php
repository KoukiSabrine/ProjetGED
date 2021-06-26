<?php

namespace App\Services;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;

class ApiService
{
    private $baseUrlApi;
    private $token = "";
    private $user;

    public function __construct($api_srv, SessionInterface $session)
    {
        $this->baseUrlApi = $api_srv;
        $this->token = $session->get('token');
        $this->user = $session->get('user');
    }

    private function linkGenerator(String $methode, String $object, Array $parameters=null)
    {
        $fullLink = $this->baseUrlApi . '/' . $object;
        if($methode == 'GET'){
            $parameterFinal = $parameters;

            if(is_array($parameterFinal)) {
                $fullLink = $fullLink . '?';
                foreach ($parameterFinal as $key => $param){
                    $fullLink = $fullLink . $key . '=' . $param  . '&' ;
                }
              //  $fullLink = $fullLink . 'page=1';
            }
        }
        return $fullLink;
    }

    public function getFromApi(String $methode, String $object, Array $parameters)
    {
        $response = $this->sendToApi($methode, $object, $parameters);
        return json_decode($response);
    }

    public function postToApi(String $methode, String $object, Array $parameters)
    {
        $response = $this->sendToApi($methode, $object, $parameters);
        return json_decode($response);
    }

    private function sendToApi(String $methode, String $object, Array $parameters){
        if($object == "login_check" || $this->token == ''){
            $httpClient = HttpClient::create();
        }else{
            $httpClient = HttpClient::create(["auth_bearer" => $this->token]);
        }
        if($methode == 'GET'){
            if($object == "after_sale/create_voucher"){
                $link = $this->linkGenerator($methode, $object, null);
                $response = $httpClient->request($methode, $link, ['json' => $parameters]);    
            } else {
                $link = $this->linkGenerator($methode, $object, $parameters);
                $response = $httpClient->request($methode, $link);
            }
        }
        if($methode != 'GET'){
            $link = $this->linkGenerator($methode, $object, null);
            $response = $httpClient->request($methode, $link, ['json' => $parameters]);
        }

        if (401 == $response->getStatusCode()) {
            if ($object == "login_check") {
                $response = '{ "message" : "Login et mot de passe incorrect" }';
            }else{
                $response = '{ "message" : "Votre session a été expirée" }';
            }
        } else {
            if(count(explode("/", $object)) == 2 && (explode("/", $object)[0] == "products" || explode("/", $object)[0] == "third_parties" /*|| explode("/", $object)[0] == "users"*/) && is_numeric(explode("/", $object)[1])){
                $response = $response->getContent(false);
                if($this->user['tenant'] != json_decode($response)->tenant->code){
                    $response = null;
                }
            }else{
                $response = $response->getContent(false);
            }
        }
        return $response;
    }
}