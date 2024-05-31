<div class="required field">
    <label for="tipo">Tipo</label>
    <select name="tipo" id="tipo" required class="enter-as-tab">
        @php
            $tipos = \App\Support\Enums\TiposLei::toArray();
        @endphp

        <option value="0"></option>
        @foreach ($tipos as $key => $value)
            <option value="{{ $value }}" {{ old('tipo', $lei->tipo ?? '') == $value ? 'selected' : '' }}>{{ $value }}</option>
        @endforeach
    </select>
    @error('tipo')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
