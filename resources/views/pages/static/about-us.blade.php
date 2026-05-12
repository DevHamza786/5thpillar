@extends('pages.layouts.structured-page')

@section('structured_meta_title', $page->trans('meta_title') ?: ($page->trans('title') . ' - 5th Pillar Family Takaful'))
@section('structured_page_title', $page->trans('title'))

@php
    $isUrdu = app()->getLocale() === 'ur';

    if ($isUrdu) {
        $introLead = 'ففتھ پلر فیملی تکافل لمیٹڈ پاکستان کے فیملی تکافل سیکٹر میں ایک نیا شامل ہونے والا ادارہ ہے جسے کویت اور پاکستان کے نامور کاروباری اداروں کی حمایت حاصل ہے۔ کمپنی نے صنعت کے ریکارڈ قائم کیے ہیں جن میں نمایاں سنگ میل شامل ہیں جیسے کہ:';
        $milestones = [
            'پاکستان کے تکافل سیکٹر میں سب سے بڑی ایف ڈی آئی',
            'غیر ملکی شیئر ہولڈرز ففتھ پلر تکافل کے 68 فیصد کے مالک ہیں اور 32 فیصد پاکستانی مفادات کے پاس ہے',
            'پاکستان کی تکافل سیکٹر کی تاریخ میں سب سے زیادہ 2.00 ارب روپے کا ابتدائی پیڈ اپ سرمایہ',
            'پاکستان کریڈٹ ریٹنگ ایجنسی (PACRA) سے سب سے زیادہ ابتدائی کریڈٹ ریٹنگ "A+ مستحکم آؤٹ لک"',
            'پاکستان میں شریعہ کے مطابق فیملی تکافل کے کاروبار کی ضمانت دینے کے لیے SECP سے لائسنس یافتہ',
            'ممبرشپ کے لائف سائیکل کے دوران کاروباری کارروائیوں میں مدد کے لیے جدید ترین آئی ٹی پلیٹ فارم',
            'ممبران کو ان کے گھروں کے آرام سے 24/7 معلومات اور مدد فراہم کرنے کے لیے آنے والی کسٹمر انگیجمنٹ موبائل ایپ/ویب پورٹل',
        ];

        $sponsorsIntro = 'ففتھ پلر فیملی تکافل لمیٹڈ کو ممتاز سپانسرز کی حمایت حاصل ہے:';
        $sponsorParagraphs = [
            [
                'strong' => 'کویت انٹرنیشنل انویسٹمنٹ ہولڈنگ ایجنسی (KIIC):',
                'text' => ' یہ ایک معروف سرمایہ کاری کمپنی ہے جس کا ہیڈ کوارٹر کویت سٹی، کویت میں واقع ہے۔ اس کمپنی کا قیام 1973 میں عمل میں آیا۔ کویت کے کئی معروف اور ممتاز کاروباری اداروں کے پاس KIIC کے ملکیتی حصص ہیں، جس میں سے کویت انویسٹمنٹ اتھارٹی (KIA) کمپنی کے 31.90% حصص کے ساتھ سرفہرست ہے۔',
            ],
            [
                'strong' => 'الفاطر گروپ کویت:',
                'text' => ' یہ بھی ایک کویتی کمپنی ہے اور 1974 میں اس کا قیام عمل میں آیا۔ البا گروپ کئی لسٹڈ کویتی کمپنیوں کے اشتراک سے وجود میں آئی ہے جن کی تکافل، میزبانی، مالیاتی خدمات اور رئیل اسٹیٹ کے شعبوں میں کئی بلین امریکی ڈالر پر مشتمل سرمایہ کاری ہے۔ اس گروپ کے چار براعظموں یعنی مشرقی وسطی، افریقہ، یورپ اور متحدہ امریکہ میں مختلف ہوٹلز کے تقریباً 11,500 فائیو اسٹار کمرے ہیں، جو میزبانی کے شعبے میں بے مثال خدمات فراہم کر رہے ہیں۔',
            ],
            [
                'strong' => 'ففتھ پلر ہولڈنگ DIFC دبئی، یو اے ای:',
                'text' => ' یہ کویت کے معروف کاروباری اداروں کی جانب سے خصوصی مقصد کے تحت بنائی گئی کمپنی ہے تاکہ کثیر مسلم آبادی والے ممالک میں تکافل کمپنیز اور حج کی خدمات کے حوالے سے ایک منظم ترتیب تشکیل دی جا سکے۔ جو کہ پاکستان سے آغاز کے ساتھ ساتھ اس مماثلت کے انتظامات بنگلہ دیش، انڈونیشیا، ترکی اور مصر میں بھی شروع کیے جائیں گے۔',
            ],
            [
                'strong' => 'محمدی فیملی اینڈ ایسوسی ایٹس:',
                'text' => ' اس میں محمدی فیملی بھی شامل ہے جو تین نسلوں سے پاکستان میں انشورنس سیکٹر کے بزنس میں پیش پیش ہے۔',
            ],
        ];
        $sponsorsClosing = 'اس اہم مالیاتی تعاون کی بدولت کمپنی اپنے صارفین کو جدید ٹیکنالوجی اور پراڈکٹ کے ذریعے بے مثال خدمات فراہم کرنے کے قابل ہو سکی ہے۔ ہمارے معزز اسپانسرز کے پاس ہمارے شرکاء تکافل کے لیے پاکستان اور دیگر منتخب ممالک سے انتہائی خصوصی سہولت اور آسانی کے ساتھ حج کی سعادت حاصل کرنے کے حوالے سے تمام ضروری مالی وسائل، تجربہ اور مہارت موجود ہے۔';
        
        $valueChainHeading = 'ففتھ پلر کے مکمل مرتب نظام کی تفصیل';
        $valueChainImage = asset('assets/images/Journey-Takaful-Urdu.webp');
        
        $reTakafulHeading = 'ری تکافل کے انتظامات';
        $reTakafulText = 'گروپ لائف تکافل پلان کے لیے ہم نے Hannover Re (دنیا کی نامی گرامی ری تکافل کمپنی) کے ساتھ ری تکافل کے انتظامات کیے ہیں۔ اس انتظام کی بدولت ہم دنیا بھر کے ایک ترقی یافتہ ادارے کے تجربے سے فائدہ اٹھاسکیں گے۔';
    } else {
        $introLead = '5th Pillar Family Takaful Limited is a new entrant into the Family Takaful sector of Pakistan which is supported by eminent business houses from Kuwait and Pakistan. The company has set industry records with remarkable milestones such as:';
        $milestones = [
            'Largest FDI in Takaful sector of Pakistan',
            'Foreign shareholders own 68% of 5th Pillar Takaful and 32% is held by Pakistani interests',
            'Largest initial paid up capital of Rs 2.00 billion in Pakistan’s Takaful sector history',
            'Highest initial credit rating “A+ Stable outlook” from Pakistan Credit Rating Agency (PACRA)',
            'Licensed by the SECP to underwrite Shariah compliant Family Takaful business in Pakistan',
            'State of the art IT platform to support business operations throughout the membership lifecycle',
            'Upcoming customer engagement mobile app/web portal to provide 24/7 information and assistance to members from the comfort of their homes',
        ];

        $sponsorsIntro = '5th Pillar Family Takaful Limited is backed by distinguished sponsors:';
        $sponsorParagraphs = [
            [
                'strong' => 'Kuwait International Investment Holding Company (KIIC)',
                'text' => ' is a leading investment company headquartered in Kuwait City, Kuwait. Founded in 1973, KIIC is owned by leading business houses of Kuwait including Government of Kuwait owned Kuwait Investment Authority (KIA) which owns 31.90% share of KIIC.',
            ],
            [
                'strong' => 'Al Bahar Group',
                'text' => ' formerly known as IFA Group, is a Kuwait-based company incorporated in 1974. Al Bahar Group is a multi-billion US dollar consortium of several listed Kuwaiti companies with diverse investments in Takaful, Hospitality, Financial Services, and Real Estate. The Group owns multiple hotels across the Middle East, Africa, Europe, and the USA, comprising approximately 11,500 five-star room keys across four continents, providing it with unmatched expertise in the hospitality sector in particular.',
            ],
            [
                'strong' => '5th Pillar Holding DIFC Dubai, UAE',
                'text' => ' is a special purpose company which has been set up by renowned business houses from Kuwait to develop Takaful companies and value chain in major Muslim populations countries. Starting with Pakistan, the plan is to set up similar operations in Bangladesh, Indonesia, Turkey and Egypt.',
            ],
            [
                'strong' => 'Muhammadi Family & Associates',
                'text' => ' include the Muhammadi Family who have been doing business in the Takaful/Insurance sector for over three generations in Pakistan.',
            ],
        ];
        $sponsorsClosing = 'This significant financial backing has allowed the company to invest in cutting-edge technology and develop innovative products in order to provide unparalleled customer service to its clients. Our reputed sponsors possess the necessary financial resources and expertise to deliver their commitment to facilitate our members to perform Hajj from Pakistan and from other selected countries with ease and comfort at an affordable Hajj price in Pakistan.';
        
        $valueChainHeading = 'The Road Map To Our<br>End-to-End Value Chain';
        $valueChainImage = asset('uploads/2024/2024/01/5th-Pillar-End-to-End-Value-Chain-1.webp');

        $reTakafulHeading = 'ReTakaful Arrangements';
        $reTakafulText = 'We have made ReTakaful arrangements with Hannover Re (world’s renowned ReTakaful Company) which allows us to enjoy the expertise of one of the most progressive institutions across the globe.';
    }

    $mastheadBg = $isUrdu 
        ? asset('assets/images/inner-banners-2-64d5da6709c98-e1691742724167.webp')
        : asset('uploads/2017/2017/09/main-bannner-64d5d132c369d.webp');

    $valueChainBg = $isUrdu 
        ? asset('assets/images/About-Background.webp')
        : '';

    $sponsorsBg = $isUrdu 
        ? asset('assets/images/inner-banners-2-64d5da6709c98-e1691742724167.webp')
        : '';
