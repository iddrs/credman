<div class="required field">
    <label for="despesa">Despesa</label>
    <input type="text" name="despesa" id="despesa" required value="{{ $value }}" class="enter-as-tab" pattern="[0-9]{6}" title="Apenas números, com 6 dígitos, como em 339030" maxlength="6">
    @error('despesa')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
