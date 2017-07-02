<?php

function lang($phrase){

    static $lang = array(

        // Home Page  Words
        "message" => "Welcome",

        // dashboard Page Words
        'Home-Admin'      =>   'Home',
        'Categories'      =>   'Categories',
        'Items'           =>   'Items',
        'Members'         =>   'Members',
        'Comments'        =>   'Comments',
        'Statistics'      =>   'Statistics',
        'Logs'            =>   'Logs',
        'Edit Profile'    =>   'Edit Profile',
        'Setting'         =>   'Setting',
        'Log out'         =>   'Log out',


    );

    return $lang[$phrase];
}