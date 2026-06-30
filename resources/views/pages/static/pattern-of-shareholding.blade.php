@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Pattern of Shareholding - 5th Pillar Family Takaful')
@section('structured_page_title', 'Pattern of Shareholding')

@section('structured_hero_title', 'Pattern of Shareholding')

@section('structured_primary')
    <article class="post_item_single post_type_page page type-page status-publish hentry laravel-pattern-shareholding-page">
        <div class="post_content entry-content">
            <section class="wpb-content-wrapper">
                <div class="vc_row wpb_row vc_row-fluid">
                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                        <div class="vc_column-inner">
                            <div class="wpb_wrapper">
                                <div class="laravel-shareholding-table-wrap">
                                    <table class="laravel-shareholding-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Name of Shareholders</th>
                                                <th scope="col">Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Kuwait International Investment Holding Company</td>
                                                <td>41.75%</td>
                                            </tr>
                                            <tr>
                                                <td>5th Pillar Holdings Limited (DIFC incorporated in UAE)</td>
                                                <td>26.25%</td>
                                            </tr>
                                            <tr>
                                                <td>Mr. Muhammad Ali</td>
                                                <td>25%</td>
                                            </tr>
                                            <tr>
                                                <td>Mrs. Saima Sohail Tabba</td>
                                                <td>7%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </article>
@endsection
