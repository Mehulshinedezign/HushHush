<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CmsPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cmsPages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'tag_line' => 'Learn more about the information we collect and the steps we take to protect your privacy',
                'content' => 'This is the about us page',
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'tag_line' => 'Learn more about the information we collect and the steps we take to protect your privacy',
                'content' => 'This is the Faq page',
            ],
            [
                'title' => 'Contact us',
                'slug' => 'contact-us',
                'tag_line' => 'Learn more about the information we collect and the steps we take to protect your privacy',
                'content' => '',
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-conditions',
                'tag_line' => 'Learn more about the information we collect and the steps we take to protect your privacy',
                'content' => 'This is the terms and conditions page',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'tag_line' => 'Learn more about the information we collect and the steps we take to protect your privacy',
                'content' => 'This is the privacy policy page',
            ],
            [
                'title' => 'Help Support',
                'slug' => 'help-support',
                'tag_line' => 'Learn more about the information we collect and the steps we take to protect your privacy',
                'content' => 'This is the help support page',
            ],
            [
                'title' => 'Cancellation and Refund policies',
                'slug' => 'cancellation-refund-policies',
                'tag_line' => 'Learn more about the information we collect and the steps we take to protect your privacy',
                'content' => 'Cancellation and Refund policies',
            ],
            [
                'title' => 'How it Works',
                'slug' => 'how-it-works',
                'tag_line' => 'Learn more about the information we collect and the steps we take to protect your privacy',
                'content' => 'how it works',
            ],

        ];

        CmsPage::insert($cmsPages);
    }
}
