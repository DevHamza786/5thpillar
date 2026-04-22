<?php

return [

    /*
    | Public paths relative to the site root (used with asset()).
    | Replace each file under public/uploads/... with your real brochure PDFs.
    */
    'pdfs' => [
        'haazir' => env('BROCHURE_PDF_HAAZIR', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
        'bulawa' => env('BROCHURE_PDF_BULAWA', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
        'saadat' => env('BROCHURE_PDF_SAADAT', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
        'hajj-safar' => env('BROCHURE_PDF_HAJJ_SAFAR', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
        'fareeza' => env('BROCHURE_PDF_FAREEZA', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
        'umrah-intro' => env('BROCHURE_PDF_UMRAH_INTRO', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
        'saadat-umrah' => env('BROCHURE_PDF_SAADAT_UMRAH', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
        'umrah-noor' => env('BROCHURE_PDF_UMRAH_NOOR', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
        'safar-aasan-umrah' => env('BROCHURE_PDF_SAFAR_AASAN_UMRAH', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
        'sukoon' => env('BROCHURE_PDF_SUKOON', 'uploads/2023/2023/09/Target-Asset-Mix-And-Charges.pdf'),
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
