<?php
require_once 'function.php';

 //panele

 //panel stron
 function sp_my_plugin_options() {
    if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __(esc_html( 'You do not have sufficient permissions to access this page.' )) );
 }
 echo '<div>';
 echo '<h1>Site Perm - Strony</h1>';
    $pages = get_pages();
    $users = get_users();
    if(current_user_can( 'manage_options' ))
        {
    if(isset($_POST['id']))
    {
        
        $id = sanitize_text_field($_POST['id']);
        $name = sanitize_text_field($_POST['name']);
        $roles = sp_wp_roles_array();
        global $wp_roles;
        $code = "";
        $id_block = get_post_meta($id, "block", true);
        if(!isset($id_block) OR empty($id_block) ) {
            $id_block = '0';
            update_post_meta($id, "block", $id_block);
        }
        $show = get_post_meta($id, "show", true);
        if($show==null) {
            $show = '1';
            
            update_post_meta($id, "show", $show);
        }
        echo "<form action='admin.php?page=sp_site-perm' method=Post><h2>you are editing the page ".esc_html($name)." </h2><h4>kod: </h4><input value=".esc_html($id_block)." name=id_block id=id><br><input type=hidden value=$id name=id_ok><br><h2>Roles:</h2>";
 
        
        for($i=0;$i<count($roles);$i++)
        {
            $namet = $roles[$i];
            $name = $namet['role'];
            $text = '';
            if($id_block == "0")
            {
                $code .= "$name;0|";
            }
            else
            {
                $tmpp = explode('|',$id_block);
                for($y = 0;$y<count($tmpp)-1;$y++)
                {
                    $tmp1 = explode(';',$tmpp[$y]);
                        if($tmp1[0]==$name)
                        {
                            if($tmp1[1]=="1")
                            {
                                $text='checked';
                            }
                        }
                }
                $code = $id_block;
            }
             $tmp = "change('$name')";
             
         
            echo"<div><input type='checkbox' id='scales' name='scales' onchange=change('".esc_js($name)."',this.checked) ".esc_js($text)."><label for='scales'>".esc_html($name)."</label></div>";
        }
        if(sp_IsKPanel())
        {
            echo "<input type=hidden value=1 name=cancheckpanel>";
            if($show=='1')
            {
                echo"<br><h2 style='display:inline-block;margin-right:8px;'>Whether to display in the panel</h2><input type='checkbox' name=showinpanel id='showpanel'></input><script>document.getElementById('showpanel').checked=true</script>";
            }
            else
            {
                echo"<br><h2 style='display:inline-block;margin-right:8px;'>Whether to display in the panel</h2><input type='checkbox' name=showinpanel ></input>";
            }
        }
        echo "<br><input type=submit value=send class='button button-primary'></form>";
        echo '<script>var code=document.getElementById("id");code.value="'.esc_js($code).'"</script>';
        echo '
        <script>function change(name, chech){var tmp = ""
         tmp1=code.value.split("|")
         if(chech == true)
         {
         for(i=0;i<tmp1["length"]-1;i++){
         tmp2=tmp1[i].split(";")
         if(tmp2[0]==name){
         tmp=tmp+tmp2[0]+";1|"
         }else
         {tmp=tmp+tmp1[i]+"|"
         }
         }
     }
     else
     {
         for(i=0;i<tmp1["length"]-1;i++){
             tmp2=tmp1[i].split(";")
             if(tmp2[0]==name){
             tmp=tmp+tmp2[0]+";0|"
             }else
             {tmp=tmp+tmp1[i]+"|"
             }
             }
     }
         code.value=tmp}
     </script>
    ';
 
}
    elseif (isset($_POST['id_block']))
    {
         $id_block = sanitize_text_field($_POST['id_block']);
         
         $id = sanitize_text_field($_POST['id_ok']);
         update_post_meta($id, "block", $id_block);
         if(isset($_POST['cancheckpanel']))
         {
             if(!isset($_POST['showinpanel']))
             {
                update_post_meta($id, "show", '0');
             }
             else
             {
                update_post_meta($id, "show", '1');
             }
         }
         echo "<h1><p>Updated :)!</p></h1>";
 
    }
    else
    {
        if(current_user_can( 'manage_options' ))
        {
     $array = array();
  
    for($i=0;$i<count($pages);$i++)
    {
     $name = $pages[$i]->post_title;
     $id = $pages[$i]->ID;
     array_push($array,array("id"=>$id, "name"=>esc_html($name),"edit"=>"<form action='admin.php?page=sp_site-perm' method='POST'><input type=hidden name=id value=".esc_html($id)."><input type=hidden name=name value='".esc_html($name)."'><input type=submit value='edit' class='button button-primary'></form>"));
    }
    $table = new sp_OWTTableList();
     sp_owt_show_date_list_table($table, $array);
}
    }
}
 
 
 }
 // panel ustawień
 function sp_seet_site_perm()
{
    if(current_user_can( 'manage_options' ))
    {
    if(isset($_POST['on']))
    {
        $on = sanitize_text_field($_POST['on']);
        $roles = sanitize_text_field($_POST['code']);
        $result = sp_DBP_Tb_read("0");
        $value = sp_DBP_tb_write("0",$on);
        if($value == "ERROR")
        {
        wp_die( __(  sp_show_admin_warning('<strong>'.__('Sorry, an error has occurred', 'Site-Perm').'</strong>', 'error') ));
        }
        $value = sp_DBP_tb_write("1",$roles);
        if($value == "ERROR")
        {
        wp_die( __( sp_show_admin_warning('<strong>'.__('Sorry, an error has occurred', 'Site-Perm').'</strong>', 'error') ) );
        }
        echo '<div class="updated notice"><p><strong>Data saved!</strong></p></div>';
    }
    else
    {
    echo "<h1>Settings:</h1>";
    $roles = sp_wp_roles_array();
    global $wp_roles;
    $value = sp_DBP_tb_read("1");
    if(!$value)
    {
        $value = "0";
    }
    else if($value == "ERROR")
    {
         sp_show_admin_warning('<strong>'.__('Sorry, an error has occurred', 'Site-Perm').'</strong>', 'error');
    }
    $or_on = sp_DBP_tb_read("0");
    if(!$or_on)
    {
        $or_on = "off";
    }
    else if($value == "ERROR")
    {
        wp_die( __( sp_show_admin_warning('<strong>'.__('Sorry, an error has occurred', 'Site-Perm').'</strong>', 'error') ) );
    }
    
    echo "<form action=admin.php?page=sp_seet-site-perm method=post>";
    echo "<input type=hidden name=code id=code_site_perm value=".esc_html($value)." />";
    echo "<h2>Select whether the client panel is enabled:</h2>";
    if($or_on == 'off')
    {
        echo "<input type='radio' name='on' value='off' onchange=test(this) id=on checked>On</input><br><input type='radio' name='on' value='on' onchange=test(this)>on</input>";
    }
    else
    {
        echo "<input type='radio' name='on' value='off' onchange=test(this) id=on>Off</input><br><input type='radio' name='on' value='on' onchange=test(this) checked>on</input>";
    }
    if($or_on == 'off')
        echo "<div id='panel' style='display: none;'>";
    else
        echo "<div id='panel' style=''>";
    echo "<h3>Select which roles have access to the customer panel:</h3>";
    echo "<h2>Role:</h2>";
    $code = $value;
    $type=false;
    for($i=0;$i<count($roles);$i++)
    {
        $text='';
        $namet = $roles[$i];
        $name = $namet['role'];
         $tmp = "change('$name')";
         if($value == '0')
         {
             if($code == '0')
            {
                $code = '';
                $type=true;
            }
            if($type)
            {
                $code .= "$name;0|";
            }
            
            
         }
         $tmpp = explode('|',$value);
         for($y = 0;$y<count($tmpp)-1;$y++)
            {
                $tmp1 = explode(';',$tmpp[$y]);
                    if($tmp1[0]==$name)
                    {
                        if($tmp1[1]=="1")
                        {
                            $text='checked';
                        }
                    }
            }
        
            echo "<div><input type='checkbox' id='scales' name='scales' onchange=change('".esc_js($name)."',this.checked) $text><label for='scales' >".esc_html($name)."</label></div>";

       
    }
    echo '<script>var codee=document.getElementById("code_site_perm");codee.value="'.esc_js($code).'"</script>';
    echo "</div><br>";
    echo "<br><input type=submit value='Save changes' class='button button-primary'>";
    echo "</form>";
    echo "<script src='".plugin_dir_url(__FILE__)."seet.js'></script>";
}
}
}
function sp_Kpanelset()
{
    $pages = get_pages();
    $array = array();
    for($i=0;$i<count($pages);$i++)
    {
     $name = $pages[$i]->post_title;
     $id = $pages[$i]->ID;
     $link = $pages[$i]->guid;
     $show = get_post_meta($id, "show", true);
     if($show==null) {
         $show = '1';
         update_post_meta($id, "show", $show);
     }
     if($show=='1')
     {
     if(haveAccess($id)){
        array_push($array,array("name"=>esc_html($name),"action"=>"<a href=$link><input type=button value='View' class='button button-primary'></a>"));
    }
}
    }
    $table = new sp_OWTTableListKlient();
    owt_show_date_list_table_klient($table, $array);
}
function sp_analytics()
{if(current_user_can( 'manage_options' ))
    {
    wp_die(esc_html("this panel is not ready yet"));
    /*
    $pages = get_pages();
    $users = get_users();
    $array = array();
  
    for($i=0;$i<count($pages);$i++)
    {
     $name = $pages[$i]->post_title;
     $id = $pages[$i]->ID;

        array_push($array,array("id"=>$id, "name"=>$name,"edit"=>"<form action='admin.php?page=site-perm' method='POST'><input type=hidden name=id value=$id><input type=hidden name=name value='$name'><input type=submit value='Zobacz' class='button button-primary'></form>"));
     
    }
    $table = new OWTTableList();
     owt_show_date_list_table($table, $array);
     */
}
}
    
?>