<?php

namespace App\Livewire\Admin;

use App\Neuron\Agents\ThemeAssistantAgent;
use Livewire\Component;
use NeuronAI\Chat\Messages\UserMessage;
use Throwable;

class ThemeAiAssistant extends Component
{
    public string $prompt = '';

    public array $suggestedSettings = [];

    public string $errorMessage = '';

    public function generate(): void
    {
        $this->errorMessage = '';
        $this->suggestedSettings = [];

        $prompt = trim($this->prompt);
        if ($prompt === '') {
            $this->errorMessage = 'Décris un style (ex: “pastel, contrasté, centre plus bas”).';

            return;
        }

        try {
            $agent = ThemeAssistantAgent::make();
            $content = (string) $agent->chat(new UserMessage($prompt))->getContent();
            $json = $this->extractJsonObject($content);

            $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            if (! is_array($decoded)) {
                $this->errorMessage = 'Réponse IA invalide.';

                return;
            }

            $filtered = [];
            foreach ($decoded as $key => $value) {
                if (! is_string($key)) {
                    continue;
                }

                $key = trim($key);
                if (preg_match('/^--rami-[a-z0-9-]{1,64}$/', $key) !== 1) {
                    continue;
                }

                if (is_array($value) || is_object($value)) {
                    continue;
                }

                $stringValue = trim((string) $value);
                if ($stringValue === '' || strlen($stringValue) > 200) {
                    continue;
                }

                if (preg_match('/[;{}]/', $stringValue) === 1) {
                    continue;
                }

                $filtered[$key] = $stringValue;
            }

            $this->suggestedSettings = $filtered;
            if (! $filtered) {
                $this->errorMessage = 'Aucune suggestion exploitable.';
            }
        } catch (Throwable) {
            $this->errorMessage = 'Génération impossible. Vérifie la configuration Neuron (provider + API key).';
        }
    }

    public function applyToPreview(): void
    {
        if (! $this->suggestedSettings) {
            return;
        }

        $this->dispatch('theme-ai-apply', settings: $this->suggestedSettings);
    }

    private function extractJsonObject(string $text): string
    {
        $start = strpos($text, '{');
        $end = strrpos($text, '}');

        if ($start === false || $end === false || $end <= $start) {
            return $text;
        }

        return substr($text, $start, $end - $start + 1);
    }

    public function render()
    {
        return view('livewire.admin.theme-ai-assistant');
    }
}
