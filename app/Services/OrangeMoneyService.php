<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Ticket;

class OrangeMoneyService
{
    protected $apiKey;
    protected $apiSecret;
    protected $paymentUrl;

    public function __construct()
    {
        $this->apiKey = config('services.orange_money.api_key');
        $this->apiSecret = config('services.orange_money.api_secret');
        $this->paymentUrl = config('services.orange_money.payment_url');
    }

    public function initiatePayment($phoneNumber, $amount)
    {
        $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
            ->post($this->paymentUrl, [
                'phoneNumber' => $phoneNumber,
                'amount' => $amount,
                'currency' => 'XAF',
                'description' => 'Ticket(s) Purchase',
                'callbackUrl' => route('tickets.payment.callback'),
            ]);

        return $response->json();
    }

    public function handleCallback($data)
    {
        // Vérifier le statut du paiement
        if ($data['status'] === 'success') {
            foreach ($data['tickets'] as $ticketData) {
                $ticket = Ticket::find($ticketData['id']);
                if ($ticket) {
                    $ticket->payment_status = 'paid';
                    $ticket->save();
                }
            }
        } else {
            // Gérer le cas d'échec de paiement si nécessaire
            foreach ($data['tickets'] as $ticketData) {
                $ticket = Ticket::find($ticketData['id']);
                if ($ticket) {
                    $ticket->payment_status = 'failed';
                    $ticket->save();
                }
            }
        }
    }
}
