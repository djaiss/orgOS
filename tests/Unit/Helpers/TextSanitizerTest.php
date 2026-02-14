<?php

declare(strict_types = 1);

namespace Tests\Unit\Helpers;

use App\Helpers\TextSanitizer;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TextSanitizerTest extends TestCase
{
    #[Test]
    public function plain_text_strips_tags_and_trims(): void
    {
        $this->assertSame('Hello World', TextSanitizer::plainText('  <p>Hello</p> <b>World</b>  '));
    }

    #[Test]
    public function plain_text_removes_script_tags(): void
    {
        $this->assertSame('', TextSanitizer::plainText('<script>alert("xss")</script>'));
    }

    #[Test]
    public function plain_text_handles_malformed_html(): void
    {
        $result = TextSanitizer::plainText('< script >alert(1)</ script >');

        $this->assertStringNotContainsString('< script >', $result);
    }

    #[Test]
    public function nullable_plain_text_returns_null_for_null(): void
    {
        $this->assertNull(TextSanitizer::nullablePlainText(null));
    }

    #[Test]
    public function nullable_plain_text_returns_null_for_empty_results(): void
    {
        $this->assertNull(TextSanitizer::nullablePlainText('<p></p>'));
        $this->assertNull(TextSanitizer::nullablePlainText('   '));
    }

    #[Test]
    public function html_strips_dangerous_tags_but_preserves_safe_ones(): void
    {
        $result = TextSanitizer::html('<p>Hello</p><script>alert(1)</script>');

        $this->assertStringContainsString('<p>Hello</p>', $result);
        $this->assertStringNotContainsString('<script>', $result);
    }

    #[Test]
    public function nullable_html_returns_null_for_empty_or_dangerous_only(): void
    {
        $this->assertNull(TextSanitizer::nullableHtml(null));
        $this->assertNull(TextSanitizer::nullableHtml('<script>alert(1)</script>'));
        $this->assertNull(TextSanitizer::nullableHtml('<p>   </p>'));
    }
}
