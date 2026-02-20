<?php

declare(strict_types=1);

namespace Tests\Unit\Mail;

use App\Mail\ApiKeyDestroyed;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ApiKeyDestroyedTest extends TestCase
{
    #[Test]
    public function it_has_the_correct_data(): void
    {
        $mailable = new ApiKeyDestroyed(label: 'My Key');

        $this->assertSame('API key removed', $mailable->envelope()->subject);

        $content = $mailable->content();

        $this->assertSame('mail.api.destroyed-text', $content->text);
        $this->assertSame('mail.api.destroyed', $content->markdown);
        $this->assertSame('My Key', $content->with['label']);
    }
}
