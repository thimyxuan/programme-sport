<?php

// SERVICE : c'est un peu le dossier fourre-tout

namespace Service;

use Entity\Membre;
use Symfony\Component\HttpFoundation\Session\Session;

class UserManager 
{
    /**
     *
     * @var Session 
     */
    private $session;
    
    /**
     * UserManager construtor
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session= $session;
    }
    
    /**
     * 
     * @param string $plainPassword
     * @return string
     */
    public function encodePassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }
    
    
    /**
     * 
     * @param string $plainPassword
     * @param string $encodePassword
     * @return bool
     */
    public function verifyPassword($plainPassword, $encodePassword)
    {
        return password_verify($plainPassword, $encodePassword);
    }
    
    /**
     * 
     * @param Membre $membre
     */
    public function login(Membre $membre)
    {
        $this->session->set('membre', $membre);
    }
    
    public function logout()
    {
        $this->session->remove('membre');
    }
    
    /**
     * 
     * @return User|null
     */
    public function getUser()
    {
        return $this->session->get('membre');
    }
    
    
    /**
     * 
     * @return string
     */
    /*public function getUserName()
    {
        if($this->session->has('membre'))
        {
            return $this->session->get('membre')->getFullName();
        }
        return '';
    }*/
    
    public function isAdmin()
    {
        return $this->session->has('membre') && $this->session->get('membre')->isAdmin();
    }
    
}
