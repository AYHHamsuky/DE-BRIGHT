<?php

/*
 |--------------------------------------------------------------------------
 | School details
 |--------------------------------------------------------------------------
 | Everything the website shows about the school lives here, so one edit
 | updates every page. Change a phone number once and it changes everywhere.
 */

return [
    'name' => 'DE BRIGHT College of Health Science and Technology',
    'short_name' => 'DE BRIGHT College',
    'motto' => 'Learning for Good Health',

    'address' => 'No. 27/28 Adjacent to General Hospital and KLM 127 Command, FRSC Office, Minna Road, Lapai, Lapai LGA, Niger State.',

    // Nigerian numbers as written on the flyers.
    'phones' => ['08064837014', '08038284265', '09066638080'],

    // WhatsApp number in international format, no plus sign or spaces.
    'whatsapp' => '2348064837014',

    // The flyers show no email address. Add one here when the school has it.
    'email' => null,

    // Used for the embedded Google map on the Contact page.
    'map_query' => 'DE BRIGHT College of Health Science and Technology, Minna Road, Lapai, Niger State',

    // Entry requirements, taken from the school flyer.
    'requirements' => [
        [
            'title' => 'Diploma',
            'text' => 'Minimum of five (5) credits at not more than two sittings, which must include English, Mathematics, Physics, Chemistry, Biology / Health Science and any other relevant subjects.',
        ],
        [
            'title' => 'Certificate',
            'text' => 'Minimum of three (3) credits at not more than two sittings, which must include English, Mathematics, Biology / Health Science and any other relevant subjects.',
        ],
        [
            'title' => 'Direct Entry',
            'text' => 'Minimum of five (5) credits at not more than two sittings, which must include English, Mathematics, Biology, Physics, Chemistry and any other subjects, in addition to a relevant professional certificate.',
        ],
    ],

    'how_to_apply' => 'Visit the school to collect your application form.',
];
