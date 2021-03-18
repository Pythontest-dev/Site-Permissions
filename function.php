<?php

// Funkcja do zczytywania meta danych wtyczki
function siteperm_DBP_tb_read($id)
{
    global $wpdb;
    $id=sanitize_sql_orderby($id);
    $conn = new mysqli($wpdb->dbhost,$wpdb->dbuser,$wpdb->dbpassword, $wpdb->dbname);
    $DPB_tb_name=sanitize_sql_orderby($wpdb->prefix."perm_site_seetings");
    $DPB_query = "Select * From $DPB_tb_name Where `key_id`='$id';";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    $result = $conn->query($DPB_query);
    if(!$result)
    {
        return "ERROR";
    }
    $rows=$result->num_rows;
    if($rows == 0)
    {
        $return = false;
    }
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $return = $row['value'];    

    return $return;
}
// funkcja do tworzenia tabeli

function siteperm_wp_roles_array() {
    $editable_roles = get_editable_roles();
    foreach ($editable_roles as $role => $details) {
        $sub['role'] = esc_attr($role);
        $sub['name'] = translate_user_role($details['name']);
        $roles[] = $sub;
    }
    return $roles;
}
//funkcja blokowania i zezwalania na strony
function siteperm_block( $content ) {

    global $post;
    $id_block = get_post_meta($post->ID, "block", true);
    $a_user = wp_get_current_user();
    $count_r = $a_user->roles;
    $accept = false;
    if(!isset($id_block) OR empty($id_block) ) {
     $id_block = 0;
     update_post_meta($post->ID, "block", $id_block);
  }
  $tmp = explode('|',$id_block);

  for($i = 0;$i<count($tmp);$i++)
  {
      $tmp1 = explode(';',$tmp[$i]);
      for($y=0;$y<count($count_r);$y++)
      {
          if($tmp1[0]==$count_r[$y])
          {
             if($tmp1[1]=="1")
             {
                 $accept=true;
             }
          }
      }
  }
  if($id_block == "0")
  {
     $accept = true;
  }
    if(!$accept)
    {
     wp_die( __( 'Sorry, you are not allowed to access this page.' ) );
    }
 }
 function siteperm_DBP_tb_write($key,$value)
 {
    global $wpdb;
    $key=sanitize_sql_orderby($key);
    $conn = new mysqli($wpdb->dbhost,$wpdb->dbuser,$wpdb->dbpassword, $wpdb->dbname);
    $DPB_tb_name=sanitize_sql_orderby($wpdb->prefix."perm_site_seetings");
    $test = siteperm_DBP_tb_read($key);
    if($test == "ERROR")
    {
        return 'ERROR';
    }
     if(!$test)
     {
        $DBP_query="INSERT INTO `$DPB_tb_name` (`key_id`, `value`) VALUES ('$key', '$value');";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
        $conn->query($DBP_query);
     }
     else
     {
        $DBP_query="UPDATE `$DPB_tb_name` SET `value` = '$value' WHERE `$DPB_tb_name`.`key_id` = '$key'";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
        $conn->query($DBP_query);
     }


 }
function siteperm_addPluginSettingsLinkk($links) {
    if (!is_network_admin()) {
        $link = '<a href="admin.php?page=sp_site-perm">'.__('Site Permissions', 'Site Permission').'</a>';
        array_unshift($links, $link);
    } 

    $link2 = '<a href="admin.php?page=sp_seet-site-perm">'.__('Settings', 'Site Permission').'</a>';
    array_unshift($links, $link2);

    return $links;
}
function siteperm_show_admin_warning($message, $class = "updated") {
    return '<div class="tfamessage '.$class.'">'."<p>$message</p></div>";
}
function siteperm_TestKPanel()
{
    $id_block = siteperm_DBP_tb_read("1");
    if(!$id_block)
        return false;
    $a_user = wp_get_current_user();
    $count_r = $a_user->roles;
    $accept = false;
  $tmp = explode('|',$id_block);

  for($i = 0;$i<count($tmp);$i++)
  {
      $tmp1 = explode(';',$tmp[$i]);
      for($y=0;$y<count($count_r);$y++)
      {
          if($tmp1[0]==$count_r[$y])
          {
             if($tmp1[1]=="1")
             {
                 $accept=true;
             }
          }
      }
  }
  return $accept;
}
function siteperm_IsKPanel()
{
    $result = siteperm_DBP_tb_read("0");
    if($result == "off")
    {
        return false;
    }else{return true;}
}
function siteperm_haveAccess($id)
{
    $id_block = get_post_meta($id, "block", true);
    $a_user = wp_get_current_user();
    $count_r = $a_user->roles;
    $accept = false;
    if(!isset($id_block) OR empty($id_block) ) {
     $id_block = 0;
     update_post_meta($id_block, "block", $id_block);
  }
  $tmp = explode('|',$id_block);

  for($i = 0;$i<count($tmp);$i++)
  {
      $tmp1 = explode(';',$tmp[$i]);
      for($y=0;$y<count($count_r);$y++)
      {
          if($tmp1[0]==$count_r[$y])
          {
             if($tmp1[1]=="1")
             {
                 $accept=true;
             }
          }
      }
  }
  if($id_block == "0")
  {
     $accept = true;
  }
  return $accept;
}
?>