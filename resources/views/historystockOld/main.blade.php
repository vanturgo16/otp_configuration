@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <a href="{{ route('historystock.rm') }}" 
                            class="btn {{ Route::is('historystock.rm') ? 'btn-primary' : 'btn-light' }} waves-effect btn-label waves-light" 
                            title="List History Stock Raw Material">
                            <span class="label-icon fw-bold"><small>RM</small></span> Raw Material
                        </a>
                        
                        <a href="{{ route('historystock.wip') }}" 
                            class="btn {{ Route::is('historystock.wip') ? 'btn-primary' : 'btn-light' }} waves-effect btn-label waves-light" 
                            title="List History Stock WIP">
                            <span class="label-icon fw-bold"><small>WIP</small></span> Work In Progress
                        </a>
                        
                        <a href="{{ route('historystock.fg') }}" 
                            class="btn {{ Route::is('historystock.fg') ? 'btn-primary' : 'btn-light' }} waves-effect btn-label waves-light" 
                            title="List History Stock Finished Goods">
                            <span class="label-icon fw-bold"><small>FG</small></span> Finish Good
                        </a>
                        
                        <a href="{{ route('historystock.ta') }}" 
                            class="btn {{ Route::is('historystock.ta') ? 'btn-primary' : 'btn-light' }} waves-effect btn-label waves-light" 
                            title="List History Stock Auxalary & Sparepart">
                            <span class="label-icon fw-bold"><small>TA</small></span> Aux & Sparepart / Other
                        </a>
                    </div>
            
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">History Stock</li>
                        </ol>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        @if(Route::is('historystock.rm'))
                            <h5>List Stock Raw Material</h5>
                        @elseif(Route::is('historystock.wip'))
                            <h5>List Stock Work In Progress</h5>
                        @elseif(Route::is('historystock.fg'))
                            <h5>List Stock Finish Good</h5>
                        @elseif(Route::is('historystock.ta'))
                            <h5>List Stock Auxalary & Sparepart / Other</h5>
                        @endif
                    </div>
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection