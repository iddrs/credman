<div class="required field">
    <label for="projativ">Proj./Ativ.</label>
    <input type="number" name="projativ" id="projativ" required value="{{ $value }}" min="1" step="1" class="enter-as-tab" title="Apenas números maiores que zero.">
    @error('projativ')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
