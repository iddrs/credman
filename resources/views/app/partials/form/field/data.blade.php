<div class="required field">
    <label for="data">Data</label>
    <input type="date" name="data" id="data" required value="{{ $value }}" min="1" step="1" class="enter-as-tab" title="Data no formato dd/mm/aaaa.">
    @error('data')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
