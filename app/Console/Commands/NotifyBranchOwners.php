<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Service\NotificationService;
use App\Events\BranchNotificationEvent;
use App\Models\User; // Import the User model
use App\Models\Branch; // Import the Branch model
use Illuminate\Support\Facades\Mail; // Import the Mail facade
use App\Mail\BranchNotificationMail; // Import your Mailable class // Ensure the correct namespace for NotificationService

// class NotifyBranchOwners extends Command
// {
//     // The signature of the command
//     protected $signature = 'notify:branch-owners';

//     // A brief description of the command
//     protected $description = 'Send notifications to branch owners about expiration dates';

//     // The handle method, which is executed when the command runs
//     public function handle()
//     {
//         // Fetch all branches from the database
//         $branches = Branch::all();
        
//         // Get the current date
//         $today = Carbon::now();

//         // Loop through each branch to check its expiration date
//         foreach ($branches as $branch) {
//             // Parse the creation date of the branch
//             $creationDate = Carbon::parse($branch->created_at);

//             // Find the owner of the branch
//             $owner = User::find($branch->owner_id);

//             // Skip if owner is not found
//             if (!$owner) {
//                 $this->warn("Owner not found for branch ID {$branch->id}. Skipping...");
//                 continue;
//             }

//             // Notify the owner when it's 3 months before expiration
//             if ($today->diffInMonths($creationDate) == 3) {
//                 NotificationService::sendNotification(
//                     $owner->id, // Owner's ID
//                     $branch->id, // Branch ID
//                     'Branch Expiration Reminder', // Notification title
//                     "Your branch '{$branch->name}' located at '{$branch->address}, {$branch->city}, {$branch->state}' will expire in 3 months.", // Notification message
//                     Carbon::now()
//                 );
//             }

//             // Notify the owner when it's 5 months before expiration
//             if ($today->diffInMonths($creationDate) == 5) {
//                 NotificationService::sendNotification(
//                     $owner->id,
//                     $branch->id,
//                     'Branch Expiration Reminder',
//                     "Your branch '{$branch->name}' located at '{$branch->address}, {$branch->city}, {$branch->state}' will expire in 1 month.",
//                     Carbon::now()
//                 );
//             }

//             // Notify the owner when the branch has expired (after 6 months)
//             if ($today->diffInMonths($creationDate) == 6) {
//                 NotificationService::sendNotification(
//                     $owner->id,
//                     $branch->id,
//                     'Branch Expired',
//                     "Your branch '{$branch->name}' located at '{$branch->address}, {$branch->city}, {$branch->state}' has expired. Please renew your subscription.",
//                     Carbon::now()
//                 );
//             }
//         }

//         // Print a message to the console after the job finishes
//         $this->info('Branch owner notifications sent successfully.');
//     }
// }

// class NotifyBranchOwners extends Command
// {
//     // The signature of the command
//     protected $signature = 'notify:branch-owners';

//     // A brief description of the command
//     protected $description = 'Send notifications to branch owners about expiration dates';

//     // The handle method, which is executed when the command runs
//     public function handle()
//     {
//         // Fetch all branches from the database
//         $branches = Branch::all();
        
//         // Get the current date
//         $today = Carbon::now();

//         // Loop through each branch to check its expiration date
//         foreach ($branches as $branch) {
//             // Parse the creation date of the branch
//             $creationDate = Carbon::parse($branch->created_at);

//             // Find the owner of the branch
//             $owner = User::find($branch->owner_id);

//             // Skip if owner is not found
//             if (!$owner) {
//                 $this->warn("Owner not found for branch ID {$branch->id}. Skipping...");
//                 continue;
//             }

//             // Prepare the email message content
//             $message = "";

//             // Notify the owner when it's 3 months before expiration
//             if ($today->diffInMonths($creationDate) == 3) {
//                 $message = "Your branch '{$branch->name}' located at '{$branch->address}, {$branch->city}, {$branch->state}' will expire in 3 months.";
//             }

//             // Notify the owner when it's 5 months before expiration
//             if ($today->diffInMonths($creationDate) == 5) {
//                 $message = "Your branch '{$branch->name}' located at '{$branch->address}, {$branch->city}, {$branch->state}' will expire in 1 month.";
//             }

//             // Notify the owner when the branch has expired (after 6 months)
//             if ($today->diffInMonths($creationDate) == 6) {
//                 $message = "Your branch '{$branch->name}' located at '{$branch->address}, {$branch->city}, {$branch->state}' has expired. Please renew your subscription.";
//             }

//             // Send an email if a message is set
//             if ($message) {
//                 Mail::to($owner->email)->send(new BranchNotificationMail($message));
//                 $this->info("Email sent to {$owner->email} for branch ID {$branch->id}");
//             }   
//         }

//         // Print a message to the console after the job finishes
//         $this->info('Branch owner notifications sent successfully.');
//     }
// }


class NotifyBranchOwners extends Command
{
    protected $signature = 'notify:branch-owners';
    protected $description = 'Dispatch the branch notification event';

    public function handle()
    {
        $branches = Branch::all();

        // Dispatch the event with the branches
        BranchNotificationEvent::dispatch($branches);

        $this->info('BranchNotificationEvent dispatched successfully.');
    }
}
