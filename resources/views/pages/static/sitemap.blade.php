@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Sitemap - 5th Pillar Family Takaful')
@section('structured_page_title', 'Sitemap')

@section('structured_hero_title', 'Sitemap')

@section('structured_primary')
    <article class="post_item_single post_type_page type-page hentry laravel-sitemap-article">
        <div class="post_content entry-content">
            <nav class="laravel-sitemap" aria-label="Site map">
                <div class="laravel-sitemap__cols">
                    <ul class="laravel-sitemap__col-list">
                        <li class="laravel-sitemap__block laravel-sitemap__block--tight">
                            <span class="laravel-sitemap__heading">Quick links</span>
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'contact']) }}">Contact</a></li>
                                <li><a href="https://5thpillartravel.com/" target="_blank" rel="noopener noreferrer">5th Pillar Travel</a></li>
                            </ul>
                        </li>
                        <li class="laravel-sitemap__block">
                            <span class="laravel-sitemap__heading">Governance</span>
                            <ul>
                                <li><a href="{{ route('pages.show', ['slug' => 'board-of-directors']) }}">Board of Directors</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'board-committees']) }}">Board Committees</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'management-committees']) }}">Management Committees</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'shariah-advisor']) }}">Shariah Advisor</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'external-auditors']) }}">External Auditors</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'legal-advisor']) }}">Legal Advisor</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'pattern-of-shareholding']) }}">Pattern of Shareholding</a></li>
                            </ul>
                        </li>
                        <li class="laravel-sitemap__block">
                            <span class="laravel-sitemap__heading">Investors Relations</span>
                            <ul>
                                <li><a href="{{ route('pages.show', ['slug' => 'financial-statements']) }}">Financial Statements</a></li>
                                <li class="laravel-sitemap__nest laravel-sitemap__nest--cols">
                                    <span class="laravel-sitemap__subheading">Notices of General Meetings</span>
                                    <ul class="laravel-sitemap__nest-list">
                                        <li>
                                            <a href="{{ \App\Support\PublicPath::uploadHref('uploads/2026/04/Notice-of-AGM-2026.pdf') }}" target="_blank" rel="noopener noreferrer">Notice of AGM 2026</a>
                                        </li>
                                        <li>
                                            <a href="{{ \App\Support\PublicPath::uploadHref('uploads/2026/04/Notice-of-EoGM-2025.pdf') }}" target="_blank" rel="noopener noreferrer">Notice of EoGM 2025</a>
                                        </li>
                                        <li>
                                            <a href="{{ \App\Support\PublicPath::uploadHref('uploads/2026/04/Notice-of-AGM-2025.pdf') }}" target="_blank" rel="noopener noreferrer">Notice of AGM 2025</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('pages.show', ['slug' => 'online-complaint-form']) }}">Online Complaint Form</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'contact-details-for-investors']) }}">Contact Details for Investors</a></li>
                                <li>
                                    <a href="{{ route('pages.show', ['slug' => 'complaints-in-respect-of-takaful-membership']) }}">Complaints in Respect of Takaful Membership</a>
                                </li>
                                <li><a href="{{ route('pages.show', ['slug' => 'secp-sdms']) }}">SECP SDMS</a></li>
                                <li class="laravel-sitemap__nest laravel-sitemap__nest--cols">
                                    <span class="laravel-sitemap__subheading">Takaful Unit-Linked Funds</span>
                                    <ul class="laravel-sitemap__nest-list">
                                        <li>
                                            <a href="{{ \App\Support\PublicPath::uploadHref('uploads/2025/2025/01/web-page.pdf') }}" target="_blank" rel="noopener noreferrer">Target Asset Mix and Charges</a>
                                        </li>
                                        <li><a href="{{ route('pages.show', ['slug' => 'daily-fund-prices']) }}">Daily Fund Prices</a></li>
                                        <li><a href="{{ route('pages.show', ['slug' => 'fund-managers-report']) }}">Fund Manager&rsquo;s Report</a></li>
                                        <li><a href="{{ route('pages.show', ['slug' => 'accounts-of-unit-linked-funds']) }}">Accounts of Unit Linked Funds</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('pages.show', ['slug' => 'sitemap']) }}" aria-current="page">Sitemap</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="laravel-sitemap__col-list">
                        <li class="laravel-sitemap__block">
                            <span class="laravel-sitemap__heading">Company</span>
                            <ul>
                                <li><a href="{{ route('pages.show', ['slug' => 'about-us']) }}">About Us</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'vision-mission']) }}">Vision &amp; Mission</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'management-team']) }}">Management Team</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'corporate-information']) }}">Corporate Information</a></li>
                                <li>
                                    <a href="{{ \App\Support\PublicPath::uploadHref('uploads/2026/03/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf') }}" target="_blank" rel="noopener noreferrer">PACRA Rating</a>
                                </li>
                                <li>
                                    <a href="{{ \App\Support\PublicPath::uploadHref('uploads/2023/09/Code-of-Conduct-Corporate.pdf') }}" target="_blank" rel="noopener noreferrer">Code of Conduct</a>
                                </li>
                                <li>
                                    <a href="{{ \App\Support\PublicPath::uploadHref('uploads/2026/04/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf') }}" target="_blank" rel="noopener noreferrer">Waqf Deed</a>
                                </li>
                                <li>
                                    <a href="{{ \App\Support\PublicPath::uploadHref('uploads/2023/12/PTF-Policies.pdf') }}" target="_blank" rel="noopener noreferrer">PTF Policies</a>
                                </li>
                                <li><a href="{{ route('pages.show', ['slug' => 'privacy-policy']) }}">Privacy Policy</a></li>
                            </ul>
                        </li>
                        <li class="laravel-sitemap__block">
                            <span class="laravel-sitemap__heading">Products</span>
                            <ul>
                                <li><a href="{{ route('pages.show', ['slug' => 'hajj-savings-plan']) }}">Hajj Savings Plan</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'umrah-savings-plan']) }}">Umrah Savings Plan</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'regular-savings-plan']) }}">Regular Savings Plan</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'group-life-takaful']) }}">Group Life Takaful</a></li>
                            </ul>
                        </li>
                        <li class="laravel-sitemap__block">
                            <span class="laravel-sitemap__heading">Media</span>
                            <ul>
                                <li><a href="{{ route('news-events.index') }}">News &amp; Events</a></li>
                                <li><a href="{{ route('pages.show', ['slug' => 'memberships']) }}">Memberships</a></li>
                            </ul>
                        </li>
                        <li class="laravel-sitemap__block">
                            <span class="laravel-sitemap__heading">Downloads</span>
                            <ul>
                                <li>
                                    <a href="{{ \App\Support\PublicPath::uploadHref('uploads/2026/04/Unclaimed-Un-Enchased-Benefits-lIst-March-2026.pdf') }}" target="_blank" rel="noopener noreferrer">List of Participants Having Unclaimed / Un-Enchased Benefits</a>
                                </li>
                                <li><a href="{{ route('pages.show', ['slug' => 'forms']) }}">Forms</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </article>
@endsection
