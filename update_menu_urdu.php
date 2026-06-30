<?php

use App\Models\NavMenuItem;

$mapping = [
    'Company' => 'کمپنی',
    'About Us' => 'ہمارے بارے میں',
    'Vision & Mission' => 'وژن اور مشن',
    'Management Team' => 'مینیجمنٹ ٹیم',
    'Corporate Information' => 'کارپوریٹ معلومات',
    'Code of Conduct' => 'کوڈ آف کنڈکٹ',
    'Waqf Deed' => 'وقف ڈیڈ',
    'PTF Policies' => 'پی ٹی ایف پالیسیز',
    'Governance' => 'گورننس',
    'Board of Directors' => 'بورڈ آف ڈائریکٹرز',
    'Board Committees' => 'بورڈ کمیٹیز',
    'Management Committees' => 'مینجمنٹ کمیٹیز',
    'Shariah Advisor' => 'شریعہ ایڈوائزر',
    'External Auditors' => 'آڈیٹر',
    'Auditors' => 'آڈیٹر',
    'Legal Advisor' => 'قانونی مشیر',
    'Pattern of Shareholding' => 'پیٹرن آف شیئرہولڈنگ',
    'Products' => 'پراڈکٹس',
    'Haazir Hajj Saving Plan' => 'حاضر حج سیونگ پلان',
    'Umrah Saving Plan' => 'عمرہ سیونگ پلان',
    'Regular Savings Plan' => 'ریگولر سیونگز پلان',
    'Corporate Takaful' => 'کارپوریٹ تکافل',
    'Investor Relations' => 'سرمایہ کاروں کے تعلقات',
    'Financial Statements' => 'مالیاتی گوشوارے',
    'Online Complaint Form' => 'آن لائن شکایت فارم',
    'Contact Details for Investors' => 'سرمایہ کاروں کے لیے رابطہ کی تفصیلات',
    'Complaints in respect of Takaful Membership' => 'تکافل ممبرشپ کے متعلق شکایات',
    'SECP SMDS' => 'SECP سروس ڈیسک مینیجمنٹ سسٹم',
    'Takaful Unit Linked Funds' => 'تکافل یونٹ لنکڈ فنڈز',
    'Target Asset Mix and Charges' => 'ٹارگٹ اسیٹ مکس اینڈ چارجز',
    'Daily Fund Prices' => 'ڈیلی فنڈ کی قیمتیں',
    'Fund Managers Report' => 'فنڈ مینیجرز رپورٹ',
    'Unit Linked Fund Accounts' => 'یونٹ لنکڈ فنڈ کے اکاؤنٹس',
    'Sitemap' => 'سائٹ میپ',
    'Media' => 'میڈیا',
    'News & Events' => 'خبریں اور واقعات',
    'Memberships' => 'ممبرشپس',
    'Contact' => 'رابطہ کریں',
    'Downloads' => 'ڈاؤن لوڈ',
    'List of Participants Having Unclaimed / Un-Enchased Benefits' => 'فوائد وصول نہ کرنے والوں کی فہرست',
    'Forms' => 'فارم',
];

foreach ($mapping as $label => $urdu) {
    NavMenuItem::where('label', $label)->update(['label_ur' => $urdu]);
}

echo "Menu items updated successfully.\n";
