<?php
namespace Stephane888\Debug;

/**
 * Semble meme pas fonctionner.
 * outil de base pour gerer les sessions.
 * utilitaire basique.
 *
 * @author stephane
 *        
 */
class Session {

  function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      ini_set('session.cookie_samesite', 'None');
      ini_set('session.cookie_secure', 'true');
      session_start();
    }
  }

  function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  function get($key)
  {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
  }

  function all()
  {
    return $_SESSION;
  }
}