<div>
    @if($errorMessage !== '')
        <div class="alert alert-danger">
            <i class="ph ph-warning-circle"></i>
            {{ $errorMessage }}
        </div>
    @endif

    <div class="color-grid">
        <div class="color-input-group color-input-group--full">
            <label>Décris le style</label>
            <div class="color-input-wrapper">
                <textarea
                    class="color-hex select-full"
                    rows="3"
                    wire:model="prompt"
                    placeholder="Ex: pastel, contrasté, plus minimaliste, centre plus bas, verbes plus serrés…"
                ></textarea>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-4 mt-4">
        <button type="button" class="btn btn-secondary" wire:click="generate" wire:loading.attr="disabled">
            Générer
        </button>

        <button type="button" class="btn btn-primary" wire:click="applyToPreview" @disabled(empty($suggestedSettings))>
            Appliquer au preview
        </button>
    </div>

    @if(!empty($suggestedSettings))
        <div class="mt-4">
            <div class="form-label">Proposition</div>
            <div class="mt-2 opacity-80">
                @foreach($suggestedSettings as $key => $value)
                    <div><strong>{{ $key }}</strong> : {{ $value }}</div>
                @endforeach
            </div>
        </div>
    @endif
</div>

