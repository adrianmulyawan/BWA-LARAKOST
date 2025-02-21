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
        $data = $request->all();

        // > simpan didalam session (cara simple)
        // $request->session()->put('id', $data);
        // dd($request->session()->get('id'));

        // > Simpan session dengan repository pattern
        $this->transactionRepository->saveTransactionDataToSession($data);
        return redirect()->route('booking.information', $slug);
    }

    public function information($slug)
    {
        // panggil session yang telah kita simpan
        $transaction = $this->transactionRepository->getTransactionDataFromSession();
        // dd($transaction);

        $boardingHouse = $this->boardingHouseRepository->getPopularBoardingHouseBySlug($slug);
        // dd($item);

        $room = $this->boardingHouseRepository->getBoardingHouseRoomById($transaction);
        dd($room);

        return view('pages.booking.cust_info', compact(
            'transaction',
            'boardingHouse',
            'room'
        ));
    }

    public function saveInformation() {}
}
