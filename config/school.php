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

    // Government approval, taken from the signed permit.
    'permit' => [
        'authority' => 'Niger State Ministry of Tertiary Education',
        'title' => 'Permit to Operate as a Private Tertiary Institution',
        'serial' => '079',
        'issued_on' => '2026-10-20',
        'signatories' => [
            ['name' => 'Hon. Adamu Abdullahi Sheshi', 'role' => 'Honourable Commissioner'],
            ['name' => "Sirajo Sa'id", 'role' => 'Permanent Secretary'],
        ],
        'image' => 'images/brand/operating-permit.jpg',
        'pdf' => 'documents/niger-state-operating-permit.pdf',
    ],

    // Students without an email address get a login email on this domain.
    // Paystack needs an email for every payment, so one is always set.
    'student_email_domain' => env('STUDENT_EMAIL_DOMAIN', 'students.debright.local'),
];
