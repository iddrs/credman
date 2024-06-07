<div class="required field">
    <label for="projativ">Proj./Ativ.</label>
    <input type="text" name="projativ" id="projativ" required value="{{ $value }}" min="1" step="1" class="enter-as-tab masked" title="Apenas números maiores que zero." maxlength="4" autocomplete="off" placeholder="2001" pattern="^\d{1}\.\d{3}$">
    @error('projativ')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
