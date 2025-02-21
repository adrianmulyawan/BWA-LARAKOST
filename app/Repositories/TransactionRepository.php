<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Room;
use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getTransactionDataFromSession()
    {
        return session()->get('transaction');
    }

    public function saveTransactionDataToSession($data)
    {
        $transaction = session()->get('transaction', []);

        foreach ($data as $key => $value) {
            $transaction[$key] = $value;
        }

        session()->put('transaction', $transaction);
    }

    public function saveTransaction($data)
    {
        $room = Room::find($data['room']);
        $data = $this->prepareTransactionData($data, $room);
        // dd($data);

        $transaction = Transaction::create([
            'code' => $data["code"],
            'boarding_house_id' => $data["boarding_house_id"],
            'room_id' => $data["room"],
            'name' => $data["name"],
            'email' => $data["email"],
            'phone_number' => $data["phone_number"],
            'payment_method' => $data["payment_method"],
            'payment_status' => $data["payment_status"],
            'start_date' => $data["start_date"],
            'duration' => $data["duration"],
            'total_amount' => $data["total_amount"],
            'transaction_date' => $data["transaction_date"],
        ]);
        session()->forget('transaction');
        return $transaction;
    }

    private function prepareTransactionData($data, $room)
    {
        $data['code'] = $this->generateTransactionCode();
        $data['payment_status'] = 'pending';
        $data['transaction_date'] = now();

        $total = $this->calculateTotalAmount($room->price_per_month, $data['duration']);
        $data['total_amount'] = $this->calculatePaymentAmount($total, $data['payment_method']);

        return $data;
    }

    private function generateTransactionCode()
    {
        return 'NGKBWA' . rand(100000, 999999);
    }

    private function calculateTotalAmount($pricePerMonth, $duration)
    {
        $subtotal = $pricePerMonth * $duration;
        $tax = $subtotal * 0.11;
        $insurance = $subtotal * 0.01;
        return $subtotal + $tax + $insurance;
    }

    private function calculatePaymentAmount($total, $paymentMethod)
    {
        return $paymentMethod === 'full_payment' ? $total : $total * 0.3;
    }
}
