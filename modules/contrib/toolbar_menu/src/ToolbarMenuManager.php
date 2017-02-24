<?php

/**
 * @file
 * Contains \Drupal\toolbar_menu\ToolbarMenuManager.
 */

namespace Drupal\toolbar_menu;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Implement a setting form for toolbar_menu module.
 */
class ToolbarMenuManager {

  use StringTranslationTrait;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The menu tree manager.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuTree;

  /**
   * The toolbar menu config.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $toolbarMenuSettings;

  /**
   * The toolbar_menu settings stored in configuration.
   *
   * @var array
   */
  protected $menuList;

  /**
   * Cache invalidator.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagsInvalidator;

  /**
   * The current account.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * Construct a new ToolbarMenu.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   *   The entity manager.
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_tree
   *    The menu link tree service.
   * @param array|\Drupal\Core\Config\ConfigFactoryInterface $toolbar_menu_settings
   *   The config factory service.
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cache
   *   The cache backend service.
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   The account service.
   */
  public function __construct(EntityTypeManagerInterface $entity_manager, MenuLinkTreeInterface $menu_tree, ConfigFactoryInterface $toolbar_menu_settings, CacheTagsInvalidatorInterface $cache, AccountProxyInterface $account) {
    $this->entityManager = $entity_manager;
    $this->menuTree = $menu_tree;
    $this->toolbarMenuSettings = $toolbar_menu_settings->getEditable('toolbar_menu.settings');
    $this->menuList = $this->toolbarMenuSettings->get('menus');
    $this->cacheTagsInvalidator = $cache;
    $this->account = $account->getAccount();
  }

  /**
   * Load Menu entities filtered by account permissions.
   *
   * @return array|\Drupal\system\Entity\Menu[]
   *   An array of menu entity objects indexed by their IDs.
   *   Returns an empty array if no matching entities are found.
   */
  public function getMenus() {
    $list = [];
    if ($this->menuList) {
      foreach ($this->menuList as $key => $menu) {
        if ($menu['active'] && ($this->account->hasPermission($this->getPermissionName($key) || $this->account->hasPermission('administer toolbar menu')))) {
          $list[] = $this->entityManager
            ->getStorage('menu')
            ->load($key);
        }
      }
    }
    return $list;
  }

  /**
   * Load enabled menus.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   An array of menu entity objects indexed by their ids.
   */
  public function getEnabledMenus() {
    $list = [];
    $default_weight = 0;
    foreach ($this->entityManager
      ->getStorage('menu')
      ->loadByProperties(['status' => TRUE]) as $menu) {
      $weight = $this->getWeight($menu->id());
      if (!$weight) {
        $weight = $default_weight++;
      }
      $list[$weight] = $menu;
    }
    ksort($list);
    return $list;
  }

  /**
   * Get the weight of a menu entry.
   *
   * @param string $menu_name
   *   The menu name.
   *
   * @return mixed
   *   The weight.
   */
  public function getWeight($menu_name) {
    return $this->menuList[$menu_name]['weight'];
  }

  /**
   * Get the toolbar menu status.
   *
   * @param string $menu_name
   *   The menu name.
   *
   * @return bool
   *   The toolbar menu status.
   */
  public function isActive($menu_name) {
    return $this->menuList[$menu_name]['active'];
  }

  /**
   * Save new toolbar menu settings.
   *
   * @param array $menu_list
   *   The new setting ready to save it.
   */
  public function saveSettings(array $menu_list) {
    $this->toolbarMenuSettings->set('menus', $menu_list);
    $this->toolbarMenuSettings->save();
    $this->cacheTagsInvalidator->invalidateTags(['toolbar_menu']);
  }

  /**
   * Helper to clean an ID.
   *
   * @param string $id
   *   The ID to clean.
   *
   * @return string
   *   The cleaned ID.
   */
  public function cleanId($id) {
    return preg_replace('/[^\p{L}\p{N}]/u', '-', $id);
  }

  /**
   * Load a menu tree.
   *
   * @param string $menu_name
   *   The name of the menu.
   *
   * @return \Drupal\Core\Menu\MenuLinkTreeElement[]
   *   A menu link tree.
   */
  public function loadTree($menu_name) {
    $parameters = new MenuTreeParameters();

    return $this->menuTree->load($menu_name, $parameters->setMaxDepth(2)
      ->onlyEnabledLinks()
      ->excludeRoot());
  }

  /**
   * Get the permission list.
   *
   * @return array
   *   The permission list.
   */
  public function getPermissionList() {
    $permissions = [];
    foreach ($this->getMenus() as $menu) {
      $permissions[$this->getPermissionName($menu->id())] = [
        'title' => $this->t('View the <em>@label</em> menu in the toolbar', ['@label' => $menu->label()]),
      ];
    }
    return $permissions;
  }

  /**
   * Get the formatted permission name.
   *
   * @param string $menu_name
   *   The name of the menu.
   *
   * @return string
   *   The name of the permission.
   */
  public function getPermissionName($menu_name) {
    return 'view ' . $menu_name . ' in toolbar';
  }

}
