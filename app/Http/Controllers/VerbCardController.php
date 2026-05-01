<?php

namespace App\Http\Controllers;

use App\Models\Conjugation;
use App\Models\ThemeSetting;
use App\Models\UserVerbProgress;
use App\Models\Verb;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VerbCardController extends Controller
{
    private const RAMI_PATTERNS = [
        'arabesque',
        'artdeco',
        'artnouveau',
        'bamboo',
        'baroque',
        'bricks',
        'brocart',
        'broderie',
        'byzantine',
        'celtic',
        'checker',
        'chevron',
        'chevronfr',
        'circles',
        'circuit',
        'constellation',
        'coquille',
        'crosses',
        'crosshatch',
        'crystalline',
        'damask',
        'diagonal-checker',
        'diamonds',
        'dots',
        'ecailles',
        'feather',
        'fleurdelys',
        'foliage',
        'geometry',
        'graph',
        'grid',
        'guilloche',
        'halfcircles',
        'herringbone',
        'hexagons',
        'houndstooth',
        'ikat',
        'japanese-wave',
        'labyrinth',
        'lace',
        'liberty',
        'mandala',
        'marble',
        'minimal',
        'mixed',
        'moroccan',
        'mosaic',
        'mosaicfr',
        'nautical',
        'nordic',
        'origami',
        'ornate',
        'paisley',
        'plain',
        'pointhongrie',
        'quilted',
        'renaissance',
        'scales',
        'seigaiha',
        'stainedglass',
        'starmap',
        'stars',
        'stripes',
        'tapisserie',
        'terrazzo',
        'ticks',
        'toiledejouy',
        'topographic',
        'triangles',
        'tribal',
        'versailles',
        'waves',
        'weave',
        'zigzag',
    ];

    /**
     * Afficher la page principale avec les cartes de conjugaison
     */
    public function index(Request $request)
    {
        return $this->renderIndex($request, 2);
    }

    public function indexV3(Request $request)
    {
        return $this->renderIndex($request, 3);
    }

    /**
     * Afficher une carte de verbe spécifique
     */
    public function show(Verb $verb)
    {
        return $this->renderShow($verb, 2);
    }

    public function showV3(Verb $verb)
    {
        return $this->renderShow($verb, 3);
    }

    public function favorites(Request $request)
    {
        return $this->renderFavorites($request, 2);
    }

    public function favoritesV3(Request $request)
    {
        return $this->renderFavorites($request, 3);
    }

    private function renderIndex(Request $request, int $cardsUiVersion)
    {
        $group = $request->query('group');
        $query = trim((string) $request->query('q', ''));

        $verbs = Verb::query()
            ->with('conjugations')
            ->where('is_active', true)
            ->when(in_array($group, ['1er', '2ème', '3ème'], true), function ($builder) use ($group) {
                $builder->where('group', $group);
            })
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($q) use ($query) {
                    $q->where('infinitive', 'like', '%'.$query.'%')
                        ->orWhere('infinitive_translation', 'like', '%'.$query.'%');
                });
            })
            ->orderBy('group')
            ->orderBy('infinitive')
            ->orderBy('suit')
            ->paginate(24)
            ->withQueryString();

        $user = Auth::user();

        $viewData = [
            'verbs' => $verbs,
            'selectedGroup' => in_array($group, ['1er', '2ème', '3ème'], true) ? $group : 'all',
            'searchQuery' => $query,
            'favoriteVerbIds' => $user && Schema::hasTable('favorites')
                ? $user->favorites()->pluck('verbs.id')->map(fn ($id) => (int) $id)->all()
                : [],
            'cardsUiVersion' => $cardsUiVersion,
        ];

        $viewData = array_merge($viewData, $this->buildThemeOverrides($cardsUiVersion));

        return view('cards.index', $viewData);
    }

    private function renderShow(Verb $verb, int $cardsUiVersion)
    {
        $verb->load('conjugations');

        $user = Auth::user();
        $isFavorited = $user && Schema::hasTable('favorites')
            ? $user->favorites()->where('verbs.id', $verb->id)->exists()
            : false;

        $viewData = [
            'verb' => $verb,
            'isFavorited' => $isFavorited,
            'cardsUiVersion' => $cardsUiVersion,
        ];

        $viewData = array_merge($viewData, $this->buildThemeOverrides($cardsUiVersion));

        return view('cards.show', $viewData);
    }

    private function renderFavorites(Request $request, int $cardsUiVersion)
    {
        if (! Schema::hasTable('favorites')) {
            return redirect()
                ->route('cards.index')
                ->with('error', 'La table des favoris est manquante. Exécutez les migrations pour activer cette fonctionnalité.');
        }

        $group = $request->query('group');
        $query = trim((string) $request->query('q', ''));

        $verbs = $request->user()
            ->favorites()
            ->with('conjugations')
            ->where('is_active', true)
            ->when(in_array($group, ['1er', '2ème', '3ème'], true), function ($builder) use ($group) {
                $builder->where('group', $group);
            })
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($q) use ($query) {
                    $q->where('infinitive', 'like', '%'.$query.'%')
                        ->orWhere('infinitive_translation', 'like', '%'.$query.'%');
                });
            })
            ->orderBy('group')
            ->orderBy('infinitive')
            ->orderBy('suit')
            ->paginate(24)
            ->withQueryString();

        $viewData = [
            'verbs' => $verbs,
            'selectedGroup' => in_array($group, ['1er', '2ème', '3ème'], true) ? $group : 'all',
            'searchQuery' => $query,
            'favoriteVerbIds' => $request->user()->favorites()->pluck('verbs.id')->map(fn ($id) => (int) $id)->all(),
            'cardsUiVersion' => $cardsUiVersion,
        ];

        $viewData = array_merge($viewData, $this->buildThemeOverrides($cardsUiVersion));

        return view('cards.favorites', $viewData);
    }

    public function quiz(Request $request)
    {
        $group = $request->query('group');

        return view('quiz.index', [
            'selectedGroup' => in_array($group, ['1er', '2ème', '3ème'], true) ? $group : 'all',
        ]);
    }

    public function recordProgress(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if (! Schema::hasTable('user_verb_progress')) {
            return response()->json(['error' => 'Progress table missing. Run migrations.'], 409);
        }

        $validated = $request->validate([
            'verb_id' => ['required', 'integer', 'exists:verbs,id'],
            'is_correct' => ['required', 'boolean'],
        ]);

        $now = now();
        $isCorrect = (bool) $validated['is_correct'];

        $progress = UserVerbProgress::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'verb_id' => (int) $validated['verb_id'],
            ],
            [
                'correct_count' => 0,
                'wrong_count' => 0,
                'streak' => 0,
                'last_practiced_at' => null,
                'next_review_at' => null,
                'mastered_at' => null,
            ]
        );

        $progress->last_practiced_at = $now;

        if ($isCorrect) {
            $progress->correct_count = (int) $progress->correct_count + 1;
            $progress->streak = (int) $progress->streak + 1;

            $nextReviewAt = match ((int) $progress->streak) {
                1 => $now->copy()->addDay(),
                2 => $now->copy()->addDays(3),
                3 => $now->copy()->addDays(7),
                4 => $now->copy()->addDays(14),
                default => $now->copy()->addDays(30),
            };

            $progress->next_review_at = $nextReviewAt;

            if ((int) $progress->streak >= 3 && $progress->mastered_at === null) {
                $progress->mastered_at = $now;
            }
        } else {
            $progress->wrong_count = (int) $progress->wrong_count + 1;
            $progress->streak = 0;
            $progress->mastered_at = null;
            $progress->next_review_at = $now->copy()->addHour();
        }

        $progress->save();

        return response()->json([
            'ok' => true,
            'progress' => [
                'verb_id' => (int) $progress->verb_id,
                'correct_count' => (int) $progress->correct_count,
                'wrong_count' => (int) $progress->wrong_count,
                'streak' => (int) $progress->streak,
                'last_practiced_at' => $progress->last_practiced_at?->toISOString(),
                'next_review_at' => $progress->next_review_at?->toISOString(),
                'mastered_at' => $progress->mastered_at?->toISOString(),
            ],
        ]);
    }

    public function adminIndex(Request $request)
    {
        $group = $request->query('group');
        $query = trim((string) $request->query('q', ''));

        $verbs = Verb::query()
            ->when(in_array($group, ['1er', '2ème', '3ème'], true), function ($builder) use ($group) {
                $builder->where('group', $group);
            })
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($q) use ($query) {
                    $q->where('infinitive', 'like', '%'.$query.'%')
                        ->orWhere('infinitive_translation', 'like', '%'.$query.'%');
                });
            })
            ->orderBy('group')
            ->orderBy('infinitive')
            ->orderBy('suit')
            ->paginate(24)
            ->withQueryString();

        return view('admin.verbs.index', [
            'verbs' => $verbs,
            'selectedGroup' => in_array($group, ['1er', '2ème', '3ème'], true) ? $group : 'all',
            'searchQuery' => $query,
        ]);
    }

    public function adminToggle(Request $request, Verb $verb)
    {
        $verb->update(['is_active' => ! $verb->is_active]);

        return back()->with('success', $verb->is_active ? 'Verbe activé.' : 'Verbe désactivé.');
    }

    public function adminEdit(Verb $verb)
    {
        $verb->load('conjugations');
        $conjugation = $verb->getPresentConjugation();

        return view('cards.create', [
            'initial' => [
                'infinitive' => (string) $verb->infinitive,
                'infinitive_translation' => $verb->infinitive_translation ? (string) $verb->infinitive_translation : '',
                'example_sentence' => $verb->example_sentence ? (string) $verb->example_sentence : '',
                'group' => (string) $verb->group,
                'suit' => (string) $verb->suit,
                'theme_color' => $verb->theme_color ? (string) $verb->theme_color : '',
                'accent_color' => $verb->accent_color ? (string) $verb->accent_color : '',
                'pattern' => $verb->pattern ? (string) $verb->pattern : '',
                'je' => $conjugation ? $verb->formatConjugation('je', (string) ($conjugation->je ?? '')) : '',
                'tu' => $conjugation ? $verb->formatConjugation('tu', (string) ($conjugation->tu ?? '')) : '',
                'il_elle_on' => $conjugation ? $verb->formatConjugation('il_elle_on', (string) ($conjugation->il_elle_on ?? '')) : '',
                'nous' => $conjugation ? $verb->formatConjugation('nous', (string) ($conjugation->nous ?? '')) : '',
                'vous' => $conjugation ? $verb->formatConjugation('vous', (string) ($conjugation->vous ?? '')) : '',
                'ils_elles' => $conjugation ? $verb->formatConjugation('ils_elles', (string) ($conjugation->ils_elles ?? '')) : '',
            ],
            'editSource' => $verb,
            'formAction' => route('admin.verbs.update', $verb),
            'formMethod' => 'PUT',
            'cancelUrl' => route('admin.verbs.index'),
            'submitLabel' => 'Enregistrer les modifications',
            'pageTitle' => 'Modifier une carte - FrenchVerbs',
        ]);
    }

    public function adminUpdate(Request $request, Verb $verb)
    {
        $validated = $request->validate([
            'infinitive' => [
                'required',
                'string',
                'max:100',
                function (string $attribute, mixed $value, \Closure $fail) use ($request, $verb): void {
                    $infinitive = mb_strtolower(trim((string) $value));
                    $group = (string) $request->input('group', '');
                    $suit = (string) $request->input('suit', 'spade');

                    if ($group === '1er' && ! Str::endsWith($infinitive, 'er')) {
                        $fail('L’infinitif doit se terminer par « er » pour le 1er groupe.');
                    }

                    if ($group === '2ème' && ! Str::endsWith($infinitive, 'ir')) {
                        $fail('L’infinitif doit se terminer par « ir » pour le 2ème groupe.');
                    }

                    $exists = Verb::query()
                        ->whereKeyNot($verb->id)
                        ->whereRaw('LOWER(infinitive) = ?', [$infinitive])
                        ->where('suit', $suit)
                        ->exists();

                    if ($exists) {
                        $fail('Ce verbe existe déjà avec cette couleur.');
                    }
                },
            ],
            'infinitive_translation' => 'nullable|string|max:100',
            'example_sentence' => 'nullable|string|max:255',
            'group' => 'required|in:1er,2ème,3ème',
            'suit' => 'required|in:heart,spade,diamond,club',
            'theme_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'accent_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'pattern' => ['nullable', 'string', 'max:32', 'regex:/^[a-z0-9-]+$/', Rule::in(self::RAMI_PATTERNS)],
            'je' => 'required|string|max:100',
            'tu' => 'required|string|max:100',
            'il_elle_on' => 'required|string|max:100',
            'nous' => 'required|string|max:100',
            'vous' => 'required|string|max:100',
            'ils_elles' => 'required|string|max:100',
        ]);

        $validated['infinitive'] = mb_strtolower(trim((string) $validated['infinitive']));

        if ($validated['infinitive'] === 'aller') {
            $validated['group'] = '3ème';
        }

        $message = Verb::editVerb($verb->id, $validated);

        return redirect()->route('admin.verbs.index')->with('success', $message);
    }

    /**
     * Afficher le formulaire de création de carte
     */
    public function create()
    {
        return view('cards.create');
    }

    public function duplicate(Verb $verb)
    {
        $verb->load('conjugations');
        $conjugation = $verb->getPresentConjugation();

        return view('cards.create', [
            'initial' => [
                'infinitive' => (string) $verb->infinitive,
                'infinitive_translation' => $verb->infinitive_translation ? (string) $verb->infinitive_translation : '',
                'example_sentence' => $verb->example_sentence ? (string) $verb->example_sentence : '',
                'group' => (string) $verb->group,
                'suit' => (string) $verb->suit,
                'theme_color' => $verb->theme_color ? (string) $verb->theme_color : '',
                'accent_color' => $verb->accent_color ? (string) $verb->accent_color : '',
                'pattern' => $verb->pattern ? (string) $verb->pattern : '',
                'je' => $conjugation ? $verb->formatConjugation('je', (string) ($conjugation->je ?? '')) : '',
                'tu' => $conjugation ? $verb->formatConjugation('tu', (string) ($conjugation->tu ?? '')) : '',
                'il_elle_on' => $conjugation ? $verb->formatConjugation('il_elle_on', (string) ($conjugation->il_elle_on ?? '')) : '',
                'nous' => $conjugation ? $verb->formatConjugation('nous', (string) ($conjugation->nous ?? '')) : '',
                'vous' => $conjugation ? $verb->formatConjugation('vous', (string) ($conjugation->vous ?? '')) : '',
                'ils_elles' => $conjugation ? $verb->formatConjugation('ils_elles', (string) ($conjugation->ils_elles ?? '')) : '',
            ],
            'duplicateSource' => $verb,
        ]);
    }

    /**
     * Enregistrer une nouvelle carte de verbe
     */
    public function store(Request $request)
    {
        $infinitiveNormalized = mb_strtolower(trim((string) $request->input('infinitive', '')));
        $groupValue = trim((string) $request->input('group', ''));
        if ($infinitiveNormalized === 'aller') {
            $groupValue = '3ème';
        }
        $forcedSuit = $this->resolveSuitForVerb($groupValue, $infinitiveNormalized);

        $request->merge([
            'infinitive' => $infinitiveNormalized,
            'infinitive_translation' => trim((string) $request->input('infinitive_translation', '')) ?: null,
            'group' => $groupValue,
            'suit' => $forcedSuit,
            'je' => trim((string) $request->input('je', '')),
            'tu' => trim((string) $request->input('tu', '')),
            'il_elle_on' => trim((string) $request->input('il_elle_on', '')),
            'nous' => trim((string) $request->input('nous', '')),
            'vous' => trim((string) $request->input('vous', '')),
            'ils_elles' => trim((string) $request->input('ils_elles', '')),
        ]);

        $validated = $request->validate([
            'infinitive' => [
                'required',
                'string',
                'max:100',
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    $infinitive = mb_strtolower(trim((string) $value));
                    $group = (string) $request->input('group', '');
                    $suit = (string) $request->input('suit', 'spade');

                    if ($group === '1er' && ! Str::endsWith($infinitive, 'er')) {
                        $fail('L’infinitif doit se terminer par « er » pour le 1er groupe.');
                    }

                    if ($group === '2ème' && ! Str::endsWith($infinitive, 'ir')) {
                        $fail('L’infinitif doit se terminer par « ir » pour le 2ème groupe.');
                    }

                    $exists = Verb::query()
                        ->whereRaw('LOWER(infinitive) = ?', [$infinitive])
                        ->where('suit', $suit)
                        ->exists();

                    if ($exists) {
                        $fail('Ce verbe existe déjà avec cette couleur.');
                    }
                },
            ],
            'infinitive_translation' => 'nullable|string|max:100',
            'example_sentence' => 'nullable|string|max:255',
            'group' => 'required|in:1er,2ème,3ème',
            'suit' => 'required|in:heart,spade,diamond,club',
            'theme_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'accent_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'pattern' => ['nullable', 'string', 'max:32', 'regex:/^[a-z0-9-]+$/', Rule::in(self::RAMI_PATTERNS)],
            'je' => 'required|string|max:100',
            'tu' => 'required|string|max:100',
            'il_elle_on' => 'required|string|max:100',
            'nous' => 'required|string|max:100',
            'vous' => 'required|string|max:100',
            'ils_elles' => 'required|string|max:100',
        ]);

        $verb = Verb::create([
            'infinitive' => $validated['infinitive'],
            'infinitive_translation' => $validated['infinitive_translation'] ?? null,
            'example_sentence' => $validated['example_sentence'] ?? null,
            'group' => $validated['group'],
            'suit' => $validated['suit'],
            'theme_color' => $validated['theme_color'] ?? '#1e3a5f',
            'accent_color' => $validated['accent_color'] ?? '#5b9bd5',
            'pattern' => $validated['pattern'] ?? null,
        ]);

        Conjugation::create([
            'verb_id' => $verb->id,
            'tense' => 'présent',
            'je' => $validated['je'],
            'tu' => $validated['tu'],
            'il_elle_on' => $validated['il_elle_on'],
            'nous' => $validated['nous'],
            'vous' => $validated['vous'],
            'ils_elles' => $validated['ils_elles'],
        ]);

        return redirect()->route('cards.index')->with('success', 'Carte créée avec succès!');
    }

    /**
     * API pour obtenir les données d'un verbe en JSON
     */
    public function apiShow(Verb $verb): JsonResponse
    {
        $verb->load('conjugations');
        $conjugation = $verb->getPresentConjugation();

        return response()->json([
            'verb' => $verb,
            'present' => $conjugation ? [
                ['pronoun' => 'je', 'conjugation' => $verb->formatConjugation('je', $conjugation->je)],
                ['pronoun' => 'tu', 'conjugation' => $verb->formatConjugation('tu', $conjugation->tu)],
                ['pronoun' => 'il/elle/on', 'conjugation' => $verb->formatConjugation('il_elle_on', $conjugation->il_elle_on)],
                ['pronoun' => 'nous', 'conjugation' => $verb->formatConjugation('nous', $conjugation->nous)],
                ['pronoun' => 'vous', 'conjugation' => $verb->formatConjugation('vous', $conjugation->vous)],
                ['pronoun' => 'ils/elles', 'conjugation' => $verb->formatConjugation('ils_elles', $conjugation->ils_elles)],
            ] : [],
        ]);
    }

    /**
     * API pour obtenir tous les verbes
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $group = $request->query('group');
        $query = trim((string) $request->query('q', ''));

        $verbs = Verb::query()
            ->with('conjugations')
            ->where('is_active', true)
            ->when(in_array($group, ['1er', '2ème', '3ème'], true), function ($builder) use ($group) {
                $builder->where('group', $group);
            })
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($q) use ($query) {
                    $q->where('infinitive', 'like', '%'.$query.'%')
                        ->orWhere('infinitive_translation', 'like', '%'.$query.'%');
                });
            })
            ->orderBy('group')
            ->orderBy('infinitive')
            ->orderBy('suit')
            ->get()
            ->map(function ($verb) {
                $conjugation = $verb->getPresentConjugation();
                $present = [];
                if ($conjugation) {
                    $present = [
                        ['pronoun' => 'je', 'conjugation' => $verb->formatConjugation('je', $conjugation->je)],
                        ['pronoun' => 'tu', 'conjugation' => $verb->formatConjugation('tu', $conjugation->tu)],
                        ['pronoun' => 'il/elle/on', 'conjugation' => $verb->formatConjugation('il_elle_on', $conjugation->il_elle_on)],
                        ['pronoun' => 'nous', 'conjugation' => $verb->formatConjugation('nous', $conjugation->nous)],
                        ['pronoun' => 'vous', 'conjugation' => $verb->formatConjugation('vous', $conjugation->vous)],
                        ['pronoun' => 'ils/elles', 'conjugation' => $verb->formatConjugation('ils_elles', $conjugation->ils_elles)],
                    ];
                }

                return [
                    'id' => $verb->id,
                    'infinitive' => $verb->infinitive,
                    'translation' => $verb->infinitive_translation,
                    'example_sentence' => $verb->example_sentence,
                    'group' => $verb->group,
                    'suit' => $verb->suit,
                    'theme_color' => $verb->theme_color,
                    'accent_color' => $verb->accent_color,
                    'illustration' => $verb->illustration_path,
                    'present' => $present,
                ];
            });

        return response()->json($verbs);
    }

    /**
     * Imprimer une carte
     */
    public function print(Verb $verb)
    {
        return $this->renderPrint($verb, 2);
    }

    public function printV3(Verb $verb)
    {
        return $this->renderPrint($verb, 3);
    }

    public function printBack(Verb $verb)
    {
        return $this->renderPrint($verb, 2, [
            'backOnly' => true,
            'includeBack' => true,
        ]);
    }

    public function printBackV3(Verb $verb)
    {
        return $this->renderPrint($verb, 3, [
            'backOnly' => true,
            'includeBack' => true,
        ]);
    }

    public function printDeck(Request $request)
    {
        return $this->renderPrintDeck($request, 2);
    }

    public function printDeckV3(Request $request)
    {
        return $this->renderPrintDeck($request, 3);
    }

    public function printDeckBack(Request $request)
    {
        return $this->renderPrintDeck($request, 2, [
            'backOnly' => true,
            'includeBack' => true,
        ]);
    }

    public function printDeckBackV3(Request $request)
    {
        return $this->renderPrintDeck($request, 3, [
            'backOnly' => true,
            'includeBack' => true,
        ]);
    }

    private function renderPrint(Verb $verb, int $printUiVersion, array $options = [])
    {
        $verb->load('conjugations');

        $conjugation = $verb->getPresentConjugation();

        $viewData = [
            'verb' => $verb,
            'conjugation' => $conjugation,
            'printUiVersion' => $printUiVersion,
            'includeBack' => (bool) ($options['includeBack'] ?? false),
            'backOnly' => (bool) ($options['backOnly'] ?? false),
        ];

        $viewData = array_merge($viewData, $this->buildThemeOverrides($printUiVersion));

        return view('cards.print', $viewData);
    }

    private function renderPrintDeck(Request $request, int $printUiVersion, array $options = [])
    {
        $groupParam = $request->query('group');
        $query = trim((string) $request->query('q', ''));
        $verbFilter = trim((string) $request->query('verb', ''));
        $includeBack = (bool) ($options['includeBack'] ?? false) || ((string) $request->query('include_back', '') === '1');
        $backOnly = (bool) ($options['backOnly'] ?? false) || ((string) $request->query('back_only', '') === '1');
        $irregularOnly = (string) $request->query('irregular', '') === '1';
        $selectedPagesRaw = $request->query('pages');
        $selectedCardsRaw = $request->query('cards');

        $allowedGroups = ['1er', '2ème', '3ème'];
        $selectedGroups = [];
        if (is_array($groupParam)) {
            $selectedGroups = array_values(array_unique(array_values(array_filter(
                array_map(static fn ($g) => is_string($g) ? trim($g) : '', $groupParam),
                static fn ($g) => in_array($g, $allowedGroups, true)
            ))));
        } elseif (is_string($groupParam) && in_array($groupParam, $allowedGroups, true)) {
            $selectedGroups = [$groupParam];
        }

        $selectedPages = is_numeric($selectedPagesRaw) ? (int) $selectedPagesRaw : null;
        if ($selectedPages !== null && $selectedPages <= 0) {
            $selectedPages = null;
        }

        $selectedCardsLimit = is_numeric($selectedCardsRaw) ? (int) $selectedCardsRaw : null;
        if ($selectedCardsLimit !== null && $selectedCardsLimit <= 0) {
            $selectedCardsLimit = null;
        }

        $verbs = Verb::query()
            ->with('conjugations')
            ->where('is_active', true)
            ->when($irregularOnly, function ($builder) {
                $builder->where(function ($q) {
                    $q->where('group', '3ème')
                        ->orWhereRaw('LOWER(infinitive) IN (?, ?, ?)', ['aller', 'être', 'avoir']);
                });
            })
            ->when(count($selectedGroups) > 0, function ($builder) use ($selectedGroups) {
                $builder->whereIn('group', $selectedGroups);
            })
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($q) use ($query) {
                    $q->where('infinitive', 'like', '%'.$query.'%')
                        ->orWhere('infinitive_translation', 'like', '%'.$query.'%');
                });
            })
            ->when($verbFilter !== '', function ($builder) use ($verbFilter) {
                $builder->where('infinitive', $verbFilter);
            })
            ->orderBy('group')
            ->orderBy('infinitive')
            ->orderBy('suit')
            ->get();

        $pronouns = [
            ['pronoun_key' => 'je', 'pronoun_label' => null, 'conjugation_key' => 'je'],
            ['pronoun_key' => 'tu', 'pronoun_label' => 'TU', 'conjugation_key' => 'tu'],
            ['pronoun_key' => 'il', 'pronoun_label' => 'IL', 'conjugation_key' => 'il_elle_on'],
            ['pronoun_key' => 'elle', 'pronoun_label' => 'ELLE', 'conjugation_key' => 'il_elle_on'],
            ['pronoun_key' => 'nous', 'pronoun_label' => 'NOUS', 'conjugation_key' => 'nous'],
            ['pronoun_key' => 'vous', 'pronoun_label' => 'VOUS', 'conjugation_key' => 'vous'],
            ['pronoun_key' => 'ils', 'pronoun_label' => 'ILS', 'conjugation_key' => 'ils_elles'],
            ['pronoun_key' => 'elles', 'pronoun_label' => 'ELLES', 'conjugation_key' => 'ils_elles'],
        ];

        $cards = [];
        $cardsByGroup = [];

        foreach ($verbs as $verb) {
            $conjugation = $verb->getPresentConjugation();
            if (! $conjugation) {
                continue;
            }

            foreach ($pronouns as $pronoun) {
                $key = (string) ($pronoun['pronoun_key'] ?? '');
                $conjugationKey = (string) ($pronoun['conjugation_key'] ?? '');
                $label = $pronoun['pronoun_label'] ?? '';

                if ($key === '' || $conjugationKey === '') {
                    continue;
                }

                $card = [
                    'verb' => $verb,
                    'pronoun_key' => $key,
                    'pronoun_label' => $key === 'je' ? $verb->pronounLabel('je') : $label,
                    'conjugation_key' => $conjugationKey,
                    'conjugation_value' => $verb->formatConjugation($conjugationKey, (string) ($conjugation->{$conjugationKey} ?? '')),
                ];

                $cards[] = $card;

                $verbGroup = (string) $verb->group;
                $cardsByGroup[$verbGroup] ??= [];
                $cardsByGroup[$verbGroup][] = $card;
            }
        }

        if (count($selectedGroups) > 1) {
            $mixed = [];
            $hasCards = true;

            while ($hasCards) {
                $hasCards = false;

                foreach ($selectedGroups as $g) {
                    if (! empty($cardsByGroup[$g])) {
                        $mixed[] = array_shift($cardsByGroup[$g]);
                        $hasCards = true;
                    }
                }
            }

            $cards = $mixed;
        }

        $totalCardsCount = count($cards);
        $totalPagesCount = (int) ceil($totalCardsCount / 8);

        $limit = $totalCardsCount;
        if ($selectedCardsLimit !== null) {
            $limit = min($limit, $selectedCardsLimit);
        }
        if ($selectedPages !== null) {
            $limit = min($limit, $selectedPages * 8);
        }

        if ($limit < $totalCardsCount) {
            $cards = array_slice($cards, 0, $limit);
        }

        $shownCardsCount = count($cards);
        $shownPagesCount = (int) ceil($shownCardsCount / 8);

        $viewData = [
            'cards' => $cards,
            'selectedGroups' => $selectedGroups,
            'searchQuery' => $query,
            'selectedVerb' => $verbFilter,
            'includeBack' => $includeBack,
            'backOnly' => $backOnly,
            'irregularOnly' => $irregularOnly,
            'selectedPages' => $selectedPages,
            'selectedCardsLimit' => $selectedCardsLimit,
            'totalCardsCount' => $totalCardsCount,
            'totalPagesCount' => $totalPagesCount,
            'shownCardsCount' => $shownCardsCount,
            'shownPagesCount' => $shownPagesCount,
            'printUiVersion' => $printUiVersion,
        ];

        $viewData = array_merge($viewData, $this->buildThemeOverrides($printUiVersion));

        return view('cards.print_deck', $viewData);
    }

    public function printFull(Request $request)
    {
        return $this->renderPrintFull($request, 2);
    }

    public function printFullV3(Request $request)
    {
        return $this->renderPrintFull($request, 3);
    }

    public function printFullBack(Request $request)
    {
        return $this->renderPrintFull($request, 2, [
            'backOnly' => true,
            'includeBack' => true,
        ]);
    }

    public function printFullBackV3(Request $request)
    {
        return $this->renderPrintFull($request, 3, [
            'backOnly' => true,
            'includeBack' => true,
        ]);
    }

    private function renderPrintFull(Request $request, int $printUiVersion, array $options = [])
    {
        $includeBack = (bool) ($options['includeBack'] ?? false) || ((string) $request->query('include_back', '') === '1');
        $backOnly = (bool) ($options['backOnly'] ?? false) || ((string) $request->query('back_only', '') === '1');
        $groupParam = $request->query('group');
        $query = trim((string) $request->query('q', ''));
        $verbFilter = trim((string) $request->query('verb', ''));
        $irregularOnly = (string) $request->query('irregular', '') === '1';
        $paperSize = strtolower((string) $request->query('paper', 'a4'));
        $paperSize = $paperSize === 'letter' ? 'letter' : 'a4';

        $allowedGroups = ['1er', '2ème', '3ème'];
        $selectedGroups = [];
        if (is_array($groupParam)) {
            $selectedGroups = array_values(array_unique(array_values(array_filter(
                array_map(static fn ($g) => is_string($g) ? trim($g) : '', $groupParam),
                static fn ($g) => in_array($g, $allowedGroups, true)
            ))));
        } elseif (is_string($groupParam) && in_array($groupParam, $allowedGroups, true)) {
            $selectedGroups = [$groupParam];
        }

        $verbs = Verb::query()
            ->with('conjugations')
            ->where('is_active', true)
            ->when($irregularOnly, function ($builder) {
                $builder->where(function ($q) {
                    $q->where('group', '3ème')
                        ->orWhereRaw('LOWER(infinitive) IN (?, ?, ?)', ['aller', 'être', 'avoir']);
                });
            })
            ->when(count($selectedGroups) > 0, function ($builder) use ($selectedGroups) {
                $builder->whereIn('group', $selectedGroups);
            })
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($q) use ($query) {
                    $q->where('infinitive', 'like', '%'.$query.'%')
                        ->orWhere('infinitive_translation', 'like', '%'.$query.'%');
                });
            })
            ->when($verbFilter !== '', function ($builder) use ($verbFilter) {
                $builder->where('infinitive', $verbFilter);
            })
            ->orderBy('group')
            ->orderBy('infinitive')
            ->orderBy('suit')
            ->get();

        $pronouns = [
            ['pronoun_key' => 'je', 'pronoun_label' => null, 'conjugation_key' => 'je'],
            ['pronoun_key' => 'tu', 'pronoun_label' => 'TU', 'conjugation_key' => 'tu'],
            ['pronoun_key' => 'il', 'pronoun_label' => 'IL', 'conjugation_key' => 'il_elle_on'],
            ['pronoun_key' => 'elle', 'pronoun_label' => 'ELLE', 'conjugation_key' => 'il_elle_on'],
            ['pronoun_key' => 'nous', 'pronoun_label' => 'NOUS', 'conjugation_key' => 'nous'],
            ['pronoun_key' => 'vous', 'pronoun_label' => 'VOUS', 'conjugation_key' => 'vous'],
            ['pronoun_key' => 'ils', 'pronoun_label' => 'ILS', 'conjugation_key' => 'ils_elles'],
            ['pronoun_key' => 'elles', 'pronoun_label' => 'ELLES', 'conjugation_key' => 'ils_elles'],
        ];

        $fullPages = [];
        foreach ($verbs as $verb) {
            $conjugation = $verb->getPresentConjugation();
            if (! $conjugation) {
                continue;
            }

            foreach ($pronouns as $pronoun) {
                $key = (string) ($pronoun['pronoun_key'] ?? '');
                $conjugationKey = (string) ($pronoun['conjugation_key'] ?? '');
                $label = $pronoun['pronoun_label'] ?? '';

                if ($key === '' || $conjugationKey === '') {
                    continue;
                }

                $fullPages[] = [
                    'verb' => $verb,
                    'pronoun_key' => $key,
                    'pronoun_label' => $key === 'je' ? $verb->pronounLabel('je') : $label,
                    'conjugation_key' => $conjugationKey,
                    'conjugation_value' => $verb->formatConjugation($conjugationKey, (string) ($conjugation->{$conjugationKey} ?? '')),
                ];
            }
        }

        $viewData = [
            'fullPages' => $fullPages,
            'selectedGroups' => $selectedGroups,
            'searchQuery' => $query,
            'selectedVerb' => $verbFilter,
            'irregularOnly' => $irregularOnly,
            'paperSize' => $paperSize,
            'printUiVersion' => $printUiVersion,
            'includeBack' => $includeBack,
            'backOnly' => $backOnly,
        ];

        $viewData = array_merge($viewData, $this->buildThemeOverrides($printUiVersion));

        return view('cards.print_full', $viewData);
    }

    public function printSingle(Request $request)
    {
        return $this->renderPrintSingle($request, 2);
    }

    public function printSingleV3(Request $request)
    {
        return $this->renderPrintSingle($request, 3);
    }

    public function printSingleBack(Request $request)
    {
        return $this->renderPrintSingle($request, 2, [
            'backOnly' => true,
            'includeBack' => true,
        ]);
    }

    public function printSingleBackV3(Request $request)
    {
        return $this->renderPrintSingle($request, 3, [
            'backOnly' => true,
            'includeBack' => true,
        ]);
    }

    private function renderPrintSingle(Request $request, int $printUiVersion, array $options = [])
    {
        $includeBack = (bool) ($options['includeBack'] ?? false) || ((string) $request->query('include_back', '') === '1');
        $backOnly = (bool) ($options['backOnly'] ?? false) || ((string) $request->query('back_only', '') === '1');
        $groupParam = $request->query('group');
        $query = trim((string) $request->query('q', ''));
        $verbFilter = trim((string) $request->query('verb', ''));
        $irregularOnly = (string) $request->query('irregular', '') === '1';
        $paperSize = strtolower((string) $request->query('paper', 'a4'));
        $paperSize = $paperSize === 'letter' ? 'letter' : 'a4';

        $allowedGroups = ['1er', '2ème', '3ème'];
        $selectedGroups = [];
        if (is_array($groupParam)) {
            $selectedGroups = array_values(array_unique(array_values(array_filter(
                array_map(static fn ($g) => is_string($g) ? trim($g) : '', $groupParam),
                static fn ($g) => in_array($g, $allowedGroups, true)
            ))));
        } elseif (is_string($groupParam) && in_array($groupParam, $allowedGroups, true)) {
            $selectedGroups = [$groupParam];
        }

        $verbs = Verb::query()
            ->with('conjugations')
            ->where('is_active', true)
            ->when($irregularOnly, function ($builder) {
                $builder->where(function ($q) {
                    $q->where('group', '3ème')
                        ->orWhereRaw('LOWER(infinitive) IN (?, ?, ?)', ['aller', 'être', 'avoir']);
                });
            })
            ->when(count($selectedGroups) > 0, function ($builder) use ($selectedGroups) {
                $builder->whereIn('group', $selectedGroups);
            })
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($q) use ($query) {
                    $q->where('infinitive', 'like', '%'.$query.'%')
                        ->orWhere('infinitive_translation', 'like', '%'.$query.'%');
                });
            })
            ->when($verbFilter !== '', function ($builder) use ($verbFilter) {
                $builder->where('infinitive', $verbFilter);
            })
            ->orderBy('group')
            ->orderBy('infinitive')
            ->orderBy('suit')
            ->get();

        $pronouns = [
            ['pronoun_key' => 'je', 'pronoun_label' => null, 'conjugation_key' => 'je'],
            ['pronoun_key' => 'tu', 'pronoun_label' => 'TU', 'conjugation_key' => 'tu'],
            ['pronoun_key' => 'il', 'pronoun_label' => 'IL', 'conjugation_key' => 'il_elle_on'],
            ['pronoun_key' => 'elle', 'pronoun_label' => 'ELLE', 'conjugation_key' => 'il_elle_on'],
            ['pronoun_key' => 'nous', 'pronoun_label' => 'NOUS', 'conjugation_key' => 'nous'],
            ['pronoun_key' => 'vous', 'pronoun_label' => 'VOUS', 'conjugation_key' => 'vous'],
            ['pronoun_key' => 'ils', 'pronoun_label' => 'ILS', 'conjugation_key' => 'ils_elles'],
            ['pronoun_key' => 'elles', 'pronoun_label' => 'ELLES', 'conjugation_key' => 'ils_elles'],
        ];

        $verbPages = [];
        foreach ($verbs as $verb) {
            $conjugation = $verb->getPresentConjugation();
            if (! $conjugation) {
                continue;
            }

            $cards = [];
            foreach ($pronouns as $pronoun) {
                $key = (string) ($pronoun['pronoun_key'] ?? '');
                $conjugationKey = (string) ($pronoun['conjugation_key'] ?? '');
                $label = $pronoun['pronoun_label'] ?? '';

                if ($key === '' || $conjugationKey === '') {
                    continue;
                }

                $cards[] = [
                    'verb' => $verb,
                    'pronoun_key' => $key,
                    'pronoun_label' => $key === 'je' ? $verb->pronounLabel('je') : $label,
                    'conjugation_key' => $conjugationKey,
                    'conjugation_value' => $verb->formatConjugation($conjugationKey, (string) ($conjugation->{$conjugationKey} ?? '')),
                ];
            }

            $verbPages[] = [
                'verb' => $verb,
                'cards' => $cards,
            ];
        }

        $viewData = [
            'verbPages' => $verbPages,
            'selectedGroups' => $selectedGroups,
            'searchQuery' => $query,
            'selectedVerb' => $verbFilter,
            'irregularOnly' => $irregularOnly,
            'paperSize' => $paperSize,
            'printUiVersion' => $printUiVersion,
            'includeBack' => $includeBack,
            'backOnly' => $backOnly,
        ];

        $viewData = array_merge($viewData, $this->buildThemeOverrides($printUiVersion));

        return view('cards.print_single', $viewData);
    }

    public function rules()
    {
        return view('cards.rules');
    }

    private function resolveSuitForVerb(string $group, string $infinitive): string
    {
        $infinitive = mb_strtolower(trim($infinitive));
        $group = trim($group);

        if (in_array($infinitive, ['avoir', 'être', 'etre'], true)) {
            return 'spade';
        }

        return match ($group) {
            '1er' => 'heart',
            '2ème' => 'diamond',
            '3ème' => 'club',
            default => 'spade',
        };
    }

    private function buildThemeOverrides(int $printUiVersion): array
    {
        // Determine which theme version to use.
        // Read the active UI version from the DB; fall back to $printUiVersion → 2.
        $uiVersionSetting = ThemeSetting::query()
            ->where('key', 'theme_ui_version')
            ->value('value');

        $uiVersion = $uiVersionSetting !== null ? (int) $uiVersionSetting : $printUiVersion;

        // Supported versioned prefixes
        if (! in_array($uiVersion, [2, 5, 6, 7], true)) {
            return [];
        }

        $prefix = '--rami-v'.$uiVersion.'-';
        $prefixLen = strlen($prefix);

        $settings = ThemeSetting::query()
            ->select(['key', 'value'])
            ->where('key', 'like', $prefix.'%')
            ->get()
            ->pluck('value', 'key')
            ->all();

        if (! $settings) {
            return [];
        }

        $mapped = [];
        foreach ($settings as $key => $value) {
            if (! is_string($key) || ! is_string($value)) {
                continue;
            }

            $key = trim($key);
            $value = trim($value);

            if (! str_starts_with($key, $prefix)) {
                continue;
            }

            if ($value === '') {
                continue;
            }

            $mapped['--rami-'.substr($key, $prefixLen)] = $value;
        }

        $pairs = [];
        foreach ($mapped as $key => $value) {
            if (! str_starts_with($key, '--rami-')) {
                continue;
            }

            if ($key === '--' || $value === '') {
                continue;
            }

            if (strlen($key) > 64 || strlen($value) > 128) {
                continue;
            }

            if (preg_match('/[;{}]/', $key.$value) === 1) {
                continue;
            }

            $pairs[] = $key.': '.$value;
        }

        $overrides = [
            'activeThemeUiVersion' => $uiVersion,
        ];

        if ($pairs) {
            $overrides['themeSettingsInlineCss'] = '.rami-card,.rami-card-large,.print-rami-card,.print-main{'.implode(';', $pairs).';}';
        }

        if (isset($mapped['--rami-card-back-pattern'])) {
            $pattern = trim((string) $mapped['--rami-card-back-pattern']);
            if ($pattern === '' || preg_match('/^[a-z0-9-]{1,32}$/', $pattern) !== 1) {
                $pattern = 'plain';
            }

            $overrides['themeCardBackPattern'] = $pattern;
        }

        if (isset($mapped['--rami-writing-style'])) {
            $overrides['themeWritingStyle'] = $mapped['--rami-writing-style'];
        }

        return $overrides;
    }
}
