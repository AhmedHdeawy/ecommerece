<?php


function lang($phrase){

    static $lang = array(

        // Home Page  Words
        "رساله" => "مرحبا ياحاج",

        // dashboard Page Words
        'Home-Admin' => 'الرئيسيه',
        'Categories' => 'التصنيفات',
        'Items' => 'العناصر',
        'Members' => 'الأعضاء',
        'Statistics' => 'احصائيات الموقع',
        'Logs' => 'التسجيلات',
        'Edit Profile' => 'تعديل الملف الشخصي',
        'Setting' => 'الاعدادات',
        'Log out' => 'تسجيل الخروج',
        '' => '',

    );

    return $lang[$phrase];
}