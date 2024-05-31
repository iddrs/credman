<div class="field">
    <label for="complemento">Complemento</label>
    <input type="text" name="complemento" id="complemento" value="{{ $value }}" class="enter-as-tab" pattern="[0-9]{4}" title="Apenas números, com 4 dígitos, como em 1070">
    @error('complemento')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
