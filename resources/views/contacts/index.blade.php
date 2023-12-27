@extends('layouts.main')

@section('title', 'Contact App | All Contacts')

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-title">
                            <div class="d-flex align-items-center">
                                <h2 class="mb-0">
                                    All Contacts
                                    @if(request()->query('trash'))
                                        <small>(In Trash)</small>
                                    @endif
                                </h2>
                                <div class="ml-auto">
                                    <a href="{{ route('contacts.create') }}" class="btn btn-success"><i
                                            class="fa fa-plus-circle"></i> Add New</a>
                                    <a href="{{ route('contacts.import.create') }}" class="btn btn-info"><i class="fa fa-upload"></i> Import</a>
                                    <a href="{{ route('contacts.export.create') }}" class="btn btn-warning"><i class="fa fa-download"></i> Export</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('shared.filter', [
                                'filterDropdown' => 'contacts._company-selection'
                            ])
                            @include('shared.flash')
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">
                                            {{-- <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'first_name']) }}">First Name</a> --}}
                                            {!!sortable("First Name")!!} 
                                            {{-- !! use because anchoer a tag not encoded  --}}
                                        </th>
                                        <th scope="col">
                                            {!!sortable("Last Name")!!}
                                        </th>
                                        <th scope="col">
                                            {!!sortable("Email")!!}    
                                        </th>
                                        <th scope="col">Company</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  {{-- Short Syntex for continue You can use same for continue, break --}}
                                          {{-- @continue($id==1) @break($id==3) --}}
                                    @php
                                        $showTrashButtons = request()->query('trash') ? true : false;
                                    @endphp
                                    @if (!@empty($contacts) && count($contacts))
                                        @foreach ($contacts as $index => $contact)
                                            @include('contacts._contact', ['contact' => $contact, 'index' => $index])
                                        @endforeach
                                    @else
                                        @include('shared.empty', ['numCol' => 6]) {{-- , 'message' => 'No contact found' --}}
                                    @endif
                                    {{-- @php $contactss=[] @endphp --}}
                                    {{-- @each('contacts._contact', $contacts, 'contact', 'contacts._empty')  --}}
                                    {{-- // , 'view.empty' --}}
                                </tbody>
                            </table>
                            {{ $contacts->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
