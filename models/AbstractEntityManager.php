<?php

/**
 * Classe abstraite qui représente un manager. Elle récupère automatiquement le gestionnaire de base de données. 
 */
abstract class AbstractEntityManager {
    
    protected $db;


    /**
     * Tableau des directions autorisées pour les requêtes SQL.
     * @var array
     */
    protected $autorisedDirection = ['ASC', 'DESC'];

    /**
     * Tableau des colonnes autorisées pour les requêtes SQL.
     * @var array
     */
    protected $autorisedOrderColumns = [];

    /**
     * Constructeur de la classe.
     * Il récupère automatiquement l'instance de DBManager. 
     */
    public function __construct() 
    {
        $this->db = DBManager::getInstance();
    }

    protected function checkOrder(?string $column, string $direction) : string
    {
       
        if ($column == null) {
            return "";
        }
        if (!array_key_exists($column, $this->autorisedOrderColumns)) {
            throw new Exception("La colonne $column n'est pas autorisée.");
        }
        if (!in_array($direction, $this->autorisedDirection)) {
            throw new Exception("La direction $direction n'est pas autorisée.");
        }
        $orderByField = $this->autorisedOrderColumns[$column];
        return " ORDER BY $orderByField $direction";
    }
}