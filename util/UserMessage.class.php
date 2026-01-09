<?php

class UserMessage {

    const INF_FORM =
        array(
            'insert' => 'Data inserted successfully',
            'update' => 'Data updated successfully',
            'delete' => 'Data deleted successfully',
            'found'  => 'Data found',
            '' => ''
        );
    
    const ERR_FORM =
        array(
            'empty_username' => 'Username must be filled',
            'empty_password' => 'Password must be filled',
            'exists_id'      => 'Username already exists',
            'not_exists_id'  => 'Username not exists',
            'not_found'      => 'No data found',
            '' => ''
        );

    const ERR_DAO =
        array(
            'insert' => 'Error inserting data',
            'update' => 'Error updating data',
            'delete' => 'Error deleting data',
            '' => ''
        );
    
}
