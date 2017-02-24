<?php

/**
 * @file
 * Definition of Drupal\toolbar_menu\Tests\ToolbarMenuFonctionalTest.
 */

namespace Drupal\toolbar_menu\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Functional tests for crop API.
 *
 * @group toolbar_menu
 */
class ToolbarMenuFonctionalTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['toolbar_menu', 'toolbar'];

  /**
   * Tests crop type crud pages.
   */
  public function testToolbarMenuCrud() {
    // Anonymous users don't have access to crop type admin pages.
    $this->drupalGet('admin/config/toolbar_menu/settings');
    $this->assertResponse(403, "Anonymous user is unauthorized to access settings page");

    // Add a new custom menu.
    $menu_name = 'test_menu';
    $label = 'Test Menu';

    $values = [
      'id' => $menu_name,
      'label' => $label,
      'description' => 'Description text',
    ];
    $menu = \Drupal::entityTypeManager()->getStorage('menu')->create($values);
    $menu->save();

    $adminUser = $this->drupalCreateUser(['administer toolbar menu', 'administer permissions', 'access toolbar']);

    // Can access pages if logged in and no crop types exist.
    $this->drupalLogin($adminUser);
    $this->drupalGet('admin/config/toolbar_menu/settings');
    $this->assertResponse(200, "User with 'administer toolbar menu' role is authorized to access settings page");

    $this->assertRaw($label, 'Custom menu was found in settings page.');

    $edit = array(
      'menus[' . $menu_name . '][weight]' => -50,
      'menus[' . $menu_name . '][active]' => 1,
    );
    $this->drupalPostForm('admin/config/toolbar_menu/settings', $edit, t('Save configuration'));

    $this->drupalGet('admin/people/permissions');

    // Enforce refresh caches.
    drupal_flush_all_caches();

    $rid = $this->drupalCreateRole(["view test_menu in toolbar"]);
    if (!$rid) {
      return FALSE;
    }
    $adminUser->addRole($rid);

    $this->checkPermissions(["view {$menu_name} in toolbar"]);

    $this->drupalGet('<front>');
    $this->assertRaw($label, 'Custom menu is viewed in toolbar');
  }

}
