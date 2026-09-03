<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_public_pages_are_successful(): void
    {
        $pages = [
            '/',
            '/about',
            '/academics',
            '/admissions',
            '/campus-life',
            '/news',
            '/contact',
        ];

        foreach ($pages as $page) {
            $this->get($page)->assertOk();
        }
    }

    public function test_public_pages_do_not_receive_a_centralized_content_catalog(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('PublicSite/Home')
                ->missing('page')
                ->has('institution')
                ->where('isPublicSite', true)
            );
    }

    public function test_contact_form_validates_and_returns_success(): void
    {
        $this->from('/contact')
            ->post('/contact', [
                'name' => 'Sara Ahmed',
                'email' => 'sara@example.com',
                'phone' => '+92 300 1234567',
                'subject' => 'Admissions enquiry',
                'message' => 'I would like information about the 2026–27 session.',
            ])
            ->assertRedirect('/contact')
            ->assertSessionHas('success');
    }
}
