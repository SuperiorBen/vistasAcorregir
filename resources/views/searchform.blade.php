<form method="POST">
    <div class="row">
        <div class="twelve columns">
            <label for="dni">{{ $subtitle }} by DNI</label>
            <input class="u-full-width" type="dni" placeholder="06377628-8" id="dni" name="dni" value="{{ isset($dni) ? $dni : '' }}" autocomplete="off">
        </div>
    </div>
    <input class="button-primary" type="submit" value="Search">
    @csrf
</form>

<hr />