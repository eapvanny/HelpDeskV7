<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index() 
    {

        return view('backend.ticket.list');
    }
    public function create() 
    {

        $departments = Department::pluck('name','id');
        $ticketOption = Ticket::all();
        $ticket = null;
        return view('backend.ticket.add', compact('departments','ticketOption','ticket'));

    }
    public function store() 
    {

    }
    public function edit() 
    {

    }
    public function update() 
    {

    }
    public function destroy() 
    {

    }
}
