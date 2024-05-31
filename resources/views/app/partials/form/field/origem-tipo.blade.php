<div class="required field">
    <label for="origem">Origem</label>
    <select name="origem" id="origem" required class="enter-as-tab">
        @php
            $tipos = \App\Support\Enums\TiposOrigem::toArray();
        @endphp

        <option value="0"></option>
        @foreach ($tipos as $key => $value)
            <option value="{{ $key }}" {{ old('origem', $lei->origem ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
        @endforeach
    </select>
    @error('origem')
        @include('app.partials.form.error-message', ['message' => $message])
    @enderror
</div>
