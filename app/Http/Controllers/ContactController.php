<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Company;
//use App\Repositories\CompanyRepository;
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
    // public function __construct(protected CompanyRepository $company){
        
    // }

    protected function userCompanies(){
        return Company::forUser(auth()->user())->orderBy('name')->pluck('name', 'id');
    }

    public function index(){

        // $companies = [
        //     1 => ['name' => 'Company One', 'contacts' => '1234567890'],
        //     2 => ['name' => 'Company Two', 'contacts' => '2345678901'],
        //     3 => ['name' => 'Company Three', 'contacts' => '3456789012'],
        // ];
        //$companies = $this->company->pluck();
        $companies = $this->userCompanies();
        //TO Debug Query log
        //DB::enableQueryLog();
        //$query = Contact::query();
        //$contacts = $query->latest()
        // $contacts = Contact::allowedTrash()
        //                 ->allowedSorts(['first_name', 'last_name', 'email'], "-id")
        //                 ->allowedFilters('company_id')
        //                 ->allowedSearch('first_name', 'last_name', 'email')
        //                 ->paginate(10);
        $contacts = Contact::allowedTrash()
                        ->allowedSorts(['first_name', 'last_name', 'email'], "-id")
                        ->allowedFilters('company_id')
                        ->allowedSearch('first_name', 'last_name', 'email')
                        ->forUser(auth()->user())
                        ->with("company")
                        ->paginate(10);
        // $contacts = auth()->user()->contacts()
        //                 ->allowedTrash()
        //                 ->allowedSorts(['first_name', 'last_name', 'email'], "-id")
        //                 ->allowedFilters('company_id')
        //                 ->allowedSearch('first_name', 'last_name', 'email')
        //                 ->paginate(10);

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

        $companies = $this->userCompanies();
        $contact = new Contact();
        return view('contacts.create', compact('companies', 'contact'));
    }
    
    public function show(Contact $contact){

        // $contact = $this->findContacts($id);
        // abort_unless(!empty($contact), 404);
        // abort_if(empty($contact), 404);
        
        return view('contacts.show')->with('contact', $contact);
    }

    // protected function findContacts($id){
    //     return Contact::findOrFail($id);
    // }

    public function store(ContactRequest $request){

        // $request->validate([
        //     'first_name'    => 'required|string|max:50', //incase pipe sign you can use array in different aggrument
        //     'last_name'     => 'required|string|max:50',
        //     'email'         => 'required|email',
        //     'phone'         => 'nullable',
        //     'address'       => 'nullable',
        //     'company_id'    => 'required|exists:companies,id'
        // ]);

        //Contact::create($request->all());
        $request->user()->contacts()->create($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contacts has been added successfully');
    }

    public function edit(Contact $contact){

        $companies = $this->userCompanies();
        //$contact = Contact::findOrFail($id);
        // abort_unless(!empty($contact), 404);
        // abort_if(empty($contact), 404);
        
        return view('contacts.edit', compact('contact', 'companies'));
    }

    public function update(ContactRequest $request, Contact $contact){

        //$contact = Contact::findOrFail($id);
        // $request->validate([
        //     'first_name'    => 'required|string|max:50', //incase pipe sign you can use array in different aggrument
        //     'last_name'     => 'required|string|max:50',
        //     'email'         => 'required|email',
        //     'phone'         => 'nullable',
        //     'address'       => 'nullable',
        //     'company_id'    => 'required|exists:companies,id'
        // ]);

        $contact->update($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contacts has been updated successfully');
    }
    public function destroy(Contact $contact){

        //$contact = Contact::findOrFail($id);
        $contact->delete();
        // return redirect()->route('contacts.index')
        //     ->with('message', 'Contacts has been moved to trash')
        //     ->with('undoRoute', route('contacts.restore', $contact->id));

        $redirect = request()->query('redirect');

        return ($redirect ? redirect()->route($redirect) : back())
            ->with('message', 'Contacts has been moved to trash')
            ->with('undoRoute', getUndoRoute('contacts.restore', $contact));
        //return back()->with('message', 'Contacts has been removed successfully');
    }

    public function restore(Contact $contact){

        //$contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->restore();
        return back()
            ->with('message', 'Contacts has been restored to trash.')
            ->with('undoRoute', getUndoRoute('contacts.destroy', $contact))
            ;
    }
    public function forceDelete(Contact $contact){

        // $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->forceDelete();
        return back()->with('message', 'Contacts has been removed permanently.');
    }

     
}
