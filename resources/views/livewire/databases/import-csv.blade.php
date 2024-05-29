<div>
    <h4>Update Table</h4>
    <form wire:submit.prevent="import">
        <input type="file" wire:model="file" id="file" class="form-control">
        <small class="text-dark"> File name must be <b>{{ $tableName }}.csv</b></small>

        @error('file')
            <span class="text-danger">{{ $message }}</span>
        @enderror

        <button type="submit" class="btn btn-primary mt-3">Upload</button>
    </form>

    @if (session()->has('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
</div>
