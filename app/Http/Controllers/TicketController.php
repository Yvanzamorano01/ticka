<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\TicketType;
use App\Services\OrangeMoneyService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected $orangeMoneyService;

    public function __construct(OrangeMoneyService $orangeMoneyService)
    {
        $this->orangeMoneyService = $orangeMoneyService;
    }

    public function index()
    {
        $tickets = Ticket::all();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $events = Event::all();
        $ticketTypes = TicketType::all();
        return view('tickets.create', compact('events', 'ticketTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
        ]);

        $ticketType = TicketType::findOrFail($request->ticket_type_id);

        $ticket = Ticket::create([
            'event_id' => $request->event_id,
            'ticket_type_id' => $ticketType->id,
            'type' => $ticketType->name,
            'price' => $ticketType->price,
            'user_id' => null, // Laisse user_id null lors de la création d'un ticket
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully!');
    }

    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $events = Event::all();
        $ticketTypes = TicketType::all();
        return view('tickets.edit', compact('ticket', 'events', 'ticketTypes'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
        ]);

        $ticket->update($request->all());

        // Mise à jour du code QR si nécessaire
        $qrCodePath = 'qr_codes/' . $ticket->id . '.png';
        $qrCodeContent = route('tickets.show', $ticket->id);
        QrCode::format('png')->size(200)->generate($qrCodeContent, storage_path('app/public/' . $qrCodePath));
        $ticket->qr_code_path = 'qr_codes/' . $ticket->id . '.png';
        $ticket->save();

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully!');
    }

    public function destroy(Ticket $ticket)
    {
        // Supprimer le fichier QR Code
        if ($ticket->qr_code_path) {
            Storage::delete('public/' . $ticket->qr_code_path);
        }

        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully!');
    }

    // Afficher le formulaire d'achat
    public function showPurchaseForm()
    {
        $events = Event::all();
        $ticketTypes = TicketType::all();
        return view('tickets.purchase', compact('events', 'ticketTypes'));
    }

    // Gérer l'achat de ticket
    public function purchase(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|numeric|min:1',
            'phone_number' => 'required',
        ]);

        $ticketType = TicketType::findOrFail($request->ticket_type_id);
        $tickets = [];
        $amount = 0;
        for ($i = 0; $i < $request->quantity; $i++) {
            $ticket = Ticket::create([
                'event_id' => $request->event_id,
                'ticket_type_id' => $ticketType->id,
                'type' => $ticketType->name,
                'price' => $ticketType->price,
                'user_id' => Auth::id(),
            ]);

            // Générer le code QR pour chaque ticket
            $qrCodePath = 'qr_codes/' . $ticket->id . '.png';
            $qrCodeContent = route('tickets.show', $ticket->id);
            QrCode::format('png')->size(200)->generate($qrCodeContent, storage_path('app/public/' . $qrCodePath));
            $ticket->qr_code_path = 'qr_codes/' . $ticket->id. '.png';
            $ticket->save();

            $amount += $ticket->price;
            $tickets[] = $ticket;
        }

        // Rediriger vers la page de paiement Orange Money
        return redirect()->route('tickets.payment', ['tickets' => json_encode($tickets), 'amount' => $amount]);
    }

    // Afficher la page de paiement
    public function showPaymentPage(Request $request)
    {
        $tickets = json_decode($request->input('tickets'), true);
        $amount = $request->input('amount');
        return view('tickets.payment', compact('tickets', 'amount'));
    }

    // Traiter le paiement
    public function processPayment(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'transaction_id' => 'required|string',
        ]);

        $tickets = json_decode($request->input('tickets'), true);
        $amount = $request->input('amount');

        // Simulation de la vérification du paiement avec Orange Money
        $paymentResponse = $this->orangeMoneyService->initiatePayment($request->phone_number, $amount);

        if ($paymentResponse['status'] === 'success') {
            // Mise à jour de l'état des tickets après paiement réussi
            foreach ($tickets as $ticketData) {
                $ticket = Ticket::find($ticketData['id']);
                $ticket->payment_status = 'paid'; // Mettre à jour le statut de paiement, par exemple
                $ticket->save();
            }
            return redirect()->route('tickets.index')->with('success', 'Payment successful and tickets generated!');
        } else {
            return redirect()->back()->with('error', 'Payment failed. Please try again.');
        }
    }

    public function handleCallback(Request $request)
    {
    $data = $request->all();
    $this->orangeMoneyService->handleCallback($data);

    return response()->json(['message' => 'Callback handled']);
    }

}
