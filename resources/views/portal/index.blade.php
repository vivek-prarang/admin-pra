<x-layout.base>
    <style>
        /* List Item */
        .col-3 a li {
            font-size: 18px;
            text-transform: capitalize;
        }

        /* Column 3/12 */
        #tbl-name-m, #main-tbl-m {
            overflow-y: scroll;
            max-width: 100%;
            max-height: 75vh;
        }
    </style>
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <section>
        <div class="container">
          
            <div class="row">
                <div class="col-3" >   
                    <h3 class="h4">Master File Table List:</h3>      
            <div id="tbl-name-m">              
            <ul class="pe-3">
                @php
                    $count=1;
                @endphp
                @foreach ($tableNames as $table=>$name )
                   <a href="?t={{ $table}}"> <li class="text-dark text-start rounded bg-primary text-white ps-4 p-1 m-1  w-100">{{$count++}}. {{$table}}</li> </a>
                @endforeach                
            </ul>
            </div>
        </div>
        <div class="col-9" >
            <div class="ps-3" >
            @if($tableData)
            <h4 class="ps-3 text-dark">Table Name:  {{ucfirst( str_replace('_',' ',$tableName))}}</h4>
            <div id="main-tbl-m">
                <small class="ps-2 text-drak">Number of Fields:   {{count($tableData)}}</small>
                @livewire('import-table', ['tableName' => $tableName])

            {{-- @forelse ($tableData as $data )
            <div class="card m-2 rounded border shadow p-2">
              
                   <div class="row">
                    <div class="col-sm-12 text-dark">
                        <b>{{$data->id}}</b> <br>
                        <b>{{$data->name}}</b>
                    </div>
                    
                   </div>
            </div>
            @empty
                
            @endforelse --}}
            </div>
            @else
            <h4 class="text-center">Please Select Any Table </h4>
            @endif
        </div>
        </div>
    </div> 
        </div>
    </section>



</x-layout.base>
