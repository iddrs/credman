<div class="required field">
    <label for="fonte">Fonte</label>
    <input type="text" name="fonte" id="fonte" required value="{{ $value }}" class="enter-as-tab" pattern="[0-9]{5}" title="Apenas números, com 5 dígitos, como em 62101" maxlength="5">
    @error('fonte')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
