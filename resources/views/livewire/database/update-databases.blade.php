<div>
    <div class="row">
        <div class="col-2">
            <h6>Tables Name</h6>
            @foreach ($tables as $table )
                     <button class="bnt btn-info btn-block w-100 text-start text-dark m-1 " wire:click="handleClick('{{ $table }}')">{{ucfirst(str_replace('_',' ',$table ))}}</button> <br>
            @endforeach
        </div>
        <div class="col-10">
            <p class="text-center">
                <h2>{{ucfirst(str_replace('_',' ',$tableName ))}}</h2>
            </p>
            @foreach ($tableSchema as $data )
                {{$data}} <br>
            @endforeach
        </div>
    </div>


</div>
