<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$d_address = array(
    'Kansas, Illinois',
    'Texas, South Courtland Circle',
    'Illinois, 8012 Main',
    'Kansas, South Applegate Drive',
    'Texas, Berkshire Avenue ',
    'Massachusetts, Lynn Street',
    'New York, Settlers Lane',
    'North Dakota, Meadow',
    'Michigan, Locust Street',
    'Arkansas, Rosebud Avenue',
    'Florida, Crockett Lane',
    'Missouri, Oak Lane',
    'Texas, Clair Street',
    'Pennsylvania, Platinum Drive',
    'Michigan, Railroad Streete',
    'Texas, Grey Fox',
    'Oklahoma, Ottis Street',
    'New Jersey, Charla Lane',
    'New York, Browning Lane',
    'Ohio, Glen Lane',
    'California, Dennison Street',
    'Louisiana, Norma Lane',
    'New Jersey, Duke Lane',
    'Kentucky, Cerullo Road',
    'Georgia, Pine Garden Lane',
    'Indiana, Bernardo Street',
    'California, Renzelli Boulevard',
    'Wisconsin, Comfort Court',
    'New York, Pen Road',
    'Mississippi, House Road',
    'Florida, Arrowood Drive',
    'New Jersey, Williams Mine',
    'Massachusetts, Smith Street',
    'California, Brown Bear Drive',
    );

$d_titles = array(
    'Black Glass House',
    'Retro House',
    'Ozalj Apartment',
    'Beauty Estate',
    'Retro Old',
    'Small Beauty',
    'Classic Americano',
    'Fear Wood',
    'Round towers',
    'Yellow Wood',
    'Sky Apartment',
    'Lux Villa',
    'Premium Hotel',
    'Spa restaurant',
    'Lux House',
    'Green Land',
    'Eco Farm',
    'Bio Glass',
    'Plastic Evo',
    'Red Willage',
    'Dream Wings',
    'Amazing Apartment',
    'Green Evo',
    'Eaton Road',
    'Lonsdale Place',
    'Elysian Apartments',
    'Atrium House',
    'Princes Place Court',
    'Palace Square',
    'Lillie Wharf ',
    'Thurloe Gate',
    'Wood House',
    );

$d_descriptions = array(
    'It comes with light and a nice balcony view, features free Wi-Fi and air conditioning. Also comes with a computer.',
    'in the peaceful neighborhood of Becka Ulica. Every room in this hotel is air-conditioned and features a flat-screen TV with cable channels.',
    'It has nice lighting, a wonderful balcony view, features free Wi-Fi, comes with air-conditioning, and a computer is provided.',
    'It has a nice balcony view, features free Wi-Fi, comes with air-conditioning, and a computer is provided.'
    );



$d_categories = array(
                array('title'=>'Apartment', 'marker_icon_filename'=>'apartment.png', 'font_icon_code'=>'fa fa-building', 'featured_image_filename'=>'apartment.jpg', 'hidden_fields_list'=>""), 
                array('title'=>'House', 'marker_icon_filename'=>'house.png', 'font_icon_code'=>'la la-home', 'featured_image_filename'=>'house.jpg', 'hidden_fields_list'=>""), 
                array('title'=>'Commercial', 'marker_icon_filename'=>'commercial.png', 'font_icon_code'=>'fa fa-usd', 'featured_image_filename'=>'pool.jpg', 'hidden_fields_list'=>""),  
                array('title'=>'Restaurant', 'marker_icon_filename'=>'restaurants.png', 'font_icon_code'=>'fa fa-cutlery', 'featured_image_filename'=>'novikov.jpg', 'parent_id'=>3, 'level'=>2, 'hidden_fields_list'=>""), 
                array('title'=>'Bakery', 'marker_icon_filename'=>'bakery.png', 'font_icon_code'=>'fa fa-cloud', 'featured_image_filename'=>'bakery.jpg', 'parent_id'=>3, 'level'=>2, 'hidden_fields_list'=>""),  
                array('title'=>'Shop', 'marker_icon_filename'=>'shop.png', 'font_icon_code'=>'fa fa-shopping-basket', 'featured_image_filename'=>'shop.jpg', 'parent_id'=>3, 'level'=>2, 'hidden_fields_list'=>""),  
                array('title'=>'Land', 'marker_icon_filename'=>'land.png', 'font_icon_code'=>'fa fa-pagelines', 'featured_image_filename'=>'land.jpg', 'hidden_fields_list'=>"")
            );

$d_fields = array( 
    array('id'=>20, 'field_name'=>'Beds', 'values'=>',1,2,3,4,5,6'), 
    array('id'=>5, 'field_name'=>'Area', 'values'=>'', 'type'=>'INTEGER', 'suffix'=>'Ft&sup2;'), 
);

