<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\PostController;
use App\Events\MessageSent;

Route::get('/', function () {
    return redirect()->route('login');  //view('login');
});
Route::get('/vj', function () {
    return view('vibhore');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('posts', PostController::class)->middleware(['auth', 'verified']);//->name('posts');

Route::get('/send', function () {
    //console.log("testing");
    broadcast(new MessageSent("Hello from Laravel!"));

    return "Message broadcasted!";
});

Route::get('/chat', function () {
    return view('chat');
});

/*Route::post('/send-message', function () {
    $message = request()->message;

    broadcast(new \App\Events\MessageSent($message))->toOthers();

    return ['status' => 'ok'];
});*/

Route::post('/send-message', function (\Illuminate\Http\Request $request) {
    $msg = $request->message;
    Log::info('POST /send-message received: ' . $msg);
    broadcast(new \App\Events\MessageSent($request->message));
    Log::info('Broadcast fired for message: ' . $msg);
    return response()->json(['status' => 'Message sent!']);
});


// to count number of connected windows. This is the FIX for the stdClass error.
Route::get('/api/channel-count', function () {
    try {
        // Use the Broadcast facade to correctly instantiate the Reverb Pusher client
        $pusher = Broadcast::driver('reverb')->getPusher();

        // Query the channel info, requesting subscription_count
        // The result is guaranteed to be an stdClass object (which we access with ->)
        $channelInfo = $pusher->get('/channels/chat-channel', ['info' => 'subscription_count']);
        
        // Access property using object syntax (->)
        // Use null-coalescing to default to 0 if the property is missing
        $count = $channelInfo->subscription_count ?? 0;
        
        Log::info("Reverb Client Count Query Success: {$count}");

        return response()->json(['count' => $count]);

    } catch (\Exception $e) {
        // Log the error if the API call to Reverb fails (e.g., host/port is wrong)
        Log::error('Reverb API Query Failed: ' . $e->getMessage(), [
            'exception_class' => get_class($e),
            'line' => $e->getLine()
        ]);
        // Return 0 on API failure so the client-side polling doesn't crash
        return response()->json(['count' => 0, 'error' => 'API Error'], 500);
    }
});

require __DIR__.'/auth.php';
