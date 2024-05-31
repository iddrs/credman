<div class="required field">
    <label for="exercicio">Exercício</label>
    <input type="number" name="exercicio" id="exercicio" required value="{{ $value }}" min="1" step="1" class="enter-as-tab" title="Apenas números maiores que zero.">
    @error('exercicio')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