$d_data_ins = array('fields_order'=>'{  "PRIMARY": {  "WHERE_SEARCH":{"direction":"NONE", "style":"", "class":"full banner_search_show", "id":"NONE", "type":"WHERE_SEARCH"} ,"DROPDOWN_MULTIPLE_4":{"direction":"NONE", "style":"", "class":"", "id":"4", "type":"DROPDOWN_MULTIPLE"} ,"CATEGORY":{"direction":"NONE", "style":"", "class":"banner_search_show", "id":"NONE", "type":"CATEGORY"} ,"INTEGER_36_FROM":{"direction":"FROM", "style":"", "class":"banner_search_show side_hide", "id":"36", "type":"INTEGER"} ,"INTEGER_36_TO":{"direction":"TO", "style":"", "class":"banner_search_show", "id":"36", "type":"INTEGER"} ,"INTEGER_20":{"direction":"NONE", "style":"", "class":"", "id":"20", "type":"INTEGER"} ,"LOCATION":{"direction":"NONE", "style":"", "class":"hide_on_all", "id":"NONE", "type":"LOCATION"} }, "SECONDARY": {  "CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_29":{"direction":"NONE", "style":"", "class":"", "id":"29", "type":"CHECKBOX"} ,"CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_11":{"direction":"NONE", "style":"", "class":"", "id":"11", "type":"CHECKBOX"} ,"CHECKBOX_33":{"direction":"NONE", "style":"", "class":"", "id":"33", "type":"CHECKBOX"} ,"CHECKBOX_31":{"direction":"NONE", "style":"", "class":"", "id":"31", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} } }');
$d_data_ins_items = array('fields_order'=>'{  "PRIMARY": {  "INTEGER_19":{"direction":"NONE", "style":"", "class":"", "id":"19", "type":"INTEGER"} ,"INTEGER_20":{"direction":"NONE", "style":"", "class":"", "id":"20", "type":"INTEGER"} ,"INTEGER_5":{"direction":"NONE", "style":"", "class":"", "id":"5", "type":"INTEGER"} }, "SECONDARY": { } }');
    
$d_colors_scheme = array();
$d_colors_scheme [] = array(
        'data-backgroundtopbar'=>'#fc2d2f',
        'data-primary-color'=>'#fc2d2f',
        'data-secondary-color'=>'#a61c1d',
        'data-btnprimary'=>'#fd4956',
        'data-btnprimaryhover'=>'#c5434d',
        'data-titlescolor'=>'#6b2525',
        'data-subtitlescolor'=>'#730707',
        'data-titlesprimary'=>'#d12123',
        'data-titlesecondary'=>'#6e1717',
        'data-contentcolor'=>'#171616'
);
$d_colors_scheme [] = array(
        'data-backgroundtopbar'=>'#bf224e',
        'data-primary-color'=>'#bf224e',
        'data-secondary-color'=>'#940a31',
        'data-btnprimary'=>'#F62459',
        'data-btnprimaryhover'=>'#DB0A5B',
        'data-titlescolor'=>'#5a626b',
        'data-subtitlescolor'=>'#6c7a89',
        'data-titlesprimary'=>'#26A65B',
        'data-titlesecondary'=>'#674172',
        'data-contentcolor'=>'#372b3b'
);
$d_colors_scheme [] = array(
        'data-backgroundtopbar'=>'#01A8CC',
        'data-primary-color'=>'#01A8CC',
        'data-secondary-color'=>'#004790',
        'data-btnprimary'=>'#01A8CC',
        'data-btnprimaryhover'=>'#1d6eb3',
        'data-titlescolor'=>'#252525',
        'data-subtitlescolor'=>'#7b7b7b',
        'data-titlesprimary'=>'#00B16A',
        'data-titlesecondary'=>'#2a3138',
        'data-contentcolor'=>'#353535'
);
$d_colors_scheme [] = array(
        'data-backgroundtopbar'=>'#1fc569',
        'data-primary-color'=>'#1fc569',
        'data-secondary-color'=>'#2f913c',
        'data-btnprimary'=>'#1fc569',
        'data-btnprimaryhover'=>'#317a3b',
        'data-titlescolor'=>'#1E824C',
        'data-subtitlescolor'=>'#1e824c',
        'data-titlesprimary'=>'#4285f4',
        'data-titlesecondary'=>'#000000',
        'data-contentcolor'=>'#232323'
);
$d_colors_scheme [] = array(
        'data-backgroundtopbar'=>'#0aa699',
        'data-primary-color'=>'#0aa699',
        'data-secondary-color'=>'#197069',
        'data-btnprimary'=>'#9d098c',
        'data-btnprimaryhover'=>'#8a277e',
        'data-titlescolor'=>'#DB0A5B',
        'data-subtitlescolor'=>'#D2527F',
        'data-titlesprimary'=>'#db0a5b',
        'data-titlesecondary'=>'#000000',
        'data-contentcolor'=>'#000000'
);
$d_colors_scheme [] = array(
        'data-backgroundtopbar'=>'#7061a4',
        'data-primary-color'=>'#7061a4',
        'data-secondary-color'=>'#5a2f5c',
        'data-btnprimary'=>'#913d88',
        'data-btnprimaryhover'=>'#995091',
        'data-titlescolor'=>'#252525',
        'data-subtitlescolor'=>'#7b7b7b',
        'data-titlesprimary'=>'#4285f4',
        'data-titlesecondary'=>'#252525',
        'data-contentcolor'=>'#353535'
);
$d_colors_scheme [] = array(
        'data-backgroundtopbar'=>'#d9b62e',
        'data-primary-color'=>'#e68c2c',
        'data-secondary-color'=>'#e68c2c',
        'data-btnprimary'=>'#e68c2c',
        'data-btnprimaryhover'=>'#ba7225',
        'data-titlescolor'=>'#252525',
        'data-subtitlescolor'=>'#7b7b7b',
        'data-titlesprimary'=>'#4285f4',
        'data-titlesecondary'=>'#252525',
        'data-contentcolor'=>'#353535'
);
$d_colors_scheme [] = array(
        'data-backgroundtopbar'=>'#846447',
        'data-primary-color'=>'#846447',
        'data-secondary-color'=>'#a1764f',
        'data-btnprimary'=>'#CA6924',
        'data-btnprimaryhover'=>'#E08A1E',
        'data-titlescolor'=>'#846447',
        'data-subtitlescolor'=>'#7c5c3f',
        'data-titlesprimary'=>'#446CB3',
        'data-titlesecondary'=>'#9d4e06',
        'data-contentcolor'=>'#9c4d06'
);


?>