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
            ->get();

        $object_ticket = $tickets->map(function ($ticket) {
            return (object)[
                "id" => $ticket->id,
                "sender_name" => $ticket->sender->name . ' ' . $ticket->sender->name,
                "receiver_name" => $ticket->receiver->family . ' ' . $ticket->sender->family,
                "company" => $ticket->receiver->company_name,
                "title" => $ticket->title,
                "code" => $ticket->code,
                "status" => $ticket->status,
                "created_at" => $ticket->created_at,
            ];
        });

        return $object_ticket;

    }

    public function allTickets()
    {
        $tickets = Ticket::latest()->get();
        $object_ticket = $tickets->map(function ($ticket) {
            return (object)[
                "id" => $ticket->id,
                "sender_name" => $ticket->sender->name . ' ' . $ticket->sender->family,
                "receiver_name" => $ticket->receiver->name . ' ' . $ticket->receiver->family,
                "company" => $ticket->receiver->company_name,
                "title" => $ticket->title,
                "code" => $ticket->code,
                "status" => $ticket->status,
                "created_at" => $ticket->created_at,
            ];
        });

        return $object_ticket;
    }

    public function createTicket(Request $request)
    {

        $user_sender_ticket = User::where(['company_user_id' => $request->sender_id])->first();
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


    public function getMessages(Request $request)
    {
//        return ;
        $ticket = Ticket::where('id', $request['ticket_id'])->first();
        $ticket->messages()->whereNull('read_at')->where('user_id', '!=', auth()->id())->update(['read_at' => now()]);
        return response()->json($ticket->load('messages.user', 'sender', 'receiver'));
    }


    public function chatInTickets(Request $request)
    {


        $ticket = Ticket::whereId($request->ticket_id)->first();


        $ticket->update(['status' => 'pending']);

        // prevent from send sequence notification
        $first_message = $ticket->messages()->orderBy('created_at', 'desc')->first();

        // end prevent from send sequence notification

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
            'user_id' => $ticket->sender_id,
            'text' => $request->text,
            'file' => isset($file) ? json_encode($file_info) : null,
        ]);

        // log

        return response()->json($ticket, 201);
    }

    public function deleteTicket(Request $request)
    {
        $ticket = Ticket::whereId($request->ticket_id)->first();
        foreach ($ticket->messages as $message) {
            if ($message->file) {
                unlink(public_path(json_decode($message->file)->path));
            }
        }

        $ticket->delete();
        return 'success';
    }


}
