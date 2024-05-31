<div class="required field">
    <label for="uniorcam">Unid. Orç.</label>
    <input type="text" name="uniorcam" id="uniorcam" required value="{{ $value }}" class="enter-as-tab" pattern="[0-9]{4}" title="Apenas números, com 4 dígitos, como em 0501">
    @error('uniorcam')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
