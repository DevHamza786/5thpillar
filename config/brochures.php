<?php

return [

    /*
    | Public paths relative to the site root (used with asset()).
    | Replace each file under public/uploads/... with your real brochure PDFs.
    */
    'pdfs' => [
        'haazir' => env('BROCHURE_PDF_HAAZIR', 'assets/pdfs/A5-Brochure-E.U-updated-short_compressed 01.pdf'),
        'bulawa' => env('BROCHURE_PDF_BULAWA', 'assets/pdfs/A5-Brochure-E.U-updated-short-V-1_compressed-1--02.pdf'),
        'saadat' => env('BROCHURE_PDF_SAADAT', 'assets/pdfs/BALF-Saadat-Hajj--03.pdf'),
        'hajj-safar' => env('BROCHURE_PDF_HAJJ_SAFAR', 'assets/pdfs/BIP-Hajj-Safar-Plan.pdf'),
        'fareeza' => env('BROCHURE_PDF_FAREEZA', 'assets/pdfs/DIB-Fareeza-Hajjj---05.pdf'),
        'umrah-intro' => env('BROCHURE_PDF_UMRAH_INTRO', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
        'saadat-umrah' => env('BROCHURE_PDF_SAADAT_UMRAH', 'assets/pdfs/Umrah saving/BALF-Saadat-Umrah 01.pdf'),
        'umrah-noor' => env('BROCHURE_PDF_UMRAH_NOOR', 'assets/pdfs/Umrah saving/BIP-Umrah-Noor-Plan 02.pdf'),
        'safar-aasan-umrah' => env('BROCHURE_PDF_SAFAR_AASAN_UMRAH', 'assets/pdfs/Umrah saving/DIB-Safar-Asaan-Umrahh 03.pdf'),
        'sukoon' => env('BROCHURE_PDF_SUKOON', 'assets/pdfs/Regular saving/Sukoon-Saving-Plan_Brochure-Final.pdf'),
        'group-family-takaful' => env('BROCHURE_PDF_GROUP_FAMILY', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
    ],

    'cities' => [
        'Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Peshawar', 'Faisalabad', 'Multan', 'Gujranwala',
        'Sialkot', 'Bahawalpur', 'Abbottabad', 'Hyderabad', 'Quetta', 'Sukkur', 'Larkana', 'Mardan',
        'Gujrat', 'Sahiwal', 'Wah Cantonment', 'Rahim Yar Khan', 'Jhelum', 'Mandi Bahauddin', 'Kasur',
        'Dera Ghazi Khan', 'Sargodha', 'Sheikhupura', 'Okara', 'Vehari', 'Chiniot', 'Attock', 'Jhang',
        'Other',
    ],

];