@endphp




@section('structured_masthead_bg', $mastheadBg)

@section('structured_primary')
    <section class="laravel-about-wp-intro" aria-label="About 5th Pillar Family Takaful">
        <div class="laravel-about-wp-intro__row">
            <div class="laravel-about-wp-intro__col laravel-about-wp-intro__col--main">
                <p class="laravel-about-wp-intro__text">{{ $introLead }}</p>
                <ul class="laravel-about-wp-intro__list">
                    @foreach ($milestones as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="laravel-about-wp-intro__col laravel-about-wp-intro__col--aside" aria-hidden="true"></div>
        </div>
    </section>
@endsection

@section('structured_tertiary')
    <section class="laravel-about-wp-band shaha_about_quote laravel-about-wp-band--sponsors" aria-labelledby="about-sponsors-heading" @if($sponsorsBg) style="background-image: url('{{ $sponsorsBg }}') !important; background-size: cover; background-position: center;" @endif>
        <div class="content_wrap">
            <div class="sc_content color_style_default sc_content_default sc_float_center extra-about-quote-mr-negative">
                <h2 id="about-sponsors-heading" class="laravel-about-wp-band__h2 {{ $isUrdu ? 'laravel-about-wp-band__h2--right' : 'laravel-about-wp-band__h2--left' }}">{{ $isUrdu ? 'ہمارے سپانسرز' : 'Our Sponsors' }}</h2>
                <p class="laravel-about-wp-prose-dark">{{ $sponsorsIntro }}</p>
                @foreach ($sponsorParagraphs as $block)
                    <p class="laravel-about-wp-prose-dark">
                        <strong>{{ $block['strong'] }}</strong>{{ $block['text'] }}
                    </p>
                @endforeach
                <p class="laravel-about-wp-prose-dark">{{ $sponsorsClosing }}</p>
            </div>
        </div>
    </section>

    <section class="laravel-about-wp-band laravel-about-wp-band--value-chain vc_row-has-fill" aria-labelledby="about-value-chain-heading" @if($valueChainBg) style="background-image: url('{{ $valueChainBg }}') !important;" @endif>
        <div class="content_wrap laravel-about-wp-value-chain__inner">
            <h2 id="about-value-chain-heading" class="laravel-about-wp-value-title">
                {!! $valueChainHeading !!}
            </h2>
            <figure class="laravel-about-wp-value-figure">
                <div class="laravel-about-wp-value-frame">
                    <img
                        src="{{ $valueChainImage }}"
                        width="1920"
                        height="1080"
                        class="laravel-about-wp-value-img"
                        alt="5th Pillar End-to-End Value Chain"
                        loading="lazy"
                        decoding="async"
                    >
                </div>
            </figure>
        </div>
    </section>

    <section class="laravel-about-wp-band shaha_about_quote laravel-about-wp-band--retakaful" aria-labelledby="about-retakaful-heading">
        <div class="content_wrap">
            <div class="laravel-about-wp-retakaful-row">
                <div class="laravel-about-wp-retakaful-row__main">
                    <div class="sc_content color_style_default sc_content_default sc_float_center extra-about-quote-mr-negative">
                        <h2 id="about-retakaful-heading" class="laravel-about-wp-band__h2 {{ $isUrdu ? 'laravel-about-wp-band__h2--right' : 'laravel-about-wp-band__h2--left' }}">{{ $reTakafulHeading }}</h2>
                        <p class="laravel-about-wp-prose-dark">{{ $reTakafulText }}</p>
                    </div>
                </div>
                <div class="laravel-about-wp-retakaful-row__aside" aria-hidden="true"></div>
            </div>
        </div>
    </section>
@endsection

