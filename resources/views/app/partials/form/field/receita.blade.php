<div class="required field">
    <label for="receita">Receita</label>
    <input type="text" name="receita" id="receita" required value="{{ $value }}" class="enter-as-tab" pattern="^\d{1}\.\d{1}\.\d{1}\.\d{1}\.\d{2}\.\d{1}\.\d{1}\.\d{2}\.\d{2}\.\d{2}$" title="Apenas números, com 14 dígitos, como em 11120104000000" autocomplete="off" placeholder="17115010102001">
    @error('receita')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
