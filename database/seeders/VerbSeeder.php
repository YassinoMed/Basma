<?php

namespace Database\Seeders;

use App\Models\Conjugation;
use App\Models\Verb;
use Illuminate\Database\Seeder;

class VerbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resolveSuit = static function (string $group, string $infinitive): string {
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
        };

        // Verbes du 1er groupe (-er)
        $verbs1erGroupe = [
            [
                'infinitive' => 'manger',
                'translation' => 'to eat',
                'description' => 'personne mangeant une pomme',
                'conjugations' => ['mange', 'manges', 'mange', 'mangeons', 'mangez', 'mangent'],
            ],
            [
                'infinitive' => 'parler',
                'translation' => 'to speak',
                'description' => 'personne en train de parler',
                'conjugations' => ['parle', 'parles', 'parle', 'parlons', 'parlez', 'parlent'],
            ],
            [
                'infinitive' => 'chanter',
                'translation' => 'to sing',
                'description' => 'personne chantant avec micro',
                'conjugations' => ['chante', 'chantes', 'chante', 'chantons', 'chantez', 'chantent'],
            ],
            [
                'infinitive' => 'danser',
                'translation' => 'to dance',
                'description' => 'personne en train de danser',
                'conjugations' => ['danse', 'danses', 'danse', 'dansons', 'dansez', 'dansent'],
            ],
            [
                'infinitive' => 'jouer',
                'translation' => 'to play',
                'description' => 'personne jouant au ballon',
                'conjugations' => ['joue', 'joues', 'joue', 'jouons', 'jouez', 'jouent'],
            ],
            [
                'infinitive' => 'travailler',
                'translation' => 'to work',
                'description' => 'personne travaillant sur ordinateur',
                'conjugations' => ['travaille', 'travailles', 'travaille', 'travaillons', 'travaillez', 'travaillent'],
            ],
            [
                'infinitive' => 'écouter',
                'translation' => 'to listen',
                'description' => 'personne écoutant de la musique',
                'conjugations' => ['écoute', 'écoutes', 'écoute', 'écoutons', 'écoutez', 'écoutent'],
            ],
            [
                'infinitive' => 'regarder',
                'translation' => 'to watch/look',
                'description' => 'personne regardant la télévision',
                'conjugations' => ['regarde', 'regardes', 'regarde', 'regardons', 'regardez', 'regardent'],
            ],
            [
                'infinitive' => 'aimer',
                'translation' => 'to love/like',
                'description' => 'personne formant un coeur avec ses mains',
                'conjugations' => ['aime', 'aimes', 'aime', 'aimons', 'aimez', 'aiment'],
            ],
            [
                'infinitive' => 'se promener',
                'translation' => 'to take a walk',
                'description' => 'personne se promenant dans un parc',
                'conjugations' => ['promène', 'promènes', 'promène', 'promenons', 'promenez', 'promènent'],
            ],
            [
                'infinitive' => 'marcher',
                'translation' => 'to walk',
                'description' => 'personne marchant dans un parc',
                'conjugations' => ['marche', 'marches', 'marche', 'marchons', 'marchez', 'marchent'],
            ],
        ];

        // Verbes du 2ème groupe (-ir avec -issons)
        $verbs2emeGroupe = [
            [
                'infinitive' => 'finir',
                'translation' => 'to finish',
                'description' => 'personne terminant un travail',
                'conjugations' => ['finis', 'finis', 'finit', 'finissons', 'finissez', 'finissent'],
                'theme_color' => '#2d5a3d',
                'accent_color' => '#6d9e7a',
            ],
            [
                'infinitive' => 'choisir',
                'translation' => 'to choose',
                'description' => 'personne choisissant entre options',
                'conjugations' => ['choisis', 'choisis', 'choisit', 'choisissons', 'choisissez', 'choisissent'],
                'theme_color' => '#2d5a3d',
                'accent_color' => '#6d9e7a',
            ],
            [
                'infinitive' => 'réussir',
                'translation' => 'to succeed',
                'description' => 'personne célébrant un succès',
                'conjugations' => ['réussis', 'réussis', 'réussit', 'réussissons', 'réussissez', 'réussissent'],
                'theme_color' => '#2d5a3d',
                'accent_color' => '#6d9e7a',
            ],
            [
                'infinitive' => 'grandir',
                'translation' => 'to grow',
                'description' => 'plant growing',
                'conjugations' => ['grandis', 'grandis', 'grandit', 'grandissons', 'grandissez', 'grandissent'],
                'theme_color' => '#2d5a3d',
                'accent_color' => '#6d9e7a',
            ],
        ];

        // Verbes du 3ème groupe (irréguliers)
        $verbs3emeGroupe = [
            [
                'infinitive' => 'être',
                'translation' => 'to be',
                'description' => 'personne existant',
                'conjugations' => ['suis', 'es', 'est', 'sommes', 'êtes', 'sont'],
                'theme_color' => '#5a2d5a',
                'accent_color' => '#9a6d9a',
            ],
            [
                'infinitive' => 'avoir',
                'translation' => 'to have',
                'description' => 'personne tenant quelque chose',
                'conjugations' => ['ai', 'as', 'a', 'avons', 'avez', 'ont'],
                'theme_color' => '#5a2d5a',
                'accent_color' => '#9a6d9a',
            ],
            [
                'infinitive' => 'aller',
                'translation' => 'to go',
                'description' => 'personne marchant avec direction',
                'conjugations' => ['vais', 'vas', 'va', 'allons', 'allez', 'vont'],
                'theme_color' => '#5a2d5a',
                'accent_color' => '#9a6d9a',
            ],
            [
                'infinitive' => 'faire',
                'translation' => 'to do/make',
                'description' => 'personne faisant quelque chose',
                'conjugations' => ['fais', 'fais', 'fait', 'faisons', 'faites', 'font'],
                'theme_color' => '#5a2d5a',
                'accent_color' => '#9a6d9a',
            ],
            [
                'infinitive' => 'comprendre',
                'translation' => 'to understand',
                'description' => 'personne comprenant une idée',
                'conjugations' => ['comprends', 'comprends', 'comprend', 'comprenons', 'comprenez', 'comprennent'],
                'theme_color' => '#5a2d5a',
                'accent_color' => '#9a6d9a',
            ],
            [
                'infinitive' => 'prendre',
                'translation' => 'to take',
                'description' => 'personne prenant un objet',
                'conjugations' => ['prends', 'prends', 'prend', 'prenons', 'prenez', 'prennent'],
                'theme_color' => '#5a2d5a',
                'accent_color' => '#9a6d9a',
            ],
            [
                'infinitive' => 'venir',
                'translation' => 'to come',
                'description' => 'personne arrivant',
                'conjugations' => ['viens', 'viens', 'vient', 'venons', 'venez', 'viennent'],
                'theme_color' => '#5a2d5a',
                'accent_color' => '#9a6d9a',
            ],
        ];

        // Créer les verbes du 1er groupe
        foreach ($verbs1erGroupe as $verbData) {
            $suit = $resolveSuit('1er', $verbData['infinitive']);
            $verb = Verb::query()->updateOrCreate(
                ['infinitive' => $verbData['infinitive'], 'suit' => $suit],
                [
                    'infinitive_translation' => $verbData['translation'],
                    'group' => '1er',
                    'illustration_description' => $verbData['description'],
                    'theme_color' => '#1e3a5f',
                    'accent_color' => '#5b9bd5',
                    'is_active' => true,
                ]
            );

            Conjugation::query()->updateOrCreate(
                ['verb_id' => $verb->id, 'tense' => 'présent'],
                [
                    'je' => $verbData['conjugations'][0],
                    'tu' => $verbData['conjugations'][1],
                    'il_elle_on' => $verbData['conjugations'][2],
                    'nous' => $verbData['conjugations'][3],
                    'vous' => $verbData['conjugations'][4],
                    'ils_elles' => $verbData['conjugations'][5],
                ]
            );
        }

        // Créer les verbes du 2ème groupe
        foreach ($verbs2emeGroupe as $verbData) {
            $suit = $resolveSuit('2ème', $verbData['infinitive']);
            $verb = Verb::query()->updateOrCreate(
                ['infinitive' => $verbData['infinitive'], 'suit' => $suit],
                [
                    'infinitive_translation' => $verbData['translation'],
                    'group' => '2ème',
                    'illustration_description' => $verbData['description'],
                    'theme_color' => $verbData['theme_color'],
                    'accent_color' => $verbData['accent_color'],
                    'is_active' => true,
                ]
            );

            Conjugation::query()->updateOrCreate(
                ['verb_id' => $verb->id, 'tense' => 'présent'],
                [
                    'je' => $verbData['conjugations'][0],
                    'tu' => $verbData['conjugations'][1],
                    'il_elle_on' => $verbData['conjugations'][2],
                    'nous' => $verbData['conjugations'][3],
                    'vous' => $verbData['conjugations'][4],
                    'ils_elles' => $verbData['conjugations'][5],
                ]
            );
        }

        // Créer les verbes du 3ème groupe
        foreach ($verbs3emeGroupe as $verbData) {
            $suit = $resolveSuit('3ème', $verbData['infinitive']);
            $verb = Verb::query()->updateOrCreate(
                ['infinitive' => $verbData['infinitive'], 'suit' => $suit],
                [
                    'infinitive_translation' => $verbData['translation'],
                    'group' => '3ème',
                    'illustration_description' => $verbData['description'],
                    'theme_color' => $verbData['theme_color'],
                    'accent_color' => $verbData['accent_color'],
                    'is_active' => true,
                ]
            );

            Conjugation::query()->updateOrCreate(
                ['verb_id' => $verb->id, 'tense' => 'présent'],
                [
                    'je' => $verbData['conjugations'][0],
                    'tu' => $verbData['conjugations'][1],
                    'il_elle_on' => $verbData['conjugations'][2],
                    'nous' => $verbData['conjugations'][3],
                    'vous' => $verbData['conjugations'][4],
                    'ils_elles' => $verbData['conjugations'][5],
                ]
            );
        }
    }
}
