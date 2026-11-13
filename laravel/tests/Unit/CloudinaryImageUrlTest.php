<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 3).'/includes/cloudinary_images.php';

class CloudinaryImageUrlTest extends TestCase
{
    public function test_external_image_urls_are_preserved(): void
    {
        $url = 'https://images.example.test/wildlife/photo.jpg?size=large';

        $this->assertSame($url, wk_cloudinary_image_url($url, 'animals'));
    }

    public function test_relative_image_references_map_to_optimized_cloudinary_urls(): void
    {
        $this->assertSame(
            'https://res.cloudinary.com/pg6m0frc/image/upload/f_auto,q_auto/animals/gallery/lion-photo',
            wk_cloudinary_image_url('uploads/animals/gallery/lion-photo.webp', 'animals')
        );

        $this->assertSame(
            'https://res.cloudinary.com/pg6m0frc/image/upload/f_auto,q_auto/events/open-day',
            wk_cloudinary_image_url('open-day.png', 'events')
        );
    }
}
