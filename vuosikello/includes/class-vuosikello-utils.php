<?php
  class Vuosikello_Utils {

    const COMMON_GROUP_NAME = 'Yhteinen';

    public static function create_capabilities($caps) {
      foreach($caps as $cap) {
        $cap_id = Groups_Capability::create(array('capability' => $cap));
        writeText("Created $cap_id");
      }
    }

    public static function remove_capabilities($caps) {
      foreach($caps as $cap) {
        $cap_id = Groups_Capability::read_by_capability($cap);
        $succ = Groups_Capability::delete($cap_id);
        writeText("Deleted $cap : $succ");
      }
    }

    public static function create_categories($cat_arr) {

    }

    public static function create_common_group()
    {
      Groups_Group::create(array('name' => Vuosikello_Utils::COMMON_GROUP_NAME, 'description' => 'A group for shared events.'));
    }

    public static function create_capability_relations($group_id, $caps) {
      foreach($caps as $cap) {
        $cap_id = Groups_Capability::read_by_capability($cap)->capability_id;
        $succ = Groups_Group_Capability::create(array('group_id' => $group_id, 'capability_id' => $cap_id));
        writeText("Combined $group_id with $cap_id");
      }
    }

    public static function create_vk_user_group($daycare) {
      $caps = array('read_vk_event');
      $common_group = Groups_Group::read_by_name(Vuosikello_Utils::COMMON_GROUP_NAME)->group_id;
      $group_id = Groups_Group::create(array('name' => $daycare."User", 'description' => "User for $daycare.", 'parent_id' => $common_group));
      if($group_id) {
        Vuosikello_Utils::create_capability_relations($group_id, $caps);
        return $group_id;
      }
      return -1;
    }

    public static function create_vk_moderator_group($daycare) {
      $caps = array('edit_vk_event','read_vk_event','delete_vk_events','edit_vk_events','publish_vk_events','create_vk_events', 'upload_files', 'edit_posts');
      $group_id = Groups_Group::create(array('name' => $daycare."Mod", 'description' => "Moderator for $daycare."));
      if($group_id) {
        Vuosikello_Utils::create_capability_relations($group_id, $caps);
        return $group_id;
      }
      return -1;
    }

    public static function create_daycare($daycare) {
      $user_group_id = self::create_vk_user_group($daycare);
      $mod_group_id = self::create_vk_moderator_group($daycare);
      $post_id = wp_insert_post(array(
        'post_content' => '',
        'post_title' => $daycare,
        'post_type' => 'vuosikello_post',
        'post_status' => 'publish'
      ));
      if($post_id) {
        Groups_Post_Access::create(array('post_id' => $post_id, 'group_id' => $user_group_id));
        Groups_Post_Access::create(array('post_id' => $post_id, 'group_id' => $mod_group_id));
      }
    }

    public static function add_vk_user($daycare, $user_id) {
      $group_id = Groups_Group::read_by_name($daycare."User")->group_id;
      $succ = Groups_User_Group::create(array('group_id' => $group_id, 'user_id' => $user_id));
      return $succ;
    }

    public static function add_vk_moderator($daycare, $user_id) {
      $group_id = Groups_Group::read_by_name($daycare."Mod")->group_id;
      $succ = Groups_User_Group::create(array('group_id' => $group_id, 'user_id' => $user_id));
      Vuosikello_Utils::add_vk_user($daycare, $user_id);
      return $succ;
    }

    public static function get_vuosikello_permalink() {
      $daycare_permalink="";
      $posts_arr = get_posts(array('post_type' => 'vuosikello_post', 'post_status' => 'publish', 'posts_per_page' => -1));
      foreach($posts_arr as $post) {
        if(Groups_Post_Access::user_can_read_post($post->ID)) {
          $daycare_permalink = get_permalink($post->ID);
          break;
        }
      }
      return $daycare_permalink;
    }

  }
?>
