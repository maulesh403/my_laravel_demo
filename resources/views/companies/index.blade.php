@extends('layouts.main')

@section('title', 'Contact App | All Companies')

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-title">
                            <div class="d-flex align-items-center">
                                <h2 class="mb-0">
                                    All Companies
                                    @if(request()->query('trash'))
                                        <small>(In Trash)</small>
                                    @endif
                                </h2>
                                <div class="ml-auto">
                                    <a href="{{ route('companies.create') }}" class="btn btn-success"><i
                                            class="fa fa-plus-circle"></i> Add New</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('shared.filter')
                            @include('shared.flash')
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">
                                            {{-- <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'first_name']) }}">First Name</a> --}}
                                            {!!sortable("Name")!!} 
                                            {{-- !! use because anchoer a tag not encoded  --}}
                                        </th>
                                        <th scope="col">
                                            {!!sortable("Website")!!}
                                        </th>
                                        <th scope="col">
                                            {!!sortable("Email")!!}    
                                        </th>
                                        <th scope="col">Contacts</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  {{-- Short Syntex for continue You can use same for continue, break --}}
                                          {{-- @continue($id==1) @break($id==3) --}}
                                    @php
                                        $showTrashButtons = request()->query('trash') ? true : false;
                                    @endphp
                                    @if (!@empty($companies) && count($companies))
                                        @foreach ($companies as $index => $company)
                                            @include('companies._company', ['company' => $company, 'index' => $index])
                                        @endforeach
                                    @else
                                        @include('shared.empty', ['numCol' => 6])
                                    @endif
                                    {{-- @php $companiess=[] @endphp --}}
                                    {{-- @each('companies._company', $companies, 'company', 'companies._empty')  --}}
                                    {{-- // , 'view.empty' --}}
                                </tbody>
                            </table>
                            {{ $companies->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
