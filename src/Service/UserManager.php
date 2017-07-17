<?php

// SERVICE : c'est un peu le dossier fourre-tout

namespace Service;

use Entity\User;
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
     * @param User $user
     */
    public function login(User $user)
    {
        $this->session->set('user', $user);
    }
    
    public function logout()
    {
        $this->session->remove('user');
    }
    
    /**
     * 
     * @return User|null
     */
    public function getUser()
    {
        return $this->session->get('user');
    }
    
    
    /**
     * 
     * @return string
     */
    public function getUserName()
    {
        if($this->session->has('user'))
        {
            return $this->session->get('user')->getFullName();
        }
        return '';
    }
    
    public function isAdmin()
    {
        return $this->session->has('user') && $this->session->get('user')->isAdmin();
    }
    
}
