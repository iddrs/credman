<div class="required field">
    <label for="tipo">Tipo</label>
    <select name="tipo" id="tipo" required class="enter-as-tab">
        @php
            $tipos = \App\Support\Enums\TiposCredito::toArray();
        @endphp

        <option value="0"></option>
        @foreach ($tipos as $key => $value)
            <option value="{{ $key }}" {{ old('tipo', $lei->tipo ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
        @endforeach
    </select>
    @error('tipo')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
