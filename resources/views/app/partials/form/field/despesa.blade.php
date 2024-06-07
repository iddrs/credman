<div class="required field">
    <label for="despesa">Despesa</label>
    <input type="text" name="despesa" id="despesa" required value="{{ $value }}" class="enter-as-tab" pattern="^\d{1}\.\d{1}\.\d{2}\.\d{2}$" title="Apenas números, com 6 dígitos, como em 339030" maxlength="6" autocomplete="off" placeholder="339030">
    @error('despesa')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
