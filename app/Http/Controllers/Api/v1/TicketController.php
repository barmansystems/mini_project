<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class TicketController extends Controller
{
    public function myTickets(Request $request)
    {
        $tickets = Ticket::where('sender_id', $request->user_id)
            ->orWhere('receiver_id', $request->user_id)
            ->with(['sender:id,name', 'receiver:id,name'])
            ->latest()
            ->paginate(30);

        $object_ticket = $tickets->map(function ($ticket) {
            return (object)[
                "id" => $ticket->id,
                "sender_name" => $ticket->sender->name.' '.$ticket->sender->name,
                "receiver_name" => $ticket->receiver->family.' '.$ticket->sender->family,
                "title" => $ticket->title,
                "code" => $ticket->code,
                "status" => $ticket->status,
                "created_at" => $ticket->created_at,
            ];
        });

        return response()->json([
            'data' => $object_ticket,
            'current_page' => $tickets->currentPage(),
            'last_page' => $tickets->lastPage(),
            'per_page' => $tickets->perPage(),
            'total' => $tickets->total(),
        ]);
    }

    public function allTickets()
    {
        $tickets = Ticket::latest()->paginate(30);

        $object_ticket = $tickets->map(function ($ticket) {
            return (object)[
                "id" => $ticket->id,
                "sender_name" => $ticket->sender->name.' '.$ticket->sender->family,
                "receiver_name" => $ticket->receiver->name.' '.$ticket->receiver->family,
                "title" => $ticket->title,
                "code" => $ticket->code,
                "status" => $ticket->status,
                "created_at" => $ticket->created_at,
            ];
        });

        return response()->json([
            'data' => $object_ticket,
            'current_page' => $tickets->currentPage(),
            'last_page' => $tickets->lastPage(),
            'per_page' => $tickets->perPage(),
            'total' => $tickets->total(),
        ]);
    }

    public function createTicket(Request $request)
    {

        $user_sender_ticket = User::where(['company_user_id' => $request->sender_id])->first();
//        return $user_sender_ticket;
        $ticket = new Ticket();
        $ticket->sender_id = $user_sender_ticket->id;
        $ticket->receiver_id = $request->receiver_id;
        $ticket->title = $request->title;
        $ticket->code = $this->generateCode();
        $ticket->save();


        if ($request->file) {
            $file_info = [
                'name' => $request->file('file')->getClientOriginalName(),
                'type' => $request->file('file')->getClientOriginalExtension(),
                'size' => $request->file('file')->getSize(),
            ];

            $file = $this->upload_file($request->file, 'Messages');
            $file_info['path'] = $file;
        }

        $ticket->messages()->create([
            'user_id' => $user_sender_ticket->id,
            'text' => $request->text,
            'file' => isset($file) ? json_encode($file_info) : null,
        ]);
        return $ticket;
    }

    public function getUsers(Request $request)
    {
        $users = User::where('company_user_id', '!=', $request->user_id)
            ->where('company_name', $request->company_name)->get();
        return response()->json($users);
    }

    public function upload_file($file, $folder)
    {
        if ($file) {
            $filename = time() . $file->getClientOriginalName();
            $year = Carbon::now()->year;
            $month = Carbon::now()->month;
            $path = public_path("/uploads/{$folder}/{$year}/{$month}/");
            $file->move($path, $filename);
            $img = "/uploads/{$folder}/{$year}/{$month}/" . $filename;
            return $img;
        }
    }

    private function generateCode()
    {
        $last_ticket = Ticket::latest()->first();
        $newCode = $last_ticket ? $last_ticket->code++ : 1;

        if ($last_ticket) {
            while (Ticket::where('code', $newCode)->exists()) {
                $newCode++;
            }
            return $newCode;
        } else {
            return $newCode;
        }
    }
}
