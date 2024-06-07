<div class="required field">
    <label for="tipo_decreto">Tipo do Decreto</label>
    <select name="tipo_decreto" id="tipo_decreto" required class="enter-as-tab">
        @php
            $tipos = \App\Support\Enums\TiposDecreto::toArray();
        @endphp

        <option value="0"></option>
        @foreach ($tipos as $key => $value)
            @php
                $label = \App\Support\Enums\TiposDecreto::getLabel($value);
            @endphp
            <option value="{{ $value }}" {{ old('tipo_decreto', $decreto->tipo_decreto ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error('tipo_decreto')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
