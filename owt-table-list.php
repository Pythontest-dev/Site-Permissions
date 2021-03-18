<?php
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
class siteperm_OWTTableList extends WP_List_Table {
    // define data set for WP_List_Table => data
    // prepair_items
    public function prepair_items($data){
        
        $this->items = $data;
        $columns = $this->get_columns();
        $this->_column_headers=array($columns);
    }
    // get_columns
    public function get_columns(){
        $columns = array(
            "id" => "ID",
            "name" => "Name",
            "edit" => "Edit"
        );
        return $columns;
    }
    // column_default
    public function column_default($item, $column_name){
        switch($column_name){
            case 'id':
            case 'name':
            case 'edit':
                return $item[$column_name];
            default:
                return "no value";
        }
    }
}
function siteperm_owt_show_date_list_table($owt_table,$data)
{
    

    //calling prepare_items from class
    $owt_table->prepair_items($data);
     echo '<h3>Sites:</h3>';

    $owt_table->display();
}
class siteperm_OWTTableListKlient extends WP_List_Table {
    // define data set for WP_List_Table => data
    // prepair_items
    public function prepair_items($data){
        
        $this->items = $data;
        $columns = $this->get_columns();
        $this->_column_headers=array($columns);
    }
    // get_columns
    public function get_columns(){
        $columns = array(
            "name" => "Name",
            "action" => "Action"
        );
        return $columns;
    }
    // column_default
    public function column_default($item, $column_name){
        switch($column_name){
            case 'name':
            case 'action':
                return $item[$column_name];
            default:
                return "no value";
        }
    }
}
function siteperm_owt_show_date_list_table_klient($owt_table,$data)
{
    

    //calling prepare_items from class
    $owt_table->prepair_items($data);
     echo '<h3>Sites:</h3>';

     $owt_table->display();
}
?>