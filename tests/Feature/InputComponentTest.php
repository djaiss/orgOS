<?php

declare(strict_types = 1);

namespace Tests\Feature;

use Tests\TestCase;

class InputComponentTest extends TestCase
{
    public function test_it_shows_remaining_character_counter_when_label_and_max_characters_are_set(): void
    {
        $view = $this->blade('<x-input id="title" label="Title" max-characters="10" value="abc" />');

        $view->assertSee('maxlength="10"', false);
        $view->assertSee('x-text="remainingCharacters"', false);
        $view->assertSee('remainingCharacters: 7', false);
    }

    public function test_it_does_not_show_remaining_character_counter_without_label(): void
    {
        $view = $this->blade('<x-input id="title" max-characters="10" value="abc" />');

        $view->assertSee('maxlength="10"', false);
        $view->assertDontSee('x-text="remainingCharacters"', false);
        $view->assertDontSee('remainingCharacters: 7', false);
    }
}
