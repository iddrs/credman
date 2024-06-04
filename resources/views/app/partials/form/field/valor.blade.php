<div class="required field">
    <label for="{{ $id }}">{{ $label }}</label>
    <input type="number" name="{{ $id }}" id="{{ $id }}" required value="{{ $value }}" min="0.00" step="0.01" class="enter-as-tab" title="Apenas números positivos." autocomplete="off">
    @error('{{ $id }}')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
