<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;


class TicketController extends Controller
{
    public function myTickets(Request $request)
    {
//         $request->All();
        $perPage = 10;
        $user = User::where(['company_user_id' => $request->user_id, 'company_name' => $request->company])->first();
        $tickets = Ticket::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->latest()
            ->paginate($perPage);
//        return $tickets;

        $object_ticket = $tickets->getCollection()->map(function ($ticket) {
            return (object)[
                "id" => $ticket->id,
                "sender_name" => $ticket->sender->name . ' ' . $ticket->sender->family,
                "receiver_name" => $ticket->receiver->name . ' ' . $ticket->receiver->family,
                "company_sender" => $ticket->sender->company_name,
                "title" => $ticket->title,
                "code" => $ticket->code,
                "status" => $ticket->status,
                "created_at" => $ticket->created_at,
            ];
        });


        return response()->json([
            'data' => $object_ticket,
            'pagination' => [
                'total' => $tickets->total(),
                'per_page' => $tickets->perPage(),
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'next_page_url' => $tickets->nextPageUrl(),
                'prev_page_url' => $tickets->previousPageUrl(),
            ]
        ]);

    }

    public function allTickets()
    {
        $perPage = 10;
        $tickets = Ticket::latest()->paginate($perPage);
        $object_ticket = $tickets->getCollection()->map(function ($ticket) {
            return (object)[
                "id" => $ticket->id,
                "sender_name" => $ticket->sender->name . ' ' . $ticket->sender->family,
                "receiver_name" => $ticket->receiver->name . ' ' . $ticket->receiver->family,
                "company_sender" => $ticket->sender->company_name,
                "title" => $ticket->title,
                "code" => $ticket->code,
                "status" => $ticket->status,
                "created_at" => $ticket->created_at,
            ];
        });


        return response()->json([
            'data' => $object_ticket,
            'pagination' => [
                'total' => $tickets->total(),
                'per_page' => $tickets->perPage(),
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'next_page_url' => $tickets->nextPageUrl(),
                'prev_page_url' => $tickets->previousPageUrl(),
            ]
        ]);
    }

    public function createTicket(Request $request)
    {

        $user_sender_ticket = User::where(['company_user_id' => $request->sender_id, 'company_name' => $request->company])->first();
        $user_receiver_ticket = User::where(['id' => $request->receiver_id])->first();
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

        $message = 'تیکتی با عنوان "' . $request->title . '" به شما ارسال شده است';
        dispatch(new \App\Jobs\SendNotificationJob($user_receiver_ticket->company_user_id, $request->company, $message));
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
//        return $request->all();
        $ticket = Ticket::where('id', $request['ticket_id'])->first();
        $ticket->messages()->whereNull('read_at')->where('user_id', '!=', auth()->id())->update(['read_at' => now()]);
        return response()->json($ticket->load('messages.user', 'sender', 'receiver'));
    }


    public function chatInTickets(Request $request)
    {

//        return $request->all();
        $ticket = Ticket::whereId($request->ticket_id)->first();
        $user_sender = User::where(['company_user_id' => $request->sender_id, 'company_name' => $request->company])->first();


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
            'user_id' => $user_sender->id,
            'text' => $request->text,
            'file' => isset($file) ? json_encode($file_info) : null,
        ]);

        if ($first_message != null && $first_message->user_id != $user_sender->id) {
            $message = 'پاسخی برای تیکت "' . $ticket->title . '" ثبت شده است';
            $receiver = $user_sender->id == $ticket->sender_id ? $ticket->receiver_id : $ticket->sender_id;
            dispatch(new \App\Jobs\SendNotificationJob($receiver, $request->company, $message));
        }

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


    public function changeStatusTicket(Request $request)
    {

        $ticket = Ticket::whereId($request->ticket_id)->first();
        $user = User::where(['company_user_id' => $request->user_id, 'company_name' => $request->company])->first();
        if ($ticket->sender_id == $user->id || $ticket->receiver_id == $user->id) {
            if ($ticket->status == 'closed') {
                $ticket->update(['status' => 'pending']);
            } else {
                $ticket->update(['status' => 'closed']);
            }
            return response()->json([
                'status' => 'success',
                'ticket' => $ticket,
            ], 201);
//
        } else {
            return response()->json([
                'status' => 'failed',
            ], 404);
        }
    }

    public function addUserToMoshrefi(Request $request)
    {
        $user = new User();
        $user->company_user_id = $request->company_user_id;
        $user->name = $request->name;
        $user->family = $request->family;
        $user->company_name = $request->company;
        $user->role_name = $request->role_name;
        $user->phone = $request->phone;
        $user->save();
    }

    public function editUserToMoshrefi(Request $request)
    {
//        return $request->all();
        $user = User::where(['company_user_id' => $request->company_user_id, 'company_name' => $request->company])->first();
        $user->company_user_id = $request->company_user_id;
        $user->name = $request->name;
        $user->family = $request->family;
        $user->company_name = $request->company;
        $user->role_name = $request->role_name;
        $user->phone = $request->phone;
        $user->save();
        return $user;
    }

    public function deleteUserToMoshrefi(Request $request)
    {
        $user = User::where(['company_user_id' => $request->user_id, 'company_name' => $request->company])->first();
        $user->delete();
    }

}
