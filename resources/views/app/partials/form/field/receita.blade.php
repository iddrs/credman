<div class="required field">
    <label for="receita">Receita</label>
    <input type="text" name="receita" id="receita" required value="{{ $value }}" class="enter-as-tab" pattern="[0-9]{14}" title="Apenas números, com 14 dígitos, como em 11120104000000">
    @error('receita')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
