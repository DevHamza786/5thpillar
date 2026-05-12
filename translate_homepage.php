<?php

use App\Models\Page;

$content_en = Page::where('slug', 'home')->value('content');

// Simple translation of key strings within the shortcodes
$content_ur = $content_en;

// Replace Slider with the requested GIF for Urdu homepage
$content_ur = str_replace('[smartslider3 slider="2"]', '[vc_single_image image="8732" img_size="full" alignment="center" onclick="link_image"]', $content_ur);
// Note: I'll need the ID for Comp-1_2.gif if I want to use it in vc_single_image, 
// but since I don't know the DB ID of the uploaded GIF, I'll use a placeholder or try to find it.

// Actually, I'll just use the asset path if I can, or use vc_raw_html.
$gif_url = asset('assets/images/Comp-1_2.gif');
$content_ur = str_replace('[smartslider3 slider="2"]', '<img src="'.$gif_url.'" style="width:100%; height:auto;" />', $content_ur);

// Translations
$translations = [
    'Prayer Times' => 'نماز کے اوقات',
    '5th Pillar Family Takaful' => 'ففتھ پلر فیملی تکافل',
    'Read More' => 'مزید پڑھیں',
    'ABOUT' => 'ہمارے بارے میں',
    '5th Pillar<br />\r\nFamily Takaful' => 'ففتھ پلر<br />فیملی تکافل',
    '5th Pillar Family Takaful Limited is a new entrant into the Family<br />\r\nTakaful sector of Pakistan which is supported by eminent<br />\r\nbusiness houses from Kuwait and Pakistan.' => 'ففتھ پلر فیملی تکافل لمیٹڈ پاکستان کے فیملی تکافل سیکٹر میں ایک نیا شامل ہونے والا ادارہ ہے جسے کویت اور پاکستان کے نامور کاروباری اداروں کی حمایت حاصل ہے۔',
    'More About Us' => 'ہمارے بارے میں مزید',
    'Mission &amp; Vision' => 'مشن اور وژن',
    'Mission & Vision' => 'مشن اور وژن',
    'Our Vision' => 'ہمارا وژن',
    'Our Mission' => 'ہمارا مشن',
    'Value Chain' => 'ویلیو چین',
    '5th Pillar End-to-End Value Chain Explained' => 'ففتھ پلر اینڈ ٹو اینڈ ویلیو چین کی وضاحت',
    'Download the Value Chain' => 'ویلیو چین ڈاؤن لوڈ کریں',
    'Shariah Advisor' => 'شریعہ ایڈوائزر',
    'Dr. Mufti Muhammad Imran Ashraf Usmani, a renowned Shariah Advisor,' => 'ڈاکٹر مفتی محمد عمران اشرف عثمانی، ایک معروف شریعہ ایڈوائزر،',
    'News &amp; Events' => 'خبریں اور واقعات',
    'News & Events' => 'خبریں اور واقعات',
];

foreach ($translations as $en => $ur) {
    $content_ur = str_replace($en, $ur, $content_ur);
}

// Descriptions (Handling specific long strings)
$content_ur = str_replace(
    'Strengthen the financial capacity of our clients through innovative Shariah compliant Takaful products empowering them to achieve their cherished goals in life.',
    'جدید شریعہ کے مطابق تکافل مصنوعات کے ذریعے اپنے کلائنٹس کی مالی صلاحیت کو مضبوط بنانا اور انہیں زندگی میں اپنے عزیز مقاصد حاصل کرنے کے لیے بااختیار بنانا۔',
    $content_ur
);

$content_ur = str_replace(
    'Provide structured Takaful savings and protection solutions specifically to Muslims in Pakistan to perform Hajj, the 5th Pillar of Islam.',
    'پاکستان میں مسلمانوں کو حج، اسلام کا پانچواں ستون، ادا کرنے کے لیے خاص طور پر منظم تکافل بچت اور تحفظ کے حل فراہم کرنا۔',
    $content_ur
);

$content_ur = str_replace(
    '5th Pillar Takaful Limited provides a complete end-to-end value chain, supporting you from the moment you start saving till the moment you\'ve performed Hajj and are back home.',
    'ففتھ پلر تکافل لمیٹڈ ایک مکمل اینڈ ٹو اینڈ ویلیو چین فراہم کرتا ہے، جو آپ کی بچت شروع کرنے کے لمحے سے لے کر حج کی ادائیگی اور گھر واپسی تک آپ کی حمایت کرتا ہے۔',
    $content_ur
);

Page::where('slug', 'home')->update([
    'content_ur' => $content_ur,
    'title_ur' => 'ہوم',
    'hero_title_ur' => 'ففتھ پلر فیملی تکافل',
]);

echo "Homepage translated successfully.\n";
