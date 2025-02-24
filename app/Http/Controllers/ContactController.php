<?php

namespace App\Http\Controllers;

use App\Http\Helpers\AppHelper;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view contact', ['only' => ['index']]);
        $this->middleware('permission:create contact', ['only' => ['create', 'store']]);
        $this->middleware('permission:update contact', ['only' => ['update', 'edit']]);
        $this->middleware('permission:delete contact', ['only' => ['destroy']]);
    }
    public $indexof = 1;
    public function index(Request $request)
{
    $contacts = Contact::query();

    if ($request->ajax()) {
        return DataTables::of($contacts)
            ->addIndexColumn() // This automatically adds DT_RowIndex
            ->filter(function ($query) use ($request) {
                if ($search = $request->input('search.value')) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('name_in_latin', 'LIKE', "%{$search}%")
                          ->orWhere('phone_no', 'LIKE', "%{$search}%");
                    });
                }
            })
            ->addColumn('photo', function ($data) {
                return '<img class="img-responsive center" style="height: 35px; width: 35px; object-fit: cover; border-radius: 50%;" 
                src="' . ($data->photo ? asset('storage/' . $data->photo) : asset('images/avatar.png')) . '" >';
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="change-action-item">';
                $actions = false;
                
                if (auth()->user()->can('update contact')) {
                    $button .= '<a title="Edit" href="' . route('contact.edit', $data->id) . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $actions = true;
                }
                if (auth()->user()->role_id == AppHelper::USER_SUPER_ADMIN) {
                    $button .= '<a href="' . route('contact.destroy', $data->id) . '" class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>';
                    $actions = true;
                }
                if (!$actions) {
                    $button .= '<span style="font-weight:bold; color:red;">No Action</span>';
                }
                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action', 'photo'])
            ->make(true);
    }

    return view('backend.contact.list');
}

    public function create()
    {
        $contact = null;
        return view(
            'backend.contact.add',
            compact(
                'contact'
            )
        );
    }
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:50',
            'name_in_latin' => 'required|max:50',
            'id_card' => 'required|min:4|max:20',
            'link_telegram' => 'required',
            'phone_no' => 'required|min:9|max:15',
            'photo' => 'mimes:jpeg,jpg,png|max:2000|dimensions:min_width=50,min_height=50',
        ];
        $this->validate($request, $rules);

        $supportData = [
            'name' => $request->name,
            'name_in_latin' => $request->name_in_latin,
            'id_card' => $request->id_card,
            'phone_no' => $request->phone_no,
            'link_telegram' => $request->link_telegram,
        ];

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->extension();
            $filePath = 'uploads/' . $fileName;
            Storage::put($filePath, file_get_contents($file));
            $supportData['photo'] = $filePath;
        }
        
        Contact::create($supportData);

        return redirect()->route('contact.index')->with('success', 'Supporter added!');
    }
    public function edit($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return redirect()->route('contact.index');
        }
        return view(
            'backend.contact.add',
            compact(
                'contact'
            )
        );
    }
    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);
        $rules = [
            'name' => 'required|max:50',
            'name_in_latin' => 'required|max:50',
            'id_card' => 'required|min:4|max:20',
            'link_telegram' => 'required',
            'phone_no' => 'required|min:9|max:15',
            'photo' => 'mimes:jpeg,jpg,png|max:2000|dimensions:min_width=50,min_height=50',
        ];
        $this->validate($request, $rules);
        $supportData = [
            'name' => $request->name,
            'name_in_latin' => $request->name_in_latin,
            'id_card' => $request->id_card,
            'phone_no' => $request->phone_no,
            'link_telegram' => $request->link_telegram,
        ];
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($contact->photo && Storage::exists($contact->photo)) {
                Storage::delete($contact->photo);
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->extension();
            $filePath = 'uploads/' . $fileName;
            Storage::put($filePath, file_get_contents($file));

            $supportData['photo'] = $filePath; // Update with new photo
        }

        $contact->update($supportData);

        return redirect()->route('contact.index')->with('success', "Contact has been updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        return redirect()->back()->with('success', "Contact has been deleted!");
    }
}
