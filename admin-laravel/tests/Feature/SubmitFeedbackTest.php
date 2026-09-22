<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubmitFeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_feedback_is_saved_locally_and_returns_a_warning_when_google_form_is_unavailable(): void
    {
        Setting::create([
            'key' => 'feedback_google_form_url',
            'name' => 'Google Form',
            'value' => 'https://docs.google.com/forms/d/e/test-form/viewform',
        ]);

        Http::fake([
            'https://docs.google.com/*' => Http::response('', 503),
        ]);

        $response = $this->postJson('/api/submit-feedback', [
            'fullname' => 'Nguyễn Văn A',
            'phone' => '0900000000',
            'title' => 'Phản ánh thử nghiệm',
            'content' => 'Nội dung phản ánh thử nghiệm.',
        ]);

        $response
            ->assertStatus(202)
            ->assertJsonPath('status', 'accepted_with_warning')
            ->assertJsonPath('sync.google_form.configured', true)
            ->assertJsonPath('sync.google_form.synced', false);

        $this->assertDatabaseHas('feedback', [
            'fullname' => 'Nguyễn Văn A',
            'title' => 'Phản ánh thử nghiệm',
            'synced_to_sheets' => false,
        ]);
    }

    public function test_feedback_is_marked_synced_after_google_form_accepts_it(): void
    {
        Setting::create([
            'key' => 'feedback_google_form_url',
            'name' => 'Google Form',
            'value' => 'https://docs.google.com/forms/d/e/test-form/viewform',
        ]);

        Cache::put('gf_entries_v2_' . md5('https://docs.google.com/forms/d/e/test-form/viewform'), [
            'fullname' => 'entry.2044570576',
            'phone' => 'entry.190937000',
            'title' => 'entry.547243983',
            'content' => 'entry.1145623248',
        ], now()->addHour());

        Http::fake(function ($request) {
            if (str_contains($request->url(), '/formResponse')) {
                return Http::response('', 200);
            }

            return Http::response('', 503);
        });

        $response = $this->postJson('/api/submit-feedback', [
            'fullname' => 'Trần Thị B',
            'phone' => '0911111111',
            'title' => 'Phản ánh đồng bộ',
            'content' => 'Nội dung phản ánh được Google Form tiếp nhận.',
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('sync.google_form.synced', true);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/formResponse')
                && $request['entry.2044570576'] === 'Trần Thị B'
                && $request['entry.190937000'] === '0911111111'
                && $request['entry.547243983'] === 'Phản ánh đồng bộ'
                && $request['entry.1145623248'] === 'Nội dung phản ánh được Google Form tiếp nhận.';
        });

        $this->assertDatabaseHas('feedback', [
            'fullname' => 'Trần Thị B',
            'synced_to_sheets' => true,
        ]);
    }
}
