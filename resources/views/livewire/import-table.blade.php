<div class="">
    <h4 class="ps-2">Update Table</h4>
    <p class="text-end">@livewire('table-name',['tableName'=>$tableName])</p>
    <form>
        <input type="file" wire:model="file" id="file" class="form-control w-50">
        <small class="text-dark ps-1"> File name must be <b>{{ $tableName }}.csv</b></small>

        @error('file')
            <span class="text-danger">{{ $message }}</span>
        @enderror
        
    </form> 
    <div class="ps-2">
        @if ($message)
            @foreach ($message as $m)
              <p class="text-black"> # {{ $m }}</p>
            @endforeach
        @endif     
</div>
    @if ($ignorebtn)
    <button wire:click="ignore" class="btn btn-primary btn-sm ms-5">Ignore</button>  
            
    @endif
    @if ($importBtn)
    <button wire:click="import" class="btn btn-success btn-sm ms-5">Import</button>      
    @endif

    @if ($diff)   
        <h3>Changes in fields: </h3>    
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <th>Key</th>
                <th>Field</th>
                <th>Old Data</th>
                <th>New Data</th>
            </tr>

            @foreach ($diff as $key => $data)
                <tr>
                    <td>{{ $data['row_1'][1] }}</td>
                    <td> {{ $header[$data['difference_column']] }} </td>
                    <td>{{ $data['difference_value']['old_value'] }}</td>
                    <td>{{ $data['difference_value']['new_value'] }}</td>
                </tr>
            @endforeach

        </table>
    @endif
</div>
{{-- difference_value --}}
