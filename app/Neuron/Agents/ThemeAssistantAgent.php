<?php

namespace App\Neuron\Agents;

use NeuronAI\Agent;
use NeuronAI\Laravel\Facades\AIProvider;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\SystemPrompt;

class ThemeAssistantAgent extends Agent
{
    protected function provider(): AIProviderInterface
    {
        $driver = (string) env('NEURON_AI_PROVIDER', 'openai');

        return AIProvider::driver($driver);
    }

    public function instructions(): string
    {
        $allowedKeys = [
            '--rami-card-bg',
            '--rami-card-border-color',
            '--rami-card-border-width',
            '--rami-card-border-style',
            '--rami-card-radius',
            '--rami-card-shadow',
            '--rami-card-shadow-hover',
            '--rami-pattern-color',
            '--rami-bg-circles-strength',
            '--rami-bg-rectangles-strength',
            '--rami-noise-opacity',
            '--rami-illustration-bg-start',
            '--rami-illustration-bg-end',
            '--rami-illustration-border-color',
            '--rami-illustration-size',
            '--rami-illustration-radius',
            '--rami-illustration-border-width',
            '--rami-illustration-shadow',
            '--rami-center-top',
            '--rami-center-padding',
            '--rami-text-muted-color',
            '--rami-verb-size',
            '--rami-verb-size-large',
            '--rami-verb-letter-spacing',
            '--rami-infinitive-letter-spacing',
            '--rami-suit-size',
            '--rami-group-1-color',
            '--rami-group-2-color',
            '--rami-group-3-color',
        ];

        return (string) new SystemPrompt(
            background: [
                'You generate UI theme settings for a French verb educational card app.',
                'You must answer with a single JSON object and nothing else.',
                'Each key is a CSS variable name, each value is a CSS value string.',
                'Only use keys from this allowed list: '.implode(', ', $allowedKeys).'.',
                'Rules:',
                '- Colors: hex (#rrggbb) or rgba(r,g,b,a).',
                '- Lengths: use px, %, em, or rem (example: 16px, 48%, 0.02em).',
                '- Border style: solid|dashed|dotted|double.',
                '- Shadows: valid CSS box-shadow string.',
                '- noise opacity: 0..1.',
            ],
            output: [
                'Return only JSON like {"--rami-card-bg":"#faf8f5","--rami-card-radius":"16px"}',
            ],
        );
    }
}
