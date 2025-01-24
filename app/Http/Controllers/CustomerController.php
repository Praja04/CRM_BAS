<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;
use App\Mail\LoyalCustomerEmail;
use App\Mail\NewCustomerEmail;
use Illuminate\Support\Facades\Mail; 


class CustomerController extends Controller
{
    public function index()
    {
        return view('customers.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::select('user_id', 'name', 'email', 'status')->get();
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-warning btn-sm edit" data-id="' . $row->user_id . '">Edit</button>
                            <button class="btn btn-danger btn-sm delete" data-id="' . $row->user_id . '">Delete</button>';
                })
                ->make(true);
        }
    }

    public function updateStatus(Request $request, $userId)
    {
        $customer = Customer::find($userId);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        // Ubah status jadi "LOYAL CUSTOMER"
        $customer->status = 'LOYAL CUSTOMER';
        $customer->save();

        // Kirim email
        Mail::to($customer->email)->queue(new LoyalCustomerEmail($customer));

        return response()->json(['message' => 'Status updated and email sent']);
    }

    // public function store(Request $request)
    // {
    //     $fromAddress = env('MAIL_FROM_ADDRESS');
    //     $fromName = env('MAIL_FROM_NAME');
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:customers,email',
    //     ]);

    //     // Membuat ID user otomatis
    //     $lastCustomer = Customer::latest('user_id')->first();
    //     $newId = $lastCustomer ? (int)substr($lastCustomer->user_id, -3) + 1 : 1;
    //     $userId = now()->format('dmY') . str_pad($newId, 3, '0', STR_PAD_LEFT);

    //     // Menambahkan customer baru dengan status "NEW CUSTOMER"
    //     $customer = Customer::create([
    //         'user_id' => $userId,
    //         'name' => $validated['name'],
    //         'email' => $validated['email'],
    //         'status' => 'NEW CUSTOMER',
    //     ]);

    //     // Kirim email menggunakan queue
    //     //Mail::to($customer->email)->queue(new NewCustomerEmail($customer));
    //     // Lanjutkan dengan pengiriman email
    //     Mail::raw('This is a test email.', function ($message) use ($fromAddress, $fromName) {
    //         $message->from($fromAddress, $fromName)
    //             ->to('rajadamang13@gmail.com')
    //             ->subject('Hello from MailerSend!');
    //     });

    //     return response()->json(['message' => 'Customer added successfully and email sent']);
    // }

 

    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
        ]);

        // Membuat ID user otomatis
        $lastCustomer = Customer::latest('user_id')->first();
        $newId = $lastCustomer ? (int)substr($lastCustomer->user_id, -3) + 1 : 1;
        $userId = now()->format('dmY') . str_pad($newId, 3, '0', STR_PAD_LEFT);

        // Menambahkan customer baru dengan status "NEW CUSTOMER"
        $customer = Customer::create([
            'user_id' => $userId,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => 'NEW CUSTOMER',
        ]);

        // Kirim email menggunakan queue dengan MailerSend driver
        $fromAddress = env('MAIL_FROM_ADDRESS');
        $fromName = env('MAIL_FROM_NAME');

       

        if (empty($fromAddress) || empty($fromName)) {
            return response()->json(['error' => 'MAIL_FROM_ADDRESS or MAIL_FROM_NAME is not configured in .env'], 500);
        }

        // Kirim email menggunakan queue dengan driver MailerSend
        Mail::to($customer->email)->queue(new NewCustomerEmail($customer));

        // Kembalikan respons sukses
        return response()->json(['message' => 'Customer added successfully and email sent']);
    }

    public function edit($userId)
    {
        $customer = Customer::find($userId);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        return response()->json($customer);
    }

    public function update(Request $request, $userId)
    {
        $customer = Customer::find($userId);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->save();

        return response()->json(['message' => 'Customer updated successfully']);
    }

    public function destroy($userId)
    {
        $customer = Customer::find($userId);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}

