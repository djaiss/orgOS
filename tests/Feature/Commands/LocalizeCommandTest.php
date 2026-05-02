<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LocalizeCommandTest extends TestCase
{
    #[Test]
    public function it_synchronizes_json_locale_files(): void
    {
        $viewPath = resource_path('views/test-localize-command.blade.php');
        $enPath = lang_path('en.json');
        $frPath = lang_path('fr.json');

        $originalView = is_file($viewPath) ? file_get_contents($viewPath) : null;
        $originalEn = file_get_contents($enPath);
        $originalFr = file_get_contents($frPath);

        try {
            file_put_contents(
                $viewPath,
                <<<'BLADE'
{{ __('Localize test key') }}
{{ trans("Localize double key") }}
@lang('Localize lang key')
{{ trans_key('Localize custom key') }}
{{ __('We\'ve sent you a temporary login link. This link is valid for 5 minutes. Please check your inbox.') }}
BLADE
            );

            file_put_contents(
                $enPath,
                json_encode([
                    'Localize test key' => 'Preserved English Value',
                    'Localize stale key' => 'Remove me',
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL,
            );

            file_put_contents(
                $frPath,
                json_encode([
                    'Localize test key' => 'Valeur Française Conservée',
                    'Localize stale key' => 'Supprime-moi',
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL,
            );

            $this->artisan('orgos:localize en,fr')
                ->assertSuccessful();

            $enTranslations = json_decode((string) file_get_contents($enPath), true);
            $frTranslations = json_decode((string) file_get_contents($frPath), true);

            $this->assertIsArray($enTranslations);
            $this->assertIsArray($frTranslations);

            $this->assertSame('Preserved English Value', $enTranslations['Localize test key']);
            $this->assertSame('Valeur Française Conservée', $frTranslations['Localize test key']);

            $this->assertSame('Localize double key', $enTranslations['Localize double key']);
            $this->assertSame('Localize lang key', $enTranslations['Localize lang key']);
            $this->assertSame('Localize custom key', $enTranslations['Localize custom key']);
            $this->assertSame(
                'We\'ve sent you a temporary login link. This link is valid for 5 minutes. Please check your inbox.',
                $enTranslations['We\'ve sent you a temporary login link. This link is valid for 5 minutes. Please check your inbox.'],
            );

            $this->assertSame('', $frTranslations['Localize double key']);
            $this->assertSame('', $frTranslations['Localize lang key']);
            $this->assertSame('', $frTranslations['Localize custom key']);
            $this->assertSame('', $frTranslations['We\'ve sent you a temporary login link. This link is valid for 5 minutes. Please check your inbox.']);
            $this->assertArrayNotHasKey('We\\', $frTranslations);

            $this->assertArrayNotHasKey('Localize stale key', $enTranslations);
            $this->assertArrayNotHasKey('Localize stale key', $frTranslations);
        } finally {
            if ($originalView === null) {
                @unlink($viewPath);
            } else {
                file_put_contents($viewPath, $originalView);
            }

            file_put_contents($enPath, (string) $originalEn);
            file_put_contents($frPath, (string) $originalFr);
        }
    }
}
