<?php


namespace Repository;

use Doctrine\DBAL\Connection;

abstract class RepositoryAbstract
{
    /**
     *
     * @var Connection
     */
    protected $db;
    
    
    /**
     * 
     * RepositoryAbstract constructor
     * @param Connection $db
     */
    
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }
    
    public function persist(array $data, array $where = null)
    {
        if (empty($where)) {
            $this->db->insert(
                $this->getTable(),
                $data
            );
        } else {echo 'ici!';
            $this->db->update(
                $this->getTable(),
                $data,
                $where
            );
        }
    }
    
    
    /**
     * Retourne le nom de la table que traite le repository
     * 
     * @return string
     */
    abstract public function getTable();
}
