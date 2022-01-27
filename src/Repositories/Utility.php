<?php

namespace Stephane888\Debug\Repositories;

class Utility {
  
  /**
   * Permet de retourner le chemin vers la home.
   */
  static public function getHomeDirectory() {
    /**
     * $_SERVER['HOME']est present sur certains serveurs.
     */
    if (!empty($_SERVER['HOME'])) {
      return $_SERVER['HOME'];
    }
    /**
     * $_SERVER['DOCUMENT_ROOT']est present sur tous les serveurs.
     * On par du principe que le dossier en dessous du premier dossier
     * ($_SERVER['DOCUMENT_ROOT']) est probrablement le bon.
     */
    elseif (!empty($_SERVER['DOCUMENT_ROOT'])) {
      $dossierName = explode("/", $_SERVER['DOCUMENT_ROOT']);
      if (count($dossierName) > 2) {
        return '/' . $dossierName['0'] . '/' . $dossierName['1'];
      }
    }
    throw new \Exception(" Impossible de determiner le dossier home ");
  }
  
}