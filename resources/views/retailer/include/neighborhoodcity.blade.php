<div class="select-option" id="neighborhood">
    <select class="neighborhood form-select" name="neighborhood">
        <option value="">Neighborhood</option>
        @foreach ($neighborhood as $neighbor)
            <option value="{{ $neighbor->id }}" @if (isset($selectedneighbor) && $selectedneighbor == $neighbor->id) selected @endif>
                {{ ucwords($neighbor->name) }}</option>
        @endforeach
    </select>
</div>
