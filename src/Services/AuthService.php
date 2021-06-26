<?php

namespace App\Services;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpClient\HttpClient;

class AuthService
{
    private $token;
    private $user;
    private $tokenExpiration;

    function __construct(SessionInterface $session)
    {
        $this->token = $session->get('token');
        $this->user = $session->get('user');
        $this->tokenExpiration = $session->get('tokenExpiration');
        return $this;
    }

    public function checkToken(){
        if($this->token == ''){
            return false;
        }else{
            $dateNow = new \DateTime('now');
            $dateNowStamp = $dateNow->getTimestamp();
            if($dateNowStamp < $this->tokenExpiration){
                return true;
            }else{
                return false;
            }
        }
    }
    public function getTenant(){
        try {
            return $this->user["tenant"];
        } catch (\Exception $e) {
            return "";
        }
    }
    public function getUser(){
        try {
            return $this->user["id"];
        } catch (\Exception $e) {
            return "";
        }
    }
}