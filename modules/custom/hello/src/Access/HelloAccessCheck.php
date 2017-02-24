<?php

namespace Drupal\hello\Access;

use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class HelloAccessCheck implements AccessCheckInterface{

  public function applies(Route $route)
  {
    return NULL;
  }

  public function access(Route $route, Request $request = NULL, AccountInterface $account){
    if((time()- $account->getAccount()->created)/3600 >= $route->getRequirement('_olduser_access') && $account->isAuthenticated()){
      return AccessResult::allowed();
    }else {
      return AccessResult::forbidden();
    }
  }
}
