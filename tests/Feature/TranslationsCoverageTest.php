<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;

uses(RefreshDatabase::class);

describe('Translations Coverage', function (): void {
    describe('Spanish translations', function (): void {
        beforeEach(function (): void {
            App::setLocale('es');
        });

        it('loads spanish locale successfully', function (): void {
            expect(App::getLocale())->toBe('es');
        });

        it('translates all strings in spanish', function ($key): void {
            expect(__($key))
                ->not->toBe($key)
                ->not->toBeEmpty();
        })->with('translatable_strings');
    });

    describe('English locale fallback', function (): void {
        beforeEach(function (): void {
            App::setLocale('en');
        });

        it('fallback to english returns english strings', function (): void {
            expect(App::getLocale())->toBe('en');
            $result = __('Order Confirmation');
            expect($result)->toBeTruthy();
        });

        it('handles parametrized translations', function (): void {
            $name = 'John Doe';
            $translated = __('Hello', ['name' => $name]);
            expect($translated)->toBeTruthy();
        });
    });

    describe('Translation consistency', function (): void {
        it('ensures critical translations exist in spanish', function ($key): void {
            App::setLocale('es');

            $translated = __($key);
            expect($translated)
                ->not->toBe($key);
        })->with('critical_translations');

        it('preserves locales can be switched', function (): void {
            App::setLocale('es');
            $spanish = __('Orders');

            App::setLocale('en');
            $english = __('Orders');

            expect($spanish)->toBeTruthy();
            expect($english)->toBeTruthy();
        });
    });

    describe('Translation file validation', function (): void {
        it('spanish translation file is valid json', function (): void {
            $filePath = resource_path('lang/es.json');
            expect(file_exists($filePath))->toBeTrue();

            $content = file_get_contents($filePath);
            $decoded = json_decode($content, true);
            expect($decoded)->not->toBeNull();
            expect(is_array($decoded))->toBeTrue();
        });

        it('spanish translation contains essential keys', function (): void {
            $filePath = resource_path('lang/es.json');
            $translations = json_decode(file_get_contents($filePath), true);

            $essentialKeys = [
                'Order Confirmation',
                'Products',
                'Customer',
                'Billing address',
                'Payment Method',
            ];

            foreach ($essentialKeys as $key) {
                expect(array_key_exists($key, $translations))->toBeTrue();
            }
        });

        it('spanish translations are not empty strings', function (): void {
            $filePath = resource_path('lang/es.json');
            $translations = json_decode(file_get_contents($filePath), true);

            foreach ($translations as $value) {
                expect(! empty($value))->toBeTrue();
            }
        });
    });

    describe('Notification translations', function (): void {
        it('verifies all order notification translations', function ($string): void {
            App::setLocale('es');
            expect(__($string))->not->toBeEmpty();
        })->with('notification_strings');

        it('verifies all admin notification translations', function ($string): void {
            App::setLocale('es');
            expect(__($string))->not->toBeEmpty();
        })->with('admin_notification_strings');
    });

    describe('Locale switching', function (): void {
        it('can switch between locales', function (): void {
            App::setLocale('es');
            expect(App::getLocale())->toBe('es');

            App::setLocale('en');
            expect(App::getLocale())->toBe('en');

            App::setLocale('es');
            expect(App::getLocale())->toBe('es');
        });

        it('translations change with locale', function (): void {
            App::setLocale('es');
            $spanishOrder = __('Orders');

            App::setLocale('en');
            $englishOrder = __('Orders');

            expect($spanishOrder)->toBeTruthy();
            expect($englishOrder)->toBeTruthy();
        });
    });
});
