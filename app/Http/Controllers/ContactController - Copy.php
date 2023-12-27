<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ContactController extends Controller
{
    //protected $company;

    // public function __construct(){
    //     $this->company = new CompanyRepository;   
    // }
    // public function __construct(CompanyRepository $company){
    //     $this->company = $company;   
    // }
    public function __construct(protected CompanyRepository $company){
        
    }

    public function index(){

        // $companies = [
        //     1 => ['name' => 'Company One', 'contacts' => '1234567890'],
        //     2 => ['name' => 'Company Two', 'contacts' => '2345678901'],
        //     3 => ['name' => 'Company Three', 'contacts' => '3456789012'],
        // ];
        $companies = $this->company->pluck();
        //TO Debug Query log
        //DB::enableQueryLog();
        //$query = Contact::query();
        //$contacts = $query->latest()
        $contacts = Contact::allowedTrash()
                        ->allowedSorts(['first_name', 'last_name', 'email'], "-id")
                        ->allowedFilters('company_id')
                        ->allowedSearch('first_name', 'last_name', 'email')
                        ->paginate(10);

        // ->where(function ($query){

        //     if($search = request()->query('search')){
        //         $query->where("first_name", "LIKE", "%{$search}%");
        //         $query->orWhere("last_name", "LIKE", "%{$search}%");
        //         $query->orWhere("email", "LIKE", "%{$search}%");
        //     }
        // }
        
        //dump(DB::getQueryLog());
        // manually pagination
        // $contactsCollection = Contact::latest()->get();
        // $perPage=10;
        // $currentPage= request()->query('page', 1);
        // $items= $contactsCollection->slice(($currentPage * $perPage) - $perPage, $perPage);
        // $total = $contactsCollection->count();
        // $contacts = new LengthAwarePaginator($items, $total, $perPage, $currentPage, [ 
        //     'path'  => request()->url(),
        //     'query' => request()->query()
        // ]);

        return view('contacts.index', compact('contacts', 'companies'));
    }

    public function create(){

        $companies = $this->company->pluck();
        $contact = new Contact();
        return view('contacts.create', compact('companies', 'contact'));
    }
    
    public function show($id){

        $contact = $this->findContacts($id);
        // abort_unless(!empty($contact), 404);
        // abort_if(empty($contact), 404);
        
        return view('contacts.show')->with('contact', $contact);
    }

    protected function findContacts($id){
        return Contact::findOrFail($id);
    }

    public function store(Request $request){

        $request->validate([
            'first_name'    => 'required|string|max:50', //incase pipe sign you can use array in different aggrument
            'last_name'     => 'required|string|max:50',
            'email'         => 'required|email',
            'phone'         => 'nullable',
            'address'       => 'nullable',
            'company_id'    => 'required|exists:companies,id'
        ]);

        Contact::create($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contacts has been added successfully');
    }

    public function edit($id){

        $companies = $this->company->pluck();
        $contact = Contact::findOrFail($id);
        // abort_unless(!empty($contact), 404);
        // abort_if(empty($contact), 404);
        
        return view('contacts.edit', compact('contact', 'companies'));
    }

    public function update(Request $request, $id){

        $contact = Contact::findOrFail($id);
        $request->validate([
            'first_name'    => 'required|string|max:50', //incase pipe sign you can use array in different aggrument
            'last_name'     => 'required|string|max:50',
            'email'         => 'required|email',
            'phone'         => 'nullable',
            'address'       => 'nullable',
            'company_id'    => 'required|exists:companies,id'
        ]);

        $contact->update($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contacts has been updated successfully');
    }
    public function destroy($id){

        $contact = Contact::findOrFail($id);
        $contact->delete();
        // return redirect()->route('contacts.index')
        //     ->with('message', 'Contacts has been moved to trash')
        //     ->with('undoRoute', route('contacts.restore', $contact->id));

        $redirect = request()->query('redirect');

        return ($redirect ? redirect()->route($redirect) : back())
            ->with('message', 'Contacts has been moved to trash')
            ->with('undoRoute', $this->getUndoRoute('contacts.restore', $contact));
        //return back()->with('message', 'Contacts has been removed successfully');
    }

    public function restore($id){

        $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->restore();
        return back()
            ->with('message', 'Contacts has been restored to trash.')
            ->with('undoRoute', $this->getUndoRoute('contacts.destroy', $contact))
            ;
    }
    public function forceDelete($id){

        $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->forceDelete();
        return back()->with('message', 'Contacts has been removed permanently.');
    }

    protected function getUndoRoute($name, $resource){
        return request()->missing('undo') ? route($name, [$resource->id, 'undo' => true]) : null;
    }
}
