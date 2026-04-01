<?php

namespace Tests\Feature;

use App\Models\Conjugation;
use App\Models\ThemeSetting;
use App\Models\User;
use App\Models\Verb;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $defaultConnection = (string) config('database.default', '');

        if ($defaultConnection === 'sqlite' && ! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite extension is required to run tests with sqlite.');
        }
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_admin_theme_redirects_guests_to_login(): void
    {
        $this->get('/admin/theme')
            ->assertRedirect('/login');
    }

    public function test_admin_theme_v2_redirects_guests_to_login(): void
    {
        $this->get('/admin/theme-v2')
            ->assertRedirect('/login');
    }

    public function test_admin_system_redirects_guests_to_login(): void
    {
        $this->get('/admin/system')
            ->assertRedirect('/login');
    }

    public function test_admin_theme_forbids_non_admin_users(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/theme')
            ->assertStatus(403);
    }

    public function test_admin_theme_v2_forbids_non_admin_users(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/theme-v2')
            ->assertStatus(403);
    }

    public function test_admin_system_forbids_non_admin_users(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/system')
            ->assertStatus(403);
    }

    public function test_admin_theme_allows_admin_users(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->get('/admin/theme')
            ->assertStatus(200);
    }

    public function test_admin_theme_v2_allows_admin_users(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->get('/admin/theme-v2')
            ->assertStatus(200);
    }

    public function test_admin_system_allows_admin_users(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->get('/admin/system')
            ->assertStatus(200)
            ->assertSeeText('Système');
    }

    public function test_admin_system_can_clear_theme_cache(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->post('/admin/system/action', ['action' => 'theme_cache_clear'])
            ->assertRedirect()
            ->assertSessionHas('success');
    }

    public function test_admin_can_update_theme_settings(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->postJson('/admin/theme', [
                'settings' => [
                    '--rami-card-bg' => '#ffffff',
                    '--rami-card-border-style' => 'dashed',
                    '--rami-card-border-width' => '2px',
                ],
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('theme_settings', [
            'key' => '--rami-card-bg',
            'value' => '#ffffff',
        ]);
        $this->assertDatabaseHas('theme_settings', [
            'key' => '--rami-card-border-style',
            'value' => 'dashed',
        ]);
        $this->assertDatabaseHas('theme_settings', [
            'key' => '--rami-card-border-width',
            'value' => '2px',
        ]);
    }

    public function test_admin_can_reset_theme_settings_to_defaults(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        ThemeSetting::query()->updateOrCreate(['key' => '--rami-card-bg'], ['value' => '#000000']);

        $this->actingAs($user)
            ->postJson('/admin/theme/reset')
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('theme_settings', [
            'key' => '--rami-card-bg',
            'value' => '#faf8f5',
        ]);
    }

    public function test_health_endpoint_returns_json(): void
    {
        $this->get('/health')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'checks' => ['db', 'storage'],
            ]);
    }

    public function test_long_illustration_description_is_rendered_below_the_frame(): void
    {
        $user = User::factory()->create();

        $verb = Verb::query()->create([
            'infinitive' => 'avoir',
            'infinitive_translation' => 'to have',
            'group' => '3ème',
            'illustration_description' => 'personne tenant quelque chose (description très longue pour test)',
            'theme_color' => '#5a2d5a',
            'accent_color' => '#9a6d9a',
            'pattern' => 'plain',
            'is_active' => true,
        ]);

        Conjugation::query()->create([
            'verb_id' => $verb->id,
            'tense' => 'présent',
            'je' => 'ai',
            'tu' => 'as',
            'il_elle_on' => 'a',
            'nous' => 'avons',
            'vous' => 'avez',
            'ils_elles' => 'ont',
        ]);

        $this->actingAs($user)
            ->get('/cards/'.$verb->id)
            ->assertStatus(200)
            ->assertSeeText('personne tenant quelque chose (description très longue pour test)');
    }

    public function test_card_create_form_is_accessible(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/cards/create')
            ->assertStatus(200);
    }

    public function test_card_store_creates_verb_and_conjugation(): void
    {
        $user = User::factory()->create();

        $payload = [
            'infinitive' => 'manger',
            'infinitive_translation' => 'to eat',
            'group' => '1er',
            'theme_color' => '#1e3a5f',
            'accent_color' => '#5b9bd5',
            'pattern' => 'plain',
            'je' => 'mange',
            'tu' => 'manges',
            'il_elle_on' => 'mange',
            'nous' => 'mangeons',
            'vous' => 'mangez',
            'ils_elles' => 'mangent',
        ];

        $this->actingAs($user)
            ->post('/cards', $payload)
            ->assertRedirect('/cards')
            ->assertSessionHas('success');

        $verb = Verb::query()->where('infinitive', 'manger')->first();
        $this->assertNotNull($verb);
        $this->assertSame('1er', $verb->group);
        $this->assertSame('heart', $verb->suit);

        $this->assertDatabaseHas('conjugations', [
            'verb_id' => $verb->id,
            'tense' => 'présent',
            'je' => 'mange',
            'tu' => 'manges',
            'il_elle_on' => 'mange',
            'nous' => 'mangeons',
            'vous' => 'mangez',
            'ils_elles' => 'mangent',
        ]);
    }

    public function test_card_store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/cards', [
            'infinitive' => '',
            'group' => '1er',
        ])->assertSessionHasErrors([
            'infinitive',
            'je',
            'tu',
            'il_elle_on',
            'nous',
            'vous',
            'ils_elles',
        ]);

        $this->assertSame(0, Verb::query()->count());
    }

    public function test_card_store_rejects_group_infinitive_mismatch(): void
    {
        $user = User::factory()->create();

        $payload = [
            'infinitive' => 'finir',
            'infinitive_translation' => 'to finish',
            'group' => '1er',
            'je' => 'finis',
            'tu' => 'finis',
            'il_elle_on' => 'finit',
            'nous' => 'finissons',
            'vous' => 'finissez',
            'ils_elles' => 'finissent',
        ];

        $this->actingAs($user)
            ->post('/cards', $payload)
            ->assertSessionHasErrors(['infinitive']);

        $this->assertDatabaseMissing('verbs', ['infinitive' => 'finir']);
    }

    public function test_card_store_rejects_duplicate_infinitive_case_insensitive(): void
    {
        $user = User::factory()->create();

        Verb::query()->create([
            'infinitive' => 'manger',
            'infinitive_translation' => 'to eat',
            'group' => '1er',
            'theme_color' => '#1e3a5f',
            'accent_color' => '#5b9bd5',
            'pattern' => 'plain',
            'is_active' => true,
        ]);

        $payloadDifferentSuit = [
            'infinitive' => 'Manger',
            'infinitive_translation' => 'to eat',
            'group' => '1er',
            'suit' => 'spade',
            'je' => 'mange',
            'tu' => 'manges',
            'il_elle_on' => 'mange',
            'nous' => 'mangeons',
            'vous' => 'mangez',
            'ils_elles' => 'mangent',
        ];

        $this->actingAs($user)
            ->post('/cards', $payloadDifferentSuit)
            ->assertSessionHasErrors(['infinitive']);

        $this->assertSame(1, Verb::query()->where('infinitive', 'manger')->count());
    }

    public function test_card_store_forces_suit_by_group(): void
    {
        $user = User::factory()->create();

        $base = [
            'infinitive' => 'rougir',
            'infinitive_translation' => 'to blush',
            'group' => '2ème',
            'theme_color' => '#2d5a3d',
            'accent_color' => '#4fb286',
            'pattern' => 'plain',
            'je' => 'rougis',
            'tu' => 'rougis',
            'il_elle_on' => 'rougit',
            'nous' => 'rougissons',
            'vous' => 'rougissez',
            'ils_elles' => 'rougissent',
        ];

        $this->actingAs($user)
            ->post('/cards', array_merge($base, ['suit' => 'spade']))
            ->assertRedirect('/cards')
            ->assertSessionHas('success');

        $verb = Verb::query()->where('infinitive', 'rougir')->first();
        $this->assertNotNull($verb);
        $this->assertSame('diamond', $verb->suit);

        $this->actingAs($user)
            ->post('/cards', array_merge($base, ['suit' => 'heart']))
            ->assertSessionHasErrors(['infinitive']);
    }

    public function test_card_store_rejects_invalid_pattern_and_colors(): void
    {
        $user = User::factory()->create();

        $payload = [
            'infinitive' => 'parler',
            'infinitive_translation' => 'to speak',
            'group' => '1er',
            'theme_color' => 'blue',
            'accent_color' => '#zzz999',
            'pattern' => 'not-a-pattern',
            'je' => 'parle',
            'tu' => 'parles',
            'il_elle_on' => 'parle',
            'nous' => 'parlons',
            'vous' => 'parlez',
            'ils_elles' => 'parlent',
        ];

        $this->actingAs($user)
            ->post('/cards', $payload)
            ->assertSessionHasErrors(['theme_color', 'accent_color', 'pattern']);

        $this->assertDatabaseMissing('verbs', ['infinitive' => 'parler']);
    }
}
