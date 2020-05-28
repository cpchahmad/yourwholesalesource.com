<?php

namespace App\Http\Controllers;

use App\ManagerLog;
use App\Ticket;
use App\TicketAttachment;
use App\TicketCategory;
use App\TicketLog;
use App\TicketThread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function create_ticket(Request $request){
        $manager = User::find($request->input('manager_id'));
        if($manager != null){
            $ticket = new Ticket();
            $ticket->title = $request->input('title');
            $ticket->token = $request->input('_token');
            $ticket->email = $request->input('email');
            $ticket->message = $request->input('message');
            $ticket->priority = $request->input('priority');
            $ticket->status = 'New';
            $ticket->status_id = '1';
            $ticket->source = $request->input('source');
            if($request->input('category') == 'default'){
                $ticket->category = $request->input('category');
            }
            else{
                $category = TicketCategory::find($request->input('category'));
                $ticket->category = $category->name;
                $ticket->category_id = $category->id;
            }
            $ticket->manager_id = $manager->id;
            if($request->type == 'user-ticket'){
                $ticket->user_id = Auth::id();
            }
            else{
                $ticket->shop_id = $request->input('shop_id');
            }

            $ticket->save();

            if($request->hasFile('attachments')){
                $files = $request->file('attachments');
                foreach ($files as $file){
                    $name = Str::slug($file->getClientOriginalName());
                    $attachement = date("mmYhisa_") . $name;
                    $file->move(public_path() . '/ticket-attachments/', $attachement);
                    $ta = new TicketAttachment();
                    $ta->source = $attachement;
                    $ta->ticket_id = $ticket->id;
                    $ta->save();
                }
            }

            /*Maintaining Log*/
            $tl = new TicketLog();
            $tl->message = 'A Ticket named "'.$ticket->title.'" against "'.$ticket->email.'" created at '.date_create($ticket->created_at)->format('d M, Y h:i a');
            $tl->status = "Created";
            $tl->ticket_id = $ticket->id;
            $tl->save();
            return redirect()->back()->with('success','Ticket created successfully!');

        }

        else{
          return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }
    public function create_ticket_thread(Request $request){
        $manager = User::find($request->input('manager_id'));
        $ticket = Ticket::find($request->input('ticket_id'));
        if($manager != null && $ticket != null){
            $thread = new TicketThread();
            $thread->reply = $request->input('reply');
            $thread->source = $request->input('source');
            $thread->manager_id = $manager->id;
            $thread->user_id = $request->input('user_id');
            $thread->shop_id = $request->input('shop_id');
            $thread->ticket_id = $request->input('ticket_id');
            $thread->save();
            $ticket->last_reply_at = now();
            if($request->input('source') == 'manager'){
                $ticket->status_id = '2';
                $ticket->status = 'Replied';
            }
            else{
                $ticket->status_id = '3';
                $ticket->status = 'Waiting For Replied';
            }

            $ticket->save();

            if($request->hasFile('attachments')){
                $files = $request->file('attachments');
                foreach ($files as $file){
                    $name = Str::slug($file->getClientOriginalName());
                    $attachement = date("mmYhisa_") . $name;
                    $file->move(public_path() . '/ticket-attachments/', $attachement);
                    $ta = new TicketAttachment();
                    $ta->source = $attachement;
                    $ta->thread_id = $thread->id;
                    $ta->save();
                }
            }


            /*Maintaining Log*/
            $tl = new TicketLog();
            if($request->input('source') == 'manager') {
                $tl->message = 'A Reply Added By Manager on Ticket at ' . date_create($thread->created_at)->format('d M, Y h:i a');
                $tl->status = "Reply From Manager";
            }
            else{
                $tl->message = 'A Reply Added on Ticket at ' . date_create($thread->created_at)->format('d M, Y h:i a');
                $tl->status = "Reply From User";
            }

            $tl->ticket_id = $ticket->id;
            $tl->save();

            if($request->input('source') == 'manager') {
                $tl = new ManagerLog();
                $tl->message = 'A Reply Added By Manager on Ticket at ' . date_create($thread->created_at)->format('d M, Y h:i a');
                $tl->status = "Reply From Manager";
                $tl->manager_id = $manager->id;
                $tl->save();
            }

            return redirect()->back()->with('success','Reply sent successfully!');

        }

        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }

}
