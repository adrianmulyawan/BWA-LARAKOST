<?php

namespace App\Http\Controllers;

use App\Interfaces\BoardingHouseRepositoryInterface;
use App\Interfaces\TransactionRepositoryInterface;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private BoardingHouseRepositoryInterface $boardingHouseRepository;
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(
        BoardingHouseRepositoryInterface $boardingHouseRepository,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->boardingHouseRepository = $boardingHouseRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function check()
    {
        return view('pages.check-booking');
    }

    public function booking(Request $request, $slug)
    {
        // Disini ngambilnya id dari table room
        // Atau id ruangan dari boarding house
        // dd($request->all());

        // > ambil request dari formnya => id dari room
        // $data = $request->all();

        // > simpan didalam session (cara simple)
        // $request->session()->put('id', $data);
        // dd($request->session()->get('id'));

        // > Simpan session dengan repository pattern
        $this->transactionRepository->saveTransactionDataToSession($request->all());
        return redirect()->route('booking.information', $slug);
    }

    public function information($slug)
    {
        // panggil session yang telah kita simpan
        $transaction = $this->transactionRepository->getTransactionDataFromSession();
        // dd(intval($transaction['room']));

        $boardingHouse = $this->boardingHouseRepository->getPopularBoardingHouseBySlug($slug);
        // dd($boardingHouse);

        $room = $this->boardingHouseRepository->getBoardingHouseRoomById(intval($transaction['room']));
        // dd($room);

        return view('pages.booking.cust_info', compact(
            'transaction',
            'boardingHouse',
            'room'
        ));
    }

    public function saveInformation(Request $request, $slug)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone_number' => 'required',
            'duration' => 'required',
            'start_date' => 'required'
        ], [
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 3 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'phone_number.required' => 'Phone is required.',
            'duration.required' => 'Duration is required.',
            'start_date.required' => 'Start date is required.',
        ]);

        $this->transactionRepository->saveTransactionDataToSession($request->all());
        // dd($this->transactionRepository->getTransactionDataFromSession());

        return redirect()->route('booking.checkout', $slug);
    }

    public function checkout($slug)
    {
        // panggil session yang telah kita simpan
        $transaction = $this->transactionRepository->getTransactionDataFromSession();
        // dd(intval($transaction['room']));

        $boardingHouse = $this->boardingHouseRepository->getPopularBoardingHouseBySlug($slug);
        // dd($boardingHouse);

        $room = $this->boardingHouseRepository->getBoardingHouseRoomById(intval($transaction['room']));
        // dd($room);

        return view('pages.booking.checkout', compact(
            'transaction',
            'boardingHouse',
            'room'
        ));
    }

    public function payment(Request $request)
    {
        $this->transactionRepository->saveTransactionDataToSession($request->all());

        $transaction = $this->transactionRepository->saveTransaction($this->transactionRepository->getTransactionDataFromSession());

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        // Add params
        $params = array(
            'transaction_details' => array(
                'order_id' => $transaction->code,
                'gross_amount' => $transaction->total_amount,
            ),
            'customer_details' => array(
                'first_name' => $transaction->name,
                'email' => $transaction->email,
                'phone' => $transaction->phone_number,
            )
        );

        $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

        return redirect($paymentUrl);
    }
}
