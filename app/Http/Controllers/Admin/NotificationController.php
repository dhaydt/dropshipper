<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Notification;
use App\Model\Seller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $notifications = Notification::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('title', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $notifications = new Notification();
        }
        $notifications = $notifications->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.notification.index', compact('notifications', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'title is required!',
        ]);

        $notification = new Notification();
        $notification->title = $request->title;
        $notification->description = $request->description;

        if ($request->has('image')) {
            $notification->image = ImageManager::upload('notification/', 'png', $request->file('image'));
        } else {
            $notification->image = 'null';
        }

        $notification->status = 1;
        $notification->save();

        $users = User::where('is_phone_verified', 1)->get();
        $sellers = Seller::where(['is_phone_verified' => 1, 'status' => 'approved'])->get();

        $tokens = [];
        foreach ($users as $u) {
            $fcm = $u->cm_firebase_token;
            if ($fcm !== null && $fcm !== '') {
                array_push($tokens, $fcm);
            }
        }
        foreach ($sellers as $s) {
            $fcms = $s->cm_firebase_token;
            if ($fcms !== null && $fcms !== '') {
                array_push($tokens, $fcms);
            }
        }

        try {
            foreach ($tokens as $token) {
                Helpers::send_push_notif_to_topic($token, $notification);
            }
        } catch (\Exception $e) {
            Toastr::warning('Push notification failed!');
        }

        Toastr::success('Notification sent successfully!');

        return back();
    }

    public function edit($id)
    {
        $notification = Notification::find($id);

        return view('admin-views.notification.edit', compact('notification'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'title is required!',
        ]);

        $notification = Notification::find($id);
        $notification->title = $request->title;
        $notification->description = $request->description;
        $notification->image = ImageManager::update('notification/', $notification->image, 'png', $request->file('image'));
        $notification->save();

        Toastr::success('Notification updated successfully!');

        return back();
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $notification = Notification::find($request->id);
            $notification->status = $request->status;
            $notification->save();
            $data = $request->status;

            return response()->json($data);
        }
    }

    public function delete(Request $request)
    {
        $notification = Notification::find($request->id);
        ImageManager::delete('/notification/'.$notification['image']);
        $notification->delete();

        return response()->json();
    }
}
