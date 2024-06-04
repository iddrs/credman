<div class="required field">
    <label for="nr">Nº</label>
    <input type="number" name="nr" id="nr" required value="{{ $value }}" min="1" step="1" class="enter-as-tab" title="Apenas números maiores que zero." autocomplete="off">
    @error('nr')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
